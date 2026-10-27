<?php

namespace Database\Seeders;

use App\Enums\ProductStatus;
use App\Enums\VoucherType;
use App\Models\Address;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Customer Demo
        $customer = User::firstOrCreate(
            ['email' => 'customer@bloomingkembang.com'],
            [
                'name' => 'Fatimah Az-Zahra',
                'phone' => '6285712345678',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $customer->assignRole('customer');

        // Customer default address
        Address::firstOrCreate(
            ['user_id' => $customer->id, 'is_default' => true],
            [
                'label' => 'Rumah',
                'recipient_name' => 'Fatimah Az-Zahra',
                'phone' => '6285712345678',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Tebet',
                'postal_code' => '12810',
                'detail' => 'Jl. Tebet Barat Dalam VII No. 15',
                'is_default' => true,
            ]
        );

        // 2. Kategori Contoh
        $categoriesData = [
            [
                'name' => 'Hijab Segi Empat',
                'slug' => 'hijab-segi-empat',
                'description' => 'Hijab segi empat signature dengan bahan voal premium, tegak di dahi dan nyaman seharian.',
                'position' => 1,
            ],
            [
                'name' => 'Pashmina',
                'slug' => 'pashmina',
                'description' => 'Pashmina flowy dengan tekstur jatuh elegan, ideal untuk tampilan casual maupun formal.',
                'position' => 2,
            ],
            [
                'name' => 'Inner & Aksesoris',
                'slug' => 'inner-dan-aksesoris',
                'description' => 'Ciput rajut anti-pusing dan peniti klip premium pelengkap gaya hijab Anda.',
                'position' => 3,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::firstOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['is_active' => true])
            );
        }

        // 3. 6 Produk CONTOH
        $productsData = [
            [
                'category_slug' => 'hijab-segi-empat',
                'name' => 'CONTOH Voal Ultrafine Signature',
                'slug' => 'contoh-voal-ultrafine-signature',
                'short_description' => 'Voal ultrafine premium dengan serat halus, tegak sempurna tanpa disemprot.',
                'material' => 'Voal Ultrafine Turkish Grade A',
                'size_guide' => '115 cm x 115 cm (toleransi jahit tepi 1-2 cm)',
                'care_guide' => 'Cuci lembut dengan tangan, jangan peras terlalu kencang, setrika suhu rendah.',
                'is_featured' => true,
                'variants' => [
                    ['color' => 'Dusty Rose', 'color_hex' => '#c89d9c', 'size' => '115x115', 'price' => 89000, 'compare_price' => 110000, 'stock' => 25, 'weight' => 110],
                    ['color' => 'Muted Sage', 'color_hex' => '#a9aea9', 'size' => '115x115', 'price' => 89000, 'compare_price' => 110000, 'stock' => 30, 'weight' => 110],
                    ['color' => 'Obsidian Black', 'color_hex' => '#1c1717', 'size' => '115x115', 'price' => 89000, 'compare_price' => 110000, 'stock' => 40, 'weight' => 110],
                ],
            ],
            [
                'category_slug' => 'hijab-segi-empat',
                'name' => 'CONTOH Paris Premium Laser Cut',
                'slug' => 'contoh-paris-premium-laser-cut',
                'short_description' => 'Hijab paris klasik yang tidak menerawang dengan finishing laser cut presisi.',
                'material' => 'Paris Silk Voile',
                'size_guide' => '110 cm x 110 cm',
                'care_guide' => 'Hindari mesin cuci, cuci gunakan deterjen lembut.',
                'is_featured' => true,
                'variants' => [
                    ['color' => 'Warm Sand', 'color_hex' => '#d7c4b7', 'size' => '110x110', 'price' => 75000, 'compare_price' => 95000, 'stock' => 18, 'weight' => 95],
                    ['color' => 'Bone White', 'color_hex' => '#f0f0f0', 'size' => '110x110', 'price' => 75000, 'compare_price' => 95000, 'stock' => 22, 'weight' => 95],
                ],
            ],
            [
                'category_slug' => 'pashmina',
                'name' => 'CONTOH Pashmina Ceruty Babydoll',
                'slug' => 'contoh-pashmina-ceruty-babydoll',
                'short_description' => 'Pashmina tekstur berpasir lembut, jatuh melambai anggun dan mudah di-styling.',
                'material' => 'Ceruty Babydoll Import',
                'size_guide' => '180 cm x 75 cm',
                'care_guide' => 'Gantung setelah dicuci, setrika dengan pelapis kain.',
                'is_featured' => true,
                'variants' => [
                    ['color' => 'Almond Milk', 'color_hex' => '#e6ded3', 'size' => '180x75', 'price' => 69000, 'compare_price' => 85000, 'stock' => 50, 'weight' => 140],
                    ['color' => 'Mocha', 'color_hex' => '#8d7060', 'size' => '180x75', 'price' => 69000, 'compare_price' => 85000, 'stock' => 35, 'weight' => 140],
                    ['color' => 'Olive Mist', 'color_hex' => '#7f8a79', 'size' => '180x75', 'price' => 69000, 'compare_price' => 85000, 'stock' => 20, 'weight' => 140],
                ],
            ],
            [
                'category_slug' => 'pashmina',
                'name' => 'CONTOH Pashmina Silk Crinkle',
                'slug' => 'contoh-pashmina-silk-crinkle',
                'short_description' => 'Pashmina bertekstur crinkle alami berpadu kilau silk sutra mewah tanpa licin.',
                'material' => 'Silk Chiffon Crinkle',
                'size_guide' => '185 cm x 75 cm',
                'care_guide' => 'Jangan disetrika agar tekstur crinkle tetap terjaga.',
                'is_featured' => false,
                'variants' => [
                    ['color' => 'Pearl Grey', 'color_hex' => '#cfd3d4', 'size' => '185x75', 'price' => 119000, 'compare_price' => 149000, 'stock' => 15, 'weight' => 130],
                    ['color' => 'Terracotta', 'color_hex' => '#b35d46', 'size' => '185x75', 'price' => 119000, 'compare_price' => 149000, 'stock' => 12, 'weight' => 130],
                ],
            ],
            [
                'category_slug' => 'inner-dan-aksesoris',
                'name' => 'CONTOH Ciput Ninja Anti Pusing',
                'slug' => 'contoh-ciput-ninja-anti-pusing',
                'short_description' => 'Inner ninja menutup leher dengan bahan kaos rayon spandex super adem berpori.',
                'material' => 'Rayon Spandex Premium',
                'size_guide' => 'All Size fit to XL',
                'care_guide' => 'Dapat dicuci tangan atau jaring mesin cuci.',
                'is_featured' => false,
                'variants' => [
                    ['color' => 'Hitam Pekat', 'color_hex' => '#1c1717', 'size' => 'All Size', 'price' => 35000, 'compare_price' => 45000, 'stock' => 60, 'weight' => 70],
                    ['color' => 'Nude Beige', 'color_hex' => '#dcc5b4', 'size' => 'All Size', 'price' => 35000, 'compare_price' => 45000, 'stock' => 45, 'weight' => 70],
                ],
            ],
            [
                'category_slug' => 'inner-dan-aksesoris',
                'name' => 'CONTOH Klip Hijab Turki Eksklusif',
                'slug' => 'contoh-klip-hijab-turki-eksklusif',
                'short_description' => 'Pengganti jarum pentul tanpa merusak serat kain voal maupun sutra favorit Anda.',
                'material' => 'Alloy Logam Stainless Steel',
                'size_guide' => 'Set isi 3 pcs',
                'care_guide' => 'Simpan di tempat kering.',
                'is_featured' => false,
                'variants' => [
                    ['color' => 'Silver Glossy', 'color_hex' => '#d1d5db', 'size' => '3 Pcs', 'price' => 25000, 'compare_price' => 35000, 'stock' => 80, 'weight' => 30],
                    ['color' => 'Rose Gold', 'color_hex' => '#e8a598', 'size' => '3 Pcs', 'price' => 25000, 'compare_price' => 35000, 'stock' => 75, 'weight' => 30],
                ],
            ],
        ];

        foreach ($productsData as $p) {
            $product = Product::firstOrCreate(
                ['slug' => $p['slug']],
                [
                    'category_id' => $categories[$p['category_slug']]->id,
                    'name' => $p['name'],
                    'short_description' => $p['short_description'],
                    'description' => $p['short_description']."\n\nKoleksi hijab BloomingKembang dibuat dengan jahitan tepi rapi, tekstur lembut yang adem dipakai seharian di cuaca tropis Indonesia.",
                    'material' => $p['material'],
                    'size_guide' => $p['size_guide'],
                    'care_guide' => $p['care_guide'],
                    'status' => ProductStatus::Active,
                    'is_featured' => $p['is_featured'],
                    'sold_count' => rand(10, 150),
                    'seo_title' => $p['name'].' — BloomingKembang Official Store',
                    'seo_description' => $p['short_description'],
                ]
            );

            if (! $product->hasMedia('images')) {
                $imageFile = match ($p['category_slug']) {
                    'hijab-segi-empat' => str_contains($p['slug'], 'paris') ? 'paris-laser-cut.jpg' : 'voal-signature.jpg',
                    'pashmina' => str_contains($p['slug'], 'silk') ? 'pashmina-silk.jpg' : 'pashmina-ceruty.jpg',
                    'inner-dan-aksesoris' => str_contains($p['slug'], 'ciput') ? 'ciput-ninja.jpg' : 'klip-turki.jpg',
                    default => 'voal-signature.jpg',
                };
                $imagePath = database_path('seeders/images/'.$imageFile);
                if (file_exists($imagePath)) {
                    $product->addMedia($imagePath)->preservingOriginal()->toMediaCollection('images');
                }
            }

            foreach ($p['variants'] as $idx => $v) {
                $sku = strtoupper(Str::slug($product->slug).'-'.substr($v['color'], 0, 3).'-'.$idx);
                $variant = ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'color' => $v['color'],
                        'size' => $v['size'],
                    ],
                    [
                        'sku' => $sku,
                        'color_hex' => $v['color_hex'],
                        'price' => $v['price'],
                        'compare_price' => $v['compare_price'],
                        'stock' => $v['stock'],
                        'reserved' => 0,
                        'weight_gram' => $v['weight'],
                        'is_active' => true,
                        'position' => $idx + 1,
                    ]
                );

                StockMovement::firstOrCreate(
                    [
                        'product_variant_id' => $variant->id,
                        'type' => 'adjustment',
                        'order_id' => null,
                    ],
                    [
                        'quantity' => $v['stock'],
                        'stock_after' => $v['stock'],
                        'note' => 'Stok awal seed data',
                    ]
                );
            }
        }

        // 4. Voucher Contoh
        Voucher::firstOrCreate(
            ['code' => 'BLOOMINGFIRST'],
            [
                'description' => 'Diskon 10% khusus pengguna baru tanpa minimum belanja',
                'type' => VoucherType::Percent,
                'value' => 10,
                'min_purchase' => 0,
                'max_discount' => 20000,
                'quota' => 500,
                'used_count' => 0,
                'per_user_limit' => 1,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(3),
                'is_active' => true,
            ]
        );

        Voucher::firstOrCreate(
            ['code' => 'KEMBANG25'],
            [
                'description' => 'Potongan langsung Rp 25.000 dengan minimum belanja Rp 150.000',
                'type' => VoucherType::Fixed,
                'value' => 25000,
                'min_purchase' => 150000,
                'max_discount' => null,
                'quota' => 200,
                'used_count' => 0,
                'per_user_limit' => 1,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(2),
                'is_active' => true,
            ]
        );

        // 5. Halaman Statis
        $staticPages = [
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'seo_title' => 'Tentang BloomingKembang — Kisah & Filosofi Brand',
                'seo_description' => 'Mengenal BloomingKembang: brand hijab kurasi premium yang berdedikasi menghadirkan kain lembut, bersahaja, dan berkarakter.',
                'body' => '<p><strong>BloomingKembang</strong> lahir dari sebuah kecintaan mendalam terhadap keanggunan wanita muslimah yang bersahaja. Kami percaya bahwa hijab bukan sekadar penutup, melainkan cerminan kelembutan hati, kesopanan budi, dan kenyamanan dalam melangkah.</p><p>Setiap helai kain di BloomingKembang dipilih melalui proses kurasi yang ketat. Mulai dari serat voal ultrafine yang ringan dan tegak di dahi, hingga ceruty babydoll yang jatuh anggun tanpa terasa panas.</p><p>Diproduksi oleh tangan-tangan penjahit lokal berpengalaman di Bandung, Jawa Barat, dengan jahitan tepi rapi khas butik.</p>',
            ],
            [
                'title' => 'Pertanyaan Umum (FAQ)',
                'slug' => 'faq',
                'seo_title' => 'FAQ — Pertanyaan Umum Seputar Pemesanan & Pengiriman',
                'seo_description' => 'Jawaban atas pertanyaan seputar pemesanan, metode pembayaran Midtrans, estimasi pengiriman kurir, dan garansi produk.',
                'body' => '<h3>Bagaimana cara berbelanja di BloomingKembang?</h3><p>Pilih produk dan warna yang Anda sukai, tambahkan ke keranjang belanja, lalu klik checkout. Anda dapat memilih alamat tersimpan atau mengisi alamat baru, serta memilih kurir pengiriman yang diinginkan.</p><h3>Metode pembayaran apa saja yang didukung?</h3><p>Kami menerima pembayaran instan melalui QRIS, Transfer Virtual Account (BCA, BNI, BRI, Mandiri, Permata), serta e-Wallet (GoPay, ShopeePay) yang terhubung aman via Midtrans.</p><h3>Berapa lama estimasi pengiriman?</h3><p>Pesanan yang diverifikasi sebelum pukul 15.00 WIB akan dikirim pada hari yang sama. Estimasi pengiriman reguler adalah 1–3 hari kerja untuk Pulau Jawa dan 2–5 hari kerja untuk luar Pulau Jawa.</p>',
            ],
            [
                'title' => 'Kebijakan Retur & Penukaran',
                'slug' => 'kebijakan-retur',
                'seo_title' => 'Kebijakan Retur — Garansi 7 Hari Pengembalian Barang',
                'seo_description' => 'Informasi syarat dan ketentuan pengembalian barang atau penukaran warna apabila terjadi cacat produksi atau ketidaksesuaian.',
                'body' => '<p>Kepuasan Anda adalah prioritas utama kami. Kami menyediakan garansi penukaran produk hingga <strong>7 hari kalender</strong> sejak pesanan diterima oleh pelanggan.</p><h3>Syarat Pengembalian:</h3><ul><li>Produk dalam kondisi baru, belum dicuci, dan belum dipakai beraktivitas.</li><li>Tag label dan kemasan asli masih utuh.</li><li>Menyertakan video unboxing saat paket pertama kali dibuka.</li></ul><p>Biaya kirim penukaran karena cacat produksi atau kesalahan kirim ditanggung 100% oleh BloomingKembang.</p>',
            ],
            [
                'title' => 'Syarat & Ketentuan',
                'slug' => 'syarat-dan-ketentuan',
                'seo_title' => 'Syarat & Ketentuan Layanan — BloomingKembang',
                'seo_description' => 'Syarat dan ketentuan hukum penggunaan situs, transaksi e-commerce, hak kekayaan intelektual, dan batasan tanggung jawab.',
                'body' => '<p>Selamat datang di situs resmi BloomingKembang. Dengan mengakses dan berbelanja di platform ini, Anda menyetujui seluruh ketentuan dan pedoman transaksi yang berlaku di bawah hukum Republik Indonesia.</p><p>Seluruh materi foto, teks, logo, dan identitas visual dilindungi oleh undang-undang hak cipta dan merek dagang.</p>',
            ],
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'seo_title' => 'Kebijakan Privasi Perlindungan Data Pengguna',
                'seo_description' => 'Kebijakan perlindungan data pribadi pelanggan, kerahasiaan nomor kontak, dan keamanan transaksi di BloomingKembang.',
                'body' => '<p>Kami sangat menghargai privasi Anda. Data pribadi seperti nama, email, nomor telepon, dan alamat rumah hanya digunakan untuk memproses pengiriman pesanan dan memberikan informasi pembaruan penting.</p><p>Kami tidak akan pernah membagikan atau menjual data pelanggan kepada pihak ketiga manapun.</p>',
            ],
            [
                'title' => 'Hubungi Kami',
                'slug' => 'kontak',
                'seo_title' => 'Kontak Layanan Pelanggan — BloomingKembang',
                'seo_description' => 'Hubungi tim customer service BloomingKembang via WhatsApp, email resmi, atau kunjungi kantor kami di Bandung.',
                'body' => '<p>Tim Customer Care kami siap membantu Anda setiap hari Senin – Sabtu, pukul 08.30 – 17.30 WIB.</p><p><strong>WhatsApp:</strong> 0812-3456-7890<br><strong>Email:</strong> cs@bloomingkembang.com<br><strong>Alamat Kantor & Gudang:</strong> Jl. Citarum No. 28, Kota Bandung, Jawa Barat 40115</p>',
            ],
        ];

        foreach ($staticPages as $pageData) {
            Page::firstOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        // 6. Banner Promo
        Banner::updateOrCreate(
            ['title' => 'Koleksi Syar\'i & Elegan 2026'],
            [
                'subtitle' => 'Ditenun dari serat voal ultrafine dan ceruty babydoll pilihan.',
                'image' => 'images/home/hero-editorial.jpg',
                'link_url' => '/koleksi',
                'position' => 'hero',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Banner::firstOrCreate(
            ['title' => 'GRATIS ONGKIR SELURUH INDONESIA DENGAN KODE VOUCHER "BLOOMINGFIRST"'],
            [
                'subtitle' => 'Potongan ongkir khusus pemesanan hari ini',
                'link_url' => '/koleksi',
                'position' => 'top_bar',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 7. Ulasan Produk Contoh
        $firstProduct = Product::first();
        if ($firstProduct && $customer) {
            Review::firstOrCreate(
                [
                    'user_id' => $customer->id,
                    'product_id' => $firstProduct->id,
                ],
                [
                    'rating' => 5,
                    'title' => 'Bahan voal ultrafine ternyaman!',
                    'comment' => 'Kainnya sangat halus, tegak sempurna di dahi dan tidak mudah kusut meski dipakai seharian untuk silaturahmi. Jahitan tepi rapi khas butik.',
                    'is_approved' => true,
                ]
            );
        }
    }
}
