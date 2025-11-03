# ✅ Checklist Deploy lên Hosting

## 📦 Chuẩn bị Code

- [ ] Export database: `mysqldump -u user -p database > database.sql`
- [ ] Tạo zip code (loại trừ vendor, node_modules, .env): `zip -r deploy.zip . -x "*.git*" -x "*.env*" -x "node_modules/*" -x "vendor/*" -x "storage/logs/*" -x "storage/framework/cache/*"`
- [ ] File `public/uploads.zip` đã có sẵn (12MB, 35 ảnh)
- [ ] File `favicon.png` đã có trong `public/`

## 🚀 Deploy lên Hosting

### Upload Files
- [ ] Upload và giải nén code Laravel
- [ ] Upload `public/uploads.zip` và giải nén vào `public/`
- [ ] Upload `favicon.png` vào `public/` (nếu chưa có)
- [ ] Upload `database.sql` để import

### Cài đặt
- [ ] Chạy `composer install --no-dev --optimize-autoloader`
- [ ] Tạo `.env` từ `.env.example`
- [ ] Chạy `php artisan key:generate`
- [ ] Cấu hình `.env` với database credentials
- [ ] Import database: `mysql -u user -p database < database.sql`
- [ ] Chạy `php artisan migrate --force`
- [ ] Chạy `php artisan config:cache`
- [ ] Chạy `php artisan route:cache`
- [ ] Chạy `php artisan view:cache`
- [ ] Chạy `php artisan optimize`

### Permissions
- [ ] `chmod -R 755 storage`
- [ ] `chmod -R 755 bootstrap/cache`
- [ ] `chmod -R 755 public/uploads`
- [ ] `chown -R www:www storage`
- [ ] `chown -R www:www bootstrap/cache`
- [ ] `chown -R www:www public/uploads`

### Cấu hình Server
- [ ] Cấu hình Nginx/Apache (nếu cần)
- [ ] Cài SSL Certificate
- [ ] Cấu hình PHP settings (upload_max_filesize = 2M)
- [ ] Thêm cron job (nếu cần)

## ✅ Kiểm tra sau khi deploy

- [ ] Website load được
- [ ] Ảnh hiển thị đầy đủ (Posts, Products, Breaking News, Slideshow, Hot Products)
- [ ] Favicon hiển thị trong browser tab
- [ ] Admin panel hoạt động: `/admin`
- [ ] Login vào admin thành công
- [ ] Upload ảnh mới trong admin hoạt động
- [ ] Không có lỗi trong `storage/logs/laravel.log`

## 📝 Files quan trọng cần có trên hosting

```
your-domain/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── uploads/          ← Từ uploads.zip
│   ├── favicon.png      ← Cần upload
│   └── index.php
├── resources/
├── routes/
├── storage/
├── .env                 ← Cần tạo và cấu hình
└── composer.json
```

























