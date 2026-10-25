<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Banner>
 */
class BannerFactory extends Factory
{
    protected $model = Banner::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement([
                'Koleksi Voal Ultrafine Signature 2026',
                'Pashmina Ceruty Flowy Series',
                'PROMO SPESIAL GAJIAN — DISKON UP TO 20%',
                'Gratis Ongkir Seluruh Indonesia',
            ]),
            'subtitle' => fake()->sentence(8),
            'image' => 'banners/banner-'.fake()->numberBetween(1, 4).'.webp',
            'link_url' => '/koleksi',
            'position' => fake()->randomElement(['hero', 'top_bar']),
            'sort_order' => fake()->numberBetween(1, 5),
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonths(2),
            'is_active' => true,
        ];
    }

    public function hero(): static
    {
        return $this->state(fn (array $attributes) => [
            'position' => 'hero',
        ]);
    }

    public function topBar(): static
    {
        return $this->state(fn (array $attributes) => [
            'position' => 'top_bar',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
