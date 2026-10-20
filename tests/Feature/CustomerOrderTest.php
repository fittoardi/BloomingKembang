<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Livewire\Account\OrderDetail;
use App\Livewire\TrackOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DemoSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(AdminSeeder::class);
    $this->seed(DemoSeeder::class);
});

function testShippingAddress(): array
{
    return [
        'recipient_name' => 'Fulanah',
        'phone' => '081234567890',
        'detail' => 'Jl. Melati No. 5',
        'city' => 'Bandung',
        'province' => 'Jawa Barat',
        'postal_code' => '40111',
    ];
}

test('customer can view own orders on account order index', function () {
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();

    $order = Order::create([
        'number' => 'BK-MY-ORDER-001',
        'user_id' => $customer->id,
        'customer_name' => $customer->name,
        'customer_email' => $customer->email,
        'customer_phone' => '081234567890',
        'shipping_address' => testShippingAddress(),
        'subtotal' => 120000,
        'discount_total' => 0,
        'shipping_cost' => 15000,
        'grand_total' => 135000,
        'status' => OrderStatus::PendingPayment,
        'payment_status' => PaymentStatus::Pending,
        'courier' => 'sicepat',
        'total_weight_gram' => 200,
    ]);

    $this->actingAs($customer)
        ->get(route('account.orders'))
        ->assertOk()
        ->assertSee('BK-MY-ORDER-001')
        ->assertSee('Menunggu Pembayaran');
});

test('customer A cannot view customer B order', function () {
    $customerA = User::where('email', 'customer@bloomingkembang.com')->first();
    $customerB = User::factory()->create([
        'email' => 'customerb@example.com',
        'name' => 'Customer B',
    ]);
    $customerB->assignRole('customer');

    $orderB = Order::create([
        'number' => 'BK-ORDER-CUSTOMER-B',
        'user_id' => $customerB->id,
        'customer_name' => $customerB->name,
        'customer_email' => $customerB->email,
        'customer_phone' => '0899999999',
        'shipping_address' => testShippingAddress(),
        'subtotal' => 100000,
        'discount_total' => 0,
        'shipping_cost' => 10000,
        'grand_total' => 110000,
        'status' => OrderStatus::PendingPayment,
        'payment_status' => PaymentStatus::Pending,
    ]);

    // Customer A tries to access Customer B's order
    $this->actingAs($customerA)
        ->get(route('account.orders.show', $orderB->number))
        ->assertForbidden();
});

test('customer can cancel unpaid pending order and release reserved stock', function () {
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = $product->variants->first();
    $variant->update(['stock' => 10, 'reserved' => 2]);

    $order = Order::create([
        'number' => 'BK-CANCEL-TEST-001',
        'user_id' => $customer->id,
        'customer_name' => $customer->name,
        'customer_email' => $customer->email,
        'customer_phone' => '081234567890',
        'shipping_address' => testShippingAddress(),
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

    $this->actingAs($customer);

    Livewire::test(OrderDetail::class, ['orderNumber' => $order->number])
        ->call('cancelOrder')
        ->assertHasNoErrors();

    $order->refresh();
    expect($order->status)->toBe(OrderStatus::Cancelled);
    expect($order->cancel_reason)->toBe('Dibatalkan oleh pelanggan');

    $variant->refresh();
    expect($variant->reserved)->toBe(0); // 2 - 2
    expect($variant->stock)->toBe(10);
});

test('guest can track order using contact verification', function () {
    $order = Order::create([
        'number' => 'BK-GUEST-TRACK-001',
        'customer_name' => 'Siti Khadijah',
        'customer_email' => 'siti@example.com',
        'customer_phone' => '081300001111',
        'shipping_address' => [
            'recipient_name' => 'Siti Khadijah',
            'phone' => '081300001111',
            'detail' => 'Jl. Mawar No. 10',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40123',
        ],
        'subtotal' => 95000,
        'discount_total' => 0,
        'shipping_cost' => 10000,
        'grand_total' => 105000,
        'status' => OrderStatus::Shipped,
        'payment_status' => PaymentStatus::Paid,
        'courier' => 'jne',
        'courier_service' => 'REG',
        'tracking_number' => 'JNE-RESI-999888',
        'shipped_at' => now(),
    ]);

    // Tracking with correct contact
    Livewire::test(TrackOrder::class)
        ->set('orderNumber', 'BK-GUEST-TRACK-001')
        ->set('contact', 'siti@example.com')
        ->call('search')
        ->assertSee('JNE-RESI-999888')
        ->assertSee('Sedang Dikirim');

    // Tracking with wrong contact
    Livewire::test(TrackOrder::class)
        ->set('orderNumber', 'BK-GUEST-TRACK-001')
        ->set('contact', 'wrong@example.com')
        ->call('search')
        ->assertSee('Data kontak (email / nomor telepon) tidak cocok dengan pesanan ini.')
        ->assertDontSee('JNE-RESI-999888');
});

test('guest can track order directly with signed url', function () {
    $order = Order::create([
        'number' => 'BK-SIGNED-TRACK-001',
        'customer_name' => 'Aisyah',
        'customer_email' => 'aisyah@example.com',
        'customer_phone' => '081399998888',
        'shipping_address' => testShippingAddress(),
        'subtotal' => 80000,
        'discount_total' => 0,
        'shipping_cost' => 10000,
        'grand_total' => 90000,
        'status' => OrderStatus::Processing,
        'payment_status' => PaymentStatus::Paid,
    ]);

    $signedUrl = URL::signedRoute('orders.track.signed', ['orderNumber' => $order->number]);

    $this->get($signedUrl)
        ->assertOk()
        ->assertSee('BK-SIGNED-TRACK-001')
        ->assertSee('Sedang Diproses');
});

test('admin can view order and download invoice pdf and shipping label pdf', function () {
    $admin = User::where('email', 'admin@bloomingkembang.com')->first();
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = $product->variants->first();

    $order = Order::create([
        'number' => 'BK-ADMIN-ORDER-001',
        'customer_name' => 'Fulanah',
        'customer_email' => 'fulanah@example.com',
        'customer_phone' => '081234567890',
        'shipping_address' => testShippingAddress(),
        'subtotal' => 75000,
        'discount_total' => 0,
        'shipping_cost' => 10000,
        'grand_total' => 85000,
        'status' => OrderStatus::Processing,
        'payment_status' => PaymentStatus::Paid,
        'courier' => 'sicepat',
        'total_weight_gram' => 150,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_variant_id' => $variant->id,
        'product_name' => $product->name,
        'variant_label' => 'Standard',
        'sku' => $variant->sku,
        'price' => 75000,
        'quantity' => 1,
        'subtotal' => 75000,
        'weight_gram' => 150,
    ]);

    $this->actingAs($admin)
        ->get('/admin/orders')
        ->assertOk()
        ->assertSee('BK-ADMIN-ORDER-001');

    $this->actingAs($admin)
        ->get("/admin/orders/{$order->id}")
        ->assertOk()
        ->assertSee('BK-ADMIN-ORDER-001');
});
