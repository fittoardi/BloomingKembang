<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Cache;

class SimulatedShippingProvider implements ShippingProviderInterface
{
    /**
     * Hitung tarif ongkir per kurir dengan cache singkat.
     */
    public function calculateRates(array $destination, int $totalWeightGram): array
    {
        $city = strtolower($destination['city'] ?? 'bandung');
        $province = strtolower($destination['province'] ?? 'jawa barat');
        $weightKg = max(1, (int) ceil($totalWeightGram / 1000));

        $cacheKey = 'shipping_rates_'.md5(json_encode([$city, $province, $weightKg]));

        return Cache::remember($cacheKey, 600, function () use ($city, $province, $weightKg) {
            // Tentukan tarif dasar per kg berdasarkan wilayah tujuan
            $baseRate = 11000;

            if (str_contains($city, 'bandung') || str_contains($province, 'jawa barat')) {
                $baseRate = 10000;
            } elseif (str_contains($province, 'dki') || str_contains($city, 'jakarta') || str_contains($province, 'banten')) {
                $baseRate = 12000;
            } elseif (str_contains($province, 'jawa tengah') || str_contains($province, 'yogyakarta') || str_contains($province, 'jawa timur')) {
                $baseRate = 15000;
            } elseif (str_contains($province, 'sumatera') || str_contains($province, 'bali')) {
                $baseRate = 26000;
            } else {
                $baseRate = 38000;
            }

            return [
                [
                    'courier' => 'JNE',
                    'service' => 'REG',
                    'name' => 'JNE Reguler',
                    'cost' => $baseRate * $weightKg,
                    'etd' => '2-3 hari',
                ],
                [
                    'courier' => 'J&T',
                    'service' => 'EZ',
                    'name' => 'J&T Express EZ',
                    'cost' => ($baseRate + 2000) * $weightKg,
                    'etd' => '1-2 hari',
                ],
                [
                    'courier' => 'SiCepat',
                    'service' => 'BEST',
                    'name' => 'SiCepat Express BEST (Next Day)',
                    'cost' => ($baseRate + 9000) * $weightKg,
                    'etd' => '1 hari',
                ],
            ];
        });
    }
}
