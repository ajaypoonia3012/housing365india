<?php

namespace App\Contracts;

use App\Models\RentLedger;
use App\Models\RentPayment;

interface RentPaymentGatewayInterface
{
    /**
     * Create an order with the payment provider for rent settlement.
     *
     * @param  array<string, mixed>  $options
     * @return array{order_id: string, amount: float|int, currency: string, client_payload?: array}
     */
    public function createPaymentOrder(RentLedger $ledger, float $amount, array $options = []): array;

    /**
     * Verify payment confirmation server-side using payment parameters.
     *
     * @param  array<string, mixed>  $paymentData
     */
    public function verifyPayment(RentPayment $payment, array $paymentData): bool;

    /**
     * Verify payment webhook signature from provider.
     *
     * @param  array<string, mixed>  $payload
     */
    public function verifyWebhookSignature(array $payload, string $signature): bool;
}
