# Quick Setup Guide - Percetakan App di Hostinger

## 5-Menit Quick Start

### Step 1: Setup Database (2 menit)
```
1. Login ke Hostinger cPanel
2. MySQL Databases → Create Database
   - Name: percetakan_app
3. Create User
   - Username: percetakan_user
   - Password: YourStrong!Pass123
4. Grant ALL PRIVILEGES
5. Save credentials ✓
```

### Step 2: Upload Files (1 menit via File Manager)
```
1. Buka File Manager
2. Navigate ke public_html
3. Upload semua files kecuali:
   ✗ .env (akan dibuat nanti)
   ✗ vendor/ (auto-create)
   ✗ node_modules/ (auto-create)
   ✗ .git (tidak perlu)
```

### Step 3: Setup .env (1 menit)
```
1. Create file .env di root folder
2. Copy isi dari .env.example
3. Update nilai:
   - APP_NAME="Percetakan App"
   - APP_ENV=production
   - APP_DEBUG=false
   - APP_URL=https://yourdomain.com
   - DB_HOST=localhost
   - DB_DATABASE=percetakan_app
   - DB_USERNAME=percetakan_user
   - DB_PASSWORD=YourStrong!Pass123
4. Save ✓
```

### Step 4: Setup & Test (1 menit via Terminal)
```bash
# SSH ke server atau gunakan Terminal di cPanel
cd public_html/percetakan-app

# Install PHP packages
composer install --no-dev --optimize-autoloader

# Generate key (jika belum)
php artisan key:generate

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:cache
php artisan route:cache

# DONE!
```

---

## File-file Penting yang Sudah Dipersiapkan

| File | Tujuan |
|------|--------|
| `.env.example` | Template untuk .env (jangan upload .env original) |
| `HOSTING_SETUP.md` | Panduan lengkap setup hosting |
| `PRODUCTION_CHECKLIST.md` | Checklist sebelum go-live |
| `deploy-production.sh` | Script otomatis deployment |
| `.htaccess` | Security & rewrite rules |

---

## Database Configuration

### Credentials Hostinger
```
Host: localhost
Database: percetakan_app
Username: percetakan_user
Password: YourStrong!Pass123
Port: 3306
```

### Di .env Hostinger
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=percetakan_app
DB_USERNAME=percetakan_user
DB_PASSWORD=YourStrong!Pass123
```

---

## Migrasi Database

### Tables yang Akan Dibuat
- ✓ users (authentication)
- ✓ categories (jenis layanan)
- ✓ products (produk printing)
- ✓ orders (pesanan customer)
- ✓ cache (untuk session/cache)

### Run Migrations
```bash
php artisan migrate --force
```

---

## SSL/HTTPS Setup

### Automatic di Hostinger
```
1. cPanel → AutoSSL / Let's Encrypt
2. Auto-renew: ON
3. Wait 24 jam untuk propagasi DNS
```

### Update .env
```env
APP_URL=https://yourdomain.com
```

### Force HTTPS (Edit .htaccess)
```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## File Permissions (via SSH)

```bash
cd /home/username/public_html/percetakan-app

# Folders writable
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 755 public/uploads

# .env read-only
chmod 644 .env
```

---

## Testing Setelah Upload

```bash
# 1. Test homepage
curl -I https://yourdomain.com

# 2. Check error logs
tail -f storage/logs/laravel.log

# 3. Database connection test
php artisan tinker
>>> DB::connection()->getPdo();

# 4. Cache test
>>> Cache::put('test', 'works');
>>> Cache::get('test');
```

---

## Production Environment (.env)

```env
APP_NAME="Percetakan App"
APP_ENV=production
APP_KEY=base64:xxxxx (dari local)
APP_DEBUG=false
APP_URL=https://yourdomain.com

APP_LOCALE=id
LOG_LEVEL=notice

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=percetakan_app
DB_USERNAME=percetakan_user
DB_PASSWORD=YourStrong!Pass123

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=email_password
```

---

## Troubleshooting

### Error: "Class not found"
```bash
composer dump-autoload -o
php artisan clear-compiled
```

### Error: "Permission denied"
```bash
chmod -R 755 storage bootstrap/cache
```

### Error: "Can't connect to database"
- Cek credentials di .env
- Pastikan database sudah dibuat
- Cek MySQL status di cPanel

### Error: "500 Internal Server Error"
```bash
# Check logs
tail -100 storage/logs/laravel.log

# Clear caches
php artisan config:clear
php artisan cache:clear
```

---

## Monitoring

### Daily
- Check `storage/logs/laravel.log`
- Verify database backups

### Weekly
- Monitor disk space
- Check error logs
- Review performance

### Monthly
- Update dependencies (composer update)
- Security review
- Database optimization

---

## Helpful Commands

```bash
# SSH to server
ssh username@yourdomain.com

# Navigate to project
cd public_html/percetakan-app

# Install/update dependencies
composer install --no-dev --optimize-autoloader

# Database
php artisan migrate --force
php artisan db:seed
php artisan tinker

# Cache management
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear

# Logs
tail -f storage/logs/laravel.log
tail -100 storage/logs/laravel.log

# Permissions
chmod -R 755 storage bootstrap/cache
chmod 644 .env
```

---

## What's Included

✅ Database configuration for MySQL  
✅ Laravel migrations ready  
✅ Security headers in .htaccess  
✅ Production optimization  
✅ Comprehensive documentation  
✅ Deployment script  
✅ Environment templates  

---

## Next Steps

1. ✅ Setup database di Hostinger
2. ✅ Upload files ke server
3. ✅ Configure .env
4. ✅ Run migrations
5. ✅ Test website thoroughly
6. ✅ Setup SSL certificate
7. ✅ Monitor logs & performance
8. ✅ Setup automated backups

---

## Support

**Dokumentasi Lengkap**: Baca `HOSTING_SETUP.md`  
**Checklist**: Lihat `PRODUCTION_CHECKLIST.md`  
**Hostinger Support**: https://support.hostinger.com/  
**Laravel Docs**: https://laravel.com/docs/  

---

**Status**: ✅ Ready for Hosting  
**Last Updated**: 2026-06-10
