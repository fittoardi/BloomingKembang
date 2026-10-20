# 🌸 BloomingKembang — E-Commerce Monolith

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat&logo=php)](https://php.net)
[![Filament](https://img.shields.io/badge/Filament-v5.10-F59E0B?style=flat)](https://filamentphp.com)
[![Livewire](https://img.shields.io/badge/Livewire-v4.4-FB70A9?style=flat&logo=livewire)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-v4-38B2AC?style=flat&logo=tailwind-css)](https://tailwindcss.com)
[![Tests](https://img.shields.io/badge/Tests-Pest%204%20(66%20Passed)-10B981?style=flat)](https://pestphp.com)

**BloomingKembang** adalah platform e-commerce monolitik berbasis Laravel yang dirancang khusus untuk brand fesyen muslimah/hijab kurasi premium. Mengusung filosofi desain *Paper-White Minimalist* dengan tipografi monokrom obsidian yang anggun, responsif, dan sarat fitur modern.

---

## 🌟 Fitur Utama

### 🛍️ Katalog & Pengalaman Berbelanja
- **Koleksi & Kategori**: Penjelajahan produk berdasarkan kategori (*Hijab Segi Empat, Pashmina, Inner & Aksesoris*), sorting harga/terbaru, dan live search.
- **Swatch Interaktif & Zoom**: Pemilihan varian warna dan ukuran dengan visual swatch, galeri multi-foto responsif, serta modal zoom detail tekstur bahan (Alpine.js).
- **Hybrid Cart System**: Keranjang belanja cerdas yang otomatis menggunakan sesi untuk pengunjung (guest) dan database untuk customer terautentikasi, lengkap dengan penggabungan otomatis (*auto-merge cart*) saat login.
- **Wishlist & Buku Alamat**: Penyimpanan produk favorit dan manajemen buku alamat bertingkat (Provinsi &rarr; Kota/Kabupaten &rarr; Kecamatan &rarr; Kode Pos) dengan proteksi policy otorisasi per pengguna.

### 💳 Checkout, Ongkir & Voucher
- **Dynamic Pricing Engine**: Perhitungan subtotal, voucher diskon (tipe persentase maupun nominal tetap dengan batas belanja & kuota), dan ongkos kirim real-time.
- **Atomic Stock Reservation**: Pembuatan pesanan dalam `DB::transaction()` dengan `lockForUpdate()`, reservasi stok varian untuk mencegah *race condition*, dan pencatatan buku besar mutasi stok (`StockMovement`).
- **Scheduler Expire Unpaid Orders**: Pembebasan otomatis stok terreservasi untuk pesanan yang tidak dibayar dalam batas waktu 24 jam via Laravel Scheduler & Queue Job.

### 💰 Pembayaran & Manajemen Pesanan
- **Midtrans Snap Integration**: Pembayaran instan mendukung QRIS, Virtual Account bank (BCA, BNI, BRI, Mandiri, Permata), GoPay, dan ShopeePay dalam mode Sandbox & Production.
- **Webhook Idempoten & Aman**: Verifikasi signature SHA512 terhadap pemalsuan payload, pencegahan eksekusi berulang (*double-decrement prevention*), dan pembatalan pesanan otomatis bila expired/gagal.
- **Finalisasi Stok Otomatis**: Event `OrderPaid` memicu mutasi stok penjualan (*sale*) dan menaikkan metrik `sold_count` produk secara atomik.
- **Pelacakan Pesanan**:
  - **Customer Portal (`/akun/pesanan`)**: Riwayat pesanan, timeline status visual (*Dibuat &rarr; Dibayar &rarr; Dikirim &rarr; Selesai*), pelacakan resi, tombol bayar ulang, dan pembatalan pesanan sebelum bayar.
  - **Guest Order Tracking (`/lacak-pesanan`)**: Pelacakan untuk pelanggan tanpa akun menggunakan verifikasi nomor kontak atau akses instan via **Signed URL**.

### 📦 Admin Panel (Filament v5) & Cetak Dokumen
- **Manajemen Katalog**: CRUD Kategori, Produk, Varian (SKU, stok, harga, berat), serta upload multi-gambar via Spatie Media Library (WebP conversion).
- **Voucher Promo**: Pembuatan kupon diskon dengan batasan kuota, masa berlaku, dan batas pemakaian per customer.
- **Pengelolaan Pesanan**: Filter status & pembayaran, modal input nomor resi kurir, catatan internal admin, serta:
  - **Cetak Invoice PDF**: Format faktur pembelian resmi BloomingKembang (DomPDF).
  - **Cetak Label Pengiriman PDF**: Format label kirim kurir standar 100mm &times; 150mm siap tempel.

### 🔔 Notifikasi Multi-Channel
- Notifikasi terantre (queued) untuk:
  - **Pesanan Dibuat** (*Order Created*).
  - **Pembayaran Berhasil** (*Order Paid*).
  - **Pesanan Dikirim** (*Order Shipped* lengkap dengan nomor resi & tautan lacak).
- Tersedia template Email resmi dan format pesan WhatsApp berbahasa Indonesia.

---

## 🛠️ Tech Stack & Ekosistem

| Komponen | Teknologi |
| :--- | :--- |
| **Framework** | [Laravel 12](https://laravel.com) (PHP 8.3+) |
| **Admin Panel** | [Filament v5.10](https://filamentphp.com) |
| **Frontend Reactive** | [Livewire v4.4](https://livewire.laravel.com) & Alpine.js |
| **Styling** | [Tailwind CSS v4](https://tailwindcss.com) & Paper-White Custom System |
| **Database & Cache** | SQLite (dev/test) / MySQL / PostgreSQL & Redis |
| **Media & Roles** | `spatie/laravel-medialibrary` & `spatie/laravel-permission` |
| **Payment Gateway** | Midtrans Snap REST API |
| **PDF Generation** | `barryvdh/laravel-dompdf` (DomPDF 3.x) |
| **Testing** | [Pest PHP v4](https://pestphp.com) (Feature & Unit Tests) |
| **Code Style** | [Laravel Pint](https://laravel.com/docs/pint) |

---

## 🚀 Panduan Instalasi Lokal

### 1. Prasyarat Sistem
- **PHP** &ge; 8.3 dengan ekstensi `pdo`, `mbstring`, `openssl`, `curl`, `gd`, `zip`, `sqlite3` (atau driver DB pilihan Anda).
- **Composer** &ge; 2.x
- **Node.js** &ge; 18.x & **npm**

### 2. Kloning Repositori & Pasang Dependensi
```bash
git clone https://github.com/fittoardi/BloomingKembang.git
cd BloomingKembang

# Install dependensi PHP
composer install

# Install dependensi Node.js
npm install
```

### 3. Konfigurasi Lingkungan (.env)
Salin berkas contoh lingkungan dan buat application key:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi pada `.env` (database, mail, serta Midtrans jika ingin menguji pembayaran):
```env
APP_NAME="BloomingKembang"
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# Atau jika menggunakan MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=blooming_kembang
# DB_USERNAME=root
# DB_PASSWORD=

# Midtrans Payment Gateway (Sandbox)
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
```

### 4. Migrasi & Seeder Database
Jalankan migrasi dan seeder untuk menyiapkan role, staf admin, produk demo, dan kategori:
```bash
php artisan migrate:fresh --seed
```

Tautkan folder penyimpanan publik untuk media gambar produk:
```bash
php artisan storage:link
```

### 5. Akun Bawaan (Default Credentials)

| Role | Email | Password | Akses |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `superadmin@bloomingkembang.com` | `password` | `/admin` (Semua akses) |
| **Admin** | `admin@bloomingkembang.com` | `password` | `/admin` (Operasional) |
| **Staff Gudang**| `warehouse@bloomingkembang.com` | `password` | `/admin` (Pesanan & Resi) |
| **Customer** | `customer@bloomingkembang.com` | `password` | Storefront & `/akun` |

---

## 💻 Menjalankan Server Pengembangan

Jalankan server Laravel dan Vite asset compiler:

```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: Vite Dev Server
npm run dev

# (Opsional) Terminal 3: Queue Worker untuk Notifikasi & Job
php artisan queue:listen
```

Buka peramban di:
- **Storefront**: [http://localhost:8000](http://localhost:8000)
- **Koleksi Produk**: [http://localhost:8000/koleksi](http://localhost:8000/koleksi)
- **Portal Customer**: [http://localhost:8000/akun](http://localhost:8000/akun)
- **Pelacakan Pesanan**: [http://localhost:8000/lacak-pesanan](http://localhost:8000/lacak-pesanan)
- **Admin Panel**: [http://localhost:8000/admin](http://localhost:8000/admin)

---

## 🧪 Pengujian & Kualitas Kode

Proyek ini menerapkan cakupan pengujian komprehensif menggunakan Pest PHP:

```bash
# Menjalankan seluruh test suite (66 tests)
php artisan test --compact

# Menjalankan tes spesifik (misal: webhook & pesanan)
php artisan test tests/Feature/PaymentWebhookTest.php tests/Feature/CustomerOrderTest.php

# Memformat kode sesuai standar PSR-12 / Laravel
vendor/bin/pint --format agent

# Kompilasi bundle produksi frontend
npm run build
```

---

## 📁 Struktur Direktori Penting

```
BloomingKembang/
├── app/
│   ├── Enums/                 # OrderStatus, PaymentStatus, ProductStatus, VoucherType
│   ├── Events/                # OrderPaid
│   ├── Filament/Resources/    # Admin Resources (Categories, Products, Vouchers, Orders)
│   ├── Http/Controllers/      # PaymentWebhookController
│   ├── Listeners/             # FinalizeStockOnOrderPaid, LinkGuestOrdersToUser, MergeCartOnLogin
│   ├── Livewire/              # CatalogIndex, ProductShow, CartDrawer, CheckoutPage, TrackOrder, dll
│   ├── Models/                # Order, Product, ProductVariant, Payment, StockMovement, dll
│   ├── Notifications/         # OrderCreatedNotification, OrderPaidNotification, OrderShippedNotification
│   ├── Policies/              # AddressPolicy, WishlistPolicy, OrderPolicy
│   └── Services/              # Business Logic (CartService, CheckoutService, PricingService, VoucherService, Payment/)
├── database/
│   ├── migrations/            # Skema tabel database
│   └── seeders/               # RolePermissionSeeder, AdminSeeder, DemoSeeder
├── resources/
│   └── views/
│       ├── layouts/           # Storefront layout (Paper-White)
│       ├── livewire/          # Komponen Blade Livewire
│       └── pdf/               # invoice.blade.php & shipping-label.blade.php
├── routes/
│   ├── web.php                # Rute storefront & akun
│   ├── webhooks.php           # Rute webhook Midtrans
│   └── console.php            # Scheduled job (ExpireUnpaidOrders)
└── tests/
    └── Feature/               # Pest Feature tests (Auth, Catalog, Cart, Checkout, Voucher, Payment, Order)
```

---

## 📄 Lisensi

Proyek ini dikembangkan secara monolitik di bawah lisensi [MIT License](LICENSE).
Hak Cipta &copy; 2026 BloomingKembang.
