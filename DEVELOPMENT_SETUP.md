# Local Development Setup - Setelah Clone Project

Jalankan command ini ketika pertama kali clone project atau setelah update code:

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Create database
php artisan migrate

# 4. **PENTING: Create Storage Link (untuk image upload)**
php artisan storage:link

# 5. Seed data (opsional)
php artisan db:seed

# 6. Start server
php artisan serve
# dan di terminal lain:
npm run dev
```

## Jika symlink storage sudah ada tapi tidak bekerja:

```bash
# Hapus symlink lama
rm public/storage

# Buat symlink baru
php artisan storage:link

# Atau manual:
ln -s storage/app/public public/storage
```

## Folder Structure untuk File Upload:

```
storage/
├── app/
│   ├── public/
│   │   ├── products/        ← Gambar produk
│   │   └── ...
│   └── private/
│       └── ...
└── logs/

public/
└── storage → symlink ke storage/app/public
```

## Upload File di Admin:

- Upload gambar produk akan tersimpan di: `storage/app/public/products/`
- URL akses: `asset('storage/products/filename.jpg')` → `/storage/products/filename.jpg`
- Ini akan otomatis di-serve oleh web server melalui symlink
