<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\StockMovement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpireUnpaidOrders implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $expiredOrders = Order::where('status', OrderStatus::PendingPayment)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->with(['items.variant', 'voucherUsage.voucher'])
            ->get();

        foreach ($expiredOrders as $order) {
            DB::transaction(function () use ($order) {
                // 1. Lepaskan reservasi stok untuk setiap item
                foreach ($order->items as $item) {
                    $variant = $item->variant;

                    if ($variant) {
                        $releaseQty = min($variant->reserved, $item->quantity);
                        $variant->decrement('reserved', $releaseQty);

                        StockMovement::create([
                            'product_variant_id' => $variant->id,
                            'type' => 'release',
                            'quantity' => $releaseQty,
                            'stock_after' => $variant->stock,
                            'order_id' => $order->id,
                            'user_id' => null,
                            'note' => 'Pelepasan stok reservasi karena pesanan #'.$order->number.' kedaluwarsa.',
                        ]);
                    }
                }

                // 2. Kembalikan kuota voucher jika dipakai
                if ($order->voucherUsage) {
                    $voucher = $order->voucherUsage->voucher;

                    if ($voucher && $voucher->used_count > 0) {
                        $voucher->decrement('used_count');
                    }

                    $order->voucherUsage->delete();
                }

                // 3. Ubah status pesanan menjadi Cancelled
                $order->update([
                    'status' => OrderStatus::Cancelled,
                    'cancel_reason' => 'Waktu batas pembayaran telah habis (dibatalkan otomatis oleh sistem).',
                    'cancelled_at' => now(),
                ]);

                // 4. Catat riwayat status
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'from_status' => OrderStatus::PendingPayment->value,
                    'to_status' => OrderStatus::Cancelled->value,
                    'changed_by' => null,
                    'note' => 'Pesanan dibatalkan otomatis karena tidak dibayar hingga batas waktu berakhir.',
                ]);

                Log::info("Pesanan #{$order->number} telah otomatis dibatalkan karena kedaluwarsa.");
            });
        }
    }
}
