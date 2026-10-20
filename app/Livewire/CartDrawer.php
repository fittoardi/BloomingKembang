<?php

namespace App\Livewire;

use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CartDrawer extends Component
{
    public bool $isOpen = false;

    #[On('open-cart')]
    public function open(): void
    {
        $this->isOpen = true;
    }

    #[On('close-cart')]
    public function close(): void
    {
        $this->isOpen = false;
    }

    #[On('cart-updated')]
    public function cartUpdated(): void
    {
        // Livewire auto-re-renders
    }

    public function updateQuantity(int $cartItemId, int $quantity, CartService $cartService): void
    {
        $cartService->updateQuantity($cartItemId, $quantity);
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $cartItemId, CartService $cartService): void
    {
        $cartService->removeItem($cartItemId);
        $this->dispatch('cart-updated');
    }

    public function render(CartService $cartService): View
    {
        return view('livewire.cart-drawer', [
            'items' => $cartService->getItems(),
            'subtotal' => $cartService->getSubtotal(),
            'totalWeight' => $cartService->getTotalWeightGram(),
            'count' => $cartService->getCount(),
        ]);
    }
}
