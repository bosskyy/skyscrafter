#!/bin/bash

# Post-Deployment Script untuk Percetakan App
# Jalankan di server production setelah push

echo "=========================================="
echo "Percetakan App - Post Deployment Script"
echo "=========================================="
echo ""

# 1. Create Symbolic Link
echo "[1/5] Creating symbolic link for storage..."
php artisan storage:link
if [ $? -eq 0 ]; then
    echo "✓ Symbolic link created successfully"
else
    echo "✗ Failed to create symbolic link"
fi
echo ""

# 2. Set Permissions
echo "[2/5] Setting proper permissions..."
chmod -R 755 storage/app/public
chmod -R 755 public/storage
echo "✓ Permissions set"
echo ""

# 3. Create products directory if not exists
echo "[3/5] Creating storage directories..."
mkdir -p storage/app/public/products
chmod -R 755 storage/app/public/products
echo "✓ Directories created"
echo ""

# 4. Migrate old images if they exist
echo "[4/5] Checking for legacy images to migrate..."
if [ -d "public/images" ]; then
    echo "Found legacy images in public/images, copying to storage..."
    find public/images -type f \( -name "*.png" -o -name "*.jpg" -o -name "*.jpeg" -o -name "*.webp" \) -exec cp {} storage/app/public/products/ \; 2>/dev/null
    echo "✓ Legacy images migrated"
else
    echo "No legacy images found"
fi
echo ""

# 5. Clear caches
echo "[5/5] Clearing application caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:cache
echo "✓ Caches cleared"
echo ""

# Verification
echo "=========================================="
echo "Verification:"
echo "=========================================="
echo ""
echo "Checking symlink..."
if [ -L "public/storage" ]; then
    echo "✓ Symbolic link exists"
    ls -la public/storage | head -1
else
    echo "✗ Symbolic link not found!"
fi
echo ""

echo "Checking storage directories..."
if [ -d "storage/app/public/products" ]; then
    echo "✓ Products directory exists"
    echo "  Files: $(find storage/app/public/products -type f | wc -l)"
else
    echo "✗ Products directory not found!"
fi
echo ""

echo "=========================================="
echo "✓ Post-deployment completed!"
echo "=========================================="
echo ""
echo "Your images should now be accessible at:"
echo "/storage/products/filename.jpg"
