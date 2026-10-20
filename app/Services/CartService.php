<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Dapatkan instance Cart aktif (berdasarkan Auth User atau Session ID).
     */
    public function getCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = Session::getId();

        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    /**
     * Dapatkan semua item di keranjang dengan relasi lengkap.
     *
     * @return Collection<int, CartItem>
     */
    public function getItems(): Collection
    {
        $cart = $this->getCart();

        return CartItem::with(['variant.product.category', 'variant.product.media'])
            ->where('cart_id', $cart->id)
            ->get();
    }

    /**
     * Tambah varian produk ke keranjang.
     */
    public function addItem(int $variantId, int $quantity = 1): CartItem
    {
        $variant = ProductVariant::where('is_active', true)->findOrFail($variantId);
        $availableStock = max(0, $variant->stock - $variant->reserved);

        if ($availableStock <= 0) {
            throw new \RuntimeException('Stok varian produk ini sedang tidak tersedia.');
        }

        $cart = $this->getCart();
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            $newQuantity = min($item->quantity + $quantity, $availableStock);
            $item->update(['quantity' => $newQuantity]);

            return $item->fresh();
        }

        $qtyToAdd = min($quantity, $availableStock);

        return CartItem::create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variantId,
            'quantity' => max(1, $qtyToAdd),
        ]);
    }

    /**
     * Ubah jumlah item di keranjang.
     */
    public function updateQuantity(int $cartItemId, int $quantity): bool
    {
        $cart = $this->getCart();
        $item = CartItem::where('cart_id', $cart->id)->findOrFail($cartItemId);

        if ($quantity <= 0) {
            return (bool) $item->delete();
        }

        $availableStock = max(1, $item->variant->stock - $item->variant->reserved);
        $finalQuantity = min($quantity, $availableStock);

        return $item->update(['quantity' => $finalQuantity]);
    }

    /**
     * Hapus item dari keranjang.
     */
    public function removeItem(int $cartItemId): bool
    {
        $cart = $this->getCart();

        return (bool) CartItem::where('cart_id', $cart->id)->where('id', $cartItemId)->delete();
    }

    /**
     * Kosongkan keranjang.
     */
    public function clear(): void
    {
        $cart = $this->getCart();
        CartItem::where('cart_id', $cart->id)->delete();
    }

    /**
     * Hitung total kuantitas barang di keranjang.
     */
    public function getCount(): int
    {
        $cart = $this->getCart();

        return (int) CartItem::where('cart_id', $cart->id)->sum('quantity');
    }

    /**
     * Hitung subtotal belanja (IDR).
     */
    public function getSubtotal(): int
    {
        $items = $this->getItems();

        return (int) $items->sum(function (CartItem $item) {
            return ($item->variant?->price ?? 0) * $item->quantity;
        });
    }

    /**
     * Hitung total berat barang dalam gram.
     */
    public function getTotalWeightGram(): int
    {
        $items = $this->getItems();

        return (int) $items->sum(function (CartItem $item) {
            return ($item->variant?->weight_gram ?? 150) * $item->quantity;
        });
    }

    /**
     * Gabungkan keranjang guest (session) ke user saat login.
     */
    public function mergeSessionCartToUser(User $user, ?string $sessionId = null): void
    {
        $sessionId = $sessionId ?: Session::getId();
        $sessionCart = Cart::where('session_id', $sessionId)->first();

        if (! $sessionCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);
        $sessionItems = CartItem::where('cart_id', $sessionCart->id)->get();

        foreach ($sessionItems as $sItem) {
            $existing = CartItem::where('cart_id', $userCart->id)
                ->where('product_variant_id', $sItem->product_variant_id)
                ->first();

            $variant = $sItem->variant;
            $availableStock = $variant ? max(1, $variant->stock - $variant->reserved) : 99;

            if ($existing) {
                $mergedQty = min($existing->quantity + $sItem->quantity, $availableStock);
                $existing->update(['quantity' => $mergedQty]);
            } else {
                CartItem::create([
                    'cart_id' => $userCart->id,
                    'product_variant_id' => $sItem->product_variant_id,
                    'quantity' => min($sItem->quantity, $availableStock),
                ]);
            }
        }

        // Hapus cart guest setelah digabung
        $sessionCart->delete();
    }
}
