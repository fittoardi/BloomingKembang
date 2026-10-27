<?php

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\CartService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DemoSeeder;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(AdminSeeder::class);
    $this->seed(DemoSeeder::class);
});

test('public pages load without errors', function (string $url) {
    $response = $this->get($url);
    $response->assertOk();
})->with([
    '/',
    '/koleksi',
    '/koleksi/hijab-segi-empat',
    '/koleksi/pashmina',
    '/koleksi/inner-dan-aksesoris',
    '/tentang-kami',
    '/faq',
    '/kebijakan-retur',
    '/syarat-dan-ketentuan',
    '/kebijakan-privasi',
    '/kontak',
    '/lacak-pesanan',
    '/login',
    '/register',
    '/forgot-password',
]);

test('checkout redirects to catalog if cart is empty', function () {
    $response = $this->get('/checkout');
    $response->assertRedirect(route('catalog.index'));
});

test('checkout page loads successfully when cart has items', function () {
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();
    $this->actingAs($customer);

    $variant = ProductVariant::where('is_active', true)->first();
    expect($variant)->not->toBeNull();

    app(CartService::class)->addItem($variant->id, 1);

    $response = $this->get('/checkout');
    $response->assertOk();
});

test('product detail page loads successfully', function () {
    $product = Product::first();
    expect($product)->not->toBeNull();

    $response = $this->get('/produk/'.$product->slug);
    $response->assertOk();
});

test('authenticated customer pages load without errors', function (string $url) {
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();
    expect($customer)->not->toBeNull();

    $response = $this->actingAs($customer)->get($url);
    $response->assertOk();
})->with([
    '/akun',
    '/akun/pesanan',
    '/akun/alamat',
    '/akun/wishlist',
    '/profile',
]);

test('customer cannot access admin panel', function () {
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();
    $response = $this->actingAs($customer)->get('/admin');

    // Filament returns 403 Forbidden for non-staff
    $response->assertForbidden();
});

test('admin panel pages load for super admin', function (string $url) {
    $admin = User::where('email', 'admin@bloomingkembang.com')->first();
    expect($admin)->not->toBeNull();

    $response = $this->actingAs($admin)->get($url);
    $response->assertOk();
})->with([
    '/admin',
    '/admin/products',
    '/admin/categories',
    '/admin/orders',
    '/admin/vouchers',
    '/admin/banners',
    '/admin/pages',
]);

test('guest is redirected to login when accessing admin panel', function () {
    $response = $this->get('/admin');
    $response->assertRedirect('/login');
});

test('home page renders hero carousel with controls', function () {
    $response = $this->get('/');
    $response->assertOk();
    $response->assertSee('aria-roledescription="carousel"', false);
    $response->assertSee('Pashmina Silk Shimmer &amp; Ceruty Flowy', false);
    $response->assertSee('Voal Miracle &amp; Segi Empat Laser Cut', false);
    $response->assertSee('Slide sebelumnya');
    $response->assertSee('Slide selanjutnya');
});
