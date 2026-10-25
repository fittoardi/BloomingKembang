<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_variant_id' => ProductVariant::factory(),
            'type' => 'adjustment',
            'quantity' => 25,
            'stock_after' => 25,
            'order_id' => null,
            'user_id' => null,
            'note' => 'Penyesuaian stok awal',
            'created_at' => now(),
        ];
    }

    public function sale(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'sale',
            'quantity' => -1,
            'stock_after' => 24,
            'order_id' => Order::factory(),
            'note' => 'Penjualan pesanan pelanggan',
        ]);
    }

    public function restock(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'adjustment',
            'quantity' => 50,
            'user_id' => User::factory(),
            'note' => 'Restok batch produksi baru dari gudang',
        ]);
    }
}
