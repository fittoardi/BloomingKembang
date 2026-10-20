<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\StockMovement;
use App\Models\VoucherUsage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class CheckoutService
{
    public function __construct(
        public CartService $cartService,
        public PricingService $pricingService,
        public VoucherService $voucherService
    ) {}

    /**
     * Buat pesanan baru dan reservasi stok secara atomik.
     *
     * @param  array{name: string, email: string, phone: string}  $customerData
     * @param  array{recipient_name: string, phone: string, province: string, city: string, district: string, postal_code: string, detail: string}  $shippingAddress
     *
     * @throws RuntimeException
     */
    public function createOrder(
        array $customerData,
        array $shippingAddress,
        string $courier,
        string $courierService,
        int $shippingCost,
        ?string $voucherCode = null,
        ?string $customerNote = null
    ): Order {
        $user = Auth::user();

        // Cek toggle guest checkout
        if (! $user && ! (bool) Setting::get('checkout.guest_enabled', true)) {
            throw new RuntimeException('Guest checkout sedang dinonaktifkan. Silakan masuk atau daftar terlebih dahulu.');
        }

        $items = $this->cartService->getItems();

        if ($items->isEmpty()) {
            throw new RuntimeException('Keranjang belanja Anda kosong.');
        }

        $subtotal = $this->cartService->getSubtotal();
        $totalWeightGram = $this->cartService->getTotalWeightGram();

        // Validasi voucher jika ada
        $voucher = null;
        if ($voucherCode) {
            $voucher = $this->voucherService->validate($voucherCode, $subtotal, $user?->id);
        }

        $pricing = $this->pricingService->calculate($subtotal, $voucher, $shippingCost);

        return DB::transaction(function () use (
            $user,
            $customerData,
            $shippingAddress,
            $items,
            $pricing,
            $courier,
            $courierService,
            $totalWeightGram,
            $customerNote
        ) {
            $orderNumber = 'ORD-'.date('Ymd').'-'.strtoupper(Str::random(6));
            $expiryHours = (int) Setting::get('order.payment_expiry_hours', 24);
            $expiresAt = now()->addHours($expiryHours);

            // 1. Kunci baris varian dan periksa kecukupan stok dengan lockForUpdate
            $lockedVariants = [];
            foreach ($items as $item) {
                $variant = ProductVariant::where('id', $item->product_variant_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $availableStock = $variant->stock - $variant->reserved;

                if ($availableStock < $item->quantity) {
                    $prodName = $variant->product?->name ?? 'Produk';
                    throw new RuntimeException("Stok untuk varian {$prodName} ({$variant->color}) tidak mencukupi (sisa {$availableStock} pcs).");
                }

                $lockedVariants[$variant->id] = $variant;
            }

            // 2. Buat entitas Order
            $order = Order::create([
                'number' => $orderNumber,
                'user_id' => $user?->id,
                'customer_name' => $customerData['name'],
                'customer_email' => $customerData['email'],
                'customer_phone' => $customerData['phone'],
                'shipping_address' => $shippingAddress,
                'subtotal' => $pricing['subtotal'],
                'discount_total' => $pricing['discount_total'],
                'shipping_cost' => $pricing['shipping_cost'],
                'grand_total' => $pricing['grand_total'],
                'voucher_code' => $pricing['voucher']?->code,
                'status' => OrderStatus::PendingPayment,
                'payment_status' => PaymentStatus::Pending,
                'courier' => $courier,
                'courier_service' => $courierService,
                'total_weight_gram' => $totalWeightGram,
                'customer_note' => $customerNote,
                'expires_at' => $expiresAt,
            ]);

            // 3. Simpan item pesanan, reservasi stok, dan catat mutasi stok
            foreach ($items as $item) {
                $variant = $lockedVariants[$item->product_variant_id];
                $product = $variant->product;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $product?->name ?? 'Produk',
                    'variant_name' => trim("{$variant->color} {$variant->size}"),
                    'price' => $variant->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $variant->price * $item->quantity,
                    'weight_gram' => $variant->weight_gram,
                ]);

                // Tambahkan reserved stok
                $variant->increment('reserved', $item->quantity);

                // Catat mutasi stok (reserve)
                StockMovement::create([
                    'product_variant_id' => $variant->id,
                    'type' => 'reserve',
                    'quantity' => $item->quantity,
                    'stock_after' => $variant->stock,
                    'order_id' => $order->id,
                    'user_id' => $user?->id,
                    'note' => 'Reservasi stok untuk pesanan #'.$order->number,
                ]);
            }

            // 4. Catat riwayat status
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => null,
                'to_status' => OrderStatus::PendingPayment->value,
                'changed_by' => $user?->id,
                'note' => 'Pesanan berhasil dibuat, menunggu pembayaran pelanggan.',
            ]);

            // 5. Catat penggunaan voucher jika ada
            if ($pricing['voucher'] && $pricing['discount_total'] > 0) {
                $voucher = $pricing['voucher'];

                VoucherUsage::create([
                    'voucher_id' => $voucher->id,
                    'order_id' => $order->id,
                    'user_id' => $user?->id,
                    'discount_amount' => $pricing['discount_total'],
                ]);

                $voucher->increment('used_count');
            }

            // 6. Kosongkan keranjang belanja
            $this->cartService->clear();

            return $order;
        });
    }
}
