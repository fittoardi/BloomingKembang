<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomElement([79000, 89000, 99000, 119000]);
        $qty = fake()->numberBetween(1, 3);

        return [
            'order_id' => Order::factory(),
            'product_variant_id' => ProductVariant::factory(),
            'product_name' => 'Voal Ultrafine Signature Series',
            'variant_label' => 'Dusty Rose / 115x115',
            'sku' => strtoupper(fake()->bothify('BK-VOAL-####')),
            'price' => $price,
            'quantity' => $qty,
            'subtotal' => $price * $qty,
            'weight_gram' => 110 * $qty,
        ];
    }
}
