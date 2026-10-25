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

    public function configure(): static
    {
        return $this->afterCreating(function (Product $product): void {
            if ($product->hasMedia('images')) {
                return;
            }

            $imagesDir = database_path('seeders/images');
            if (! is_dir($imagesDir)) {
                return;
            }

            $name = strtolower($product->name);
            $imageFile = match (true) {
                str_contains($name, 'pashmina silk') => 'pashmina-silk.jpg',
                str_contains($name, 'pashmina') => 'pashmina-ceruty.jpg',
                str_contains($name, 'paris') => 'paris-laser-cut.jpg',
                str_contains($name, 'voal') => 'voal-signature.jpg',
                str_contains($name, 'bergo') => 'bergo-daily.jpg',
                str_contains($name, 'khimar') => 'khimar-syari.jpg',
                str_contains($name, 'mukena') => 'mukena-silk.jpg',
                str_contains($name, 'ciput') => 'ciput-ninja.jpg',
                str_contains($name, 'klip') || str_contains($name, 'bros') || str_contains($name, 'scrunchie') => 'klip-turki.jpg',
                default => 'voal-signature.jpg',
            };

            $imagePath = $imagesDir.'/'.$imageFile;
            if (file_exists($imagePath)) {
                $product->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        });
    }

    public function withImage(?string $imageFile = null): static
    {
        return $this->afterCreating(function (Product $product) use ($imageFile): void {
            $imageFile ??= 'voal-signature.jpg';
            $imagePath = database_path('seeders/images/'.$imageFile);
            if (file_exists($imagePath)) {
                $product->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        });
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
