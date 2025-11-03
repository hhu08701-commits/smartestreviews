# Hướng dẫn Deploy Đầy Đủ lên 1Panel/Hosting

## ✅ Trạng thái hiện tại

### 1. Ảnh đã được lưu local
- ✅ Đã chạy `php artisan images:download-and-store`
- ✅ Tất cả ảnh đã được download và lưu vào `public/uploads/`
- ✅ Database đã được cập nhật với local paths
- ✅ File zip đã được tạo: `public/uploads.zip` (12MB)

### 2. Favicon
- ✅ Đã thêm favicon vào layout (`app.blade.php`)
- ⚠️ Cần đảm bảo file `favicon.png` hoặc `favicon.ico` có trong `public/`

---

## 📦 Chuẩn bị trước khi deploy

### Bước 1: Export Database
```bash
php artisan db:export > database.sql
# Hoặc dùng MySQL/MariaDB:
mysqldump -u username -p database_name > database.sql
```

### Bước 2: Tạo file zip uploads (nếu chưa có)
```bash
./zip-uploads.sh
# File sẽ được tạo tại: public/uploads.zip
```

### Bước 3: Chuẩn bị file deploy
Các file cần upload:
- ✅ Code Laravel (trừ `vendor/`, `node_modules/`, `.env`)
- ✅ `public/uploads.zip`
- ✅ `database.sql`
- ✅ `favicon.png` hoặc `favicon.ico` vào `public/`

---

## 🚀 Deploy lên 1Panel/Hosting

### Bước 1: Upload Code
1. Zip code (loại trừ các thư mục không cần):
   ```bash
   zip -r deploy.zip . \
     -x "*.git*" \
     -x "*.env*" \
     -x "node_modules/*" \
     -x "vendor/*" \
     -x "storage/logs/*" \
     -x "storage/framework/cache/*" \
     -x "storage/framework/sessions/*" \
     -x "storage/framework/views/*" \
     -x ".DS_Store"
   ```

2. Upload và giải nén lên hosting vào thư mục website

### Bước 2: Upload và giải nén ảnh
1. Upload `public/uploads.zip` lên hosting vào thư mục `public/`
2. SSH vào server:
   ```bash
   cd /www/wwwroot/your-domain/public
   unzip uploads.zip
   chmod -R 755 uploads/
   chown -R www:www uploads/
   ```

### Bước 3: Cài đặt Dependencies
```bash
cd /www/wwwroot/your-domain
composer install --no-dev --optimize-autoloader
```

### Bước 4: Cấu hình .env
```bash
cp .env.example .env
php artisan key:generate
nano .env  # Hoặc dùng editor trên 1Panel
```

Cấu hình `.env`:
```env
APP_NAME=Smartest Reviews
APP_ENV=production
APP_KEY=base64:... (từ key:generate)
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### Bước 5: Import Database
```bash
mysql -u your_database_user -p your_database_name < database.sql
```

### Bước 6: Chạy Migrations và Optimize
```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Bước 7: Set Permissions
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public/uploads
chown -R www:www storage
chown -R www:www bootstrap/cache
chown -R www:www public/uploads
```

### Bước 8: Upload Favicon
Đảm bảo file `favicon.png` hoặc `favicon.ico` có trong `public/`:
```bash
# Upload favicon.png vào public/
```

### Bước 9: Cấu hình Nginx (nếu cần)
Trong 1Panel, vào Website → Settings → Nginx Config, đảm bảo có:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/tmp/php-cgi-XX.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

---

## ✅ Kiểm tra sau khi deploy

1. ✅ Truy cập website - kiểm tra ảnh hiển thị
2. ✅ Kiểm tra favicon hiển thị trong browser tab
3. ✅ Kiểm tra admin panel: `/admin`
4. ✅ Kiểm tra upload ảnh mới trong admin có hoạt động không

---

## 📝 Lưu ý quan trọng

1. **File size limits**: Đảm bảo PHP settings cho phép upload ít nhất 2MB:
   ```ini
   upload_max_filesize = 2M
   post_max_size = 8M
   memory_limit = 256M
   ```

2. **Permissions**: Thư mục `storage/`, `bootstrap/cache/`, và `public/uploads/` phải có quyền ghi

3. **SSL**: Cài SSL certificate trên 1Panel để dùng HTTPS

4. **Cron Jobs**: Thêm cron job nếu cần:
   ```bash
   * * * * * cd /www/wwwroot/your-domain && php artisan schedule:run >> /dev/null 2>&1
   ```

---

## 🐛 Troubleshooting

- **Lỗi 500**: Kiểm tra `storage/logs/laravel.log`
- **Ảnh không hiển thị**: Kiểm tra quyền `public/uploads/` và đường dẫn trong database
- **Favicon không hiển thị**: Đảm bảo file có trong `public/` và path đúng trong layout
- **Upload ảnh không được**: Kiểm tra PHP upload limits và quyền thư mục


























