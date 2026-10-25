<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Enums\VoucherType;
use App\Models\Address;
use App\Models\Banner;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Page;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Models\Wishlist;

test('category factory creates valid category and inactive state', function () {
    $category = Category::factory()->create();
    expect($category->id)->not->toBeNull();
    expect($category->is_active)->toBeTrue();

    $inactive = Category::factory()->inactive()->create();
    expect($inactive->is_active)->toBeFalse();
});

test('product factory creates valid product with status states', function () {
    $product = Product::factory()->create();
    expect($product->id)->not->toBeNull();
    expect($product->status)->toBe(ProductStatus::Active);

    $featured = Product::factory()->featured()->create();
    expect($featured->is_featured)->toBeTrue();

    $draft = Product::factory()->draft()->create();
    expect($draft->status)->toBe(ProductStatus::Draft);

    $archived = Product::factory()->archived()->create();
    expect($archived->status)->toBe(ProductStatus::Archived);
});

test('product variant factory creates valid variant with states', function () {
    $variant = ProductVariant::factory()->create();
    expect($variant->id)->not->toBeNull();
    expect($variant->sku)->not->toBeNull();

    $discounted = ProductVariant::factory()->discounted()->create();
    expect($discounted->compare_price)->toBeGreaterThan($discounted->price);

    $outOfStock = ProductVariant::factory()->outOfStock()->create();
    expect($outOfStock->stock)->toBe(0);

    $inactive = ProductVariant::factory()->inactive()->create();
    expect($inactive->is_active)->toBeFalse();
});

test('address factory creates valid address with default state', function () {
    $address = Address::factory()->create();
    expect($address->id)->not->toBeNull();
    expect($address->is_default)->toBeFalse();

    $defaultAddress = Address::factory()->default()->create();
    expect($defaultAddress->is_default)->toBeTrue();
});

test('voucher factory creates percent, fixed, expired, and inactive states', function () {
    $voucher = Voucher::factory()->create();
    expect($voucher->type)->toBe(VoucherType::Percent);

    $fixed = Voucher::factory()->fixed()->create();
    expect($fixed->type)->toBe(VoucherType::Fixed);

    $expired = Voucher::factory()->expired()->create();
    expect($expired->ends_at->isPast())->toBeTrue();

    $inactive = Voucher::factory()->inactive()->create();
    expect($inactive->is_active)->toBeFalse();
});

test('order factory creates valid order across all lifecycle states', function () {
    $order = Order::factory()->create();
    expect($order->status)->toBe(OrderStatus::PendingPayment);
    expect($order->payment_status)->toBe(PaymentStatus::Pending);

    $paid = Order::factory()->paid()->create();
    expect($paid->status)->toBe(OrderStatus::Paid);
    expect($paid->paid_at)->not->toBeNull();

    $processing = Order::factory()->processing()->create();
    expect($processing->status)->toBe(OrderStatus::Processing);

    $shipped = Order::factory()->shipped()->create();
    expect($shipped->status)->toBe(OrderStatus::Shipped);
    expect($shipped->tracking_number)->not->toBeNull();

    $completed = Order::factory()->completed()->create();
    expect($completed->status)->toBe(OrderStatus::Completed);
    expect($completed->completed_at)->not->toBeNull();

    $cancelled = Order::factory()->cancelled()->create();
    expect($cancelled->status)->toBe(OrderStatus::Cancelled);
    expect($cancelled->cancel_reason)->not->toBeNull();

    $guest = Order::factory()->guest()->create();
    expect($guest->user_id)->toBeNull();
});

test('order item factory creates valid item attached to order', function () {
    $item = OrderItem::factory()->create();
    expect($item->id)->not->toBeNull();
    expect($item->order_id)->not->toBeNull();
    expect($item->product_variant_id)->not->toBeNull();
});

test('payment factory creates valid payment with status states', function () {
    $payment = Payment::factory()->create();
    expect($payment->status)->toBe(PaymentStatus::Pending);

    $paid = Payment::factory()->paid()->create();
    expect($paid->status)->toBe(PaymentStatus::Paid);

    $failed = Payment::factory()->failed()->create();
    expect($failed->status)->toBe(PaymentStatus::Failed);

    $expired = Payment::factory()->expired()->create();
    expect($expired->status)->toBe(PaymentStatus::Expired);
});

test('order status history factory creates valid audit log', function () {
    $history = OrderStatusHistory::factory()->create();
    expect($history->id)->not->toBeNull();
    expect($history->to_status)->toBe('paid');
});

test('voucher usage factory creates valid redemption record', function () {
    $usage = VoucherUsage::factory()->create();
    expect($usage->id)->not->toBeNull();
    expect($usage->voucher_id)->not->toBeNull();
    expect($usage->discount_amount)->toBeGreaterThan(0);
});

test('banner factory creates hero, topBar, and inactive states', function () {
    $banner = Banner::factory()->create();
    expect($banner->id)->not->toBeNull();

    $hero = Banner::factory()->hero()->create();
    expect($hero->position)->toBe('hero');

    $topBar = Banner::factory()->topBar()->create();
    expect($topBar->position)->toBe('top_bar');

    $inactive = Banner::factory()->inactive()->create();
    expect($inactive->is_active)->toBeFalse();
});

test('page factory creates valid page and unpublished state', function () {
    $page = Page::factory()->create();
    expect($page->is_published)->toBeTrue();

    $unpublished = Page::factory()->unpublished()->create();
    expect($unpublished->is_published)->toBeFalse();
});

test('wishlist factory creates valid user favorite', function () {
    $wishlist = Wishlist::factory()->create();
    expect($wishlist->id)->not->toBeNull();
    expect($wishlist->user_id)->not->toBeNull();
    expect($wishlist->product_id)->not->toBeNull();
});

test('stock movement factory creates valid adjustment, sale, and restock records', function () {
    $movement = StockMovement::factory()->create();
    expect($movement->id)->not->toBeNull();
    expect($movement->type)->toBe('adjustment');

    $sale = StockMovement::factory()->sale()->create();
    expect($sale->type)->toBe('sale');
    expect($sale->quantity)->toBe(-1);

    $restock = StockMovement::factory()->restock()->create();
    expect($restock->type)->toBe('adjustment');
    expect($restock->quantity)->toBe(50);
});

test('cart and cart item factories create user and guest carts', function () {
    $userCart = Cart::factory()->create();
    expect($userCart->user_id)->not->toBeNull();
    expect($userCart->session_id)->toBeNull();

    $guestCart = Cart::factory()->guest()->create();
    expect($guestCart->user_id)->toBeNull();
    expect($guestCart->session_id)->not->toBeNull();

    $cartItem = CartItem::factory()->create(['cart_id' => $userCart->id]);
    expect($cartItem->id)->not->toBeNull();
    expect($cartItem->quantity)->toBeGreaterThanOrEqual(1);
});
