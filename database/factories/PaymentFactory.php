<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'provider' => 'midtrans',
            'provider_ref' => fake()->unique()->uuid(),
            'method' => fake()->randomElement(['qris', 'bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'gopay']),
            'amount' => 193000,
            'status' => PaymentStatus::Pending,
            'payment_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/'.fake()->uuid(),
            'expires_at' => now()->addHours(24),
            'paid_at' => null,
            'raw_payload' => ['transaction_status' => 'pending'],
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Paid,
            'paid_at' => now(),
            'raw_payload' => ['transaction_status' => 'settlement'],
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Failed,
            'raw_payload' => ['transaction_status' => 'deny'],
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Expired,
            'raw_payload' => ['transaction_status' => 'expire'],
        ]);
    }
}
