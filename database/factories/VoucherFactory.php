<?php

namespace Database\Factories;

use App\Enums\VoucherType;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Voucher>
 */
class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('BLOOM???##')),
            'description' => fake()->sentence(8),
            'type' => VoucherType::Percent,
            'value' => fake()->randomElement([10, 15, 20, 25]),
            'min_purchase' => fake()->randomElement([0, 50000, 100000, 150000]),
            'max_discount' => 25000,
            'quota' => fake()->numberBetween(50, 500),
            'used_count' => 0,
            'per_user_limit' => 1,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonths(2),
            'is_active' => true,
        ];
    }

    public function fixed(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => VoucherType::Fixed,
            'value' => fake()->randomElement([15000, 20000, 25000, 30000]),
            'max_discount' => null,
            'min_purchase' => 100000,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->subDay(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
