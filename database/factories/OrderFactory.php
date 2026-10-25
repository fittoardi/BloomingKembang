<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomElement([89000, 158000, 178000, 245000, 310000]);
        $shippingCost = fake()->randomElement([12000, 15000, 18000, 22000]);
        $discount = 0;
        $grandTotal = $subtotal + $shippingCost - $discount;

        return [
            'number' => 'INV-'.date('Ymd').'-'.fake()->unique()->numerify('#####'),
            'user_id' => User::factory(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '628'.fake()->numerify('##########'),
            'shipping_address' => [
                'recipient_name' => fake()->name(),
                'phone' => '628'.fake()->numerify('##########'),
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'district' => 'Coblong',
                'postal_code' => '40132',
                'detail' => 'Jl. Dago Asri No. '.fake()->buildingNumber(),
            ],
            'subtotal' => $subtotal,
            'discount_total' => $discount,
            'shipping_cost' => $shippingCost,
            'grand_total' => $grandTotal,
            'voucher_code' => null,
            'status' => OrderStatus::PendingPayment,
            'payment_status' => PaymentStatus::Pending,
            'courier' => fake()->randomElement(['JNE', 'J&T', 'SiCepat']),
            'courier_service' => 'REG',
            'tracking_number' => null,
            'total_weight_gram' => fake()->randomElement([150, 220, 330, 450]),
            'customer_note' => fake()->boolean(20) ? 'Mohon dipacking rapi untuk kado ya kak.' : null,
            'internal_note' => null,
            'expires_at' => now()->addHours(24),
            'paid_at' => null,
            'shipped_at' => null,
            'completed_at' => null,
            'cancelled_at' => null,
            'cancel_reason' => null,
        ];
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Paid,
            'payment_status' => PaymentStatus::Paid,
            'paid_at' => now(),
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'paid_at' => now()->subHours(2),
        ]);
    }

    public function shipped(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Shipped,
            'payment_status' => PaymentStatus::Paid,
            'paid_at' => now()->subDay(),
            'shipped_at' => now()->subHours(5),
            'tracking_number' => 'BK'.fake()->numerify('############'),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Completed,
            'payment_status' => PaymentStatus::Paid,
            'paid_at' => now()->subDays(4),
            'shipped_at' => now()->subDays(3),
            'completed_at' => now()->subDay(),
            'tracking_number' => 'BK'.fake()->numerify('############'),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Cancelled,
            'payment_status' => PaymentStatus::Expired,
            'cancelled_at' => now(),
            'cancel_reason' => 'Batas waktu pembayaran telah habis (otomatis oleh sistem)',
        ]);
    }
}
