<?php

namespace App\Livewire\Account;

use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class WishlistIndex extends Component
{
    use WithPagination;

    public function removeFromWishlist(int $wishlistId): void
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->findOrFail($wishlistId);
        $wishlist->delete();

        session()->flash('status', 'Produk berhasil dihapus dari Wishlist.');
    }

    public function addToCart(int $variantId, CartService $cartService): void
    {
        $cartService->addItem($variantId, 1);
        $this->dispatch('cart-updated');
        $this->dispatch('open-cart');
    }

    public function render(): View
    {
        $wishlists = Wishlist::with(['product.category', 'product.media', 'product.variants' => function ($q) {
            $q->where('is_active', true)->orderBy('position');
        }])
            ->where('user_id', Auth::id())
            ->latest('id')
            ->paginate(12);

        return view('livewire.account.wishlist-index', [
            'wishlists' => $wishlists,
        ])->layout('layouts.storefront', [
            'title' => 'Wishlist Saya — BloomingKembang',
        ]);
    }
}
