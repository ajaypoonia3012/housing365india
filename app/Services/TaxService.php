<?php

namespace App\Services;

use App\Models\MonetizationPlan;
use App\Models\MonetizationProduct;
use App\Models\TaxConfiguration;
use App\Models\User;
use Carbon\Carbon;

class TaxService
{
    /**
     * Resolve the active tax configuration.
     */
    public function getActiveConfig(?Carbon $date = null, ?string $productCode = null): ?TaxConfiguration
    {
        return TaxConfiguration::getActiveConfiguration($date, $productCode);
    }

    /**
     * Determine whether global tax is enabled.
     */
    public function isTaxEnabled(?TaxConfiguration $config = null): bool
    {
        $config = $config ?? $this->getActiveConfig();

        if (! $config) {
            return false;
        }

        return (bool) $config->tax_enabled && (bool) $config->active;
    }

    /**
     * Determine whether a product or plan is taxable.
     */
    public function isProductTaxable($product = null, ?TaxConfiguration $config = null): bool
    {
        if (! $this->isTaxEnabled($config)) {
            return false;
        }

        if ($product instanceof MonetizationPlan) {
            $product = $product->product;
        }

        if ($product instanceof MonetizationProduct) {
            return (bool) ($product->is_taxable ?? true);
        }

        return true;
    }

    /**
     * Calculate tax for an amount, taking product and customer context into account.
     * Order of operations:
     * Subtotal -> Coupon Discount -> Taxable Amount -> Tax -> Total.
     *
     * @param  float  $taxableAmount  Pre-tax base amount after discount
     * @param  MonetizationProduct|MonetizationPlan|string|null  $product
     * @param  array  $context  Optional parameters like 'effective_date', 'is_interstate', 'customer_state'
     * @return array Tax breakdown and totals
     */
    public function calculate(float $taxableAmount, $product = null, ?User $customer = null, array $context = []): array
    {
        $taxableAmount = round(max(0, $taxableAmount), 2);
        $effectiveDate = isset($context['effective_date']) ? Carbon::parse($context['effective_date']) : Carbon::now();

        $productCode = null;
        if ($product instanceof MonetizationPlan) {
            $productCode = $product->product?->code;
            $productModel = $product->product;
        } elseif ($product instanceof MonetizationProduct) {
            $productCode = $product->code;
            $productModel = $product;
        } else {
            $productCode = is_string($product) ? $product : null;
            $productModel = $productCode ? MonetizationProduct::where('code', $productCode)->first() : null;
        }

        $config = $this->getActiveConfig($effectiveDate, $productCode);
        $taxEnabled = $this->isTaxEnabled($config);
        $isTaxable = $taxEnabled && $this->isProductTaxable($productModel, $config);

        // Case 1: Tax is Disabled or Product is Exempt
        if (! $taxEnabled || ! $isTaxable || ! $config) {
            return [
                'taxable_amount' => $taxableAmount,
                'tax_enabled' => false,
                'tax_name' => $config?->tax_name ?? 'GST',
                'tax_type' => $config?->tax_type ?? 'NONE',
                'tax_rate' => 0.00,
                'cgst_enabled' => false,
                'sgst_enabled' => false,
                'igst_enabled' => false,
                'cgst_rate' => 0.00,
                'sgst_rate' => 0.00,
                'igst_rate' => 0.00,
                'cgst' => 0.00,
                'sgst' => 0.00,
                'igst' => 0.00,
                'total_tax' => 0.00,
                'total_amount' => $taxableAmount,
                'tax_details' => [
                    'tax_enabled' => false,
                    'tax_name' => $config?->tax_name ?? 'GST',
                    'tax_rate' => 0.00,
                    'reason' => ! $taxEnabled ? 'Tax is disabled globally in configuration' : 'Product is exempt from tax',
                    'snapshot_date' => $effectiveDate->toIso8601String(),
                ],
            ];
        }

        // Case 2: Tax is Enabled & Product is Taxable
        $taxRate = (float) $config->tax_rate;
        $isInterstate = ! empty($context['is_interstate']);

        $cgst = 0.00;
        $sgst = 0.00;
        $igst = 0.00;
        $totalTax = 0.00;

        if ($isInterstate || $config->igst_enabled) {
            $igstRate = (float) ($config->igst_rate ?: $taxRate);
            $igst = round(($taxableAmount * $igstRate) / 100, 2);
            $totalTax = $igst;
        } else {
            $cgstRate = (float) ($config->cgst_rate ?: ($taxRate / 2));
            $sgstRate = (float) ($config->sgst_rate ?: ($taxRate / 2));

            $cgst = round(($taxableAmount * $cgstRate) / 100, 2);
            $sgst = round(($taxableAmount * $sgstRate) / 100, 2);
            $totalTax = round($cgst + $sgst, 2);
        }

        $totalAmount = round($taxableAmount + $totalTax, 2);

        $taxDetails = [
            'tax_enabled' => true,
            'tax_name' => $config->tax_name,
            'tax_type' => $config->tax_type,
            'tax_rate' => $taxRate,
            'cgst_enabled' => (bool) $config->cgst_enabled,
            'sgst_enabled' => (bool) $config->sgst_enabled,
            'igst_enabled' => (bool) $config->igst_enabled,
            'cgst_rate' => $config->cgst_rate ? (float) $config->cgst_rate : null,
            'sgst_rate' => $config->sgst_rate ? (float) $config->sgst_rate : null,
            'igst_rate' => $config->igst_rate ? (float) $config->igst_rate : null,
            'cgst' => $cgst,
            'sgst' => $sgst,
            'igst' => $igst,
            'total_tax' => $totalTax,
            'configuration_id' => $config->id,
            'snapshot_date' => $effectiveDate->toIso8601String(),
        ];

        return [
            'taxable_amount' => $taxableAmount,
            'tax_enabled' => true,
            'tax_name' => $config->tax_name,
            'tax_type' => $config->tax_type,
            'tax_rate' => $taxRate,
            'cgst_enabled' => (bool) $config->cgst_enabled,
            'sgst_enabled' => (bool) $config->sgst_enabled,
            'igst_enabled' => (bool) $config->igst_enabled,
            'cgst_rate' => (float) ($config->cgst_rate ?: ($taxRate / 2)),
            'sgst_rate' => (float) ($config->sgst_rate ?: ($taxRate / 2)),
            'igst_rate' => (float) ($config->igst_rate ?: $taxRate),
            'cgst' => $cgst,
            'sgst' => $sgst,
            'igst' => $igst,
            'total_tax' => $totalTax,
            'total_amount' => $totalAmount,
            'tax_details' => $taxDetails,
        ];
    }
}
