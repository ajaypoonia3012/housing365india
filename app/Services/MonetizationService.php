<?php

namespace App\Services;

use App\Contracts\MonetizationGatewayInterface;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\MonetizationPlan;
use App\Models\MonetizationTransaction;
use App\Models\Promotion;
use App\Models\Property;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MonetizationService
{
    public function __construct(
        protected MonetizationGatewayInterface $gateway,
        protected TaxService $taxService
    ) {}

    /**
     * Validate and apply coupon code.
     */
    public function validateCoupon(string $code, float $amount, ?User $user = null, ?string $productCode = null): array
    {
        $code = strtoupper(trim($code));
        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();

        if (! $coupon) {
            throw ValidationException::withMessages(['coupon' => 'Invalid or expired coupon code.']);
        }

        $now = Carbon::now();
        if ($coupon->starts_at && $now->lt($coupon->starts_at)) {
            throw ValidationException::withMessages(['coupon' => 'Coupon promotion has not started yet.']);
        }
        if ($coupon->expires_at && $now->gt($coupon->expires_at)) {
            throw ValidationException::withMessages(['coupon' => 'Coupon has expired.']);
        }

        if ($coupon->min_purchase_amount && $amount < (float) $coupon->min_purchase_amount) {
            throw ValidationException::withMessages(['coupon' => "Minimum purchase of ₹{$coupon->min_purchase_amount} required to use this coupon."]);
        }

        if ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
            throw ValidationException::withMessages(['coupon' => 'Coupon usage limit has been reached.']);
        }

        if ($user && $coupon->usage_limit_per_user) {
            $userUsage = CouponRedemption::where('coupon_id', $coupon->id)->where('user_id', $user->id)->count();
            if ($userUsage >= $coupon->usage_limit_per_user) {
                throw ValidationException::withMessages(['coupon' => 'You have already reached the maximum usage for this coupon.']);
            }
        }

        if (! empty($coupon->applicable_product_codes) && $productCode) {
            if (! in_array($productCode, $coupon->applicable_product_codes)) {
                throw ValidationException::withMessages(['coupon' => 'Coupon is not valid for this product.']);
            }
        }

        // Calculate discount
        if ($coupon->discount_type === 'percentage') {
            $discount = round(($amount * (float) $coupon->discount_value) / 100, 2);
            if ($coupon->max_discount_amount && $discount > (float) $coupon->max_discount_amount) {
                $discount = (float) $coupon->max_discount_amount;
            }
        } else {
            $discount = min($amount, (float) $coupon->discount_value);
        }

        return [
            'coupon' => $coupon,
            'discount' => $discount,
            'final_amount' => max(0, round($amount - $discount, 2)),
        ];
    }

    /**
     * Create checkout order / transaction intent.
     */
    public function initiatePurchase(User $user, MonetizationPlan $plan, ?Property $property = null, ?string $couponCode = null, array $metadata = []): array
    {
        $product = $plan->product;
        $basePrice = (float) $plan->price;

        $discount = 0.00;
        $coupon = null;
        if (! empty($couponCode)) {
            $couponResult = $this->validateCoupon($couponCode, $basePrice, $user, $product?->code);
            $coupon = $couponResult['coupon'];
            $discount = $couponResult['discount'];
        }

        $taxableAmount = max(0, $basePrice - $discount);
        $taxCalculation = $this->taxService->calculate($taxableAmount, $product, $user, [
            'property' => $property,
        ]);

        $taxAmount = $taxCalculation['total_tax'];
        $totalAmount = $taxCalculation['total_amount'];

        $transactionNumber = 'TXN-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(4)));
        $idempotencyKey = 'trx_'.$user->id.'_'.$plan->id.'_'.($property?->id ?? 'np').'_'.bin2hex(random_bytes(6));

        // Create transaction record with full tax snapshot
        $transaction = MonetizationTransaction::create([
            'transaction_number' => $transactionNumber,
            'idempotency_key' => $idempotencyKey,
            'user_id' => $user->id,
            'monetization_plan_id' => $plan->id,
            'product_id' => $product?->id,
            'plan_id' => $plan->id,
            'property_id' => $property?->id,
            'coupon_id' => $coupon?->id,
            'amount' => $totalAmount,
            'net_amount' => $taxableAmount,
            'currency' => $plan->currency ?? 'INR',
            'tax_amount' => $taxAmount,
            'tax_enabled' => $taxCalculation['tax_enabled'],
            'tax_name' => $taxCalculation['tax_name'],
            'tax_rate' => $taxCalculation['tax_rate'],
            'tax_details' => $taxCalculation['tax_details'],
            'discount_amount' => $discount,
            'status' => 'pending',
            'gateway' => 'sandbox',
            'payable_type' => $property ? Property::class : null,
            'payable_id' => $property?->id,
            'metadata' => [
                'property_id' => $property?->id,
                'coupon_id' => $coupon?->id,
                'tax_snapshot' => $taxCalculation,
            ],
        ]);

        $gatewayOrder = $this->gateway->createOrder([
            'amount' => $totalAmount,
            'currency' => $plan->currency ?? 'INR',
            'receipt' => $transaction->idempotency_key,
        ]);

        $transaction->update([
            'gateway_order_id' => $gatewayOrder['order_id'],
            'gateway_payload' => $gatewayOrder,
        ]);

        return [
            'transaction' => $transaction,
            'order' => $gatewayOrder,
            'pricing' => [
                'base_price' => $basePrice,
                'discount' => $discount,
                'taxable_amount' => $taxableAmount,
                'tax' => $taxAmount,
                'tax_enabled' => $taxCalculation['tax_enabled'],
                'tax_name' => $taxCalculation['tax_name'],
                'tax_rate' => $taxCalculation['tax_rate'],
                'cgst' => $taxCalculation['cgst'],
                'sgst' => $taxCalculation['sgst'],
                'igst' => $taxCalculation['igst'],
                'total' => $totalAmount,
                'tax_details' => $taxCalculation['tax_details'],
            ],
        ];
    }

    /**
     * Server-side verify and complete payment.
     */
    public function verifyAndFulfill(string $idempotencyKey, array $paymentPayload): MonetizationTransaction
    {
        $transaction = MonetizationTransaction::where('idempotency_key', $idempotencyKey)->firstOrFail();

        // Idempotency: if already paid, return existing completed transaction
        if ($transaction->status === 'successful') {
            return $transaction;
        }

        // Verify with gateway provider server-side
        $isValid = $this->gateway->verifyPayment($paymentPayload);
        if (! $isValid) {
            $transaction->update([
                'status' => 'failed',
                'failure_reason' => 'Server-side signature verification failed',
            ]);
            throw ValidationException::withMessages(['payment' => 'Payment verification failed. Invalid gateway signature.']);
        }

        return DB::transaction(function () use ($transaction, $paymentPayload) {
            $now = Carbon::now();

            $transaction->update([
                'gateway_payment_id' => $paymentPayload['gateway_payment_id'],
                'gateway_signature' => $paymentPayload['gateway_signature'],
                'status' => 'successful',
                'paid_at' => $now,
            ]);

            $plan = $transaction->plan;
            $product = $plan?->product;
            $property = $transaction->metadata['property_id'] ? Property::find($transaction->metadata['property_id']) : null;

            // Redeem coupon if attached
            if ($transaction->coupon_id) {
                CouponRedemption::create([
                    'coupon_id' => $transaction->coupon_id,
                    'user_id' => $transaction->user_id,
                    'transaction_id' => $transaction->id,
                    'discount_applied' => $transaction->discount_amount,
                ]);
                $transaction->coupon()->increment('usage_count');
            }

            // Create Promotion if product is listing promotion (Featured / Boosted)
            if ($property && in_array($product?->code, ['FEATURED_LISTING', 'BOOSTED_LISTING', 'PREMIUM_MARKETING'])) {
                $days = $plan->validity_days ?? 30;
                Promotion::create([
                    'property_id' => $property->id,
                    'user_id' => $transaction->user_id,
                    'monetization_plan_id' => $plan->id,
                    'plan_id' => $plan->id,
                    'transaction_id' => $transaction->id,
                    'promotion_type' => strtolower($product->code),
                    'starts_at' => $now,
                    'ends_at' => $now->copy()->addDays($days),
                    'amount_paid' => $transaction->amount,
                    'payment_status' => 'paid',
                    'status' => 'active',
                    'is_active' => true,
                ]);

                // Update property flag if featured
                if ($product && $product->code === 'FEATURED_LISTING') {
                    $property->update(['featured' => true]);
                }
            }

            // Generate Tax Invoice
            $this->generateInvoice($transaction);

            return $transaction->fresh(['invoice', 'property']);
        });
    }

    /**
     * Generate Legal Tax Invoice using stored tax snapshot.
     */
    public function generateInvoice(MonetizationTransaction $transaction): Invoice
    {
        $plan = $transaction->plan;
        $product = $plan?->product;
        $subtotal = (float) $plan->price;
        $discount = (float) $transaction->discount_amount;
        $tax = (float) $transaction->tax_amount;
        $total = (float) $transaction->amount;

        // Retrieve immutable tax snapshot from transaction
        $taxSnapshot = $transaction->tax_details ?? ($transaction->metadata['tax_snapshot']['tax_details'] ?? ($transaction->metadata['tax_snapshot'] ?? null));
        $taxEnabled = $transaction->tax_enabled ?? ($taxSnapshot['tax_enabled'] ?? ($tax > 0));
        $taxName = $transaction->tax_name ?? ($taxSnapshot['tax_name'] ?? 'GST');
        $taxRate = (float) ($transaction->tax_rate ?? ($taxSnapshot['tax_rate'] ?? 0.00));
        $cgst = (float) ($taxSnapshot['cgst'] ?? 0.00);
        $sgst = (float) ($taxSnapshot['sgst'] ?? 0.00);
        $igst = (float) ($taxSnapshot['igst'] ?? 0.00);

        $invoiceNumber = 'INV-'.Carbon::now()->format('Y').'-'.str_pad($transaction->id, 6, '0', STR_PAD_LEFT);

        $invoice = Invoice::create([
            'transaction_id' => $transaction->id,
            'user_id' => $transaction->user_id,
            'invoice_number' => $invoiceNumber,
            'billing_name' => $transaction->user->name,
            'customer_name' => $transaction->user->name,
            'customer_email' => $transaction->user->email,
            'customer_phone' => $transaction->user->mobile ?? $transaction->user->phone,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'discount' => $discount,
            'tax_amount' => $tax,
            'tax' => $tax,
            'tax_enabled' => $taxEnabled,
            'tax_name' => $taxName,
            'tax_rate' => $taxRate,
            'cgst_amount' => $cgst,
            'sgst_amount' => $sgst,
            'igst_amount' => $igst,
            'tax_details' => $taxSnapshot,
            'total' => $total,
            'currency' => $transaction->currency ?? 'INR',
            'payment_status' => 'paid',
            'issued_at' => Carbon::now(),
            'paid_at' => Carbon::now(),
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => ($product?->name ?? 'Monetization Plan').' - '.$plan->name,
            'item_description' => ($product?->name ?? 'Monetization Plan').' - '.$plan->name,
            'quantity' => 1,
            'unit_price' => $subtotal,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $tax,
            'total' => $total,
        ]);

        return $invoice;
    }
}
