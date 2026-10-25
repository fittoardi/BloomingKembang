<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Hijab Segi Empat',
            'Pashmina Silk',
            'Pashmina Ceruty',
            'Bergo Daily',
            'Khimar Syar\'i',
            'Inner Rajut Anti Pusing',
            'Scrunchie Jumbo',
            'Bros & Peniti Butik',
            'Mukena Travel Silk',
            'Manset Gamis Rayon',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(12),
            'position' => fake()->numberBetween(1, 20),
            'is_active' => true,
            'seo_title' => $name.' Premium — Koleksi Eksklusif BloomingKembang',
            'seo_description' => 'Temukan koleksi '.strtolower($name).' berkualitas terbaik dan nyaman dipakai seharian.',
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
