# Post-Deployment Instructions (Setelah Push)

Setelah push ke server production, jalankan command berikut **di server production**:

## Commands untuk Jalankan di Server:

```bash
# 1. Navigate ke project folder
cd /path/to/percetakan-app

# 2. Create Symbolic Link untuk storage (PENTING!)
php artisan storage:link

# 3. Set proper permissions
chmod -R 755 storage/app/public
chmod -R 755 public/storage

# 4. Jika ada gambar lama di public/images, copy ke storage
cp -r public/images/Pas\ foto.png storage/app/public/products/ 2>/dev/null
cp -r public/images/Fotocopy*.png storage/app/public/products/ 2>/dev/null
cp -r public/images/Jilid.png storage/app/public/products/ 2>/dev/null
cp -r public/images/Laminating.png storage/app/public/products/ 2>/dev/null
cp -r public/images/Undangan.png storage/app/public/products/ 2>/dev/null
cp -r public/images/Polaroid*.png storage/app/public/products/ 2>/dev/null
cp -r public/images/Gantungan\ kunci\ photostrip.png storage/app/public/products/ 2>/dev/null

# 5. Clear cache
php artisan cache:clear
php artisan config:clear

# 6. (Optional) Verify symlink
ls -la public/storage
```

## Atau Menggunakan Script Shell:

Simpan command di atas ke file `deploy.sh` dan jalankan:

```bash
bash deploy.sh
```

## Troubleshooting:

Jika gambar masih tidak muncul:
1. Pastikan symlink sudah dibuat: `ls -la public/storage` harus ada
2. Pastikan storage folder writable: `chmod -R 755 storage`
3. Cek URL yang di-generate: `echo asset('storage/products/test.png')` di tinker
4. Restart web server: `sudo service nginx restart` atau `sudo systemctl restart apache2`

## Important Notes:

- Storage link wajib ada untuk Laravel bekerja dengan file upload
- Gambar baru akan otomatis disimpan di `storage/app/public/products`
- URL akan otomatis menjadi `/storage/products/filename.jpg`
