<?php

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Enums\VoucherType;
use App\Jobs\ExpireUnpaidOrders;
use App\Livewire\Account\OrderDetail;
use App\Livewire\ProductShow;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Notifications\OrderCreatedNotification;
use App\Notifications\OrderPaidNotification;
use App\Notifications\OrderShippedNotification;
use Database\Seeders\AdminSeeder;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(AdminSeeder::class);

    $this->customer = User::create([
        'name' => 'Fatimah Az-Zahra',
        'email' => 'customer_test@example.com',
        'phone' => '08123456789',
        'password' => 'password',
        'email_verified_at' => now(),
        'is_active' => true,
    ]);
    $this->customer->assignRole('customer');

    $this->category = Category::create([
        'name' => 'Segi Empat',
        'slug' => 'segi-empat',
        'is_active' => true,
    ]);

    $this->product = Product::create([
        'category_id' => $this->category->id,
        'name' => 'Voal Signature Lavender',
        'slug' => 'voal-signature-lavender',
        'short_description' => 'Voal premium lembut dan elegan',
        'description' => 'Deskripsi voal',
        'status' => ProductStatus::Active,
        'rating_avg' => 0.0,
        'rating_count' => 0,
    ]);

    $this->variant = ProductVariant::create([
        'product_id' => $this->product->id,
        'sku' => 'VSL-01',
        'color' => 'Lavender',
        'size' => 'Standard',
        'price' => 125000,
        'stock' => 20,
        'reserved' => 2,
        'weight_gram' => 150,
        'is_active' => true,
    ]);

    $this->voucher = Voucher::create([
        'code' => 'TESTVOUCHER',
        'type' => VoucherType::Fixed,
        'value' => 25000,
        'min_purchase' => 100000,
        'quota' => 10,
        'used_count' => 1,
        'is_active' => true,
    ]);
});

test('expire unpaid orders job releases reserved stock, restores voucher, and marks order cancelled', function () {
    $order = Order::create([
        'number' => 'ORD-EXP-001',
        'user_id' => $this->customer->id,
        'customer_name' => $this->customer->name,
        'customer_email' => $this->customer->email,
        'customer_phone' => $this->customer->phone,
        'shipping_address' => [
            'recipient_name' => 'Fatimah Az-Zahra',
            'phone' => '08123456789',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Tebet',
            'postal_code' => '12810',
            'detail' => 'Jl. Tebet Barat No. 10',
        ],
        'status' => OrderStatus::PendingPayment,
        'subtotal' => 250000,
        'discount_amount' => 25000,
        'shipping_cost' => 15000,
        'grand_total' => 240000,
        'expires_at' => now()->subMinutes(10),
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_variant_id' => $this->variant->id,
        'product_name' => $this->product->name,
        'variant_label' => 'Lavender',
        'sku' => $this->variant->sku,
        'price' => 125000,
        'quantity' => 2,
        'subtotal' => 250000,
        'weight_gram' => 300,
    ]);

    VoucherUsage::create([
        'order_id' => $order->id,
        'voucher_id' => $this->voucher->id,
        'user_id' => $this->customer->id,
        'discount_amount' => 25000,
    ]);

    // Jalankan Job kedaluwarsa pesanan
    (new ExpireUnpaidOrders)->handle();

    $order->refresh();
    $this->variant->refresh();
    $this->voucher->refresh();

    expect($order->status)->toBe(OrderStatus::Cancelled)
        ->and($this->variant->reserved)->toBe(0)
        ->and($this->voucher->used_count)->toBe(0)
        ->and(VoucherUsage::where('order_id', $order->id)->exists())->toBeFalse();

    $this->assertDatabaseHas('stock_movements', [
        'product_variant_id' => $this->variant->id,
        'type' => 'release',
        'quantity' => 2,
    ]);
});

test('customer can cancel their unpaid order from portal and voucher is restored', function () {
    $order = Order::create([
        'number' => 'ORD-CANCEL-002',
        'user_id' => $this->customer->id,
        'customer_name' => $this->customer->name,
        'customer_email' => $this->customer->email,
        'customer_phone' => $this->customer->phone,
        'shipping_address' => [
            'recipient_name' => 'Fatimah Az-Zahra',
            'phone' => '08123456789',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Tebet',
            'postal_code' => '12810',
            'detail' => 'Jl. Tebet Barat No. 10',
        ],
        'status' => OrderStatus::PendingPayment,
        'subtotal' => 125000,
        'discount_amount' => 25000,
        'shipping_cost' => 10000,
        'grand_total' => 110000,
        'expires_at' => now()->addHours(24),
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_variant_id' => $this->variant->id,
        'product_name' => $this->product->name,
        'variant_label' => 'Lavender',
        'sku' => $this->variant->sku,
        'price' => 125000,
        'quantity' => 2,
        'subtotal' => 125000,
        'weight_gram' => 150,
    ]);

    VoucherUsage::create([
        'order_id' => $order->id,
        'voucher_id' => $this->voucher->id,
        'user_id' => $this->customer->id,
        'discount_amount' => 25000,
    ]);

    $this->actingAs($this->customer);

    Livewire::test(OrderDetail::class, ['orderNumber' => $order->number])
        ->call('cancelOrder')
        ->assertHasNoErrors();

    $order->refresh();
    $this->variant->refresh();
    $this->voucher->refresh();

    expect($order->status)->toBe(OrderStatus::Cancelled)
        ->and($this->variant->reserved)->toBe(0)
        ->and($this->voucher->used_count)->toBe(0);
});

test('whatsapp notification formatting works without exception', function () {
    $order = Order::create([
        'number' => 'ORD-NOTIF-003',
        'user_id' => $this->customer->id,
        'customer_name' => $this->customer->name,
        'customer_email' => $this->customer->email,
        'customer_phone' => '081234567890',
        'shipping_address' => [
            'recipient_name' => 'Fatimah Az-Zahra',
            'phone' => '081234567890',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Tebet',
            'postal_code' => '12810',
            'detail' => 'Jl. Tebet Barat No. 10',
        ],
        'status' => OrderStatus::PendingPayment,
        'subtotal' => 125000,
        'shipping_cost' => 10000,
        'grand_total' => 135000,
        'expires_at' => now()->addHours(24),
    ]);

    $createdNotif = new OrderCreatedNotification($order);
    $paidNotif = new OrderPaidNotification($order);
    $shippedNotif = new OrderShippedNotification($order);

    expect($createdNotif->toWhatsApp($this->customer))->toContain('ORD-NOTIF-003')
        ->and($paidNotif->toWhatsApp($this->customer))->toContain('Pembayaran Berhasil')
        ->and($shippedNotif->toWhatsApp($this->customer))->toContain('Telah Dikirim');
});

test('verified buyer can submit product review and updates product average rating', function () {
    // Buat pesanan status completed untuk memverifikasi customer sebagai pembeli asli
    $order = Order::create([
        'number' => 'ORD-REVIEW-004',
        'user_id' => $this->customer->id,
        'customer_name' => $this->customer->name,
        'customer_email' => $this->customer->email,
        'customer_phone' => $this->customer->phone,
        'shipping_address' => [
            'recipient_name' => 'Fatimah Az-Zahra',
            'phone' => '08123456789',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Tebet',
            'postal_code' => '12810',
            'detail' => 'Jl. Tebet Barat No. 10',
        ],
        'status' => OrderStatus::Completed,
        'subtotal' => 125000,
        'shipping_cost' => 10000,
        'grand_total' => 135000,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_variant_id' => $this->variant->id,
        'product_name' => $this->product->name,
        'variant_label' => 'Lavender',
        'sku' => $this->variant->sku,
        'price' => 125000,
        'quantity' => 1,
        'subtotal' => 125000,
        'weight_gram' => 150,
    ]);

    $this->actingAs($this->customer);

    Livewire::test(ProductShow::class, ['slug' => $this->product->slug])
        ->set('newRating', 5)
        ->set('newTitle', 'Sangat Nyaman')
        ->set('newComment', 'Bahan adem dan tegak di dahi, sangat suka!')
        ->call('submitReview')
        ->assertHasNoErrors()
        ->assertSee('Terima kasih! Ulasan Anda telah berhasil diterbitkan.');

    $this->product->refresh();

    expect($this->product->rating_avg)->toBe('5.0')
        ->and($this->product->rating_count)->toBe(1);

    $this->assertDatabaseHas('reviews', [
        'product_id' => $this->product->id,
        'user_id' => $this->customer->id,
        'rating' => 5,
        'title' => 'Sangat Nyaman',
    ]);
});

test('non-buyer cannot submit review', function () {
    $nonBuyer = User::create([
        'name' => 'Budi Santoso',
        'email' => 'budi_nonbuyer@example.com',
        'phone' => '0899887766',
        'password' => 'password',
        'email_verified_at' => now(),
        'is_active' => true,
    ]);
    $nonBuyer->assignRole('customer');

    $this->actingAs($nonBuyer);

    Livewire::test(ProductShow::class, ['slug' => $this->product->slug])
        ->set('newRating', 5)
        ->set('newComment', 'Mencoba submit tanpa membeli')
        ->call('submitReview')
        ->assertSee('Hanya pelanggan yang telah membeli produk ini yang dapat memberikan ulasan.');

    expect(Review::where('user_id', $nonBuyer->id)->count())->toBe(0);
});
