<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DemoSeeder;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(AdminSeeder::class);
    $this->seed(DemoSeeder::class);
    config()->set('services.midtrans.server_key', 'test-server-key');
});

function generateMidtransSignature(string $orderId, string $statusCode, string $grossAmount, string $serverKey): string
{
    return hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
}

function dummyShippingAddress(): array
{
    return [
        'recipient_name' => 'Fulanah',
        'phone' => '081234567890',
        'detail' => 'Jl. Mawar No. 1',
        'city' => 'Bandung',
        'province' => 'Jawa Barat',
        'postal_code' => '40123',
    ];
}

test('midtrans webhook with valid settlement signature marks order paid and finalizes stock', function () {
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = $product->variants->first();

    // Pastikan stok awal
    $variant->update(['stock' => 20, 'reserved' => 2]);
    $initialSoldCount = $product->sold_count;

    $order = Order::create([
        'number' => 'BK-TEST-PAID-001',
        'user_id' => $customer->id,
        'customer_name' => 'Fulanah',
        'customer_email' => 'fulanah@example.com',
        'customer_phone' => '081234567890',
        'shipping_address' => dummyShippingAddress(),
        'subtotal' => 100000,
        'discount_total' => 0,
        'shipping_cost' => 10000,
        'grand_total' => 110000,
        'status' => OrderStatus::PendingPayment,
        'payment_status' => PaymentStatus::Pending,
        'courier' => 'jne',
        'total_weight_gram' => 200,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_variant_id' => $variant->id,
        'product_name' => $product->name,
        'variant_label' => 'Mocca Standard',
        'sku' => $variant->sku,
        'price' => 50000,
        'quantity' => 2,
        'subtotal' => 100000,
        'weight_gram' => 100,
    ]);

    $serverKey = 'test-server-key';
    $statusCode = '200';
    $grossAmount = '110000.00';
    $signature = generateMidtransSignature($order->number, $statusCode, $grossAmount, $serverKey);

    $payload = [
        'order_id' => $order->number,
        'status_code' => $statusCode,
        'gross_amount' => $grossAmount,
        'transaction_status' => 'settlement',
        'payment_type' => 'qris',
        'transaction_id' => 'midtrans-trx-12345',
        'signature_key' => $signature,
    ];

    $response = $this->postJson(route('webhooks.payment.midtrans'), $payload);

    $response->assertOk()
        ->assertJsonFragment(['message' => 'Payment settled successfully.']);

    $order->refresh();
    expect($order->status)->toBe(OrderStatus::Processing);
    expect($order->payment_status)->toBe(PaymentStatus::Paid);
    expect($order->paid_at)->not->toBeNull();

    // Check payment record
    $payment = Payment::where('order_id', $order->id)->first();
    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(PaymentStatus::Paid);
    expect($payment->method)->toBe('qris');

    // Check stock decremented and reserved decremented
    $variant->refresh();
    expect($variant->stock)->toBe(18); // 20 - 2
    expect($variant->reserved)->toBe(0); // 2 - 2

    // Check product sold_count incremented
    $product->refresh();
    expect($product->sold_count)->toBe($initialSoldCount + 2);

    // Check stock movement 'sale' recorded
    $saleMovement = StockMovement::where('order_id', $order->id)
        ->where('type', 'sale')
        ->first();
    expect($saleMovement)->not->toBeNull();
    expect($saleMovement->quantity)->toBe(-2);
});

test('midtrans webhook with invalid signature returns 403', function () {
    $order = Order::create([
        'number' => 'BK-TEST-INVALID-001',
        'customer_name' => 'Fulanah',
        'customer_email' => 'fulanah@example.com',
        'customer_phone' => '081234567890',
        'shipping_address' => dummyShippingAddress(),
        'subtotal' => 100000,
        'discount_total' => 0,
        'shipping_cost' => 10000,
        'grand_total' => 110000,
        'status' => OrderStatus::PendingPayment,
        'payment_status' => PaymentStatus::Pending,
    ]);

    $payload = [
        'order_id' => $order->number,
        'status_code' => '200',
        'gross_amount' => '110000.00',
        'transaction_status' => 'settlement',
        'signature_key' => 'invalid-fake-signature',
    ];

    $response = $this->postJson(route('webhooks.payment.midtrans'), $payload);

    $response->assertStatus(403);
    $order->refresh();
    expect($order->payment_status)->toBe(PaymentStatus::Pending);
});

test('midtrans webhook is idempotent and does not double decrement stock', function () {
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = $product->variants->first();
    $variant->update(['stock' => 10, 'reserved' => 1]);

    $order = Order::create([
        'number' => 'BK-TEST-IDEMPOTENT-001',
        'customer_name' => 'Fulanah',
        'customer_email' => 'fulanah@example.com',
        'customer_phone' => '081234567890',
        'shipping_address' => dummyShippingAddress(),
        'subtotal' => 50000,
        'discount_total' => 0,
        'shipping_cost' => 10000,
        'grand_total' => 60000,
        'status' => OrderStatus::PendingPayment,
        'payment_status' => PaymentStatus::Pending,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_variant_id' => $variant->id,
        'product_name' => $product->name,
        'variant_label' => 'Standard',
        'sku' => $variant->sku,
        'price' => 50000,
        'quantity' => 1,
        'subtotal' => 50000,
        'weight_gram' => 100,
    ]);

    $serverKey = 'test-server-key';
    $statusCode = '200';
    $grossAmount = '60000.00';
    $signature = generateMidtransSignature($order->number, $statusCode, $grossAmount, $serverKey);

    $payload = [
        'order_id' => $order->number,
        'status_code' => $statusCode,
        'gross_amount' => $grossAmount,
        'transaction_status' => 'settlement',
        'payment_type' => 'gopay',
        'transaction_id' => 'midtrans-trx-repeat',
        'signature_key' => $signature,
    ];

    // Request 1
    $response1 = $this->postJson(route('webhooks.payment.midtrans'), $payload);
    $response1->assertOk();

    $variant->refresh();
    expect($variant->stock)->toBe(9);
    expect($variant->reserved)->toBe(0);

    // Request 2 (Webhook sent again by Midtrans retry)
    $response2 = $this->postJson(route('webhooks.payment.midtrans'), $payload);
    $response2->assertOk()
        ->assertJsonFragment(['message' => 'Order already processed (idempotent).']);

    $variant->refresh();
    // Stock remains 9, not double decremented to 8!
    expect($variant->stock)->toBe(9);
    expect($variant->reserved)->toBe(0);
});

test('midtrans webhook with expire status cancels order and releases reserved stock', function () {
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = $product->variants->first();
    $variant->update(['stock' => 15, 'reserved' => 2]);

    $order = Order::create([
        'number' => 'BK-TEST-EXPIRE-001',
        'customer_name' => 'Fulanah',
        'customer_email' => 'fulanah@example.com',
        'customer_phone' => '081234567890',
        'shipping_address' => dummyShippingAddress(),
        'subtotal' => 50000,
        'discount_total' => 0,
        'shipping_cost' => 10000,
        'grand_total' => 60000,
        'status' => OrderStatus::PendingPayment,
        'payment_status' => PaymentStatus::Pending,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_variant_id' => $variant->id,
        'product_name' => $product->name,
        'variant_label' => 'Standard',
        'sku' => $variant->sku,
        'price' => 25000,
        'quantity' => 2,
        'subtotal' => 50000,
        'weight_gram' => 100,
    ]);

    $serverKey = 'test-server-key';
    $statusCode = '200';
    $grossAmount = '60000.00';
    $signature = generateMidtransSignature($order->number, $statusCode, $grossAmount, $serverKey);

    $payload = [
        'order_id' => $order->number,
        'status_code' => $statusCode,
        'gross_amount' => $grossAmount,
        'transaction_status' => 'expire',
        'payment_type' => 'bca_va',
        'transaction_id' => 'midtrans-trx-expire',
        'signature_key' => $signature,
    ];

    $response = $this->postJson(route('webhooks.payment.midtrans'), $payload);
    $response->assertOk();

    $order->refresh();
    expect($order->status)->toBe(OrderStatus::Expired);
    expect($order->payment_status)->toBe(PaymentStatus::Expired);

    $variant->refresh();
    // Reserved stock released from 2 back to 0
    expect($variant->reserved)->toBe(0);
    // Physical stock untouched (still 15)
    expect($variant->stock)->toBe(15);

    // Stock movement 'release' recorded
    $releaseMovement = StockMovement::where('order_id', $order->id)
        ->where('type', 'release')
        ->first();
    expect($releaseMovement)->not->toBeNull();
    expect($releaseMovement->quantity)->toBe(2);
});
