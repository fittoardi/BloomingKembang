<?php

namespace App\Services;

use App\Enums\VoucherType;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use RuntimeException;

class VoucherService
{
    /**
     * Validasi kode voucher berdasarkan status, tanggal berlaku, kuota, min belanja, dan limit per user.
     *
     * @throws RuntimeException
     */
    public function validate(string $code, int $subtotal, ?int $userId = null): Voucher
    {
        $voucher = Voucher::where('code', strtoupper(trim($code)))->first();

        if (! $voucher) {
            throw new RuntimeException('Kode voucher tidak ditemukan.');
        }

        if (! $voucher->is_active) {
            throw new RuntimeException('Voucher ini sudah tidak aktif.');
        }

        $now = now();

        if ($voucher->starts_at && $now->lt($voucher->starts_at)) {
            throw new RuntimeException('Voucher belum dapat digunakan saat ini.');
        }

        if ($voucher->ends_at && $now->gt($voucher->ends_at)) {
            throw new RuntimeException('Masa berlaku voucher telah berakhir.');
        }

        if ($voucher->quota !== null && $voucher->used_count >= $voucher->quota) {
            throw new RuntimeException('Kuota penggunaan voucher telah habis.');
        }

        if ($voucher->min_purchase !== null && $subtotal < $voucher->min_purchase) {
            $minFormatted = 'Rp '.number_format($voucher->min_purchase, 0, ',', '.');
            throw new RuntimeException("Minimal pembelanjaan untuk memakai voucher ini adalah {$minFormatted}.");
        }

        if ($userId && $voucher->per_user_limit !== null) {
            $userUsageCount = VoucherUsage::where('voucher_id', $voucher->id)
                ->where('user_id', $userId)
                ->count();

            if ($userUsageCount >= $voucher->per_user_limit) {
                throw new RuntimeException('Anda telah mencapai batas maksimal penggunaan voucher ini.');
            }
        }

        return $voucher;
    }

    /**
     * Hitung nilai diskon yang didapat dari voucher.
     */
    public function calculateDiscount(Voucher $voucher, int $subtotal): int
    {
        if ($voucher->type === VoucherType::Percent) {
            $rawDiscount = (int) round(($subtotal * $voucher->value) / 100);

            if ($voucher->max_discount !== null) {
                return (int) min($rawDiscount, $voucher->max_discount);
            }

            return (int) min($rawDiscount, $subtotal);
        }

        // Tipe Fixed
        return (int) min($voucher->value, $subtotal);
    }
}
