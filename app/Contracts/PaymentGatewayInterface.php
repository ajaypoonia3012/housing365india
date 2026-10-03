<?php

namespace App\Contracts;

use App\Models\ContactAccessPurchase;

interface PaymentGatewayInterface
{
    /**
     * Create an order with the payment provider.
     *
     * @return array{order_id: string, amount: float|int, currency: string, client_payload?: array}
     */
    public function createOrder(ContactAccessPurchase $purchase, array $options = []): array;

    /**
     * Verify payment confirmation server-side.
     *
     * @param  array<string, mixed>  $paymentData
     */
    public function verifyPayment(ContactAccessPurchase $purchase, array $paymentData): bool;
}
