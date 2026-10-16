# Prompt — Membangun Toko Online Kerudung (Single Brand) dengan Full Laravel

## 1. Master Prompt (Konteks Proyek)

```text
Kamu adalah senior Laravel engineer sekaligus UI/UX designer yang berpengalaman membangun e-commerce di Indonesia.

## Proyek
Bangun website toko online (e-commerce) untuk brand kerudung milik sendiri: [BloomingKembang].
- Model bisnis: single brand (hanya menjual produk brand sendiri, bukan marketplace)
- Produk: hijab segi empat, pashmina, hijab instan, bergo, inner/ciput, aksesoris
- Atribut produk: bahan (voal, ceruty, satin, paris, dll.), warna, ukuran, berat, stok per varian
- Target pasar: perempuan muslimah di Indonesia, mayoritas mengakses lewat HP
- Bahasa antarmuka: Bahasa Indonesia. Mata uang: Rupiah (IDR), format "Rp 129.000"
- Karakter brand: [MISAL: lembut, elegan, minimalis, hangat]
- Palet warna utama: [HEX WARNA]. Font: [NAMA FONT atau "pilihkan yang cocok"]

## Arsitektur: FULL LARAVEL (monolith)
Seluruh sistem dalam satu aplikasi Laravel: storefront, area customer, dan panel admin.

Stack wajib:
- Laravel (versi stabil terbaru), PHP 8.3+
- Frontend: Blade + Livewire + Alpine.js + Tailwind CSS (Vite). Jangan memakai SPA terpisah.
- Panel admin: Filament (panel "admin")
- Auth customer: Laravel Breeze (Livewire stack): register, login, verifikasi email, reset password
- Role & izin: spatie/laravel-permission dengan role super_admin, staff, customer + Laravel Policies
- Database: [MySQL / PostgreSQL]
- Media: spatie/laravel-medialibrary (konversi WebP + thumbnail)
- Pembayaran: [Midtrans / Xendit] (QRIS, VA bank, e-wallet)
- Ongkir: [Biteship / RajaOngkir] via Laravel HTTP Client
- Queue + Scheduler (driver [database / Redis])
- Email: Laravel Mail ([Resend / Brevo / SMTP]); WhatsApp: [Fonnte / WhatsApp Cloud API] via custom Notification channel
- PDF invoice/label: barryvdh/laravel-dompdf
- Testing: Pest. Kualitas: Laravel Pint + Larastan

## Dua Peran Utama
1. ADMIN (super_admin & staff) → panel /admin (Filament)
   - Dashboard (omzet, jumlah order, terlaris, stok menipis, order perlu diproses)
   - CRUD produk + varian (warna, hex, ukuran, SKU, harga, harga coret, stok, berat) + multi-gambar + SEO
   - CRUD koleksi/kategori, voucher, banner, halaman statis
   - Kelola pesanan: filter status, ubah status, input resi, cetak invoice & label, catatan internal
   - Kelola customer (lihat profil, alamat, riwayat pesanan; ubah/nonaktifkan hanya super_admin)
   - Pengaturan toko: kontak, alamat gudang, kurir aktif, toggle guest checkout
   - Kelola staf & role (hanya super_admin)
2. CUSTOMER → storefront + area /akun
   - Registrasi, login, verifikasi email, lupa password
   - Profil, ubah password, buku alamat (alamat default), riwayat pesanan, detail & lacak pesanan, bayar ulang, batalkan pesanan yang belum dibayar
   - Wishlist
   - Keranjang (session untuk guest, database untuk customer; digabung saat login)
   - Checkout (pilih alamat tersimpan/isi baru), guest checkout opsional (bisa dimatikan admin)

## Storefront
- Beranda (hero, koleksi unggulan, terlaris, testimoni, newsletter)
- Koleksi dengan filter (warna, bahan, harga, ketersediaan) dan sortir
- Halaman produk: galeri, swatch warna, pilihan ukuran, stok per varian, panduan ukuran, perawatan, produk terkait
- Ongkir real-time per kurir, kode voucher, pembayaran online, halaman status/lacak pesanan
- Halaman statis: Tentang, FAQ, Kebijakan Retur, S&K, Privasi, Kontak; tombol WhatsApp

## Aturan Teknis (wajib)
1. Logika bisnis di Services/Actions (CartService, PricingService, CheckoutService, StockService, VoucherService), bukan di controller/komponen Livewire.
2. Integrasi pihak ketiga lewat interface (PaymentGateway, ShippingProvider) agar mudah diganti.
3. Harga, diskon, ongkir, total SELALU dihitung ulang di server.
4. Simpan snapshot nama, harga, dan alamat pada order/order_items.
5. Stok: reserve saat order dibuat, finalisasi saat dibayar, lepas saat kedaluwarsa. Gunakan DB::transaction + lockForUpdate() untuk mencegah oversell.
6. Webhook pembayaran: di luar CSRF, verifikasi signature, idempotent, log payload.
7. Otorisasi dengan Policies: customer hanya bisa mengakses order/alamat miliknya (cegah IDOR). Tulis test-nya.
8. Gunakan Form Request untuk validasi, $fillable untuk mass assignment, PHP backed Enum untuk status.
9. Rate limiting pada login, register, checkout, dan cek voucher.
10. Semua secret lewat .env; sediakan .env.example. Jangan hardcode kredensial.
11. SEO: meta per halaman, Open Graph, sitemap, JSON-LD Product. Gambar WebP + lazy loading.
12. Aksesibilitas dan mobile-first; target Lighthouse mobile ≥ 90.
13. Ikuti konvensi Laravel; jalankan Pint dan Pest sebelum menyatakan fase selesai.

## Desain
- Terasa seperti brand fashion premium yang hangat, bukan template generik
- Fokus pada foto produk, tipografi rapi, CTA jelas, checkout minim langkah
- Skeleton loading, empty state, pesan error ramah
- Tema Filament disesuaikan dengan warna brand

## Cara Kerja
- Kerjakan per fase yang kuberikan secara bertahap. Jangan lompat ke fase berikutnya sebelum aku bilang lanjut.
- Di awal tiap fase: tulis rencana singkat (file yang akan dibuat/diubah).
- Di akhir tiap fase: ringkas yang selesai, cara menjalankan & mengetes, dan yang belum.
- Jika ada keputusan ambigu, ajukan maksimal 3 pertanyaan singkat, lalu lanjutkan dengan asumsi terbaik dan sebutkan asumsinya.
- Seed data harus jelas berlabel "CONTOH".
```

---

## 2. Prompt per Fase

### Fase 1 — Fondasi, Role Admin & Customer

```text
Fase 1: Fondasi.
1. Inisialisasi proyek Laravel + Tailwind (Vite) + Livewire. Install Breeze (Livewire stack) untuk auth customer,
   Filament (panel "admin" di /admin), dan spatie/laravel-permission.
2. Buat migrasi, model, factory untuk: users, addresses, categories, products, product_variants, carts, cart_items,
   wishlists, orders, order_items, payments, vouchers, settings. Tambahkan index, relasi, dan PHP backed Enum
   (OrderStatus, PaymentStatus, VoucherType).
3. Seeder: role super_admin, staff, customer; 1 super admin; 1 customer contoh; 3 kategori; 6 produk "CONTOH"
   dengan varian warna & ukuran.
4. Pisahkan akses: hanya super_admin/staff yang bisa masuk /admin; customer diarahkan ke /akun. Implementasikan
   lewat Filament canAccessPanel + middleware role.
5. Layout storefront dasar (header, footer, navigasi mobile) dengan design tokens dari palet brand.
6. Siapkan .env.example, Pint, Larastan, Pest, dan README cara menjalankan lokal.
7. Test: customer tidak bisa membuka /admin; admin bisa; guest diarahkan ke login.
Berhenti setelah selesai dan beri ringkasan + cara tes.
```

### Fase 2 — Katalog & Admin Produk

```text
Fase 2: Katalog.
1. Filament: ProductResource dengan relation manager Varian (warna, hex, ukuran, SKU, harga, harga coret, stok, berat),
   upload multi-gambar (medialibrary) dengan urutan, field SEO. CategoryResource.
2. Storefront (Livewire): /koleksi dan /koleksi/{slug} dengan filter (warna, bahan, harga, stok), sortir, pagination.
3. Halaman /produk/{slug}: galeri + zoom, swatch warna yang mengganti foto, pilihan ukuran, indikator stok,
   tab Deskripsi/Bahan/Panduan Ukuran/Perawatan, produk terkait.
4. Tangani stok habis & varian tidak tersedia. Cache query katalog yang berat.
5. Metadata SEO + JSON-LD Product.
6. Test fitur untuk filter dan ketersediaan stok.
Berhenti setelah selesai dan beri ringkasan + cara tes.
```

### Fase 3 — Area Customer & Keranjang

```text
Fase 3: Akun customer & keranjang.
1. Lengkapi alur auth: register, login, verifikasi email, reset password (Bahasa Indonesia). Opsional: login Google (Socialite)
   sebagai toggle.
2. Area /akun (Livewire): dashboard, profil, ubah password, buku alamat (CRUD + alamat default, field provinsi → kota →
   kecamatan → kode pos), wishlist.
3. CartService + CartDrawer: guest pakai session, customer pakai database; gabungkan keranjang saat login.
4. Policies: customer hanya bisa akses alamat dan wishlist miliknya. Tulis test otorisasi.
5. Sinkronkan guest → customer: pesanan guest dengan email sama dihubungkan ke akun setelah verifikasi email (siapkan
   hook/event-nya).
Berhenti setelah selesai dan beri ringkasan + cara tes.
```

### Fase 4 — Checkout, Ongkir & Voucher

```text
Fase 4: Checkout (belum pembayaran).
1. Halaman checkout: pilih alamat tersimpan (customer) atau isi baru; guest checkout bisa dimatikan lewat setting admin.
2. Interface ShippingProvider + implementasi [Biteship/RajaOngkir]: ongkir real-time per kurir/layanan berdasarkan total
   berat. Cache singkat; tangani error API dengan pesan ramah.
3. VoucherService (persen/nominal, min. belanja, maks. diskon, kuota, masa berlaku) divalidasi di server; VoucherResource
   di Filament.
4. PricingService menghitung subtotal, diskon, ongkir, total di server.
5. CheckoutService membuat Order status pending_payment dalam DB::transaction, reserve stok dengan lockForUpdate(), set expires_at.
6. Scheduler + job ExpireUnpaidOrders untuk melepas stok order kedaluwarsa.
7. Test: perhitungan harga, voucher tidak valid, stok habis, dua checkout bersamaan pada stok terakhir.
Berhenti setelah selesai dan beri ringkasan + cara tes.
```

### Fase 5 — Pembayaran & Manajemen Pesanan

```text
Fase 5: Pembayaran & pesanan.
1. Interface PaymentGateway + implementasi [Midtrans/Xendit] (QRIS, VA bank, e-wallet), mode sandbox via env.
2. Endpoint webhook di routes/webhooks.php (di luar CSRF): verifikasi signature, idempotent, simpan payload, ubah status
   menjadi paid, jalankan event OrderPaid (finalisasi stok, notifikasi). Tangani expired/failed/cancelled.
3. Customer: /akun/pesanan (daftar + filter status) dan detail pesanan (timeline status, resi, tombol bayar ulang,
   batalkan jika belum dibayar). Lacak pesanan untuk guest via signed URL atau verifikasi nomor kontak.
4. Filament OrderResource: filter status, detail, ubah status, input resi, catatan internal, cetak invoice & label (dompdf).
5. Notifikasi (Laravel Notifications via queue): pesanan dibuat, dibayar, dikirim (dengan resi) lewat email dan WhatsApp.
   Template Bahasa Indonesia.
6. Test: webhook valid/invalid signature, webhook dikirim ulang, customer A tidak bisa melihat order customer B.
Berhenti setelah selesai dan beri ringkasan + cara tes sandbox.
```

### Fase 6 — Dashboard, Konten, SEO

```text
Fase 6: Dashboard, konten & SEO.
1. Widget Filament: omzet harian/bulanan, jumlah order per status, produk terlaris, stok menipis.
2. Polish beranda dan halaman statis (Tentang, FAQ, Retur, S&K, Privasi, Kontak) yang bisa diedit dari admin; banner promo.
3. Sitemap otomatis, robots.txt, canonical, Open Graph, JSON-LD (Product, Organization, BreadcrumbList).
4. GA4 + Meta Pixel (view_item, add_to_cart, begin_checkout, purchase) dengan consent banner sederhana.
5. Tombol WhatsApp melayang; optimasi gambar dan performa; laporkan skor Lighthouse mobile.
Berhenti setelah selesai dan beri ringkasan.
```

### Fase 7 — Hardening, QA & Deploy

```text
Fase 7: Finalisasi.
1. Audit keamanan: rate limiting, security headers (CSP, HSTS), cek IDOR di semua route akun & admin, validasi upload,
   APP_DEBUG=false di produksi.
2. Lengkapi test suite Pest (unit + feature) dan pastikan Pint + Larastan lulus.
3. Pasang Sentry, halaman 404/500 ramah, dan spatie/laravel-backup (backup harian terjadwal).
4. Siapkan panduan deploy: Nginx + PHP-FPM + Supervisor (queue:work) + cron scheduler, perintah optimasi
   (config/route/view/event cache), storage:link, daftar env variable, dan checklist go-live.
5. Tulis "Panduan Admin" singkat untuk owner (tambah produk, proses pesanan, buat voucher, kelola staf).
Berikan laporan akhir: fitur selesai, yang tertunda, dan saran Fase 2.
```

---

## 3. Prompt Tambahan (Opsional)

**Review kode & keamanan**
```text
Lakukan code review menyeluruh proyek Laravel ini. Fokus: IDOR dan policy yang belum dipasang, validasi webhook,
race condition stok, N+1 query, mass assignment, dan performa query katalog. Urutkan temuan dari severity tertinggi
beserta perbaikannya.
```

**Generator deskripsi produk**
```text
Buat deskripsi produk untuk hijab [NAMA PRODUK]: bahan [BAHAN], ukuran [UKURAN], warna [WARNA].
Tone: [elegan/hangat/santai]. Sertakan: ringkasan 2 kalimat, 3–5 keunggulan, detail bahan & perawatan,
dan meta description ≤ 155 karakter. Jangan membuat klaim berlebihan.
```

**Fitur Fase 2**
```text
Tambahkan fitur: [ulasan dengan foto / pengajuan retur / poin loyalitas / waitlist stok habis / pre-order / bundling / reseller].
Berikan rencana singkat dulu (migrasi, model, Livewire/Filament, policy, test), tunggu persetujuanku, lalu implementasikan.
```

---

## 4. Checklist Input yang Perlu Kamu Siapkan

- [ ] Nama brand, logo, palet warna, font
- [ ] Domain dan server/hosting (VPS disarankan)
- [ ] Daftar produk awal (nama, bahan, warna, ukuran, harga, berat, stok) + foto
- [ ] Akun & API key sandbox: payment gateway, ongkir, email, WhatsApp
- [ ] Alamat gudang & kurir yang ingin diaktifkan
- [ ] Teks kebijakan retur, FAQ, dan Tentang Kami
- [ ] Akun Meta Pixel & GA4
- [ ] Daftar akun staf awal (nama, email, role)
