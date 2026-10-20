<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Enums\VoucherType;
use App\Jobs\ExpireUnpaidOrders;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Voucher;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\PricingService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DemoSeeder;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(AdminSeeder::class);
    $this->seed(DemoSeeder::class);
});

test('pricing service calculates subtotal, discounts, and shipping cost correctly', function () {
    $pricingService = app(PricingService::class);

    $voucher = Voucher::create([
        'code' => 'HEMAT20',
        'type' => VoucherType::Fixed,
        'value' => 20000,
        'is_active' => true,
    ]);

    $calc = $pricingService->calculate(subtotal: 150000, voucher: $voucher, shippingCost: 18000);

    expect($calc['subtotal'])->toBe(150000);
    expect($calc['discount_total'])->toBe(20000);
    expect($calc['shipping_cost'])->toBe(18000);
    expect($calc['grand_total'])->toBe(148000); // 150000 - 20000 + 18000
});

test('checkout service creates order atomically and reserves stock', function () {
    $cartService = app(CartService::class);
    $checkoutService = app(CheckoutService::class);
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();

    $this->actingAs($customer);

    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = $product->variants->first();
    $initialReserved = $variant->reserved;

    $cartService->addItem($variant->id, 2);

    $order = $checkoutService->createOrder(
        customerData: [
            'name' => 'Fatimah Az-Zahra',
            'email' => 'customer@bloomingkembang.com',
            'phone' => '081234567890',
        ],
        shippingAddress: [
            'recipient_name' => 'Fatimah',
            'phone' => '081234567890',
            'province' => 'Jawa Barat',
            'city' => 'Bandung',
            'district' => 'Coblong',
            'postal_code' => '40132',
            'detail' => 'Jl. Dago Asri No. 10',
        ],
        courier: 'JNE',
        courierService: 'REG',
        shippingCost: 10000
    );

    expect($order)->toBeInstanceOf(Order::class);
    expect($order->status)->toBe(OrderStatus::PendingPayment);
    expect($order->payment_status)->toBe(PaymentStatus::Pending);
    expect($order->items()->count())->toBe(1);

    // Reserved stock pada varian harus bertambah
    expect($variant->fresh()->reserved)->toBe($initialReserved + 2);

    // Stock movement reserve harus tercatat
    expect(StockMovement::where('order_id', $order->id)->where('type', 'reserve')->exists())->toBeTrue();

    // Keranjang harus sudah dikosongkan
    expect($cartService->getCount())->toBe(0);
});

test('checkout fails when variant stock is insufficient', function () {
    $cartService = app(CartService::class);
    $checkoutService = app(CheckoutService::class);

    // Buat varian dengan stok 1
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'TEST-LIMITED',
        'color' => 'Navy Blue',
        'price' => 129000,
        'stock' => 1,
        'reserved' => 1, // Available = 0
        'is_active' => true,
    ]);

    // Force add to cart
    $cart = $cartService->getCart();
    $cart->items()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 1,
    ]);

    expect(function () use ($checkoutService) {
        $checkoutService->createOrder(
            customerData: ['name' => 'Guest', 'email' => 'guest@example.com', 'phone' => '0811111111'],
            shippingAddress: ['recipient_name' => 'Guest', 'phone' => '08111', 'province' => 'Jabar', 'city' => 'Bdg', 'district' => 'Cbl', 'postal_code' => '40132', 'detail' => 'Dago'],
            courier: 'JNE',
            courierService: 'REG',
            shippingCost: 10000
        );
    })->toThrow(\RuntimeException::class, 'tidak mencukupi');
});

test('concurrent checkouts on last stock item prevents overselling', function () {
    $checkoutService = app(CheckoutService::class);

    // Varian dengan sisa persis 1 pcs
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'LAST-PIECE',
        'color' => 'Sage Green Special',
        'price' => 149000,
        'stock' => 1,
        'reserved' => 0, // Available = 1
        'is_active' => true,
    ]);

    // Customer A memesan 1 pcs
    $customerA = User::where('email', 'customer@bloomingkembang.com')->first();
    $this->actingAs($customerA);
    $cartServiceA = app(CartService::class);
    $cartServiceA->addItem($variant->id, 1);

    $orderA = $checkoutService->createOrder(
        customerData: ['name' => 'A', 'email' => 'a@example.com', 'phone' => '081'],
        shippingAddress: ['recipient_name' => 'A', 'phone' => '081', 'province' => 'Jabar', 'city' => 'Bdg', 'district' => 'Cbl', 'postal_code' => '401', 'detail' => 'Dago'],
        courier: 'JNE',
        courierService: 'REG',
        shippingCost: 10000
    );

    expect($orderA)->not->toBeNull();
    expect($variant->fresh()->reserved)->toBe(1); // Reserved = 1, available = 0

    // Customer B mencoba checkout barang yang sama (mis. sudah ada di keranjang)
    $customerB = User::factory()->create();
    $customerB->assignRole('customer');
    $this->actingAs($customerB);
    $cartServiceB = app(CartService::class);
    $cartB = $cartServiceB->getCart();
    $cartB->items()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 1,
    ]);

    expect(function () use ($checkoutService) {
        $checkoutService->createOrder(
            customerData: ['name' => 'B', 'email' => 'b@example.com', 'phone' => '082'],
            shippingAddress: ['recipient_name' => 'B', 'phone' => '082', 'province' => 'Jabar', 'city' => 'Bdg', 'district' => 'Cbl', 'postal_code' => '401', 'detail' => 'Dago'],
            courier: 'JNE',
            courierService: 'REG',
            shippingCost: 10000
        );
    })->toThrow(\RuntimeException::class, 'tidak mencukupi');
});

test('expire unpaid orders job releases reserved stock and cancels expired orders', function () {
    $product = Product::where('status', ProductStatus::Active)->first();
    $variant = $product->variants->first();
    $variant->update(['stock' => 10, 'reserved' => 3]);

    $voucher = Voucher::create([
        'code' => 'PROMOEXPIRE',
        'type' => VoucherType::Fixed,
        'value' => 15000,
        'used_count' => 1,
        'is_active' => true,
    ]);

    // Buat order kedaluwarsa
    $order = Order::create([
        'number' => 'ORD-EXP-001',
        'customer_name' => 'Test Expired',
        'customer_email' => 'exp@example.com',
        'customer_phone' => '0811',
        'shipping_address' => ['detail' => 'Dago'],
        'subtotal' => 150000,
        'discount_total' => 15000,
        'shipping_cost' => 10000,
        'grand_total' => 145000,
        'voucher_code' => 'PROMOEXPIRE',
        'status' => OrderStatus::PendingPayment,
        'payment_status' => PaymentStatus::Pending,
        'expires_at' => now()->subMinutes(10), // Sudah lewat waktu
    ]);

    $order->items()->create([
        'product_variant_id' => $variant->id,
        'product_name' => $product->name,
        'variant_name' => $variant->color,
        'price' => 50000,
        'quantity' => 3,
        'subtotal' => 150000,
        'weight_gram' => 450,
    ]);

    $order->voucherUsage()->create([
        'voucher_id' => $voucher->id,
        'discount_amount' => 15000,
    ]);

    // Jalankan Job ExpireUnpaidOrders
    (new ExpireUnpaidOrders)->handle();

    // Order harus berubah menjadi Cancelled
    $orderFresh = $order->fresh();
    expect($orderFresh->status)->toBe(OrderStatus::Cancelled);
    expect($orderFresh->cancelled_at)->not->toBeNull();

    // Reserved stock harus dilepaskan (kembali ke 0)
    expect($variant->fresh()->reserved)->toBe(0);

    // Mutasi stok release harus tercatat
    expect(StockMovement::where('order_id', $order->id)->where('type', 'release')->exists())->toBeTrue();

    // Kuota used_count voucher harus dikembalikan
    expect($voucher->fresh()->used_count)->toBe(0);
});

test('guest checkout is forbidden when setting disabled', function () {
    Setting::set('checkout.guest_enabled', false);

    $cartService = app(CartService::class);
    $checkoutService = app(CheckoutService::class);
    $product = Product::where('status', ProductStatus::Active)->first();

    $cartService->addItem($product->variants->first()->id, 1);

    expect(function () use ($checkoutService) {
        $checkoutService->createOrder(
            customerData: ['name' => 'Guest', 'email' => 'guest@example.com', 'phone' => '081'],
            shippingAddress: ['recipient_name' => 'Guest', 'phone' => '081', 'province' => 'Jabar', 'city' => 'Bdg', 'district' => 'Cbl', 'postal_code' => '401', 'detail' => 'Dago'],
            courier: 'JNE',
            courierService: 'REG',
            shippingCost: 10000
        );
    })->toThrow(\RuntimeException::class, 'Guest checkout sedang dinonaktifkan.');
});
