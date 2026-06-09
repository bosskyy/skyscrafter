#!/bin/bash

# Production Deployment Script untuk Percetakan App
# Jalankan: bash deploy-production.sh

set -e  # Exit on error

echo "================================"
echo "Percetakan App - Production Deploy"
echo "================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Update Dependencies
echo -e "\n${YELLOW}[1] Installing PHP Dependencies...${NC}"
composer install --no-dev --optimize-autoloader
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Composer dependencies installed${NC}"
else
    echo -e "${RED}✗ Failed to install composer dependencies${NC}"
    exit 1
fi

# 2. Generate Key (jika belum)
echo -e "\n${YELLOW}[2] Checking Application Key...${NC}"
if grep -q "APP_KEY=" .env && ! grep -q "APP_KEY=$" .env; then
    echo -e "${GREEN}✓ APP_KEY sudah ada${NC}"
else
    echo -e "${YELLOW}Generating new APP_KEY...${NC}"
    php artisan key:generate
fi

# 3. Database Migration
echo -e "\n${YELLOW}[3] Running Database Migrations...${NC}"
php artisan migrate --force
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Database migrations completed${NC}"
else
    echo -e "${RED}✗ Database migrations failed${NC}"
    exit 1
fi

# 4. Seed Database (optional)
echo -e "\n${YELLOW}[4] Seeding Database (Optional)...${NC}"
read -p "Run database seeder? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan db:seed
    echo -e "${GREEN}✓ Database seeded${NC}"
fi

# 5. Optimize Application
echo -e "\n${YELLOW}[5] Optimizing Application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
echo -e "${GREEN}✓ Application optimized${NC}"

# 6. Set Permissions
echo -e "\n${YELLOW}[6] Setting File Permissions...${NC}"
chmod -R 755 storage bootstrap/cache
chmod 644 .env
echo -e "${GREEN}✓ Permissions set${NC}"

# 7. Clear Logs (optional)
echo -e "\n${YELLOW}[7] Clearing Old Logs...${NC}"
php artisan optimize:clear
echo -e "${GREEN}✓ Logs cleared${NC}"

# 8. Summary
echo -e "\n${GREEN}================================"
echo "✓ Production Deployment Complete!"
echo "================================${NC}"
echo -e "\nNext steps:"
echo "1. ✓ Test website functionality"
echo "2. ✓ Monitor storage/logs/laravel.log"
echo "3. ✓ Setup SSL certificate (if not done)"
echo "4. ✓ Configure firewall rules"
echo "5. ✓ Setup monitoring & alerts"
echo ""
