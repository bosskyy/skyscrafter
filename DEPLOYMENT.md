# Panduan Deployment ke Hostinger

## 1. Konfigurasi `.env` Production

Copy `.env.production.example` ke `.env` di Hostinger:

```bash
cp .env.production.example .env
```

Update dengan credentials MySQL Hostinger Anda:
```env
APP_NAME="Percetakan App"
APP_ENV=production
APP_URL=https://your-domain.com
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_mysql_user
DB_PASSWORD=your_mysql_password
```

> **Cara mendapat credentials:**
> - Login cPanel → MySQL Databases
> - Lihat bagian "Current Databases" atau buat baru
> - Copy hostname (biasanya `localhost`), username, password

---

## 2. Generate APP_KEY (jika belum ada)

```bash
php artisan key:generate --force
```

---

## 3. Jalankan Migrations

```bash
php artisan migrate --force
```

Output yang diharapkan:
```
  Illuminate\Database\Migrations\Migration  
  ...✓ create_users_table
  ...✓ create_products_table
  ...✓ create_categories_table
  ...✓ create_orders_table
  ... (9 migrations total)
```

---

## 4. Seed Database Dengan Data Produk & User Admin

```bash
php artisan db:seed --force
```

**Ini akan membuat:**
- Admin user: `admin@percetakan.com` / password: `admin123456`
- Test customer: `customer@example.com` / password: `customer123456`
- 7 produk layanan percetakan

---

## 5. Clear Cache & Config

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

---

## 6. Set File Permissions (jika perlu)

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public
```

---

## Troubleshooting

### ✗ "No connection could be made to MySQL"
- Cek username & password di `.env`
- Cek DB_HOST (biasanya `localhost` atau IP address)
- Pastikan database sudah dibuat di cPanel

### ✗ "SQLSTATE[HY000] [1045] Access denied for user"
- Login cPanel → MySQL Databases
- Verify username & password sudah benar
- Buat user baru jika perlu: User: `your_dbname_user`, Password: `random`
- Grant privileges ke database

### ✗ "Layanan masih tidak muncul setelah migrations"
- Cek error log: 
  ```bash
  tail -f storage/logs/laravel.log
  ```
- Pastikan seeder berhasil jalan: 
  ```bash
  php artisan db:seed --force --verbose
  ```
- Cek database punya data:
  ```bash
  php artisan tinker
  > App\Models\Product::count()
  ```

### ✗ "502 Bad Gateway" atau "500 Error"
- Cek PHP version (minimal PHP 8.1)
- Enable extensions: `php.ini` harus punya: `mbstring`, `pdo_mysql`, `zip`
- Cek `.htaccess` di public folder

### ✗ "Login admin tidak bisa"
- Cek apakah seeders jalan
- Reset password:
  ```bash
  php artisan tinker
  > $user = App\Models\User::where('email', 'admin@percetakan.com')->first();
  > $user->password = Hash::make('admin123456');
  > $user->save();
  > exit
  ```

---

## Verify Deployment

Setelah semua langkah, jalankan:

```bash
# Cek produk tersimpan
php artisan tinker
> App\Models\Product::count()
> 7  (harus 7 produk)

> App\Models\User::count()
> 2  (harus 2 user: admin + customer)

exit
```

Akses website:
- **Frontend:** https://your-domain.com/layanan
- **Admin Login:** https://your-domain.com/login
  - Email: `admin@percetakan.com`
  - Password: `admin123456`

---

## Jika Masih Ada Error

1. **Check Laravel log:**
   ```bash
   cat storage/logs/laravel.log
   ```

2. **Check PHP error log:**
   - cPanel → File Manager → `/home/username/public_html/`
   - Cari file `error_log`

3. **Enable debug mode sementara (JANGAN di production final):**
   ```env
   APP_DEBUG=true
   ```
   
4. **Hubungi Hostinger Support** dengan:
   - Error message lengkap
   - Output dari `php artisan migrate --force`
   - PHP version & extensions terinstall

---

## Setelah Go-Live

**PENTING: Disable debug mode:**
```env
APP_DEBUG=false
APP_ENV=production
```

**SECURITY:**
- Change admin password:
  ```bash
  php artisan tinker
  > $user = App\Models\User::find(1);
  > $user->password = Hash::make('new-secure-password');
  > $user->save();
  ```
- Enable HTTPS di cPanel → SSL/TLS
- Set strong `.env` values untuk semua keys

---

## Commands Bermanfaat untuk Production

```bash
# Cek status semua
php artisan doctor

# Optimize untuk production
php artisan optimize

# Backup database
mysqldump -u username -p database_name > backup.sql

# Maintenance mode (jika perlu update)
php artisan down
php artisan up
```
