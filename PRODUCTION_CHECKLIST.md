# Production Checklist dan Best Practices

## 🔐 Security Checklist

### Environment & Configuration
- [ ] APP_DEBUG = false
- [ ] APP_ENV = production  
- [ ] APP_KEY sudah generate (php artisan key:generate)
- [ ] .env file TIDAK di-commit ke git
- [ ] .env file permissions = 644 (tidak readable by others)
- [ ] .gitignore sudah include .env

### Database Security
- [ ] Database user dengan password STRONG (min 12 karakter)
- [ ] Database user dengan MINIMAL privileges (hanya DB tertentu)
- [ ] Database credentials TIDAK di-hardcode
- [ ] Database backup schedule sudah setup
- [ ] Database encryption ON (jika perlu)

### File & Folder Permissions
- [ ] storage/ folder = 755 (writable by web server)
- [ ] bootstrap/cache/ folder = 755
- [ ] .env file = 644 (read-only)
- [ ] public/ folder = 755
- [ ] config/ folder TIDAK writable

### Web Server & SSL
- [ ] SSL Certificate installed (HTTPS)
- [ ] HTTP redirects ke HTTPS
- [ ] Document Root = public/ folder
- [ ] Index file = public/index.php
- [ ] Directory listing DISABLED (.htaccess)
- [ ] .env file access BLOCKED

### Application Security
- [ ] CORS headers configured (jika API)
- [ ] CSRF protection ENABLED
- [ ] SQL Injection protection (use Eloquent)
- [ ] XSS protection (use Blade escaping)
- [ ] Rate limiting configured
- [ ] Input validation on all forms
- [ ] File upload size limited
- [ ] File upload extensions whitelist
- [ ] Authentication middleware configured

### Dependencies
- [ ] composer install --no-dev (production only)
- [ ] No dev packages di production
- [ ] Dependencies sudah update (security patches)
- [ ] vendor/ folder TIDAK accessible via web

---

## ⚡ Performance Checklist

### Caching & Optimization
- [ ] php artisan config:cache (dijalankan)
- [ ] php artisan route:cache (dijalankan)
- [ ] php artisan view:cache (dijalankan)
- [ ] CACHE_STORE = database (atau Redis)
- [ ] LOG_LEVEL = notice (debug info tidak di-log)

### Database
- [ ] Database indexes sudah disetup
- [ ] Queries di-optimize (no N+1 queries)
- [ ] Database connection pooling (jika perlu)
- [ ] Query caching (Redis) enabled

### Frontend
- [ ] CSS/JS di-minify dan di-compile (npm run build)
- [ ] Images di-optimize
- [ ] Lazy loading untuk images
- [ ] CDN configured (jika ada)

### Server
- [ ] PHP OPcache ENABLED
- [ ] Gzip compression ENABLED
- [ ] HTTP/2 ENABLED (jika supported)
- [ ] Server memory adequate
- [ ] CPU resources monitored

---

## 📋 Deployment Checklist

### Before Deployment
- [ ] Kode sudah di-test (unit tests)
- [ ] Database migrations sudah di-test
- [ ] .env.example sudah updated
- [ ] Documentation sudah complete
- [ ] Backup database sudah di-buat
- [ ] Backup files sudah di-buat

### Upload & Setup
- [ ] Semua files sudah uploaded (except .env, vendor/, node_modules/)
- [ ] Permissions sudah di-set (755 untuk folders, 644 untuk files)
- [ ] .env sudah di-buat dan di-configure
- [ ] Database sudah di-buat
- [ ] php artisan migrate --force sudah dijalankan
- [ ] composer install sudah dijalankan
- [ ] npm install && npm run build sudah dijalankan (jika ada)

### Testing
- [ ] Homepage loading correctly
- [ ] All forms submitting correctly
- [ ] Database reads working
- [ ] Database writes working
- [ ] File uploads working
- [ ] Error handling working (check logs)
- [ ] Email sending working (jika ada)
- [ ] Authentication working (jika ada)

### Monitoring
- [ ] Error logs accessible
- [ ] Performance monitored
- [ ] Uptime monitoring setup
- [ ] Backup verification schedule

---

## 🚨 Error Handling & Logging

### Logging Configuration
```php
// config/logging.php
LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=notice  // Production: notice, Local: debug
```

### View Logs
```bash
# Via SSH
tail -f storage/logs/laravel.log

# Or check last 100 lines
tail -100 storage/logs/laravel.log
```

### Common Issues
- **500 Error**: Check storage/logs/laravel.log
- **Database Error**: Verify .env DB credentials
- **Permission Error**: Check folder permissions
- **Class Not Found**: Run `composer dump-autoload -o`

---

## 📊 Monitoring & Maintenance

### Weekly Tasks
- [ ] Check error logs
- [ ] Verify database backups
- [ ] Monitor disk space
- [ ] Check performance metrics

### Monthly Tasks
- [ ] Update dependencies (composer update)
- [ ] Review security logs
- [ ] Test backup restoration
- [ ] Performance optimization review

### Quarterly Tasks
- [ ] Security audit
- [ ] Database optimization
- [ ] Code review
- [ ] Infrastructure review

---

## 🔄 Update & Maintenance

### Safe Deployment Steps
```bash
# 1. Create backup
# 2. Test updates locally
# 3. Upload updated files (except .env)
# 4. Run migrations (if any)
php artisan migrate --force

# 5. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 6. Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Verify deployment
# Test all critical features
```

---

## 📞 Hostinger-Specific Notes

### Default Credentials
- **cPanel**: yourusername:password
- **MySQL Host**: localhost (atau IP yang diberikan)
- **FTP/SFTP**: yourusername:password

### Key Features
- **File Manager**: Untuk upload files tanpa FTP
- **Terminal**: Untuk menjalankan artisan commands
- **phpMyAdmin**: Untuk manage database
- **Auto SSL**: Untuk HTTPS (Let's Encrypt)
- **Backup**: Automatic daily backups

### Limits (Hostinger Shared Hosting)
- **PHP Max Upload**: Usually 128MB (check limit)
- **Execution Time**: Usually 300 seconds
- **Memory**: Usually 256MB (check limit)
- **Database**: Unlimited (tergantung plan)

### Optimization untuk Shared Hosting
- Disable email queue (direct sending)
- Use database cache (Redis mungkin terbatas)
- Limit log retention
- Regular cleanup temporary files
- Monitor disk space usage

---

## ✅ Final Verification

Sebelum declare production ready:

```bash
# 1. Test homepage
curl -I https://yourdomain.com

# 2. Check SSL
openssl s_client -connect yourdomain.com:443

# 3. View error logs
tail -f storage/logs/laravel.log

# 4. Test database
php artisan tinker
> DB::connection()->getPdo();

# 5. Test cache
php artisan tinker
> Cache::put('test', 'value'); Cache::get('test');

# 6. Check disk space
df -h

# 7. Monitor resources
top -b -n 1
```

---

**Status**: ✅ Ready for Production
**Last Updated**: 2026-06-10
**Next Review**: 2026-07-10
