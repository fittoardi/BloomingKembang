<?php

namespace App\Listeners;

use App\Enums\OrderStatus;
use App\Events\OrderPaid;
use App\Models\OrderStatusHistory;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Notifications\OrderPaidNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class FinalizeStockOnOrderPaid
{
    public function handle(OrderPaid $event): void
    {
        $order = $event->order;

        DB::transaction(function () use ($order, $event): void {
            $order->loadMissing('items.variant.product');

            foreach ($order->items as $item) {
                $variant = ProductVariant::where('id', $item->product_variant_id)
                    ->lockForUpdate()
                    ->first();

                if (! $variant) {
                    continue;
                }

                $variant->decrement('reserved', $item->quantity);
                $variant->decrement('stock', $item->quantity);
                $variant->product()->increment('sold_count', $item->quantity);

                StockMovement::create([
                    'product_variant_id' => $variant->id,
                    'type' => 'sale',
                    'quantity' => -$item->quantity,
                    'stock_after' => $variant->stock,
                    'order_id' => $order->id,
                    'note' => "Penjualan pesanan #{$order->number}",
                ]);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => OrderStatus::PendingPayment->value,
                'to_status' => OrderStatus::Processing->value,
                'note' => 'Pembayaran terkonfirmasi via '.($event->payment?->method ?? 'Midtrans'),
            ]);
        });

        // Kirim notifikasi konfirmasi pembayaran
        if ($order->user) {
            $order->user->notify(new OrderPaidNotification($order, $event->payment));
        } elseif ($order->customer_email) {
            Notification::route('mail', $order->customer_email)
                ->notify(new OrderPaidNotification($order, $event->payment));
        }
    }
}
