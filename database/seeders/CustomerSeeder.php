<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customersData = [
            [
                'name' => 'Aisyah Rahmadani',
                'email' => 'aisyah.rahmadani@example.com',
                'phone' => '6281234567801',
                'is_active' => true,
                'last_login_at' => now()->subHours(3),
                'addresses' => [
                    [
                        'label' => 'Rumah Utama',
                        'recipient_name' => 'Aisyah Rahmadani',
                        'phone' => '6281234567801',
                        'province' => 'Jawa Barat',
                        'city' => 'Bandung',
                        'district' => 'Coblong',
                        'postal_code' => '40132',
                        'detail' => 'Jl. Dago Asri No. 24, RT 03/RW 09',
                        'is_default' => true,
                    ],
                    [
                        'label' => 'Kantor Studio',
                        'recipient_name' => 'Aisyah (Creative Studio)',
                        'phone' => '6281234567801',
                        'province' => 'Jawa Barat',
                        'city' => 'Bandung',
                        'district' => 'Sumur Bandung',
                        'postal_code' => '40111',
                        'detail' => 'Gedung Wisma Dago Lt. 2 No. 201',
                        'is_default' => false,
                    ],
                ],
                'orders_count' => 3,
                'status_mix' => [OrderStatus::Completed, OrderStatus::Completed, OrderStatus::Shipped],
                'review' => [
                    'rating' => 5,
                    'title' => 'Serat voal ternyaman yang pernah saya punya',
                    'comment' => 'Kainnya adem banget dan mudah dibentuk tegak paripurna di dahi tanpa perlu semprotan hairspray/pelicin hijab. Sukses selalu BloomingKembang!',
                ],
            ],
            [
                'name' => 'Nadia Khairunnisa',
                'email' => 'nadia.khairunnisa@example.com',
                'phone' => '6281234567802',
                'is_active' => true,
                'last_login_at' => now()->subDay(),
                'addresses' => [
                    [
                        'label' => 'Apartemen',
                        'recipient_name' => 'Nadia Khairunnisa',
                        'phone' => '6281234567802',
                        'province' => 'DKI Jakarta',
                        'city' => 'Jakarta Selatan',
                        'district' => 'Kebayoran Baru',
                        'postal_code' => '12180',
                        'detail' => 'Apartemen Senopati Suites Tower 1 Unit 12B',
                        'is_default' => true,
                    ],
                ],
                'orders_count' => 2,
                'status_mix' => [OrderStatus::Completed, OrderStatus::Processing],
                'review' => [
                    'rating' => 5,
                    'title' => 'Warna muted earth tone sangat berkelas',
                    'comment' => 'Warnanya persis seperti foto produk di katalog. Sangat cocok dipadukan dengan busana formal maupun daily outfit kasual.',
                ],
            ],
            [
                'name' => 'Zahra Safira',
                'email' => 'zahra.safira@example.com',
                'phone' => '6281234567803',
                'is_active' => true,
                'last_login_at' => now()->subDays(2),
                'addresses' => [
                    [
                        'label' => 'Rumah',
                        'recipient_name' => 'Zahra Safira',
                        'phone' => '6281234567803',
                        'province' => 'Jawa Timur',
                        'city' => 'Surabaya',
                        'district' => 'Wonokromo',
                        'postal_code' => '60241',
                        'detail' => 'Jl. Raya Darmo Permai III No. 8',
                        'is_default' => true,
                    ],
                ],
                'orders_count' => 2,
                'status_mix' => [OrderStatus::Completed, OrderStatus::Paid],
                'review' => [
                    'rating' => 5,
                    'title' => 'Pengiriman super cepat dan kemasan aman',
                    'comment' => 'Bahan ceruty babydoll jatuh flowy dan elegan, tidak menerawang jika dilipat dua. Packaging kotak kertasnya juga premium banget.',
                ],
            ],
            [
                'name' => 'Hafidzah Nurul Izzah',
                'email' => 'hafidzah.izzah@example.com',
                'phone' => '6281234567804',
                'is_active' => true,
                'last_login_at' => now()->subDays(5),
                'addresses' => [
                    [
                        'label' => 'Kos Mahasiswi',
                        'recipient_name' => 'Hafidzah Nurul Izzah',
                        'phone' => '6281234567804',
                        'province' => 'D.I. Yogyakarta',
                        'city' => 'Sleman',
                        'district' => 'Depok',
                        'postal_code' => '55281',
                        'detail' => 'Kos Putri Melati Asri No. 17, Jl. Kaliurang KM 5',
                        'is_default' => true,
                    ],
                ],
                'orders_count' => 1,
                'status_mix' => [OrderStatus::Completed],
                'review' => [
                    'rating' => 5,
                    'title' => 'Jahit tepi rapi dan halus',
                    'comment' => 'Kualitas butik tapi harganya sangat ramah di kantong mahasiswa. Pashminanya mudah di-styling berbagai model gaya.',
                ],
            ],
            [
                'name' => 'Dinda Permata Sari',
                'email' => 'dinda.permata@example.com',
                'phone' => '6281234567805',
                'is_active' => true,
                'last_login_at' => now()->subDay(),
                'addresses' => [
                    [
                        'label' => 'Rumah',
                        'recipient_name' => 'Dinda Permata Sari',
                        'phone' => '6281234567805',
                        'province' => 'Sumatera Utara',
                        'city' => 'Medan',
                        'district' => 'Medan Baru',
                        'postal_code' => '20153',
                        'detail' => 'Jl. Dr. Mansyur Komp. USU No. 42',
                        'is_default' => true,
                    ],
                ],
                'orders_count' => 1,
                'status_mix' => [OrderStatus::Shipped],
                'review' => null,
            ],
            [
                'name' => 'Siti Maryam Al-Habsyi',
                'email' => 'siti.maryam@example.com',
                'phone' => '6281234567806',
                'is_active' => true,
                'last_login_at' => now()->subDays(4),
                'addresses' => [
                    [
                        'label' => 'Rumah',
                        'recipient_name' => 'Siti Maryam',
                        'phone' => '6281234567806',
                        'province' => 'Jawa Tengah',
                        'city' => 'Semarang',
                        'district' => 'Banyumanik',
                        'postal_code' => '50269',
                        'detail' => 'Jl. Setiabudi Perumahan Srondol Asri Blok B-5',
                        'is_default' => true,
                    ],
                ],
                'orders_count' => 1,
                'status_mix' => [OrderStatus::Processing],
                'review' => null,
            ],
            [
                'name' => 'Putri Wulandari',
                'email' => 'putri.wulandari@example.com',
                'phone' => '6281234567807',
                'is_active' => true,
                'last_login_at' => now()->subHours(12),
                'addresses' => [
                    [
                        'label' => 'Rumah',
                        'recipient_name' => 'Putri Wulandari',
                        'phone' => '6281234567807',
                        'province' => 'Sulawesi Selatan',
                        'city' => 'Makassar',
                        'district' => 'Panakkukang',
                        'postal_code' => '90231',
                        'detail' => 'Jl. Boulevard Ruko Ruby Blok F No. 12',
                        'is_default' => true,
                    ],
                ],
                'orders_count' => 1,
                'status_mix' => [OrderStatus::Completed],
                'review' => [
                    'rating' => 4,
                    'title' => 'Mukena travel praktis dan compact',
                    'comment' => 'Ukurannya kecil muat di tas kerja tapi saat dipakai tetap syar\'i menutup aurat dengan sempurna. Bahannya juga sejuk di kulit.',
                ],
            ],
            [
                'name' => 'Anisa Larasati',
                'email' => 'anisa.larasati@example.com',
                'phone' => '6281234567808',
                'is_active' => true,
                'last_login_at' => now()->subHours(1),
                'addresses' => [
                    [
                        'label' => 'Rumah',
                        'recipient_name' => 'Anisa Larasati',
                        'phone' => '6281234567808',
                        'province' => 'Jawa Timur',
                        'city' => 'Malang',
                        'district' => 'Lowokwaru',
                        'postal_code' => '65141',
                        'detail' => 'Jl. Soekarno Hatta No. 88A',
                        'is_default' => true,
                    ],
                ],
                'orders_count' => 0, // Pelanggan baru mendaftar
                'status_mix' => [],
                'review' => null,
            ],
            [
                'name' => 'Rania Zulaikha',
                'email' => 'rania.zulaikha@example.com',
                'phone' => '6281234567809',
                'is_active' => true,
                'last_login_at' => now()->subMinutes(45),
                'addresses' => [
                    [
                        'label' => 'Rumah',
                        'recipient_name' => 'Rania Zulaikha',
                        'phone' => '6281234567809',
                        'province' => 'Banten',
                        'city' => 'Tangerang Selatan',
                        'district' => 'Serpong',
                        'postal_code' => '15310',
                        'detail' => 'Cluster Florence BSD City Blok C No. 9',
                        'is_default' => true,
                    ],
                ],
                'orders_count' => 1,
                'status_mix' => [OrderStatus::PendingPayment],
                'review' => null,
            ],
            [
                'name' => 'Maya Anggraini (Akun Nonaktif)',
                'email' => 'maya.anggraini@example.com',
                'phone' => '6281234567810',
                'is_active' => false, // Untuk pengujian toggle akun nonaktif di Admin Panel
                'last_login_at' => now()->subMonths(2),
                'addresses' => [
                    [
                        'label' => 'Rumah',
                        'recipient_name' => 'Maya Anggraini',
                        'phone' => '6281234567810',
                        'province' => 'Jawa Barat',
                        'city' => 'Bekasi',
                        'district' => 'Bekasi Barat',
                        'postal_code' => '17145',
                        'detail' => 'Perumahan Harapan Baru Blok G No. 14',
                        'is_default' => true,
                    ],
                ],
                'orders_count' => 0,
                'status_mix' => [],
                'review' => null,
            ],
        ];

        $variants = ProductVariant::with('product')->get();
        $products = Product::where('status', ProductStatus::Active)->get();

        foreach ($customersData as $cIndex => $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'is_active' => $data['is_active'],
                    'last_login_at' => $data['last_login_at'],
                ]
            );

            $user->assignRole('customer');

            // Tambahkan buku alamat
            foreach ($data['addresses'] as $addr) {
                Address::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'label' => $addr['label'],
                    ],
                    [
                        'recipient_name' => $addr['recipient_name'],
                        'phone' => $addr['phone'],
                        'province' => $addr['province'],
                        'city' => $addr['city'],
                        'district' => $addr['district'],
                        'postal_code' => $addr['postal_code'],
                        'detail' => $addr['detail'],
                        'is_default' => $addr['is_default'],
                    ]
                );
            }

            $primaryAddress = $data['addresses'][0];

            // Tambahkan pesanan jika ada
            if ($variants->isNotEmpty() && ! empty($data['status_mix'])) {
                foreach ($data['status_mix'] as $oIdx => $status) {
                    $selectedVariant = $variants->random();
                    $qty = fake()->numberBetween(1, 2);
                    $subtotal = $selectedVariant->price * $qty;
                    $shippingCost = 15000;
                    $grandTotal = $subtotal + $shippingCost;

                    $isPaid = in_array($status, [
                        OrderStatus::Paid,
                        OrderStatus::Processing,
                        OrderStatus::Shipped,
                        OrderStatus::Completed,
                    ]);

                    $orderNumber = 'ORD-'.date('Ymd').'-C'.($cIndex + 1).($oIdx + 1);

                    $order = Order::firstOrCreate(
                        ['number' => $orderNumber],
                        [
                            'user_id' => $user->id,
                            'customer_name' => $user->name,
                            'customer_email' => $user->email,
                            'customer_phone' => $user->phone,
                            'shipping_address' => [
                                'recipient_name' => $primaryAddress['recipient_name'],
                                'phone' => $primaryAddress['phone'],
                                'province' => $primaryAddress['province'],
                                'city' => $primaryAddress['city'],
                                'district' => $primaryAddress['district'],
                                'postal_code' => $primaryAddress['postal_code'],
                                'detail' => $primaryAddress['detail'],
                            ],
                            'subtotal' => $subtotal,
                            'discount_total' => 0,
                            'shipping_cost' => $shippingCost,
                            'grand_total' => $grandTotal,
                            'status' => $status,
                            'payment_status' => $isPaid ? PaymentStatus::Paid : PaymentStatus::Pending,
                            'courier' => 'JNE',
                            'courier_service' => 'REG',
                            'tracking_number' => in_array($status, [OrderStatus::Shipped, OrderStatus::Completed]) ? ('JNE'.fake()->numerify('##########')) : null,
                            'total_weight_gram' => $selectedVariant->weight_gram * $qty,
                            'expires_at' => $status === OrderStatus::PendingPayment ? now()->addHours(24) : null,
                            'paid_at' => $isPaid ? now()->subDays(fake()->numberBetween(1, 5)) : null,
                            'shipped_at' => in_array($status, [OrderStatus::Shipped, OrderStatus::Completed]) ? now()->subDays(2) : null,
                            'completed_at' => $status === OrderStatus::Completed ? now()->subDay() : null,
                            'created_at' => now()->subDays(fake()->numberBetween(1, 10)),
                        ]
                    );

                    $orderItem = OrderItem::firstOrCreate(
                        ['order_id' => $order->id, 'product_variant_id' => $selectedVariant->id],
                        [
                            'product_name' => $selectedVariant->product->name,
                            'variant_label' => $selectedVariant->color.' / '.($selectedVariant->size ?? 'Standard'),
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
                            'provider_ref' => 'MID-CUST-'.strtoupper(Str::random(10)),
                            'method' => 'qris',
                            'amount' => $grandTotal,
                            'status' => $isPaid ? PaymentStatus::Paid : PaymentStatus::Pending,
                            'paid_at' => $isPaid ? $order->paid_at : null,
                            'raw_payload' => ['transaction_status' => $isPaid ? 'settlement' : 'pending'],
                        ]
                    );

                    OrderStatusHistory::firstOrCreate(
                        [
                            'order_id' => $order->id,
                            'to_status' => $status->value,
                        ],
                        [
                            'from_status' => OrderStatus::PendingPayment->value,
                            'changed_by' => null,
                            'note' => 'Pesanan diperbarui ke status '.$status->label(),
                            'created_at' => $order->created_at,
                        ]
                    );

                    // Buat ulasan produk jika ada data review dan pesanan completed
                    if ($status === OrderStatus::Completed && ! empty($data['review'])) {
                        Review::firstOrCreate(
                            [
                                'user_id' => $user->id,
                                'product_id' => $selectedVariant->product_id,
                            ],
                            [
                                'order_item_id' => $orderItem->id,
                                'rating' => $data['review']['rating'],
                                'title' => $data['review']['title'],
                                'comment' => $data['review']['comment'],
                                'is_approved' => true,
                                'created_at' => now()->subDays(fake()->numberBetween(1, 3)),
                            ]
                        );
                    }
                }
            }

            // Tambahkan 1-2 wishlist produk
            if ($products->isNotEmpty()) {
                $favProduct = $products->random();
                Wishlist::firstOrCreate([
                    'user_id' => $user->id,
                    'product_id' => $favProduct->id,
                ]);
            }
        }
    }
}
