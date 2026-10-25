<?php

namespace App\Livewire;

use App\Models\Order;
use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CheckoutSuccess extends Component
{
    public Order $order;

    public ?string $errorMessage = null;

    public function mount(string $orderNumber): void
    {
        $this->order = Order::with(['items.variant.product', 'user'])
            ->where('number', $orderNumber)
            ->firstOrFail();
    }

    public function payNow(PaymentGatewayInterface $paymentGateway): void
    {
        if ($this->order->status->value !== 'pending_payment') {
            return;
        }

        try {
            $result = $paymentGateway->createTransaction($this->order);
            if (! empty($result['redirect_url'])) {
                $this->redirect($result['redirect_url']);
            }
        } catch (\Throwable $e) {
            $this->errorMessage = 'Gagal memproses pembayaran: '.$e->getMessage();
        }
    }

    public function render(): View
    {
        return view('pages.checkout.success')->layout('layouts.storefront', [
            'title' => "Pesanan #{$this->order->number} — BloomingKembang",
        ]);
    }
}
