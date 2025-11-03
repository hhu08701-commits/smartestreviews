# 🚀 Hướng Dẫn Deploy Lên Hosting

## 📋 Tổng quan

Tất cả ảnh đã được lưu local vào database và file zip đã được tạo sẵn.

### ✅ Đã hoàn thành:
- ✅ Download và lưu 35 ảnh vào `public/uploads/` (12MB)
- ✅ Cập nhật database với local paths
- ✅ Tạo file zip: `public/uploads.zip`
- ✅ Thêm favicon vào layout
- ✅ Copy `favicon.png` vào `public/`

---

## 📦 Files cần upload lên hosting

### 1. Code Laravel
```bash
# Chạy script để tạo zip code:
./zip-deploy.sh

# File sẽ được tạo: deploy-YYYYMMDD-HHMMSS.zip
```

### 2. Ảnh (đã zip sẵn)
- File: `public/uploads.zip` (12MB)
- Upload vào `public/` và giải nén

### 3. Favicon
- File: `public/favicon.png` (1.2MB)
- Upload vào `public/`

### 4. Database
- File: `database.sql` (export từ local)
- Dùng để import vào hosting

---

## 🚀 Các bước deploy chi tiết

### Bước 1: Chuẩn bị Files
```bash
# 1. Export database
mysqldump -u user -p database > database.sql

# 2. Zip code (đã có script)
./zip-deploy.sh

# Files sẽ có:
# - deploy-YYYYMMDD-HHMMSS.zip
# - public/uploads.zip (đã có sẵn)
# - public/favicon.png (đã có sẵn)
# - database.sql
```

### Bước 2: Upload lên Hosting
1. Upload `deploy-YYYYMMDD-HHMMSS.zip` và giải nén
2. Upload `public/uploads.zip` vào `public/`
3. Upload `public/favicon.png` vào `public/`

### Bước 3: SSH vào Server và cài đặt
```bash
cd /www/wwwroot/your-domain

# Giải nén uploads
cd public
unzip uploads.zip
chmod -R 755 uploads/
chown -R www:www uploads/
cd ..

# Cài dependencies
composer install --no-dev --optimize-autoloader

# Tạo .env
cp .env.example .env
php artisan key:generate

# Cấu hình .env với database info
nano .env

# Import database
mysql -u user -p database < database.sql

# Migrate và optimize
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Set permissions
chmod -R 755 storage bootstrap/cache public/uploads
chown -R www:www storage bootstrap/cache public/uploads
```

---

## ✅ Kiểm tra sau deploy

1. ✅ Website load: `https://your-domain.com`
2. ✅ Ảnh hiển thị: Kiểm tra posts, products, breaking news
3. ✅ Favicon hiển thị: Check browser tab
4. ✅ Admin panel: `/admin`
5. ✅ Upload ảnh: Test upload ảnh mới trong admin

---

## 📝 Chi tiết xem thêm

- **Hướng dẫn deploy đầy đủ**: `DEPLOY_FULL.md`
- **Hướng dẫn deploy ảnh**: `DEPLOY_IMAGES.md`
- **Checklist deploy**: `DEPLOY_CHECKLIST.md`
- **Hướng dẫn 1Panel**: `DEPLOY_1PANEL.md`

---

## 🎯 Tổng kết Files

| File | Vị trí | Mô tả |
|------|--------|-------|
| `deploy-*.zip` | Root | Code Laravel (tạo bởi `zip-deploy.sh`) |
| `public/uploads.zip` | `public/` | 35 ảnh đã được zip (12MB) |
| `public/favicon.png` | `public/` | Favicon logo |
| `database.sql` | Root | Export database |

**Lưu ý**: Tất cả ảnh đã được lưu trong database với path local (`/uploads/...`), nên khi deploy lên hosting với thư mục `public/uploads/` thì ảnh sẽ hiển thị ngay!

























