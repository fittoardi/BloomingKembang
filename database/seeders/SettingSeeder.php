<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'store.name' => 'BloomingKembang',
            'store.tagline' => 'Sentuhan Lembut & Elegan Hijab Pilihan',
            'store.phone' => '6281234567890',
            'store.whatsapp' => '6281234567890',
            'store.email' => 'cs@bloomingkembang.com',
            'store.warehouse_address' => [
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'district' => 'Coblong',
                'postal_code' => '40132',
                'detail' => 'Jl. Dago Asri No. 12',
            ],
            'shipping.couriers' => ['jne', 'jnt', 'sicepat'],
            'checkout.guest_enabled' => true,
            'order.payment_expiry_hours' => 24,
            'analytics.ga4_id' => null,
            'analytics.meta_pixel_id' => null,
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
