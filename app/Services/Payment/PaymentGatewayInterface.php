<?php

namespace App\Services\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;

interface PaymentGatewayInterface
{
    /**
     * Create a payment transaction for an order.
     *
     * @return array{snap_token: string, redirect_url: string}
     */
    public function createTransaction(Order $order): array;

    /**
     * Verify the webhook notification signature.
     *
     * @param  array<string, mixed>  $payload
     */
    public function verifySignature(array $payload): bool;

    /**
     * Parse payment status from webhook notification.
     *
     * @param  array<string, mixed>  $payload
     * @return array{payment_status: PaymentStatus, order_status: OrderStatus, method: ?string, transaction_id: string}
     */
    public function parseStatus(array $payload): array;
}
