<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Account\AddressBook;
use App\Livewire\Account\OrderDetail;
use App\Livewire\Account\OrderIndex;
use App\Livewire\Account\WishlistIndex;
use App\Livewire\CatalogIndex;
use App\Livewire\CheckoutPage;
use App\Livewire\CheckoutSuccess;
use App\Livewire\ProductShow;
use App\Livewire\StaticPage;
use App\Livewire\TrackOrder;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home.index');
});

// Katalog & Produk Storefront
Route::get('/koleksi', CatalogIndex::class)->name('catalog.index');
Route::get('/koleksi/{category}', CatalogIndex::class)->name('catalog.category');
Route::get('/produk/{slug}', ProductShow::class)->name('product.show');

// Halaman Statis & Kebijakan
Route::get('/tentang-kami', StaticPage::class)->defaults('slug', 'tentang-kami')->name('pages.about');
Route::get('/faq', StaticPage::class)->defaults('slug', 'faq')->name('pages.faq');
Route::get('/kebijakan-retur', StaticPage::class)->defaults('slug', 'kebijakan-retur')->name('pages.returns');
Route::get('/syarat-dan-ketentuan', StaticPage::class)->defaults('slug', 'syarat-dan-ketentuan')->name('pages.terms');
Route::get('/kebijakan-privasi', StaticPage::class)->defaults('slug', 'kebijakan-privasi')->name('pages.privacy');
Route::get('/kontak', StaticPage::class)->defaults('slug', 'kontak')->name('pages.contact');
Route::get('/halaman/{slug}', StaticPage::class)->name('pages.show');

// Keranjang & Checkout
Route::get('/keranjang', function () {
    return redirect()->route('catalog.index');
})->name('cart.index');
Route::get('/checkout', CheckoutPage::class)->middleware('throttle:checkout')->name('checkout.index');
Route::get('/checkout/sukses/{orderNumber}', CheckoutSuccess::class)->name('checkout.success');

Route::get('/dashboard', function () {
    return redirect()->route('account.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/akun', function () {
    return view('pages.account.dashboard');
})->middleware(['auth'])->name('account.dashboard');

Route::get('/lacak-pesanan', TrackOrder::class)->middleware('throttle:track-order')->name('orders.track');
Route::get('/lacak-pesanan/{orderNumber}', TrackOrder::class)->middleware('throttle:track-order')->name('orders.track.signed');

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

// SEO, Sitemap & Robots
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

require __DIR__.'/auth.php';
