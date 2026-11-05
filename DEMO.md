# 🎬 Panduan Skenario Demo — BloomingKembang E-Commerce

Dokumen ini merupakan panduan lengkap skenario demonstrasi aplikasi **BloomingKembang** (Toko Online Hijab & Busana Muslimah Monolith berbasis Laravel, Livewire, Tailwind CSS, dan Filament).

Panduan ini dapat digunakan untuk presentasi ke klien, pemilik bisnis (*owner*), tim pengembang, maupun penguji/stakeholder.

---

## 📋 Informasi Akses & Akun Demo

| Peran | URL Akses | Email / Akun | Password | Keterangan |
|---|---|---|---|---|
| **Super Admin** | [`/admin`](http://localhost:8000/admin) | `admin@bloomingkembang.com` | `password` | Akses penuh panel pengelolaan toko |
| **Staf Gudang** | [`/admin`](http://localhost:8000/admin) | `staff@bloomingkembang.com` | `password` | Akses operasional pesanan & stok |
| **Pelanggan (Customer)** | [`/login`](http://localhost:8000/login) | `customer@bloomingkembang.com` | `password` | Akun demo dengan alamat & riwayat pesanan |
| **Pengunjung (Guest)** | [`/`](http://localhost:8000/) | *Tanpa login* | - | Jelajah katalog, keranjang sesi & guest checkout |

### 🎟️ Kupon / Voucher Promo untuk Demo

| Kode Voucher | Tipe Potongan | Syarat Minimum | Manfaat |
|---|---|---|---|
| `BLOOMINGFIRST` | Diskon 10% (Maks. Rp 20.000) | Rp 0 (Semua belanja) | Kupon sambutan pengguna baru |
| `KEMBANG25` | Potongan Tetap Rp 25.000 | Min. belanja Rp 150.000 | Promo potongan langsung |

---

## 🚀 Persiapan Sebelum Demo

Pastikan server lokal sudah berjalan:

```bash
# 1. Jalankan web server Laravel (Port 8000)
php artisan serve

# 2. (Opsional) Jalankan worker antrean untuk notifikasi & email
php artisan queue:work
```

Akses aplikasi di browser: **[http://localhost:8000](http://localhost:8000)**

---

## 🎭 Skenario 1: Customer Journey (Storefront & Berbelanja)

### Langkah 1: Eksplorasi Beranda ([`/`](http://localhost:8000/))
1. **Top Bar Header**: Tunjukkan informasi pengiriman gratis ke seluruh Indonesia dan tautan WhatsApp CS.
2. **Branding & Logo**: Tunjukkan logo baru kaligrafi BloomingKembang yang elegan.
3. **Hero Carousel**:
   - Tunjukkan slider otomatis dengan transisi halus (5 slide koleksi unggulan).
   - Klik indikator slide (*bullet dot*) di bagian bawah hero.
4. **Pintasan Kategori Cepat**:
   - Tunjukkan navigasi lingkaran foto (*Segi Empat, Pashmina, Bergo, Aksesoris*).
5. **Koleksi Unggulan & Fitur Brand**:
   - Tunjukkan kartu produk dengan label harga format Rupiah rapi, rating bintang, dan tag status (*Best Seller / New Arrival*).

---

### Langkah 2: Katalog & Filter Pintar ([`/koleksi`](http://localhost:8000/koleksi))
1. Masuk ke halaman **Koleksi** melalui menu navbar.
2. **Pencarian Cepat**:
   - Masukkan kata kunci pencarian, misalnya: `voal` atau `silk`.
   - Tunjukkan hasil pencarian instan tanpa reload yang mengganggu.
3. **Filter Samping**:
   - Filter berdasarkan Kategori (*Hijab Segi Empat*).
   - Filter warna dengan swatch visual (misal: *Mauve, Sage, Nude*).
   - Filter rentang harga dan ketersediaan stok (*Ready Stock*).
4. **Sorting**: Ubah urutan ke *Harga: Rendah ke Tinggi* atau *Terlaris*.

---

### Langkah 3: Halaman Detail Produk & Swatch Varian ([`/produk/{slug}`](http://localhost:8000/koleksi))
1. Klik salah satu produk, contoh: **Voal Signature Scarf** atau **Pashmina Silk Premium**.
2. **Galeri Foto**:
   - Klik thumbnail foto untuk berganti sudut pandang gambar.
   - Arahkan kursor atau klik foto untuk melihat detail serat kain.
3. **Pilihan Varian Interaktif**:
   - Klik swatch warna (misal: *Dusty Rose*, *Soft Sage*, *Burgundy*).
   - Perhatikan SKU, stok real-time, dan harga yang langsung menyesuaikan.
4. **Tambah ke Keranjang**:
   - Tentukan kuantiti belanja (misal: 2 pcs).
   - Klik tombol **+ Keranjang**.
   - Keranjang belanja samping (*Slide-over Cart Drawer*) otomatis terbuka dengan animasi halus.

---

### Langkah 4: Keranjang Slide-Over (*Cart Drawer*)
1. Tunjukkan produk yang baru ditambahkan di dalam drawer.
2. Ubah kuantiti menggunakan tombol `+` / `-`.
3. Tunjukkan subtotal harga yang otomatis terhitung ulang.
4. Klik tombol **Lanjut ke Checkout**.

---

### Langkah 5: Checkout & Penerapan Voucher Promo ([`/checkout`](http://localhost:8000/checkout))
1. **Login atau Mode Tamu**:
   - Jika sudah login sebagai `customer@bloomingkembang.com`, alamat utama otomatis terisi.
   - Jika belum login, form alamat pengiriman dapat diisi secara mandiri.
2. **Kalkulasi Ongkos Kirim**:
   - Pilih kurir pengiriman (Reguler / Express / Kargo).
   - Ongkos kirim otomatis ditambahkan ke total pembayaran.
3. **Uji Coba Kode Voucher**:
   - Masukkan kode `BLOOMINGFIRST` lalu klik **Terapkan**.
   - Tunjukkan potongan diskon langsung memotong grand total secara instan.
4. **Pilih Metode Pembayaran**:
   - Pilih *Midtrans / Online Payment (QRIS, VA Bank, E-Wallet)*.
   - Klik tombol **Buat Pesanan & Bayar**.

---

### Langkah 6: Simulasi Pembayaran & Checkout Berhasil
1. **Popup Pembayaran Midtrans Snap**:
   - Tunjukkan opsi metode bayar QRIS / Virtual Account BCA / Mandiri / GoPay.
   - Lakukan simulasi bayar berhasil pada mode demo/sandbox.
2. **Halaman Checkout Sukses**:
   - Tampilkan nomor pesanan resmi (contoh: `BK-20261105-XXXX`).
   - Tunjukkan detail rincian belanja, estimasi pengiriman, dan tautan lacak.

---

### Langkah 7: Portal Akun Pelanggan & Lacak Pesanan
1. Akses menu **Akun Saya** ([`/akun`](http://localhost:8000/akun)).
2. **Riwayat Pesanan ([`/akun/pesanan`](http://localhost:8000/akun/pesanan))**:
   - Klik salah satu pesanan untuk melihat timeline status (*Pesanan Dibuat &rarr; Lunas &rarr; Dikemas &rarr; Dikirim*).
3. **Fitur Lacak Pesanan Mandiri ([`/lacak-pesanan`](http://localhost:8000/lacak-pesanan))**:
   - Tunjukkan bagaimana pembeli tanpa login (tamu) dapat mengecek status pesanan hanya dengan memasukkan Nomor Pesanan & Nomor HP.

---

## 🛠️ Skenario 2: Admin & Fulfillment Journey ([`/admin`](http://localhost:8000/admin))

Masuk ke panel admin menggunakan kredensial:
- **Email:** `admin@bloomingkembang.com`
- **Password:** `password`

### Langkah 1: Dashboard Analytics & Metrik Toko
1. Tunjukkan statistik penjualan hari ini, pendapatan bulanan, dan total pesanan.
2. Tunjukkan grafik tren pesanan dan widget peringatan stok menipis (*Low Stock Alert*).

---

### Langkah 2: Memproses Pesanan Baru (*Fulfillment*)
1. Buka menu **Pesanan**:
   - Tunjukkan filter pesanan dengan status *Lunas (Paid)*.
   - Buka salah satu pesanan baru.
2. **Cetak Dokumen Resmi**:
   - Klik **Cetak Invoice PDF**: Tunjukkan invoice format faktur resmi butik BloomingKembang.
   - Klik **Cetak Label Pengiriman PDF**: Tunjukkan label paket pengiriman 100 &times; 150 mm siap tempel kurir.
3. **Perbarui Status Pesanan**:
   - Ubah status dari *Lunas* &rarr; *Diproses (Sedang Dikemas)*.
   - Ubah status &rarr; *Dikirim*, lalu input kurir (*JNE*) dan nomor resi pengiriman (*JNE1234567890*).

---

### Langkah 3: Manajemen Produk & Stok Varian
1. Buka menu **Katalog** &rarr; **Produk**.
2. Buka salah satu produk atau buat produk baru:
   - Tunjukkan pengelolaan varian warna & kode hex warna.
   - Tunjukkan pencatatan mutasi stok (*Stock Movement history*).

---

### Langkah 4: Manajemen Voucher & Promosi
1. Buka menu **Promosi** &rarr; **Voucher**.
2. Tunjukkan kupon aktif (`BLOOMINGFIRST` & `KEMBANG25`).
3. Tunjukkan pengaturan kuota pemakaian, minimum transaksi, dan tanggal kedaluwarsa kupon.

---

### Langkah 5: Konten Web & Pengaturan Toko
1. Buka menu **Konten Web** &rarr; **Banner**: Tunjukkan pengelolaan slide hero beranda.
2. Buka menu **Konten Web** &rarr; **Halaman Statis**: Tunjukkan konten *FAQ*, *Kebijakan Retur*, dan *Tentang Kami*.
3. Buka menu **Pengaturan Toko**: Tunjukkan pengaturan identitas toko, nomor WhatsApp CS, dan alamat atelier.

---

## 🛡️ Skenario 3: Penanganan Error & Keandalan (*Error Handling*)

Tunjukkan kesiapan sistem dalam menangani skenario tak terduga dengan tampilan bermerek elegan:

1. **Error 404 (Halaman Tidak Ada)**:
   - Akses: [http://localhost:8000/halaman-ngasal](http://localhost:8000/halaman-ngasal)
   - Tunjukkan logo BloomingKembang, pesan ramah pelanggan, tombol *Kembali ke Beranda* & *Jelajahi Koleksi*.
2. **Error 403 (Akses Dibatasi)**:
   - Coba akses panel admin menggunakan akun pelanggan biasa untuk menunjukkan proteksi hak akses.
3. **Error 419 (Sesi Kadaluarsa)**:
   - Tersedia proteksi sesi form dengan tombol langsung *Muat Ulang Halaman*.

---

## 💡 Tips & Highlight untuk Dipresentasikan

> [!TIP]
> **Poin Unggulan Teknis yang Bisa Ditekankan Saat Presentasi:**
> 1. **Full Monolith Laravel**: Storefront pelanggan dan panel admin berada dalam satu kesatuan sistem yang efisien tanpa ketergantungan API pihak ketiga yang rumit.
> 2. **Pencegahan Race Condition Stok**: Stok dikunci menggunakan `DB::transaction()` & `lockForUpdate()`, memastikan tidak ada *overselling* saat banyak pembeli berebut barang bersamaan.
> 3. **Desain Paper-White Minimalist**: Gaya tampilan butik premium khusus muslimah dengan perpaduan warna Sage Botanic dan Blossom Pink yang anggun.
> 4. **SEO & Mobile Ready**: Skor aksesibilitas tinggi, meta tags OpenGraph lengkap, sitemap dinamis (`/sitemap.xml`), serta robots.txt otomatis.

