<?php

namespace App\Contracts;

interface MonetizationGatewayInterface
{
    /**
     * Create an order / payment intent with the gateway.
     */
    public function createOrder(array $params): array;

    /**
     * Verify payment signature / server-side verification.
     */
    public function verifyPayment(array $payload): bool;

    /**
     * Process refund if applicable.
     */
    public function refund(string $transactionId, float $amount): array;
}
