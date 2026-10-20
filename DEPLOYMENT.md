# Panduan Deployment & Operasional Produksi — BloomingKembang

Dokumen ini memuat panduan lengkap konfigurasi server, optimasi performa, keamanan, dan checklist go-live untuk aplikasi e-commerce **BloomingKembang** berbasis **Laravel 12 (PHP 8.3)**.

---

## 1. Spesifikasi Server yang Direkomendasikan

- **OS**: Ubuntu 22.04 LTS atau Ubuntu 24.04 LTS
- **Spesifikasi Minimum**: 2 vCPU, 2 GB RAM, 25 GB SSD (disarankan 4 GB RAM untuk traffic tinggi)
- **Runtime & Layanan**:
  - PHP 8.3 (dengan ekstensi: `php8.3-fpm`, `php8.3-mysql`, `php8.3-mbstring`, `php8.3-xml`, `php8.3-curl`, `php8.3-gd`, `php8.3-zip`, `php8.3-intl`, `php8.3-bcmath`, `php8.3-redis`)
  - Web Server: Nginx
  - Database: MySQL 8.0+ atau MariaDB 10.11+
  - In-Memory Cache: Redis 7.0+
  - Process Manager: Supervisor

---

## 2. Konfigurasi Nginx Server Block

Simpan file di `/etc/nginx/sites-available/bloomingkembang.com`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name bloomingkembang.com www.bloomingkembang.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name bloomingkembang.com www.bloomingkembang.com;

    root /var/www/bloomingkembang/public;
    index index.php index.html;

    # SSL Configuration (Let's Encrypt / Certbot)
    ssl_certificate /etc/letsencrypt/live/bloomingkembang.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/bloomingkembang.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers (tambahan di tingkat web server)
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Gzip & Static Compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript image/svg+xml;
    gzip_vary on;

    # Static Assets Caching
    location ~* \.(jpg|jpeg|png|gif|ico|webp|svg|css|js|woff2|woff|ttf)$ {
        expires 30d;
        access_log off;
        add_header Cache-Control "public, no-transform";
    }

    # Core Laravel Routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM Handler
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 60s;
    }

    # Block Hidden Files & Sensitive Directories
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan konfigurasi dan reload Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/bloomingkembang.com /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 3. Background Queue Worker (Supervisor)

BloomingKembang menggunakan queue database/Redis untuk pengiriman notifikasi email/WhatsApp dan pemrosesan order.

Buat file konfigurasi Supervisor di `/etc/supervisor/conf.d/bloomingkembang-worker.conf`:

```ini
[program:bloomingkembang-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/bloomingkembang/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600 --timeout=90
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/bloomingkembang/storage/logs/worker.log
stopwaitsecs=3600
```

Aktifkan Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start bloomingkembang-worker:*
```

---

## 4. Crontab Scheduler (Pembersihan Stok Kedaluwarsa & Backup)

Buka crontab milik user web server (`crontab -e -u www-data`):

```cron
* * * * * cd /var/www/bloomingkembang && php artisan schedule:run >> /dev/null 2>&1
```

Task yang dijalankan otomatis:
- `ExpireUnpaidOrders`: Melepaskan reserved stock untuk pesanan belum dibayar setiap 15 menit.
- `backup:clean` & `backup:run`: Cadangan harian database & berkas (pukul 01:00 dan 01:30 dini hari).

---

## 5. Script Deployment Otomatis (Zero-Downtime Pipeline)

Gunakan skrip bash berikut (misal simpan sebagai `deploy.sh`):

```bash
#!/bin/bash
set -e

echo "=== Memulai Deployment BloomingKembang ==="

# 1. Masuk ke maintenance mode sementara
php artisan down --retry=60 --secret="blooming-bypass-token" || true

# 2. Tarik kode terbaru
git pull origin main

# 3. Instal dependensi Composer (tanpa dev)
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 4. Compile frontend assets
npm ci
npm run build

# 5. Jalankan migrasi database
php artisan migrate --force

# 6. Pastikan symlink storage public aktif
php artisan storage:link

# 7. Optimasi & caching aplikasi
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 8. Restart queue workers
sudo supervisorctl restart bloomingkembang-worker:*

# 9. Matikan maintenance mode
php artisan up

echo "=== Deployment Berhasil Selesai! ==="
```

---

## 6. Checklist Go-Live

| Komponen | Status Wajib | Keterangan |
| :--- | :---: | :--- |
| `APP_ENV` & `APP_DEBUG` | `production` & `false` | Jangan biarkan `APP_DEBUG=true` di server publik. |
| `APP_URL` | `https://bloomingkembang.com` | Pastikan skema HTTPS sudah terpasang. |
| `MIDTRANS_IS_PRODUCTION` | `true` | Ganti Server Key & Client Key dari sandbox ke production. |
| Midtrans Notification URL | Terdaftar | Daftarkan `https://bloomingkembang.com/webhooks/payment/midtrans` di MAP Midtrans. |
| Pengaturan Email (SMTP) | Terverifikasi | Konfigurasikan Mailgun / SES / SMTP Hostinger / Google Workspace. |
| Pelindung Rate Limit | Aktif | Rate limit login, checkout, pelacakan order, dan webhooks aktif. |
| Backup Rutin | Berjalan | Pastikan file backup tersimpan di storage lokal / AWS S3 terpisah. |
| DNS Records | Selesai | A Record terarah ke IP server, CNAME `www` mengarah ke domain utama. |
