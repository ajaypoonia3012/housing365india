<?php

namespace App\Contracts;

use App\Models\CareInvoice;

interface CarePaymentGatewayInterface
{
    /**
     * Create a payment order for the Care invoice.
     *
     * @param  array<string, mixed>  $options
     * @return array{order_id: string, amount: float|int, currency: string, client_payload?: array}
     */
    public function createPaymentOrder(CareInvoice $invoice, array $options = []): array;

    /**
     * Verify payment confirmation server-side using payment parameters.
     *
     * @param  array<string, mixed>  $paymentData
     */
    public function verifyPayment(CareInvoice $invoice, array $paymentData): bool;

    /**
     * Verify payment webhook signature from provider.
     *
     * @param  array<string, mixed>  $payload
     */
    public function verifyWebhookSignature(array $payload, string $signature): bool;
}
