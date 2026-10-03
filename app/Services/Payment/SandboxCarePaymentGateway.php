<?php

namespace App\Services\Payment;

use App\Contracts\CarePaymentGatewayInterface;
use App\Models\CareInvoice;
use RuntimeException;

class SandboxCarePaymentGateway implements CarePaymentGatewayInterface
{
    public function __construct()
    {
        if (app()->environment('production')) {
            throw new RuntimeException('FATAL SECURITY LOCK: SandboxCarePaymentGateway cannot operate in a production environment.');
        }
    }

    public function createPaymentOrder(CareInvoice $invoice, array $options = []): array
    {
        $orderId = 'order_care_sbx_'.bin2hex(random_bytes(6));

        return [
            'order_id' => $orderId,
            'amount' => (float) $invoice->total_amount,
            'currency' => 'INR',
            'client_payload' => [
                'gateway' => 'housing365_sandbox_care',
                'environment' => 'sandbox',
                'order_id' => $orderId,
                'invoice_number' => $invoice->invoice_number,
                'amount' => (float) $invoice->total_amount,
                'mock_signature' => hash_hmac('sha256', $orderId.'|'.$invoice->total_amount, config('app.key', 'secret_key')),
            ],
        ];
    }

    public function verifyPayment(CareInvoice $invoice, array $paymentData): bool
    {
        $orderId = $paymentData['gateway_order_id'] ?? null;
        $paymentId = $paymentData['gateway_payment_id'] ?? null;
        $signature = $paymentData['gateway_signature'] ?? null;

        if (! $orderId || ! $paymentId) {
            return false;
        }

        // Validate HMAC signature for sandbox
        $expectedSignature = hash_hmac('sha256', $orderId.'|'.$invoice->total_amount, config('app.key', 'secret_key'));

        return hash_equals($expectedSignature, (string) $signature);
    }

    public function verifyWebhookSignature(array $payload, string $signature): bool
    {
        $expected = hash_hmac('sha256', json_encode($payload), config('app.key', 'secret_key'));

        return hash_equals($expected, $signature);
    }
}
