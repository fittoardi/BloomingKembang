<?php

namespace App\Services\Shipping;

interface ShippingProviderInterface
{
    /**
     * Hitung tarif ongkos kirim berdasarkan alamat tujuan dan berat total (gram).
     *
     * @param  array{province?: string, city?: string, district?: string, postal_code?: string}  $destination
     * @return array<int, array{courier: string, service: string, name: string, cost: int, etd: string}>
     */
    public function calculateRates(array $destination, int $totalWeightGram): array;
}
