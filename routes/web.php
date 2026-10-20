<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Account\AddressBook;
use App\Livewire\Account\OrderDetail;
use App\Livewire\Account\OrderIndex;
use App\Livewire\Account\WishlistIndex;
use App\Livewire\CatalogIndex;
use App\Livewire\CheckoutPage;
use App\Livewire\CheckoutSuccess;
use App\Livewire\ProductShow;
use App\Livewire\TrackOrder;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Katalog & Produk Storefront
Route::get('/koleksi', CatalogIndex::class)->name('catalog.index');
Route::get('/koleksi/{category}', CatalogIndex::class)->name('catalog.category');
Route::get('/produk/{slug}', ProductShow::class)->name('product.show');

// Keranjang & Checkout
Route::get('/keranjang', function () {
    return redirect()->route('catalog.index');
})->name('cart.index');
Route::get('/checkout', CheckoutPage::class)->name('checkout.index');
Route::get('/checkout/sukses/{orderNumber}', CheckoutSuccess::class)->name('checkout.success');

Route::get('/dashboard', function () {
    return redirect()->route('account.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/akun', function () {
    return view('account.dashboard');
})->middleware(['auth'])->name('account.dashboard');

Route::get('/lacak-pesanan', TrackOrder::class)->name('orders.track');
Route::get('/lacak-pesanan/{orderNumber}', TrackOrder::class)->name('orders.track.signed');

Route::middleware('auth')->prefix('akun')->group(function () {
    Route::get('/pesanan', OrderIndex::class)->name('account.orders');
    Route::get('/pesanan/{orderNumber}', OrderDetail::class)->name('account.orders.show');
    Route::get('/alamat', AddressBook::class)->name('account.address');
    Route::get('/wishlist', WishlistIndex::class)->name('account.wishlist');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
