<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer@bloomingkembang.com')->first();
        if (! $customer) {
            return;
        }

        $address = Address::where('user_id', $customer->id)->where('is_default', true)->first();
        $shippingAddress = $address ? [
            'recipient_name' => $address->recipient_name,
            'phone' => $address->phone,
            'province' => $address->province,
            'city' => $address->city,
            'district' => $address->district,
            'postal_code' => $address->postal_code,
            'detail' => $address->detail,
        ] : [
            'recipient_name' => 'Fatimah Az-Zahra',
            'phone' => '6285712345678',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Tebet',
            'postal_code' => '12810',
            'detail' => 'Jl. Tebet Barat Dalam VII No. 15',
        ];

        $variants = ProductVariant::with('product')->take(10)->get();
        if ($variants->isEmpty()) {
            return;
        }

        // 1. Pesanan Selesai (Completed) dengan Voucher
        $v1 = $variants->get(0);
        $v2 = $variants->get(1) ?? $v1;
        $voucher = Voucher::where('code', 'BLOOMINGFIRST')->first();

        $item1Subtotal = $v1->price * 2;
        $item2Subtotal = $v2->price * 1;
        $subtotal1 = $item1Subtotal + $item2Subtotal;
        $discount1 = 15000;
        $shipping1 = 18000;
        $grandTotal1 = $subtotal1 + $shipping1 - $discount1;

        $order1 = Order::firstOrCreate(
            ['number' => 'INV-20261020-0001'],
            [
                'user_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone ?? '6285712345678',
                'shipping_address' => $shippingAddress,
                'subtotal' => $subtotal1,
                'discount_total' => $discount1,
                'shipping_cost' => $shipping1,
                'grand_total' => $grandTotal1,
                'voucher_code' => $voucher?->code ?? 'BLOOMINGFIRST',
                'status' => OrderStatus::Completed,
                'payment_status' => PaymentStatus::Paid,
                'courier' => 'JNE',
                'courier_service' => 'REG',
                'tracking_number' => 'BKJNE9823412093',
                'total_weight_gram' => 330,
                'customer_note' => 'Mohon sertakan kartu ucapan selamat ya kak.',
                'paid_at' => now()->subDays(5),
                'shipped_at' => now()->subDays(4),
                'completed_at' => now()->subDays(2),
                'created_at' => now()->subDays(5),
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order1->id, 'product_variant_id' => $v1->id],
            [
                'product_name' => $v1->product->name,
                'variant_label' => $v1->color.' / '.($v1->size ?? 'All Size'),
                'sku' => $v1->sku,
                'price' => $v1->price,
                'quantity' => 2,
                'subtotal' => $item1Subtotal,
                'weight_gram' => $v1->weight_gram * 2,
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order1->id, 'product_variant_id' => $v2->id],
            [
                'product_name' => $v2->product->name,
                'variant_label' => $v2->color.' / '.($v2->size ?? 'All Size'),
                'sku' => $v2->sku,
                'price' => $v2->price,
                'quantity' => 1,
                'subtotal' => $item2Subtotal,
                'weight_gram' => $v2->weight_gram,
            ]
        );

        Payment::firstOrCreate(
            ['order_id' => $order1->id],
            [
                'provider' => 'midtrans',
                'provider_ref' => 'MID-DEMO-'.Str::upper(Str::random(10)),
                'method' => 'qris',
                'amount' => $grandTotal1,
                'status' => PaymentStatus::Paid,
                'paid_at' => now()->subDays(5),
                'raw_payload' => ['transaction_status' => 'settlement', 'payment_type' => 'qris'],
            ]
        );

        if ($voucher) {
            VoucherUsage::firstOrCreate(
                ['order_id' => $order1->id],
                [
                    'voucher_id' => $voucher->id,
                    'user_id' => $customer->id,
                    'discount_amount' => $discount1,
                    'created_at' => now()->subDays(5),
                ]
            );
        }

        OrderStatusHistory::firstOrCreate(
            ['order_id' => $order1->id, 'to_status' => 'completed'],
            [
                'from_status' => 'shipped',
                'changed_by' => null,
                'note' => 'Pesanan telah diterima oleh pembeli.',
                'created_at' => now()->subDays(2),
            ]
        );

        // 2. Pesanan Sedang Dikirim (Shipped)
        $v3 = $variants->get(2) ?? $v1;
        $subtotal2 = $v3->price * 1;
        $shipping2 = 12000;
        $grandTotal2 = $subtotal2 + $shipping2;

        $order2 = Order::firstOrCreate(
            ['number' => 'INV-20261023-0002'],
            [
                'user_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone ?? '6285712345678',
                'shipping_address' => $shippingAddress,
                'subtotal' => $subtotal2,
                'discount_total' => 0,
                'shipping_cost' => $shipping2,
                'grand_total' => $grandTotal2,
                'status' => OrderStatus::Shipped,
                'payment_status' => PaymentStatus::Paid,
                'courier' => 'SiCepat',
                'courier_service' => 'SIUNTUNG',
                'tracking_number' => '0041289123891',
                'total_weight_gram' => $v3->weight_gram,
                'paid_at' => now()->subDays(2),
                'shipped_at' => now()->subDay(),
                'created_at' => now()->subDays(2),
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order2->id, 'product_variant_id' => $v3->id],
            [
                'product_name' => $v3->product->name,
                'variant_label' => $v3->color.' / '.($v3->size ?? 'All Size'),
                'sku' => $v3->sku,
                'price' => $v3->price,
                'quantity' => 1,
                'subtotal' => $subtotal2,
                'weight_gram' => $v3->weight_gram,
            ]
        );

        Payment::firstOrCreate(
            ['order_id' => $order2->id],
            [
                'provider' => 'midtrans',
                'provider_ref' => 'MID-DEMO-'.Str::upper(Str::random(10)),
                'method' => 'bca_va',
                'amount' => $grandTotal2,
                'status' => PaymentStatus::Paid,
                'paid_at' => now()->subDays(2),
                'raw_payload' => ['transaction_status' => 'settlement', 'payment_type' => 'bank_transfer'],
            ]
        );

        // 3. Pesanan Sedang Diproses (Processing)
        $v4 = $variants->get(3) ?? $v1;
        $subtotal3 = $v4->price * 2;
        $shipping3 = 15000;
        $grandTotal3 = $subtotal3 + $shipping3;

        $order3 = Order::firstOrCreate(
            ['number' => 'INV-20261024-0003'],
            [
                'user_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone ?? '6285712345678',
                'shipping_address' => $shippingAddress,
                'subtotal' => $subtotal3,
                'discount_total' => 0,
                'shipping_cost' => $shipping3,
                'grand_total' => $grandTotal3,
                'status' => OrderStatus::Processing,
                'payment_status' => PaymentStatus::Paid,
                'courier' => 'J&T',
                'courier_service' => 'EZ',
                'total_weight_gram' => $v4->weight_gram * 2,
                'paid_at' => now()->subHours(6),
                'created_at' => now()->subHours(7),
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order3->id, 'product_variant_id' => $v4->id],
            [
                'product_name' => $v4->product->name,
                'variant_label' => $v4->color.' / '.($v4->size ?? 'All Size'),
                'sku' => $v4->sku,
                'price' => $v4->price,
                'quantity' => 2,
                'subtotal' => $subtotal3,
                'weight_gram' => $v4->weight_gram * 2,
            ]
        );

        Payment::firstOrCreate(
            ['order_id' => $order3->id],
            [
                'provider' => 'midtrans',
                'provider_ref' => 'MID-DEMO-'.Str::upper(Str::random(10)),
                'method' => 'gopay',
                'amount' => $grandTotal3,
                'status' => PaymentStatus::Paid,
                'paid_at' => now()->subHours(6),
                'raw_payload' => ['transaction_status' => 'settlement', 'payment_type' => 'gopay'],
            ]
        );

        // 4. Pesanan Menunggu Pembayaran (Pending Payment)
        $v5 = $variants->get(4) ?? $v1;
        $subtotal4 = $v5->price * 1;
        $shipping4 = 15000;
        $grandTotal4 = $subtotal4 + $shipping4;

        $order4 = Order::firstOrCreate(
            ['number' => 'INV-20261025-0004'],
            [
                'user_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone ?? '6285712345678',
                'shipping_address' => $shippingAddress,
                'subtotal' => $subtotal4,
                'discount_total' => 0,
                'shipping_cost' => $shipping4,
                'grand_total' => $grandTotal4,
                'status' => OrderStatus::PendingPayment,
                'payment_status' => PaymentStatus::Pending,
                'courier' => 'JNE',
                'courier_service' => 'REG',
                'total_weight_gram' => $v5->weight_gram,
                'expires_at' => now()->addHours(23),
                'created_at' => now()->subHour(),
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order4->id, 'product_variant_id' => $v5->id],
            [
                'product_name' => $v5->product->name,
                'variant_label' => $v5->color.' / '.($v5->size ?? 'All Size'),
                'sku' => $v5->sku,
                'price' => $v5->price,
                'quantity' => 1,
                'subtotal' => $subtotal4,
                'weight_gram' => $v5->weight_gram,
            ]
        );

        Payment::firstOrCreate(
            ['order_id' => $order4->id],
            [
                'provider' => 'midtrans',
                'provider_ref' => 'MID-DEMO-'.Str::upper(Str::random(10)),
                'method' => 'qris',
                'amount' => $grandTotal4,
                'status' => PaymentStatus::Pending,
                'payment_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/demo-token-12345',
                'expires_at' => now()->addHours(23),
                'raw_payload' => ['transaction_status' => 'pending'],
            ]
        );

        // 5. Pesanan Tamu / Guest (Guest Order)
        $v6 = $variants->get(5) ?? $v1;
        $guestSubtotal = $v6->price * 1;
        $guestShipping = 16000;
        $guestTotal = $guestSubtotal + $guestShipping;

        $guestAddress = [
            'recipient_name' => 'Aisyah Putri',
            'phone' => '6281298765432',
            'province' => 'Jawa Timur',
            'city' => 'Surabaya',
            'district' => 'Wonokromo',
            'postal_code' => '60241',
            'detail' => 'Jl. Ketintang Baru III No. 8',
        ];

        $guestOrder = Order::firstOrCreate(
            ['number' => 'INV-20261025-0005'],
            [
                'user_id' => null,
                'customer_name' => 'Aisyah Putri',
                'customer_email' => 'aisyah.putri@gmail.com',
                'customer_phone' => '6281298765432',
                'shipping_address' => $guestAddress,
                'subtotal' => $guestSubtotal,
                'discount_total' => 0,
                'shipping_cost' => $guestShipping,
                'grand_total' => $guestTotal,
                'status' => OrderStatus::Paid,
                'payment_status' => PaymentStatus::Paid,
                'courier' => 'J&T',
                'courier_service' => 'EZ',
                'total_weight_gram' => $v6->weight_gram,
                'paid_at' => now()->subHours(3),
                'created_at' => now()->subHours(4),
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $guestOrder->id, 'product_variant_id' => $v6->id],
            [
                'product_name' => $v6->product->name,
                'variant_label' => $v6->color.' / '.($v6->size ?? 'All Size'),
                'sku' => $v6->sku,
                'price' => $v6->price,
                'quantity' => 1,
                'subtotal' => $guestSubtotal,
                'weight_gram' => $v6->weight_gram,
            ]
        );

        Payment::firstOrCreate(
            ['order_id' => $guestOrder->id],
            [
                'provider' => 'midtrans',
                'provider_ref' => 'MID-DEMO-'.Str::upper(Str::random(10)),
                'method' => 'bca_va',
                'amount' => $guestTotal,
                'status' => PaymentStatus::Paid,
                'paid_at' => now()->subHours(3),
                'raw_payload' => ['transaction_status' => 'settlement'],
            ]
        );

        // 6. Seed Wishlist Customer
        $products = Product::where('status', 'active')->take(3)->get();
        foreach ($products as $p) {
            Wishlist::firstOrCreate([
                'user_id' => $customer->id,
                'product_id' => $p->id,
            ], [
                'created_at' => now(),
            ]);
        }
    }
}
