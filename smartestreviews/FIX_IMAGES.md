# Hướng dẫn khắc phục lỗi ảnh không hiển thị trên production

## Vấn đề
- Máy bạn thấy được ảnh (vì chạy local với APP_URL=http://127.0.0.1:8001)
- Người khác không thấy ảnh (vì APP_URL trên server chưa đúng)

## Nguyên nhân
Laravel dùng `asset()` helper để tạo URL ảnh. Helper này lấy APP_URL từ file `.env`. Nếu APP_URL sai, URL ảnh sẽ bị lỗi.

## Cách khắc phục

### Bước 1: Kiểm tra và sửa APP_URL trên server

SSH vào server và sửa file `.env`:

```bash
# Mở file .env
nano .env

# Tìm dòng APP_URL và sửa thành:
APP_URL=https://smartestreviews.us

# Lưu và thoát (Ctrl+X, Y, Enter)
```

### Bước 2: Clear cache trên server

```bash
cd /path/to/your/project
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
```

### Bước 3: Kiểm tra thư mục uploads

Đảm bảo thư mục `public/uploads/` tồn tại và có quyền đúng:

```bash
# Tạo thư mục nếu chưa có
mkdir -p public/uploads/posts
mkdir -p public/uploads/products
mkdir -p public/uploads/slideshow
mkdir -p public/uploads/hot-products

# Set permissions
chmod -R 755 public/uploads
chown -R www-data:www-data public/uploads  # hoặc user chạy web server của bạn
```

### Bước 4: Đảm bảo file ảnh đã được upload

- Kiểm tra xem file ảnh trong `public/uploads/` trên server có đầy đủ không
- Nếu thiếu, cần upload lại các file ảnh từ local lên server

### Bước 5: Kiểm tra .htaccess (nếu dùng Apache)

Đảm bảo file `public/.htaccess` có quyền truy cập:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

## Kiểm tra nhanh

1. Mở browser và truy cập: `https://smartestreviews.us/uploads/posts/1761971957_4jaiZjR64A.webp`
   - Nếu thấy ảnh → APP_URL đúng, vấn đề có thể là ở code
   - Nếu không thấy → File ảnh chưa được upload hoặc permissions sai

2. Kiểm tra console browser (F12):
   - Xem các URL ảnh bị lỗi 404
   - Kiểm tra xem URL có đúng domain không

## Lưu ý quan trọng

- **APP_URL phải dùng HTTPS** trên production
- **Không có trailing slash** ở cuối APP_URL (ví dụ: `https://smartestreviews.us`, không phải `https://smartestreviews.us/`)
- **Clear cache** sau khi sửa APP_URL

## Test trên local

Có thể test bằng cách tạm thời đổi APP_URL trong `.env` local:

```
APP_URL=https://smartestreviews.us
```

Sau đó clear cache và xem URL ảnh có đúng không.


