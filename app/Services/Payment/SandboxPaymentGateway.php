<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Models\ContactAccessPurchase;
use Illuminate\Support\Str;

class SandboxPaymentGateway implements PaymentGatewayInterface
{
    /**
     * Create an order for sandbox/testing environment.
     * Guarded: Strictly disabled in production environment.
     */
    public function createOrder(ContactAccessPurchase $purchase, array $options = []): array
    {
        if (app()->isProduction()) {
            throw new \RuntimeException('SandboxPaymentGateway is strictly restricted to local and testing environments. Configure a production payment provider (e.g. Razorpay) for production.');
        }

        $orderId = 'sandbox_order_'.Str::random(12);

        $purchase->update([
            'payment_gateway' => 'sandbox_development_only',
            'gateway_order_id' => $orderId,
            'payment_status' => 'pending',
        ]);

        return [
            'order_id' => $orderId,
            'amount' => (float) $purchase->amount_paid,
            'currency' => $purchase->currency,
            'client_payload' => [
                'provider' => 'sandbox',
                'order_id' => $orderId,
                'key' => 'sandbox_test_key',
            ],
        ];
    }

    /**
     * Verify payment confirmation server-side.
     * In Sandbox mode, requires valid signature token or secret verification.
     */
    public function verifyPayment(ContactAccessPurchase $purchase, array $paymentData): bool
    {
        if (app()->isProduction()) {
            return false;
        }

        // Enforce strict server-side validation: must match order id and signature
        if (empty($paymentData['gateway_payment_id']) || empty($paymentData['gateway_order_id'])) {
            return false;
        }

        if ($paymentData['gateway_order_id'] !== $purchase->gateway_order_id) {
            return false;
        }

        // Validate verification token / signature
        $expectedSignature = hash_hmac('sha256', $purchase->gateway_order_id.'|'.$paymentData['gateway_payment_id'], config('app.key'));
        if (! isset($paymentData['signature']) || ! hash_equals($expectedSignature, (string) $paymentData['signature'])) {
            // For testing convenience, also accept test token if running in testing environment
            if (! app()->environment('testing', 'local') || $paymentData['signature'] !== 'test_signature_valid') {
                return false;
            }
        }

        return true;
    }
}
