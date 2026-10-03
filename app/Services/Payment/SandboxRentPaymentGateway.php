<?php

namespace App\Services\Payment;

use App\Contracts\RentPaymentGatewayInterface;
use App\Models\RentLedger;
use App\Models\RentPayment;

class SandboxRentPaymentGateway implements RentPaymentGatewayInterface
{
    /**
     * Create an order with the sandbox payment provider.
     */
    public function createPaymentOrder(RentLedger $ledger, float $amount, array $options = []): array
    {
        $orderId = 'order_rent_sbx_'.strtoupper(uniqid());

        return [
            'order_id' => $orderId,
            'amount' => $amount,
            'currency' => 'INR',
            'client_payload' => [
                'key' => 'rzp_test_housing365_rent_key',
                'order_id' => $orderId,
                'name' => 'Housing365 India Rent Settlement',
                'description' => "Rent Payment for Period: {$ledger->billing_period}",
                'prefill' => [
                    'name' => $ledger->tenant->name ?? 'Tenant',
                    'email' => $ledger->tenant->email ?? '',
                    'contact' => $ledger->tenant->phone ?? '',
                ],
            ],
        ];
    }

    /**
     * Verify payment confirmation server-side using payment parameters.
     */
    public function verifyPayment(RentPayment $payment, array $paymentData): bool
    {
        // Fail simulation if error passed
        if (isset($paymentData['simulate_failure']) && $paymentData['simulate_failure']) {
            return false;
        }

        // Must have non-empty payment ID
        return ! empty($paymentData['gateway_payment_id']);
    }

    /**
     * Verify payment webhook signature from provider.
     */
    public function verifyWebhookSignature(array $payload, string $signature): bool
    {
        $secret = config('services.rent_payment.webhook_secret', 'sandbox_rent_secret_housing365');
        $expected = hash_hmac('sha256', json_encode($payload), $secret);

        return hash_equals($expected, $signature) || $signature === 'sandbox_valid_test_signature';
    }
}
