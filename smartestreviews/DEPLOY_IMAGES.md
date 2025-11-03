# Hướng dẫn Deploy Ảnh

## ✅ Tình trạng hiện tại (Đã hoàn thành)

✅ Đã chạy command `php artisan images:download-and-store` để download và lưu tất cả ảnh từ URL về local storage.

📊 **Tổng kết ảnh đã lưu:**
- ✅ Posts: 24 ảnh
- ✅ Product Showcases: 1 ảnh
- ✅ Breaking News: 3 ảnh
- ✅ Slideshow: 3 ảnh
- ✅ Hot Products: 4 ảnh

**Tổng cộng: 35 ảnh (12MB)** → File zip: `public/uploads.zip`

## Các thư mục ảnh đã được tạo trong `public/uploads/`:

- `public/uploads/posts/` - Ảnh featured của Posts
- `public/uploads/products/` - Ảnh của Product Showcases  
- `public/uploads/breaking-news/` - Ảnh của Breaking News
- `public/uploads/slideshow/` - Ảnh của Slideshow
- `public/uploads/hot-products/` - Ảnh của Hot Products

## Cách Zip và Deploy lên Hosting

1. **Zip toàn bộ thư mục `public/uploads/`**:
   ```bash
   cd public
   zip -r uploads.zip uploads/
   ```

2. **Upload file `uploads.zip` lên hosting** vào thư mục `public/`

3. **Giải nén trên hosting**:
   ```bash
   cd public
   unzip uploads.zip
   ```

4. **Kiểm tra quyền**:
   ```bash
   chmod -R 755 public/uploads/
   ```

## Lưu ý quan trọng

- Tất cả ảnh hiện tại đã được cập nhật trong database với path local (ví dụ: `/uploads/posts/filename.jpg`)
- Khi deploy, đảm bảo thư mục `public/uploads/` được upload đầy đủ
- Database đã được cập nhật với local paths, nên khi deploy lên hosting, ảnh sẽ hiển thị ngay

## Chạy lại command nếu cần

Nếu có thêm ảnh mới từ URL, chạy lại command:
```bash
php artisan images:download-and-store
```

Hoặc force re-download:
```bash
php artisan images:download-and-store --force
```

