# Implementation Plan — Toko Online Kerudung (Single Brand) dengan Full Laravel

> Revisi: seluruh sistem dibangun sebagai **satu aplikasi Laravel monolith** (backend, storefront, panel admin, dan area customer dalam satu codebase). Ganti `[BloomingKembang]` dengan nama brand kamu.

---

## 1. Tujuan & Ruang Lingkup

**Tujuan bisnis**
- Toko online sendiri untuk brand `[BloomingKembang]`, bukan marketplace.
- Data pelanggan dimiliki sendiri (akun, riwayat belanja, alamat) untuk repeat order.
- Admin bisa kelola produk, stok, pesanan, dan promosi sendiri tanpa developer.

**Dua peran utama**

| Peran | Akses |
|---|---|
| **Admin** (Super Admin, Staf) | Panel `/admin`: produk, stok, pesanan, voucher, banner, laporan, pengaturan, kelola customer |
| **Customer** | Storefront publik + area akun `/akun`: profil, buku alamat, riwayat & lacak pesanan, wishlist, ulasan |

**MVP**
- Katalog produk dengan varian warna & ukuran
- Registrasi/login customer, keranjang, checkout, pembayaran online, ongkir otomatis
- Panel admin lengkap untuk produk dan pesanan
- Notifikasi pesanan (email + WhatsApp)
- SEO dasar dan pelacakan iklan

**Fase 2**
- Reseller/dropship, loyalty points, pre-order/waitlist, bundling, laporan lanjutan, API untuk aplikasi mobile

---

## 2. Asumsi

| Item | Asumsi |
|---|---|
| Pasar | Indonesia (IDR, Bahasa Indonesia) |
| Produk awal | 20–100 SKU: hijab segi empat, pashmina, instan, bergo, inner, aksesoris |
| Tim | 1 developer (atau AI coding agent) + 1 owner |
| Checkout | Customer login **atau** guest (bisa dimatikan lewat pengaturan) |
| Hosting | VPS / cloud (Nginx + PHP-FPM); shared hosting cPanel masih mungkin untuk skala kecil |
| Trafik awal | < 1.000 pengunjung/hari |

---

## 3. Tech Stack (Full Laravel)

| Lapisan | Teknologi |
|---|---|
| Framework | Laravel (versi stabil terbaru), PHP 8.3+ |
| Frontend storefront | Blade + **Livewire** + Alpine.js + Tailwind CSS (Vite) |
| Panel admin | **Filament** (panel `admin`, resource CRUD, widget dashboard, relation manager) |
| Auth customer | Laravel **Breeze** (Livewire stack) atau Fortify: register, login, verifikasi email, reset password; opsional login Google via Socialite |
| Role & izin | `spatie/laravel-permission` (role: `super_admin`, `staff`, `customer`) + Laravel Policies |
| Database | MySQL/MariaDB atau PostgreSQL |
| Media/gambar | `spatie/laravel-medialibrary` (konversi WebP, thumbnail) |
| Pembayaran | Midtrans (`midtrans/midtrans-php`) atau Xendit SDK: QRIS, VA bank, e-wallet |
| Ongkir | Biteship atau RajaOngkir via Laravel HTTP Client |
| Antrian & jadwal | Laravel Queue (database/Redis) + Scheduler + Supervisor |
| Cache | Redis (atau file/database di tahap awal) |
| Email | Laravel Mail + Resend/Brevo/SMTP |
| WhatsApp | Fonnte / WhatsApp Cloud API lewat Notification channel kustom |
| PDF invoice/label | `barryvdh/laravel-dompdf` |
| SEO | `spatie/laravel-sitemap` + JSON-LD manual |
| Testing | Pest (feature + unit test) |
| Kualitas kode | Laravel Pint, Larastan |
| Monitoring | Sentry, Laravel Telescope (hanya di lokal/staging) |
| Deploy | VPS + Nginx + Supervisor + Let's Encrypt (opsional: Laravel Forge / aaPanel) |

---

## 4. Pembagian Area Aplikasi

```
Publik (guest)        → beranda, koleksi, produk, keranjang, checkout, lacak pesanan, halaman statis
Customer (auth)       → /akun: dashboard, profil, alamat, pesanan, wishlist, ulasan
Admin (auth + role)   → /admin (Filament): seluruh manajemen toko
Webhook / API         → /webhooks/payment, /webhooks/shipping (tanpa CSRF, dengan verifikasi signature)
```

**Matriks hak akses**

| Fitur | Guest | Customer | Staf | Super Admin |
|---|:-:|:-:|:-:|:-:|
| Lihat katalog & checkout guest | ✅ | ✅ | – | – |
| Akun, alamat, riwayat pesanan sendiri | – | ✅ | – | – |
| Wishlist & ulasan | – | ✅ | – | – |
| Kelola produk & stok | – | – | ✅ | ✅ |
| Kelola pesanan & input resi | – | – | ✅ | ✅ |
| Kelola voucher, banner, konten | – | – | ✅ | ✅ |
| Laporan & ekspor | – | – | terbatas | ✅ |
| Kelola customer & staf | – | – | – | ✅ |
| Pengaturan toko & integrasi | – | – | – | ✅ |

---

## 5. Daftar Fitur

### 5.1 Storefront & Customer

**MVP**
- [ ] Beranda: hero, koleksi unggulan, terlaris, testimoni, newsletter
- [ ] Halaman koleksi: filter (warna, bahan, harga, ketersediaan), sortir, pagination
- [ ] Halaman produk: galeri, swatch warna, ukuran, stok per varian, deskripsi bahan, panduan ukuran, perawatan, produk terkait
- [ ] Keranjang (Livewire, tersimpan di session untuk guest dan di database untuk customer login; digabung saat login)
- [ ] Checkout: pilih alamat tersimpan atau isi baru, pilih kurir (ongkir real-time), voucher, pembayaran online
- [ ] Registrasi, login, verifikasi email, lupa password
- [ ] Area akun: profil, ubah password, **buku alamat**, **riwayat pesanan**, detail & lacak pesanan, bayar ulang, batalkan pesanan (jika belum dibayar)
- [ ] Wishlist
- [ ] Halaman statis: Tentang, FAQ, Retur, S&K, Privasi, Kontak
- [ ] Tombol chat WhatsApp, responsif mobile-first

**Fase 2**
- [ ] Ulasan & rating dengan foto (hanya pembeli terverifikasi)
- [ ] Pengajuan retur dari akun customer
- [ ] Waitlist "beritahu saya" saat stok habis, pre-order
- [ ] Poin loyalitas, kode referral, level member
- [ ] Blog / tutorial styling hijab

### 5.2 Panel Admin (Filament)

**MVP**
- [ ] Login admin terpisah (guard/panel `admin`), opsi 2FA
- [ ] Resource **Produk** dengan varian (relation manager: warna, hex, ukuran, SKU, harga, harga coret, stok, berat), multi-gambar, SEO
- [ ] Resource **Koleksi/Kategori**
- [ ] Resource **Pesanan**: filter status, detail, ubah status, input resi, cetak invoice & label, catatan internal
- [ ] Resource **Voucher**
- [ ] Resource **Customer**: lihat profil, alamat, riwayat pesanan (hanya super admin yang bisa ubah/nonaktifkan)
- [ ] Resource **Banner & halaman statis**
- [ ] **Dashboard**: omzet, jumlah order, produk terlaris, stok menipis, order menunggu diproses
- [ ] Halaman **Pengaturan toko**: kontak, alamat gudang, kurir aktif, aktif/nonaktif guest checkout
- [ ] Manajemen staf & role

**Fase 2**
- [ ] Laporan penjualan + ekspor Excel/CSV
- [ ] Manajemen retur/refund
- [ ] Log aktivitas (`spatie/laravel-activitylog`)
- [ ] Pickup kurir otomatis

---

## 6. Struktur Route (Ringkas)

```
GET   /                              home
GET   /koleksi                       katalog
GET   /koleksi/{category:slug}       per koleksi
GET   /produk/{product:slug}         detail produk
GET   /keranjang                     keranjang
GET   /checkout                      checkout
GET   /pesanan/{order:number}        status & lacak (signed URL / verifikasi kontak untuk guest)
GET   /tentang | /faq | /kebijakan-retur | /kontak

# Auth (Breeze)
GET   /masuk | /daftar | /lupa-password

# Customer (middleware: auth, verified, role:customer)
GET   /akun                          dashboard akun
GET   /akun/profil
GET   /akun/alamat
GET   /akun/pesanan
GET   /akun/pesanan/{order}
GET   /akun/wishlist

# Admin (Filament, middleware: auth, role:super_admin|staff)
/admin  → dashboard, produk, pesanan, voucher, customer, pengaturan

# Webhook
POST  /webhooks/payment              verifikasi signature
POST  /webhooks/shipping
```

---

## 7. Model Data & Migrasi

| Tabel / Model | Kolom utama |
|---|---|
| `users` | id, name, email, phone, password, email_verified_at, is_active, (role via spatie) |
| `addresses` | id, user_id, label, recipient, phone, province, city, district, postal_code, detail, is_default |
| `categories` | id, name, slug, image, position, is_active |
| `products` | id, category_id, name, slug, description, material, care_guide, status, seo_title, seo_description |
| `product_variants` | id, product_id, sku, color, color_hex, size, price, compare_price, stock, weight_gram |
| `carts`, `cart_items` | user_id/session_id, variant_id, qty |
| `wishlists` | user_id, product_id |
| `orders` | id, number, user_id (nullable untuk guest), customer_name/email/phone, address_snapshot (json), subtotal, shipping_cost, discount, total, status, payment_status, courier, service, tracking_number, notes, expires_at |
| `order_items` | id, order_id, variant_id, name_snapshot, price_snapshot, qty |
| `payments` | id, order_id, provider, provider_ref, method, status, paid_at, raw_payload (json) |
| `vouchers` | id, code, type, value, min_purchase, max_discount, quota, used_count, starts_at, ends_at |
| `reviews` *(Fase 2)* | id, user_id, product_id, order_item_id, rating, body, is_approved |
| `settings` | key, value (json) |
| `media` | tabel spatie medialibrary |
| spatie permission | roles, permissions, model_has_roles, dst. |

**Enum (PHP backed enum):** `OrderStatus` (pending_payment, paid, processing, shipped, completed, cancelled, expired), `PaymentStatus`, `VoucherType`.

**Aturan penting**
- Snapshot nama, harga, dan alamat di order agar riwayat tidak berubah.
- Total selalu dihitung di server (service class `PricingService`), bukan dari input klien.
- Stok: reserve saat order dibuat, finalisasi saat dibayar, lepas saat kedaluwarsa. Gunakan `DB::transaction` + `lockForUpdate()` untuk mencegah oversell.
- Webhook idempotent berdasarkan `provider_ref` + status.

---

## 8. Arsitektur Kode

```
app/
  Enums/                 OrderStatus, PaymentStatus, VoucherType
  Models/                User, Product, ProductVariant, Order, ...
  Policies/              OrderPolicy, AddressPolicy, ...
  Services/              CartService, PricingService, CheckoutService,
                         StockService, VoucherService
  Integrations/
    Payment/             PaymentGateway (interface), MidtransGateway
    Shipping/            ShippingProvider (interface), BiteshipProvider
    WhatsApp/            WhatsAppChannel
  Actions/               CreateOrderAction, MarkOrderPaidAction, ...
  Jobs/                  ExpireUnpaidOrders, SendOrderNotifications
  Notifications/         OrderCreated, OrderPaid, OrderShipped
  Events/ Listeners/     OrderPaid → kurangi stok, kirim notifikasi
  Livewire/
    Shop/                ProductFilter, VariantPicker, CartDrawer, Checkout
    Account/             AddressBook, OrderList, Wishlist, Profile
  Filament/Admin/
    Resources/           ProductResource, OrderResource, VoucherResource, CustomerResource
    Widgets/             SalesStats, TopProducts, LowStock
  Http/
    Controllers/Webhooks/
    Middleware/
resources/views/         layouts, components (Blade), livewire, pages, emails, pdf
routes/                  web.php, webhooks.php
database/                migrations, factories, seeders
tests/                   Feature/, Unit/ (Pest)
```

Prinsip: logika bisnis di **Services/Actions**, bukan di controller/komponen; integrasi pihak ketiga lewat **interface** agar mudah diganti.

---

## 9. Alur Utama

**Checkout & pembayaran**
1. Customer/guest isi keranjang → checkout (pilih/isi alamat, kurir, voucher).
2. `CheckoutService` hitung ulang harga & ongkir di server → buat `Order` (`pending_payment`), reserve stok, set `expires_at`.
3. Buat transaksi di Midtrans/Xendit → customer membayar.
4. Webhook masuk → verifikasi signature → `MarkOrderPaidAction` → status `paid` → event `OrderPaid`.
5. Listener: finalisasi stok, kirim email + WhatsApp ke customer & admin.
6. Admin proses → input resi → `shipped` → notifikasi resi.
7. Customer lacak di `/akun/pesanan/{order}`; setelah selesai bisa memberi ulasan (Fase 2).

**Order kedaluwarsa:** Scheduler menjalankan `ExpireUnpaidOrders` tiap beberapa menit → status `expired` → stok dilepas.

**Guest → Customer:** jika guest mendaftar dengan email yang sama, pesanan lama dihubungkan ke akun baru setelah verifikasi email.

---

## 10. Desain & Brand

- Tentukan logo, palet warna (jadikan token Tailwind/CSS variable), font, tone of voice, gaya foto
- Fokus **foto kualitas tinggi** dan akurasi warna; tampilkan detail kain & beberapa gaya pemakaian
- Mobile-first, bersih, CTA jelas, checkout singkat
- Tema Filament admin disesuaikan warna brand (logo & warna primer)

---

## 11. Fase & Timeline (Estimasi)

| Fase | Durasi | Output |
|---|---|---|
| **0. Persiapan** | Minggu 1 | Brand, domain, daftar produk, foto, akun Midtrans/Xendit & Biteship, kebijakan toko |
| **1. Fondasi** | Minggu 1–2 | Install Laravel, Breeze, Filament, spatie permission, migrasi & seeder, role admin/customer, deploy staging |
| **2. Katalog & admin produk** | Minggu 2–3 | ProductResource + varian + media, halaman koleksi & produk |
| **3. Akun customer** | Minggu 3–4 | Register/login/verifikasi, profil, buku alamat, wishlist, keranjang tersinkron |
| **4. Checkout & ongkir** | Minggu 4–5 | Checkout, ongkir real-time, voucher, pembuatan order & reserve stok |
| **5. Pembayaran & pesanan** | Minggu 5–6 | Gateway, webhook, OrderResource, invoice/label PDF, notifikasi email/WA, riwayat & lacak pesanan |
| **6. Konten & SEO** | Minggu 6–7 | Halaman statis, sitemap, JSON-LD, Pixel/GA4, dashboard admin |
| **7. QA & uji coba** | Minggu 7–8 | Pest tests, uji keamanan, transaksi nyata nominal kecil |
| **8. Launch** | Minggu 8 | Produksi, SSL, queue worker + scheduler aktif, monitoring, soft launch |
| **9. Pasca-launch** | Minggu 9+ | Iterasi dan fitur Fase 2 |

---

## 12. Keamanan

- HTTPS, `APP_DEBUG=false` di produksi, `APP_KEY` dan semua secret di `.env`
- Password hashing bawaan Laravel (bcrypt/argon2), throttle login, opsi 2FA admin
- Authorization via **Policies**: customer hanya bisa akses order/alamat miliknya (cegah IDOR)
- Mass assignment dilindungi (`$fillable`), validasi lewat Form Request
- Webhook: kecualikan dari CSRF, **verifikasi signature**, idempotent, log payload
- Rate limiting pada login, register, checkout, dan cek voucher
- Security headers (CSP, HSTS), upload file dibatasi tipe/ukuran, simpan di disk terpisah
- Jangan simpan data kartu; serahkan ke payment gateway
- Backup database harian terjadwal (`spatie/laravel-backup`), uji restore
- Halaman Privasi & S&K sesuai UU PDP; fitur hapus/nonaktifkan akun

---

## 13. SEO & Marketing

- URL bersih (slug), meta title/description, Open Graph, sitemap otomatis, `robots.txt`
- JSON-LD `Product`, `Organization`, `BreadcrumbList`
- Gambar WebP, lazy loading, `alt` deskriptif, target Core Web Vitals hijau
- GA4 + Meta Pixel (view_item, add_to_cart, begin_checkout, purchase), Conversions API (Fase 2)
- Email/WhatsApp cart abandonment dan promo repeat order via scheduled job

---

## 14. Testing & QA

**Otomatis (Pest)**
- [ ] Unit: `PricingService`, `VoucherService`, `StockService`
- [ ] Feature: register/login, akses `/akun` vs `/admin` per role, policy order (customer A tidak bisa lihat order customer B)
- [ ] Feature: checkout sukses, stok habis, voucher tidak valid
- [ ] Feature: webhook valid/invalid signature, webhook dikirim ulang
- [ ] Job `ExpireUnpaidOrders` melepas stok

**Manual / UAT**
- [ ] Responsif Android & iOS
- [ ] Transaksi nyata nominal kecil dengan beberapa metode bayar
- [ ] Owner mencoba seluruh alur admin sampai resi
- [ ] Lighthouse mobile ≥ 85, tidak ada error kritis di Sentry

---

## 15. Deployment

1. Server: Ubuntu + Nginx + PHP-FPM 8.3 + MySQL/PostgreSQL + Redis + Supervisor
2. `composer install --no-dev --optimize-autoloader`, `php artisan migrate --force`
3. `php artisan config:cache route:cache view:cache event:cache`, `storage:link`
4. Supervisor menjalankan `queue:work`; cron `* * * * * php artisan schedule:run`
5. SSL (Let's Encrypt), firewall, backup otomatis, monitoring uptime
6. Alur rilis: Git → staging → produksi (CI sederhana: Pint + Larastan + Pest sebelum deploy)

---

## 16. Rencana Launch

1. Staging disetujui owner
2. Isi semua produk, foto, deskripsi, kebijakan
3. Domain + SSL, env produksi, payment gateway mode live
4. Soft launch ke audiens terbatas 1–2 minggu
5. Grand launch + promo pembeli pertama
6. Pantau error, konversi, dan keluhan harian di minggu pertama

---

## 17. Biaya (Komponen)

| Komponen | Sifat |
|---|---|
| Domain | Tahunan |
| VPS/hosting + database | Bulanan |
| Payment gateway | Fee per transaksi |
| API ongkir | Gratis–berbayar sesuai paket |
| Email & WhatsApp gateway | Bulanan/per pesan |
| Foto produk & model, logo | Sekali/berkala |
| Iklan | Berkala |

> Laravel, Filament, dan Livewire gratis (open source); tidak ada biaya lisensi platform seperti Shopify.

---

## 18. Risiko & Mitigasi

| Risiko | Mitigasi |
|---|---|
| Oversell stok | `lockForUpdate()` dalam transaksi, reserve + expire order |
| Webhook gagal/ganda | Idempotent, retry, halaman cek status, log payload |
| Warna foto tidak akurat → retur | Foto cahaya alami, swatch, catatan perbedaan warna |
| Ongkir salah | Berat produk akurat, uji tiap kurir, margin pembulatan |
| IDOR data customer | Policies + test otorisasi |
| Performa saat promo | Cache query katalog, CDN gambar, queue untuk notifikasi |
| Maintenance & update dependency | Update berkala, test otomatis, staging sebelum produksi |

---

## 19. Definition of Done (MVP)

- [ ] Customer bisa daftar, login, belanja, bayar, dan melacak pesanan dari akunnya
- [ ] Guest checkout berfungsi (bisa dimatikan admin)
- [ ] Admin/staf bisa kelola produk, pesanan, voucher, dan customer sesuai role
- [ ] Ongkir sesuai kurir; order otomatis `paid` setelah webhook sukses dan stok berkurang
- [ ] Notifikasi email/WhatsApp terkirim di status penting
- [ ] Test otorisasi dan pembayaran lulus; Lighthouse mobile ≥ 85
- [ ] Queue worker, scheduler, backup, dan monitoring aktif
- [ ] Halaman kebijakan lengkap

---

## 20. Langkah Berikutnya

1. Tentukan nama brand, domain, dan produk awal
2. Daftar akun Midtrans/Xendit & Biteship (verifikasi bisa beberapa hari)
3. Siapkan aset foto & logo
4. Jalankan `prompt.md` fase demi fase bersama AI coding agent
5. Review tiap fase sebelum lanjut
