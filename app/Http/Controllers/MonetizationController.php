<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\MonetizationPlan;
use App\Models\MonetizationProduct;
use App\Models\Property;
use App\Services\MonetizationService;
use App\Services\TaxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonetizationController extends Controller
{
    public function __construct(
        protected MonetizationService $monetizationService
    ) {}

    /**
     * Show monetization pricing & plans catalog.
     */
    public function index(Request $request)
    {
        $products = MonetizationProduct::with(['plans' => function ($q) {
            $q->where('is_active', true)->orderBy('price', 'asc');
        }])->where('is_active', true)->get();

        $userProperties = Auth::check()
            ? Property::where('user_id', Auth::id())->where('status', 'active')->get()
            : collect();

        $selectedPropertyId = $request->query('property_id');
        $taxConfig = app(TaxService::class)->getActiveConfig();

        return view('monetization.plans', compact('products', 'userProperties', 'selectedPropertyId', 'taxConfig'));
    }

    /**
     * Validate Coupon endpoint.
     */
    public function validateCoupon(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'product_code' => 'nullable|string',
        ]);

        try {
            $result = $this->monetizationService->validateCoupon(
                $validated['coupon_code'],
                (float) $validated['amount'],
                Auth::user(),
                $validated['product_code'] ?? null
            );

            $taxCalculation = app(TaxService::class)->calculate(
                $result['final_amount'],
                $validated['product_code'] ?? null,
                Auth::user()
            );

            return response()->json([
                'success' => true,
                'discount' => $result['discount'],
                'taxable_amount' => $result['final_amount'],
                'tax' => $taxCalculation['total_tax'],
                'tax_enabled' => $taxCalculation['tax_enabled'],
                'tax_name' => $taxCalculation['tax_name'],
                'tax_rate' => $taxCalculation['tax_rate'],
                'final_amount' => $taxCalculation['total_amount'],
                'total_amount' => $taxCalculation['total_amount'],
                'message' => 'Coupon code applied successfully!',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Initiate checkout transaction.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:monetization_plans,id',
            'property_id' => 'nullable|exists:properties,id',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        $plan = MonetizationPlan::findOrFail($validated['plan_id']);
        $property = ! empty($validated['property_id']) ? Property::findOrFail($validated['property_id']) : null;

        // Security: Ensure property belongs to user if provided
        if ($property && $property->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized property selection.');
        }

        $checkout = $this->monetizationService->initiatePurchase(
            Auth::user(),
            $plan,
            $property,
            $validated['coupon_code'] ?? null
        );

        return response()->json([
            'success' => true,
            'idempotency_key' => $checkout['transaction']->idempotency_key,
            'gateway_order_id' => $checkout['order']['order_id'],
            'amount' => $checkout['pricing']['total'],
            'currency' => $checkout['order']['currency'],
            'key' => $checkout['order']['key'],
            'pricing' => $checkout['pricing'],
        ]);
    }

    /**
     * Server-side verify payment webhook / callback.
     */
    public function verifyPayment(Request $request)
    {
        $validated = $request->validate([
            'idempotency_key' => 'required|string',
            'gateway_order_id' => 'required|string',
            'gateway_payment_id' => 'required|string',
            'gateway_signature' => 'required|string',
        ]);

        try {
            $transaction = $this->monetizationService->verifyAndFulfill(
                $validated['idempotency_key'],
                $validated
            );

            return response()->json([
                'success' => true,
                'status' => 'successful',
                'transaction_id' => $transaction->id,
                'invoice_id' => $transaction->invoice?->id,
                'invoice_number' => $transaction->invoice?->invoice_number,
                'message' => 'Payment verified and service activated successfully!',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * View Invoice.
     */
    public function showInvoice(Invoice $invoice)
    {
        // Security check
        if ($invoice->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized to view this invoice.');
        }

        $invoice->load(['items', 'transaction.plan', 'transaction.product']);

        return view('monetization.invoice', compact('invoice'));
    }
}
