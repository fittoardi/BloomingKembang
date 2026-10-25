<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Enums\VoucherType;
use App\Models\Address;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FactorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori Tambahan menggunakan CategoryFactory
        $additionalCategories = [
            'Bergo Daily',
            'Khimar Syar\'i',
            'Pashmina Silk',
            'Pashmina Ceruty Babydoll',
            'Mukena Travel Silk',
        ];

        $categories = collect();
        foreach ($additionalCategories as $catName) {
            $categories->push(Category::firstOrCreate(
                ['slug' => Str::slug($catName)],
                [
                    'name' => $catName,
                    'description' => 'Koleksi '.$catName.' dengan material berkualitas premium, sejuk dan nyaman dipakai seharian.',
                    'position' => fake()->numberBetween(4, 15),
                    'is_active' => true,
                    'seo_title' => $catName.' — Koleksi Eksklusif BloomingKembang',
                    'seo_description' => 'Belanja '.$catName.' elegan berkualitas butik terbaik.',
                ]
            ));
        }

        // Ambil semua kategori aktif untuk dikaitkan dengan produk
        $allCategories = Category::where('is_active', true)->get();

        // 2. Buat 18 Produk Menggunakan ProductFactory & ProductVariantFactory
        $productsToGenerate = [
            ['name' => 'Bergo Maryam Crinkle Airflow', 'cat' => 'Bergo Daily', 'material' => 'Crinkle Airflow Premium'],
            ['name' => 'Bergo Hamidah Jersey Sporty', 'cat' => 'Bergo Daily', 'material' => 'Jersey ITY Super Soft'],
            ['name' => 'Khimar French Syar\'i Two Tone', 'cat' => 'Khimar Syar\'i', 'material' => 'Ceruty Babydoll Armani'],
            ['name' => 'Khimar Safiyya Layered Soft Pet', 'cat' => 'Khimar Syar\'i', 'material' => 'Chiffon Silk Arab'],
            ['name' => 'Pashmina Silk Shimmer Luxe', 'cat' => 'Pashmina Silk', 'material' => 'Silk Shimmer Organza'],
            ['name' => 'Pashmina Silk Textured Crinkle', 'cat' => 'Pashmina Silk', 'material' => 'Crinkle Silk Satin'],
            ['name' => 'Pashmina Ceruty Flowy Drape', 'cat' => 'Pashmina Ceruty Babydoll', 'material' => 'Ceruty Babydoll Armani'],
            ['name' => 'Pashmina Inner 2in1 Instant', 'cat' => 'Pashmina Ceruty Babydoll', 'material' => 'Ceruty Babydoll + Rayon Spandex'],
            ['name' => 'Mukena Travel Mini Parasut Silk', 'cat' => 'Mukena Travel Silk', 'material' => 'Korean Parachute Silk'],
            ['name' => 'Mukena 2in1 Silk Laser Cut Floral', 'cat' => 'Mukena Travel Silk', 'material' => 'Zara Silk Premium'],
            ['name' => 'Voal Watersplash Waterproof Series', 'cat' => 'Hijab Segi Empat', 'material' => 'Voal Watersplash Grade A'],
            ['name' => 'Voal Miracle Glowing Syar\'i 130', 'cat' => 'Hijab Segi Empat', 'material' => 'Voal Miracle Turkish'],
            ['name' => 'Voal Paris Jadul Klasik Tegak', 'cat' => 'Hijab Segi Empat', 'material' => 'Paris Japan Asli Red Rose'],
            ['name' => 'Voal Denim Soft Daily Square', 'cat' => 'Hijab Segi Empat', 'material' => 'Voal Denim Slub'],
            ['name' => 'Ciput Rajut Bandana 4 Warna', 'cat' => 'Inner & Aksesoris', 'material' => 'Benang Rajut Spandex Premium'],
            ['name' => 'Ciput Ninja Anti Pusing Resleting', 'cat' => 'Inner & Aksesoris', 'material' => 'Rayon Spandex Kaos'],
            ['name' => 'Scrunchie Jumbo Anti Rambut Kusut', 'cat' => 'Inner & Aksesoris', 'material' => 'Satin Silk Velvet'],
            ['name' => 'Bros Dagul Tuspin Mutiara Korea', 'cat' => 'Inner & Aksesoris', 'material' => 'Alloy Logam Emas & Mutiara Air Tawar'],
        ];

        foreach ($productsToGenerate as $pData) {
            $cat = $allCategories->firstWhere('name', $pData['cat']) ?? $allCategories->first();
            $slug = Str::slug($pData['name']);

            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $cat->id,
                    'name' => $pData['name'],
                    'short_description' => 'Koleksi '.$pData['name'].' dengan sentuhan bahan '.$pData['material'].' yang anggun dan adem.',
                    'description' => '<p>Hadirkan keanggunan sehari-hari bersama koleksi pilihan BloomingKembang. Dirancang dengan presisi jahit tepi berkualitas butik.</p><p>Material '.$pData['material'].' memberikan rasa percaya diri dan kenyamanan maksimal saat beraktivitas.</p>',
                    'material' => $pData['material'],
                    'size_guide' => 'Ukuran standar butik dengan toleransi kerapian jahit 1-2 cm.',
                    'care_guide' => 'Cuci lembut dengan detergen cair, jangan diperas kencang, setrika suhu rendah.',
                    'status' => ProductStatus::Active,
                    'is_featured' => fake()->boolean(30),
                    'sold_count' => fake()->numberBetween(12, 140),
                    'rating_avg' => fake()->randomElement([4.8, 4.9, 5.0]),
                    'rating_count' => fake()->numberBetween(5, 45),
                    'seo_title' => $pData['name'].' — BloomingKembang',
                    'seo_description' => 'Beli '.$pData['name'].' asli kualitas butik di BloomingKembang.',
                ]
            );

            if (! $product->hasMedia('images')) {
                $imageFile = match ($pData['cat']) {
                    'Bergo Daily' => 'bergo-daily.jpg',
                    'Khimar Syar\'i' => 'khimar-syari.jpg',
                    'Pashmina Silk' => 'pashmina-silk.jpg',
                    'Pashmina Ceruty Babydoll' => 'pashmina-ceruty.jpg',
                    'Mukena Travel Silk' => 'mukena-silk.jpg',
                    'Hijab Segi Empat' => str_contains($slug, 'paris') ? 'paris-laser-cut.jpg' : 'voal-signature.jpg',
                    'Inner & Aksesoris' => str_contains($slug, 'ciput') ? 'ciput-ninja.jpg' : 'klip-turki.jpg',
                    default => 'voal-signature.jpg',
                };
                $imagePath = database_path('seeders/images/'.$imageFile);
                if (file_exists($imagePath)) {
                    $product->addMedia($imagePath)->preservingOriginal()->toMediaCollection('images');
                }
            }

            // Buat 3 Varian Warna untuk Setiap Produk
            $palette = [
                ['color' => 'Dusty Rose', 'hex' => '#c89d9c'],
                ['color' => 'Muted Sage', 'hex' => '#a9aea9'],
                ['color' => 'Soft Lilac', 'hex' => '#bfa3b8'],
                ['color' => 'Almond Milk', 'hex' => '#e8ded4'],
                ['color' => 'Warm Taupe', 'hex' => '#b39c8e'],
                ['color' => 'Obsidian Black', 'hex' => '#1c1717'],
                ['color' => 'Deep Navy', 'hex' => '#212d40'],
                ['color' => 'Terracotta', 'hex' => '#c97a63'],
            ];

            $selectedColors = fake()->randomElements($palette, 3);
            $basePrice = fake()->randomElement([69000, 79000, 89000, 99000, 119000, 139000, 159000]);

            foreach ($selectedColors as $index => $c) {
                $sku = 'BK-'.strtoupper(Str::limit(Str::slug($pData['name']), 4, '')).'-'.strtoupper(Str::limit($c['color'], 3, '')).'-'.fake()->unique()->numerify('###');

                $variant = ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'color' => $c['color'],
                    ],
                    [
                        'sku' => $sku,
                        'color_hex' => $c['hex'],
                        'size' => fake()->randomElement(['115x115', '120x120', '180x75', 'All Size']),
                        'price' => $basePrice,
                        'compare_price' => fake()->boolean(40) ? $basePrice + 20000 : null,
                        'stock' => fake()->numberBetween(15, 60),
                        'reserved' => 0,
                        'weight_gram' => fake()->randomElement([100, 120, 150, 200]),
                        'is_active' => true,
                        'position' => $index + 1,
                    ]
                );

                StockMovement::firstOrCreate(
                    [
                        'product_variant_id' => $variant->id,
                        'type' => 'adjustment',
                        'order_id' => null,
                    ],
                    [
                        'quantity' => $variant->stock,
                        'stock_after' => $variant->stock,
                        'note' => 'Stok awal dari batch produksi baru',
                        'created_at' => now(),
                    ]
                );
            }
        }

        // 3. Buat 5 Dummy Customers Baru Menggunakan UserFactory & AddressFactory
        $customerUsers = User::factory()->count(5)->create();
        foreach ($customerUsers as $user) {
            $user->assignRole('customer');
            Address::factory()->default()->create([
                'user_id' => $user->id,
                'recipient_name' => $user->name,
            ]);
        }

        // 4. Buat Voucher Promo Tambahan menggunakan VoucherFactory
        Voucher::firstOrCreate(
            ['code' => 'GAJIANHEMAT'],
            [
                'description' => 'Diskon 15% spesial payday weekend',
                'type' => VoucherType::Percent,
                'value' => 15,
                'min_purchase' => 100000,
                'max_discount' => 30000,
                'quota' => 200,
                'used_count' => 8,
                'per_user_limit' => 1,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDays(14),
                'is_active' => true,
            ]
        );

        Voucher::firstOrCreate(
            ['code' => 'KEMBANGSERU'],
            [
                'description' => 'Potongan langsung Rp 20.000 untuk minimal belanja Rp 120.000',
                'type' => VoucherType::Fixed,
                'value' => 20000,
                'min_purchase' => 120000,
                'max_discount' => null,
                'quota' => 150,
                'used_count' => 12,
                'per_user_limit' => 1,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addMonth(),
                'is_active' => true,
            ]
        );

        // 5. Buat 10 Pesanan Tambahan dengan Variasi Status
        $allVariants = ProductVariant::with('product')->get();
        if ($allVariants->count() >= 2) {
            $statuses = [
                OrderStatus::Completed,
                OrderStatus::Shipped,
                OrderStatus::Processing,
                OrderStatus::Paid,
                OrderStatus::PendingPayment,
            ];

            for ($i = 1; $i <= 10; $i++) {
                $orderUser = $customerUsers->random();
                $orderStatus = fake()->randomElement($statuses);
                $isPaid = in_array($orderStatus, [
                    OrderStatus::Completed,
                    OrderStatus::Shipped,
                    OrderStatus::Processing,
                    OrderStatus::Paid,
                ]);

                $selectedVariant = $allVariants->random();
                $qty = fake()->numberBetween(1, 3);
                $subtotal = $selectedVariant->price * $qty;
                $shipping = fake()->randomElement([12000, 15000, 18000]);
                $grandTotal = $subtotal + $shipping;

                $orderNumber = 'INV-'.date('Ymd').'-F'.str_pad((string) $i, 4, '0', STR_PAD_LEFT);

                $order = Order::firstOrCreate(
                    ['number' => $orderNumber],
                    [
                        'user_id' => $orderUser->id,
                        'customer_name' => $orderUser->name,
                        'customer_email' => $orderUser->email,
                        'customer_phone' => $orderUser->phone ?? ('628'.fake()->numerify('##########')),
                        'shipping_address' => [
                            'recipient_name' => $orderUser->name,
                            'phone' => '628'.fake()->numerify('##########'),
                            'province' => 'Jawa Barat',
                            'city' => 'Bandung',
                            'district' => 'Coblong',
                            'postal_code' => '40132',
                            'detail' => 'Jl. Cisitu Indah No. '.fake()->numberBetween(1, 99),
                        ],
                        'subtotal' => $subtotal,
                        'discount_total' => 0,
                        'shipping_cost' => $shipping,
                        'grand_total' => $grandTotal,
                        'status' => $orderStatus,
                        'payment_status' => $isPaid ? PaymentStatus::Paid : PaymentStatus::Pending,
                        'courier' => fake()->randomElement(['JNE', 'SiCepat', 'J&T']),
                        'courier_service' => 'REG',
                        'tracking_number' => in_array($orderStatus, [OrderStatus::Shipped, OrderStatus::Completed]) ? ('BK'.fake()->numerify('############')) : null,
                        'total_weight_gram' => $selectedVariant->weight_gram * $qty,
                        'paid_at' => $isPaid ? now()->subDays(fake()->numberBetween(1, 5)) : null,
                        'shipped_at' => in_array($orderStatus, [OrderStatus::Shipped, OrderStatus::Completed]) ? now()->subDays(2) : null,
                        'completed_at' => $orderStatus === OrderStatus::Completed ? now()->subDay() : null,
                        'created_at' => now()->subDays(fake()->numberBetween(1, 6)),
                    ]
                );

                OrderItem::firstOrCreate(
                    ['order_id' => $order->id, 'product_variant_id' => $selectedVariant->id],
                    [
                        'product_name' => $selectedVariant->product->name,
                        'variant_label' => $selectedVariant->color.' / '.($selectedVariant->size ?? 'All Size'),
                        'sku' => $selectedVariant->sku,
                        'price' => $selectedVariant->price,
                        'quantity' => $qty,
                        'subtotal' => $subtotal,
                        'weight_gram' => $selectedVariant->weight_gram * $qty,
                    ]
                );

                Payment::firstOrCreate(
                    ['order_id' => $order->id],
                    [
                        'provider' => 'midtrans',
                        'provider_ref' => 'MID-FAC-'.strtoupper(Str::random(10)),
                        'method' => fake()->randomElement(['qris', 'bca_va', 'mandiri_va', 'gopay']),
                        'amount' => $grandTotal,
                        'status' => $isPaid ? PaymentStatus::Paid : PaymentStatus::Pending,
                        'paid_at' => $isPaid ? $order->paid_at : null,
                        'raw_payload' => ['transaction_status' => $isPaid ? 'settlement' : 'pending'],
                    ]
                );
            }
        }

        // 6. Tambahkan Banner Promo Tambahan menggunakan BannerFactory
        Banner::firstOrCreate(
            ['title' => 'Koleksi Bergo & Khimar Elegan 2026'],
            [
                'subtitle' => 'Ditenun dari jersey airflow dan ceruty babydoll super soft.',
                'link_url' => '/koleksi',
                'position' => 'mid_page',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );
    }
}
