<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locations = [
            ['province' => 'DKI Jakarta', 'city' => 'Jakarta Selatan', 'district' => 'Tebet', 'postal_code' => '12810'],
            ['province' => 'Jawa Barat', 'city' => 'Bandung', 'district' => 'Coblong', 'postal_code' => '40132'],
            ['province' => 'Jawa Barat', 'city' => 'Bekasi', 'district' => 'Bekasi Barat', 'postal_code' => '17145'],
            ['province' => 'Jawa Tengah', 'city' => 'Semarang', 'district' => 'Candisari', 'postal_code' => '50257'],
            ['province' => 'DI Yogyakarta', 'city' => 'Yogyakarta', 'district' => 'Danurejan', 'postal_code' => '55212'],
            ['province' => 'Jawa Timur', 'city' => 'Surabaya', 'district' => 'Gubeng', 'postal_code' => '60281'],
        ];

        $loc = fake()->randomElement($locations);

        return [
            'user_id' => User::factory(),
            'label' => fake()->randomElement(['Rumah', 'Kantor', 'Apartemen', 'Rumah Orang Tua']),
            'recipient_name' => fake()->name(),
            'phone' => '628'.fake()->numerify('##########'),
            'province' => $loc['province'],
            'city' => $loc['city'],
            'district' => $loc['district'],
            'postal_code' => $loc['postal_code'],
            'detail' => 'Jl. '.fake()->streetName().' No. '.fake()->buildingNumber().', RT 03/RW 04',
            'shipping_area_id' => 'ID-'.fake()->numerify('######'),
            'is_default' => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }
}
