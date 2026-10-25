<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderStatusHistory>
 */
class OrderStatusHistoryFactory extends Factory
{
    protected $model = OrderStatusHistory::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'from_status' => 'pending_payment',
            'to_status' => 'paid',
            'changed_by' => null,
            'note' => 'Pembayaran berhasil dikonfirmasi oleh sistem gateway.',
            'created_at' => now(),
        ];
    }
}
