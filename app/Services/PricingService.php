<?php

namespace App\Services;

use App\Models\Voucher;

class PricingService
{
    public function __construct(
        public VoucherService $voucherService
    ) {}

    /**
     * Hitung total rincian pembayaran pesanan (subtotal, diskon voucher, ongkir, grand total).
     *
     * @return array{subtotal: int, discount_total: int, shipping_cost: int, grand_total: int, voucher: ?Voucher}
     */
    public function calculate(int $subtotal, ?Voucher $voucher = null, int $shippingCost = 0): array
    {
        $discountTotal = 0;

        if ($voucher) {
            $discountTotal = $this->voucherService->calculateDiscount($voucher, $subtotal);
        }

        $grandTotal = max(0, ($subtotal - $discountTotal) + $shippingCost);

        return [
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'shipping_cost' => $shippingCost,
            'grand_total' => $grandTotal,
            'voucher' => $voucher,
        ];
    }
}
