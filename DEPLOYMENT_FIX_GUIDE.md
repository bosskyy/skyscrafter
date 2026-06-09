# Post-Deployment Instructions (Setelah Push)

## ⚡ Quick Start - Jalankan Satu Command Ini:

```bash
php artisan images:check --fix
```

Selesai! Sistem akan otomatis:
- ✓ Membuat storage symlink
- ✓ Migrasi gambar lama (jika ada)
- ✓ Set permissions yang tepat
- ✓ Diagnostik dan report

---

## Atau Manual Commands (Jika Diperlukan):

Setelah pull code terbaru, jalankan di server production:

```bash
cd /path/to/percetakan-app

# 1. Auto-check dan auto-fix semua image issues
php artisan images:check --fix

# 2. Atau hanya diagnose tanpa fix
php artisan images:check

# 3. Create storage link (jika belum ada)
php artisan storage:link

# 4. Set permissions
chmod -R 755 storage/app/public
chmod -R 755 public/storage

# 5. Clear caches
php artisan cache:clear
php artisan config:clear
```

---

## ✨ Apa Yang Berubah (Kenapa Sekarang Bekerja):

### 1. **Auto-Create Symlink** 
   - Symlink akan otomatis dibuat saat app startup
   - Tidak perlu manual command lagi

### 2. **Smart Image Helper**
   - Menggunakan helper `imageUrl()` untuk display gambar
   - Otomatis detect path yang benar
   - Support fallback jika symlink belum ready

### 3. **Fallback Image Server**
   - Jika symlink gagal, route `/api/images/{path}` akan melayani gambar
   - Gambar tetap bisa diakses bahkan tanpa symlink

### 4. **Diagnostic Command**
   - `php artisan images:check` untuk melihat status
   - `php artisan images:check --fix` untuk auto-repair

### 5. **Backward Compatibility**
   - Gambar lama di `public/images/` masih bisa diakses
   - Sistem akan auto-migrate jika diminta

---

## 🔧 Troubleshooting:

### ❌ Gambar masih tidak muncul?

Jalankan diagnostic command:
```bash
php artisan images:check --fix
```

Ini akan:
1. Check apakah storage directory ada
2. Check apakah symlink sudah dibuat
3. Check permissions
4. Check files yang ada
5. Auto-fix semua issues

### ❌ Upload di admin tidak bekerja?

```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 storage/app/public

# Clear caches
php artisan cache:clear
```

### ❌ 403 Permission Denied Error?

```bash
# Fix ownership (jika di Linux/Mac)
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data public/storage

# Atau ganti www-data dengan user yang menjalankan web server
```

---

## 📋 Checklist Deployment:

- [ ] Pull latest code: `git pull origin main`
- [ ] Run: `php artisan images:check --fix`
- [ ] Test image di website frontend
- [ ] Test upload gambar di admin panel
- [ ] Check nginx/Apache logs jika ada error

---

## 🎯 Important Notes:

✓ **Symlink bukan mandatory** - sistem bisa jalan tanpa symlink
✓ **Otomatis setup** - symlink dibuat otomatis saat app boot
✓ **Fallback built-in** - ada fallback route jika symlink gagal
✓ **Smart detection** - sistem otomatis detect mana path yang harus digunakan
✓ **No manual migration needed** - `--fix` flag akan handle semuanya

---

## 🚀 Testing:

Setelah deployment, test dengan:

```bash
# Check status
php artisan images:check

# Di browser, buka dashboard admin dan coba upload gambar produk
```

Jika masih ada issue, jalankan:
```bash
php artisan images:check --fix
```
