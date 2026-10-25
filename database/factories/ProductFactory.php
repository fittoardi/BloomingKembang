<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $adjective = fake()->randomElement(['Signature', 'Exclusive', 'Elegance', 'Daily Basic', 'Monogram', 'Deluxe', 'Heritage']);
        $type = fake()->randomElement(['Voal Ultrafine', 'Ceruty Flowy', 'Paris Japan', 'Silk Crinkle', 'Jersey Premium', 'Chiffon Silk']);
        $name = $type.' '.$adjective.' '.fake()->unique()->numberBetween(100, 999);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'short_description' => fake()->paragraph(1),
            'description' => '<p>'.fake()->paragraph(3).'</p><p>'.fake()->paragraph(2).'</p>',
            'material' => fake()->randomElement([
                'Voal Ultrafine Turkish Grade A',
                'Ceruty Babydoll Armani',
                'Paris Premium Japan',
                'Silk Satin Velvet',
                'Rayon Twill Organik',
            ]),
            'size_guide' => '115 cm x 115 cm (toleransi jahit tepi 1-2 cm)',
            'care_guide' => 'Cuci lembut dengan tangan menggunakan detergen cair, jangan peras, setrika dengan suhu rendah.',
            'status' => ProductStatus::Active,
            'is_featured' => fake()->boolean(25),
            'sold_count' => fake()->numberBetween(0, 300),
            'rating_avg' => fake()->randomElement([4.8, 4.9, 5.0]),
            'rating_count' => fake()->numberBetween(5, 80),
            'seo_title' => $name.' — BloomingKembang',
            'seo_description' => 'Beli '.$name.' original kualitas butik premium, nyaman dan tidak mudah kusut.',
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProductStatus::Draft,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProductStatus::Archived,
        ]);
    }
}
