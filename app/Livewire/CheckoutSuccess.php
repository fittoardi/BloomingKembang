<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CheckoutSuccess extends Component
{
    public Order $order;

    public function mount(string $orderNumber): void
    {
        $this->order = Order::with(['items.variant.product', 'user'])
            ->where('number', $orderNumber)
            ->firstOrFail();
    }

    public function render(): View
    {
        return view('livewire.checkout-success')->layout('layouts.storefront', [
            'title' => "Pesanan #{$this->order->number} — BloomingKembang",
        ]);
    }
}
