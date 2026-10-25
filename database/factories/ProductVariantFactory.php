<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = [
            ['name' => 'Dusty Rose', 'hex' => '#c89d9c'],
            ['name' => 'Muted Sage', 'hex' => '#a9aea9'],
            ['name' => 'Obsidian Black', 'hex' => '#1c1717'],
            ['name' => 'Almond Milk', 'hex' => '#e8ded4'],
            ['name' => 'Warm Taupe', 'hex' => '#b39c8e'],
            ['name' => 'Terracotta Blossom', 'hex' => '#c97a63'],
            ['name' => 'Soft Lilac', 'hex' => '#bfa3b8'],
            ['name' => 'Deep Navy', 'hex' => '#212d40'],
            ['name' => 'Olive Forest', 'hex' => '#5e6852'],
            ['name' => 'Pale Mauve', 'hex' => '#9e7b88'],
        ];

        $chosenColor = fake()->randomElement($colors);
        $basePrice = fake()->randomElement([69000, 79000, 89000, 99000, 119000, 129000]);

        return [
            'product_id' => Product::factory(),
            'sku' => strtoupper(fake()->unique()->bothify('BK-???-#####')),
            'color' => $chosenColor['name'],
            'color_hex' => $chosenColor['hex'],
            'size' => fake()->randomElement(['115x115', '120x120', '180x75', 'All Size']),
            'price' => $basePrice,
            'compare_price' => fake()->boolean(40) ? $basePrice + 20000 : null,
            'stock' => fake()->numberBetween(15, 60),
            'reserved' => 0,
            'weight_gram' => fake()->randomElement([100, 110, 120, 130, 150]),
            'is_active' => true,
            'position' => fake()->numberBetween(1, 10),
        ];
    }

    public function discounted(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => 79000,
            'compare_price' => 109000,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
            'reserved' => 0,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
