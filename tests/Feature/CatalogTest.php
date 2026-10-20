<?php

use App\Enums\ProductStatus;
use App\Livewire\CatalogIndex;
use App\Livewire\ProductShow;
use App\Models\Category;
use App\Models\Product;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DemoSeeder;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(AdminSeeder::class);
    $this->seed(DemoSeeder::class);
});

test('catalog index page can be rendered', function () {
    $response = $this->get('/koleksi');

    $response->assertSuccessful();
    $response->assertSee('Semua Koleksi');
    $response->assertSee('Filter Koleksi');
});

test('catalog can filter products by category', function () {
    $category = Category::where('slug', 'hijab-segi-empat')->first();

    $response = $this->get('/koleksi/'.$category->slug);

    $response->assertSuccessful();
    $response->assertSee($category->name);
});

test('catalog can filter by color and availability using livewire', function () {
    Livewire::test(CatalogIndex::class)
        ->set('color', 'Dusty Rose')
        ->assertSet('color', 'Dusty Rose')
        ->assertSee('Dusty Rose');
});

test('product detail page can be rendered with variants and json ld', function () {
    $product = Product::where('status', ProductStatus::Active)->first();

    $response = $this->get('/produk/'.$product->slug);

    $response->assertSuccessful();
    $response->assertSee($product->name);
    $response->assertSee('schema.org');
    $response->assertSee('Pilihan Warna');
});

test('product show handles stock indicator and variant switching', function () {
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = $product->variants->first();

    Livewire::test(ProductShow::class, ['slug' => $product->slug])
        ->assertSet('selectedVariantId', $variant->id)
        ->assertSee($variant->color)
        ->call('setTab', 'material')
        ->assertSet('activeTab', 'material')
        ->assertSee('Karakter Bahan');
});

test('product show can increment and decrement quantity properly', function () {
    $product = Product::where('status', ProductStatus::Active)->first();

    Livewire::test(ProductShow::class, ['slug' => $product->slug])
        ->assertSet('quantity', 1)
        ->call('incrementQuantity')
        ->assertSet('quantity', 2)
        ->call('decrementQuantity')
        ->assertSet('quantity', 1)
        ->call('decrementQuantity')
        ->assertSet('quantity', 1); // cannot go below 1
});

test('catalog can sort and reset filters', function () {
    Livewire::test(CatalogIndex::class)
        ->set('sortBy', 'price_low')
        ->assertSet('sortBy', 'price_low')
        ->set('color', 'Dusty Rose')
        ->assertSet('color', 'Dusty Rose')
        ->call('resetFilters')
        ->assertSet('color', '')
        ->assertSet('sortBy', 'newest');
});

test('product model has registered media collections', function () {
    $product = Product::first();
    $product->registerMediaCollections();

    expect($product->getMediaCollection('images'))->not->toBeNull();
});
