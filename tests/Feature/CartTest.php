<?php

use App\Enums\ProductStatus;
use App\Livewire\CartDrawer;
use App\Livewire\ProductShow;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DemoSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(AdminSeeder::class);
    $this->seed(DemoSeeder::class);
});

test('guest can add items to cart via cart service', function () {
    $service = app(CartService::class);
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = $product->variants->first();

    $item = $service->addItem($variant->id, 2);

    expect($item->quantity)->toBe(2);
    expect($service->getCount())->toBe(2);
    expect($service->getSubtotal())->toBe($variant->price * 2);
    expect($service->getItems()->count())->toBe(1);
});

test('guest can update and remove cart items via livewire cart drawer', function () {
    $service = app(CartService::class);
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = $product->variants->first();

    $item = $service->addItem($variant->id, 1);

    Livewire::test(CartDrawer::class)
        ->call('open')
        ->assertSet('isOpen', true)
        ->call('updateQuantity', $item->id, 3)
        ->assertDispatched('cart-updated');

    expect($service->getCount())->toBe(3);

    Livewire::test(CartDrawer::class)
        ->call('removeItem', $item->id)
        ->assertDispatched('cart-updated');

    expect($service->getCount())->toBe(0);
});

test('product show can add item to cart and trigger drawer', function () {
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = $product->variants->first();

    Livewire::test(ProductShow::class, ['slug' => $product->slug])
        ->assertSet('selectedVariantId', $variant->id)
        ->call('addToCart')
        ->assertDispatched('cart-updated')
        ->assertDispatched('open-cart');

    $service = app(CartService::class);
    expect($service->getCount())->toBe(1);
});

test('session cart merges into customer cart on login event', function () {
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = $product->variants->first();
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();

    // Buat session cart untuk guest
    $sessionId = Session::getId();
    $sessionCart = Cart::create(['session_id' => $sessionId]);
    $sessionCart->items()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 2,
    ]);

    // Dispatch Login event
    event(new Login('web', $customer, false));

    // Cart user sekarang harus berisi barang
    $userCart = Cart::where('user_id', $customer->id)->first();
    expect($userCart)->not->toBeNull();
    expect($userCart->items()->sum('quantity'))->toBe(2);

    // Cart session lama harus terhapus
    expect(Cart::where('session_id', $sessionId)->exists())->toBeFalse();
});
