# Hướng dẫn Deploy Laravel lên 1Panel

## Yêu cầu hệ thống trên 1Panel:

### 1. **PHP Requirements** (Bắt buộc)
- PHP >= 8.1 (khuyến nghị PHP 8.2 hoặc 8.3)
- Các PHP Extensions cần thiết:
  - `php-fpm`
  - `php-cli`
  - `php-mbstring`
  - `php-xml`
  - `php-curl`
  - `php-zip`
  - `php-gd`
  - `php-mysql` hoặc `php-mysqli` hoặc `php-pdo_mysql`
  - `php-fileinfo`
  - `php-openssl`
  - `php-tokenizer`
  - `php-json`
  - `php-pcre`

### 2. **Web Server**
- Nginx (khuyến nghị) hoặc Apache
- Đã cấu hình sẵn trên 1Panel

### 3. **Database**
- MySQL >= 5.7 hoặc MariaDB >= 10.3
- Hoặc PostgreSQL (nếu dùng)

### 4. **Composer**
- Composer (để cài dependencies)

### 5. **Node.js và NPM** (nếu có assets cần build)
- Node.js >= 16.x
- NPM

---

## Các bước deploy:

### Bước 1: Chuẩn bị trên máy local

1. **Export Database:**
   ```bash
   php artisan migrate:fresh --seed
   php artisan db:export > database.sql
   ```

2. **Zip code (bỏ các thư mục không cần):**
   ```bash
   zip -r deploy.zip . \
     -x "*.git*" \
     -x "*.env*" \
     -x "node_modules/*" \
     -x "vendor/*" \
     -x "storage/logs/*" \
     -x "storage/framework/cache/*" \
     -x "storage/framework/sessions/*" \
     -x "storage/framework/views/*"
   ```

### Bước 2: Trên 1Panel

1. **Tạo Website:**
   - Vào "Website" > "Create Website"
   - Chọn PHP version (8.1+)
   - Chọn domain hoặc subdomain
   - Đặt Document Root: `/www/wwwroot/your-domain/public`

2. **Tạo Database:**
   - Vào "Database" > "Create Database"
   - Ghi lại: Database name, username, password

3. **Upload code:**
   - Vào "Files" > chọn thư mục website
   - Upload file `deploy.zip` và giải nén
   - Upload thêm `public/uploads.zip` (nếu có) và giải nén vào `public/`

### Bước 3: Cấu hình trên Server

1. **SSH vào server và chạy:**

   ```bash
   cd /www/wwwroot/your-domain
   
   # Cài Composer dependencies
   composer install --no-dev --optimize-autoloader
   
   # Tạo file .env
   cp .env.example .env
   php artisan key:generate
   
   # Cấu hình .env với database credentials
   nano .env
   # Hoặc dùng editor trên 1Panel
   ```

2. **Cấu hình .env:**
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

3. **Import Database:**
   ```bash
   mysql -u your_database_user -p your_database_name < database.sql
   ```

4. **Chạy migrations và optimize:**
   ```bash
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```

5. **Set permissions:**
   ```bash
   chmod -R 755 storage
   chmod -R 755 bootstrap/cache
   chown -R www:www storage
   chown -R www:www bootstrap/cache
   chmod -R 755 public/uploads
   chown -R www:www public/uploads
   ```

### Bước 4: Cấu hình Nginx trên 1Panel

1. Vào "Website" > chọn website > "Settings" > "Nginx Config"

2. Thêm cấu hình sau vào phần `server` block:

   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   
   location ~ \.php$ {
       fastcgi_pass unix:/tmp/php-cgi-XX.sock; # Thay XX bằng PHP version
       fastcgi_index index.php;
       fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
       include fastcgi_params;
   }
   
   location ~ /\.(?!well-known).* {
       deny all;
   }
   ```

### Bước 5: Kiểm tra

1. Vào website và kiểm tra xem có hoạt động không
2. Kiểm tra ảnh có hiển thị không (từ `public/uploads/`)
3. Kiểm tra admin panel: `/admin`

---

## Lưu ý quan trọng:

1. **File .env:**
   - Không commit file `.env` lên git
   - Phải tạo `.env` mới trên server

2. **Storage:**
   - Thư mục `storage/` và `bootstrap/cache/` phải có quyền ghi
   - Tạo symbolic link: `php artisan storage:link` (nếu dùng storage)

3. **PHP Settings:**
   - `upload_max_filesize`: ít nhất 2MB (hoặc 5MB nếu muốn)
   - `post_max_size`: ít nhất 8MB
   - `memory_limit`: ít nhất 256M

4. **SSL Certificate:**
   - Cài SSL trên 1Panel để dùng HTTPS

5. **Cron Jobs:**
   - Thêm cron job trên 1Panel:
     ```bash
     * * * * * cd /www/wwwroot/your-domain && php artisan schedule:run >> /dev/null 2>&1
     ```

---

## Troubleshooting:

- **Lỗi 500:** Kiểm tra logs trong `storage/logs/laravel.log`
- **Lỗi permission:** Chạy lại `chmod` và `chown` cho storage, bootstrap/cache
- **Ảnh không hiển thị:** Kiểm tra quyền của `public/uploads/` và đường dẫn trong database
- **Database connection error:** Kiểm tra thông tin DB trong `.env`

