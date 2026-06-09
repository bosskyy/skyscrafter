# Panduan Setup Hosting Percetakan App di Hostinger

## Daftar Isi
1. [Setup Database MySQL](#setup-database-mysql)
2. [Upload Kode ke Server](#upload-kode-ke-server)
3. [Konfigurasi .env untuk Production](#konfigurasi-env-untuk-production)
4. [Jalankan Migrasi Database](#jalankan-migrasi-database)
5. [Konfigurasi Web Server](#konfigurasi-web-server)
6. [Optimasi Security](#optimasi-security)
7. [Troubleshooting](#troubleshooting)

---

## 1. Setup Database MySQL

### A. Membuat Database di Hostinger
1. Login ke Hostinger cPanel
2. Cari **MySQL Databases** atau **phpMyAdmin**
3. Buat database baru:
   - **Database Name**: `percetakan_app`
   - **Prefix** (optional): Biarkan kosong atau gunakan yang disarankan
4. Buat user database:
   - **Username**: `percetakan_user` (atau username pilihan Anda)
   - **Password**: Gunakan password yang KUAT (min 12 karakter, campurkan huruf, angka, simbol)
   - **Privileges**: Pilih **ALL PRIVILEGES**

### B. Catat Informasi Penting
Simpan informasi berikut (Anda akan membutuhkannya):
```
Database Host: localhost (atau yang diberikan Hostinger)
Database Name: percetakan_app
Username: percetakan_user
Password: your_strong_password
Port: 3306 (default)
```

---

## 2. Upload Kode ke Server

### Menggunakan FTP/SFTP (via File Manager Hostinger)
1. Login ke Hostinger cPanel
2. Buka **File Manager**
3. Navigasi ke folder `public_html` atau folder root public
4. Upload seluruh file proyek Laravel KECUALI:
   - ❌ `.env` (jangan upload, buat baru di server)
   - ❌ `node_modules/` (buat baru di server dengan npm install)
   - ❌ `vendor/` (buat baru di server dengan composer install)
   - ❌ `.git/` (jangan perlu di production)
   - ✅ `.env.example` (upload untuk referensi)

### Struktur Folder di Hosting
```
public_html/
├── public/              ← Folder PUBLIC (point document root ke sini)
│   ├── index.php
│   └── ...
├── app/
├── config/
├── database/
├── resources/
├── routes/
├── storage/            ← Pastikan writable (chmod 755)
├── bootstrap/
├── .env                 ← Buat baru di server (JANGAN GIT IGNORE)
├── .env.example        ← Upload untuk referensi
├── composer.json
└── package.json
```

---

## 3. Konfigurasi .env untuk Production

### A. SSH/Terminal (Rekomendasi)
Jika Hostinger mendukung SSH:
```bash
# SSH ke server
ssh username@your_domain.com

# Navigasi ke folder project
cd public_html/your_project

# Buat .env dari .env.example
cp .env.example .env

# Edit .env dengan nano atau vim
nano .env
```

### B. Via cPanel File Manager
1. Buat file baru bernama `.env`
2. Copy isi dari `.env.example`
3. Update nilai-nilai berikut:

```env
# General
APP_NAME="Percetakan App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_KEY=base64:xxxxx  (jangan perlu diubah jika sudah ada)

# Locale
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

# Database (dari informasi yang dicatat)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=percetakan_app
DB_USERNAME=percetakan_user
DB_PASSWORD=your_strong_password

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=notice

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache & Queue
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail (setup dengan Hostinger SMTP)
MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="Percetakan App"

# Filesystem
FILESYSTEM_DISK=local
```

---

## 4. Jalankan Migrasi Database

### Via SSH (Rekomendasi)
```bash
# SSH ke server
ssh username@your_domain.com

# Navigasi ke folder project
cd public_html/your_project

# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate APP_KEY (jika belum)
php artisan key:generate

# Jalankan migrasi
php artisan migrate --force

# Seed database (optional, jika ada seeder)
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Via File Manager (Jika Tidak Ada SSH)
Hubungi Hostinger Support untuk menjalankan artisan command, atau gunakan Hostinger Terminal jika tersedia.

---

## 5. Konfigurasi Web Server

### A. Document Root
Pastikan Document Root menunjuk ke folder `public/`:

**Via cPanel:**
1. Buka **Addon Domains** atau **Domains**
2. Untuk domain Anda, set:
   - **Document Root**: `/home/username/public_html/your_project/public`

### B. PHP Version
1. Buka **MultiPHP Manager** di cPanel
2. Pilih domain Anda
3. Set PHP version ke **8.1 atau lebih tinggi**

### C. Permissions (Sangat Penting!)
```bash
# Via SSH, jalankan:
cd public_html/your_project

# Pastikan folder writable
chmod 755 storage
chmod 755 bootstrap/cache
chmod 644 .env

# Atau lebih permissive:
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

---

## 6. Optimasi Security

### A. Hide .env File (Security)
Edit `.htaccess` di root folder:
```apache
<FilesMatch "^\.env">
    Deny from all
</FilesMatch>
```

### B. Disable Directory Listing
Di `.htaccess`:
```apache
Options -Indexes
```

### C. Mod Rewrite untuk Laravel
Pastikan file `public/.htaccess` sudah ada dengan isi:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### D. SSL Certificate
1. Gunakan **AutoSSL** atau **Let's Encrypt** (biasanya gratis di Hostinger)
2. Force HTTPS di `.env`:
   - `APP_URL=https://yourdomain.com`

### E. Database Backup
1. Setup Hostinger **Automatic Backup**
2. Export database secara berkala via phpMyAdmin

---

## 7. Troubleshooting

### "500 Internal Server Error"
- Check `storage/logs/laravel.log` untuk error message
- Pastikan `storage/` folder writable
- Pastikan `bootstrap/cache/` folder writable
- Pastikan PHP version 8.1+

### "Database Connection Error"
```
Error: SQLSTATE[HY000] [2002] Can't connect to local MySQL server
```
**Solusi:**
- Verifikasi database credentials di `.env`
- Pastikan database sudah dibuat
- Restart MySQL (hubungi Hostinger support)
- Coba gunakan IP address MySQL jika `localhost` tidak bekerja

### "Class Not Found / Autoload Error"
```bash
# SSH dan jalankan:
composer dump-autoload -o
php artisan clear-compiled
php artisan optimize:clear
```

### "No Application Encryption Key Has Been Specified"
```bash
php artisan key:generate
```

### "Uploaded Files Not Saving"
- Pastikan folder `storage/app/public/` writable: `chmod 755`
- Pastikan folder `public/uploads/` writable: `chmod 755`
- Check disk space di server

### Email Tidak Terkirim
- Verifikasi SMTP credentials di `.env`
- Check email di folder `storage/logs/`
- Hostinger biasanya blocks port SMTP, gunakan port 587 atau 465

---

## 8. Production Checklist

Sebelum go-live, pastikan:

- [ ] ✅ `.env` sudah dikonfigurasi dengan benar
- [ ] ✅ `APP_DEBUG=false` di `.env`
- [ ] ✅ `APP_ENV=production` di `.env`
- [ ] ✅ `APP_KEY` sudah generate
- [ ] ✅ Database sudah dibuat dan migrasi selesai
- [ ] ✅ Document Root menunjuk ke folder `public/`
- [ ] ✅ Storage folder writable
- [ ] ✅ SSL certificate aktif
- [ ] ✅ `.htaccess` sudah setup
- [ ] ✅ `php artisan config:cache` sudah dijalankan
- [ ] ✅ `php artisan route:cache` sudah dijalankan
- [ ] ✅ Coba akses website dan test semua fitur
- [ ] ✅ Setup email dan test pengiriman
- [ ] ✅ Backup database sudah setup

---

## 9. Performance Optimization

Setelah production, jalankan:

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Optimize autoloader
composer dump-autoload -o

# View cache
php artisan view:cache
```

Untuk disable cache saat development:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 10. Daily Operations

### Monitor Logs
```bash
# Real-time logs
tail -f storage/logs/laravel.log

# Or via cPanel Log Manager
```

### Regular Backup
- **Database**: Export via phpMyAdmin mingguan
- **Files**: Use Hostinger Backup
- **Git**: Push ke repository regular

### Update Dependencies
```bash
composer update --no-dev --optimize-autoloader
```

---

**Selamat! Aplikasi Anda siap dihosting di Hostinger!** 🎉

Jika ada pertanyaan, hubungi Hostinger Support atau cek dokumentasi Laravel di https://laravel.com/docs
