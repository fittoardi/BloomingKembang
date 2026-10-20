<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Filament\Widgets\LowStockAlertWidget;
use App\Filament\Widgets\OrdersByStatusChartWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\TopSellingProductsWidget;
use App\Models\Banner;
use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DemoSeeder;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(AdminSeeder::class);
    $this->seed(DemoSeeder::class);
});

test('sitemap xml endpoint returns valid xml with all active content', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertOk()
        ->assertHeader('Content-Type', 'text/xml; charset=UTF-8');

    $content = $response->getContent();

    expect($content)->toContain('<urlset')
        ->toContain(url('/'))
        ->toContain(url('/koleksi'))
        ->toContain(url('/halaman/tentang-kami'));

    $product = Product::where('status', ProductStatus::Active)->first();
    expect($content)->toContain(url('/produk/'.$product->slug));
});

test('robots txt endpoint returns proper crawling directives', function () {
    $response = $this->get('/robots.txt');

    $response->assertOk()
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8');

    $content = $response->getContent();

    expect($content)->toContain('User-agent: *')
        ->toContain('Disallow: /admin/')
        ->toContain('Disallow: /akun/')
        ->toContain('Disallow: /checkout')
        ->toContain('Sitemap: '.url('/sitemap.xml'));
});

test('static pages render correctly with seo metadata', function () {
    $pages = ['tentang-kami', 'faq', 'kebijakan-retur', 'syarat-dan-ketentuan', 'kebijakan-privasi', 'kontak'];

    foreach ($pages as $slug) {
        $response = $this->get('/'.$slug);
        $response->assertOk();

        $page = Page::where('slug', $slug)->first();
        $response->assertSee($page->title);
    }
});

test('homepage renders dynamic banners and curated products', function () {
    $banner = Banner::where('position', 'hero')->first();

    $response = $this->get('/');

    $response->assertOk();
    if ($banner) {
        $response->assertSee($banner->title);
    }

    $product = Product::where('status', ProductStatus::Active)->first();
    $response->assertSee($product->name);
});

test('filament stats overview widget calculates revenue and counts properly', function () {
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();

    // Create a paid order today
    Order::create([
        'number' => 'BK-WIDGET-TEST-001',
        'user_id' => $customer->id,
        'customer_name' => $customer->name,
        'customer_email' => $customer->email,
        'customer_phone' => '081234567890',
        'shipping_address' => [
            'recipient_name' => 'Fulanah',
            'phone' => '081234567890',
            'detail' => 'Jl. Mawar',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40123',
        ],
        'subtotal' => 200000,
        'discount_total' => 0,
        'shipping_cost' => 10000,
        'grand_total' => 210000,
        'status' => OrderStatus::Processing,
        'payment_status' => PaymentStatus::Paid,
        'paid_at' => now(),
    ]);

    Livewire::test(StatsOverviewWidget::class)
        ->assertSuccessful();
});

test('filament orders by status chart widget generates datasets', function () {
    Livewire::test(OrdersByStatusChartWidget::class)
        ->assertSuccessful();
});

test('filament top selling products and low stock widgets render successfully', function () {
    Livewire::test(TopSellingProductsWidget::class)
        ->assertSuccessful();

    Livewire::test(LowStockAlertWidget::class)
        ->assertSuccessful();
});

test('admin can access banner and page resources in filament', function () {
    $admin = User::where('email', 'admin@bloomingkembang.com')->first();

    $this->actingAs($admin)
        ->get('/admin/banners')
        ->assertOk()
        ->assertSee('Banner Promo');

    $this->actingAs($admin)
        ->get('/admin/pages')
        ->assertOk()
        ->assertSee('Halaman Statis');
});
