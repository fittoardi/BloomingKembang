<?php

namespace App\Livewire\Account;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OrderDetail extends Component
{
    use AuthorizesRequests;

    public Order $order;

    public ?string $paymentUrl = null;

    public ?string $snapToken = null;

    public ?string $errorMessage = null;

    public function mount(string $orderNumber): void
    {
        $this->order = Order::with([
            'items.variant.product',
            'statusHistories' => fn ($q) => $q->orderBy('created_at', 'asc'),
            'payments' => fn ($q) => $q->latest(),
        ])
            ->where('number', $orderNumber)
            ->firstOrFail();

        $this->authorize('view', $this->order);
    }

    public function payNow(PaymentGatewayInterface $paymentGateway): void
    {
        $this->authorize('pay', $this->order);

        try {
            $result = $paymentGateway->createTransaction($this->order);
            $this->snapToken = $result['snap_token'];
            $this->paymentUrl = $result['redirect_url'];

            if ($this->paymentUrl) {
                $this->redirect($this->paymentUrl);
            }
        } catch (\Throwable $e) {
            $this->errorMessage = 'Gagal memproses pembayaran: '.$e->getMessage();
        }
    }

    public function cancelOrder(): void
    {
        $this->authorize('cancel', $this->order);

        DB::transaction(function (): void {
            $this->order->refresh();

            if ($this->order->status !== OrderStatus::PendingPayment) {
                return;
            }

            foreach ($this->order->items as $item) {
                $variant = ProductVariant::where('id', $item->product_variant_id)
                    ->lockForUpdate()
                    ->first();

                if (! $variant) {
                    continue;
                }

                $variant->decrement('reserved', $item->quantity);

                StockMovement::create([
                    'product_variant_id' => $variant->id,
                    'type' => 'release',
                    'quantity' => $item->quantity,
                    'stock_after' => $variant->stock,
                    'order_id' => $this->order->id,
                    'user_id' => Auth::id(),
                    'note' => "Pembatalan oleh pelanggan untuk pesanan #{$this->order->number}",
                ]);
            }

            $this->order->update([
                'status' => OrderStatus::Cancelled,
                'cancelled_at' => now(),
                'cancel_reason' => 'Dibatalkan oleh pelanggan',
            ]);

            OrderStatusHistory::create([
                'order_id' => $this->order->id,
                'from_status' => OrderStatus::PendingPayment->value,
                'to_status' => OrderStatus::Cancelled->value,
                'changed_by' => Auth::id(),
                'note' => 'Pesanan dibatalkan atas permintaan pelanggan',
            ]);
        });

        $this->order->refresh();
        session()->flash('status', 'Pesanan Anda telah berhasil dibatalkan.');
    }

    public function render(): View
    {
        return view('livewire.account.order-detail')->layout('layouts.storefront', [
            'title' => "Pesanan #{$this->order->number} — BloomingKembang",
        ]);
    }
}
