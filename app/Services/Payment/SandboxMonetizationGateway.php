<?php

namespace App\Services\Payment;

use App\Contracts\MonetizationGatewayInterface;

class SandboxMonetizationGateway implements MonetizationGatewayInterface
{
    public function createOrder(array $params): array
    {
        $orderId = 'ord_sbx_'.bin2hex(random_bytes(8));

        return [
            'success' => true,
            'gateway' => 'sandbox',
            'order_id' => $orderId,
            'amount' => $params['amount'] ?? 0,
            'currency' => $params['currency'] ?? 'INR',
            'key' => 'sbx_publishable_key_phase9',
        ];
    }

    public function verifyPayment(array $payload): bool
    {
        // Require server-side verification token or valid order confirmation
        if (empty($payload['gateway_payment_id']) || empty($payload['gateway_signature'])) {
            return false;
        }

        // Test signature validator
        return hash_equals(
            hash('sha256', ($payload['gateway_order_id'] ?? '').'|'.($payload['gateway_payment_id'] ?? '').'|housing365_secret'),
            $payload['gateway_signature']
        ) || ($payload['gateway_signature'] === 'sandbox_valid_signature');
    }

    public function refund(string $transactionId, float $amount): array
    {
        return [
            'success' => true,
            'refund_id' => 'ref_sbx_'.bin2hex(random_bytes(8)),
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'status' => 'processed',
        ];
    }
}
