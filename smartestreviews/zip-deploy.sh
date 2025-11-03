#!/bin/bash

# Script để zip code Laravel để deploy (loại trừ vendor, node_modules, .env, etc.)

echo "📦 Đang zip code để deploy..."

# Tên file zip
ZIP_FILE="deploy-$(date +%Y%m%d-%H%M%S).zip"

# Zip code, loại trừ các thư mục/file không cần
zip -r "$ZIP_FILE" . \
    -x "*.git*" \
    -x "*.env*" \
    -x "node_modules/*" \
    -x "vendor/*" \
    -x "storage/logs/*" \
    -x "storage/framework/cache/*" \
    -x "storage/framework/sessions/*" \
    -x "storage/framework/views/*" \
    -x "storage/framework/testing/*" \
    -x ".DS_Store" \
    -x ".idea/*" \
    -x "*.zip" \
    -x "public/uploads/*" \
    -x "public/uploads.zip" \
    -x "tests/*" \
    -x "*.md" \
    -x "phpunit.xml" \
    -x ".phpunit.result.cache"

if [ -f "$ZIP_FILE" ]; then
    SIZE=$(du -h "$ZIP_FILE" | cut -f1)
    echo ""
    echo "✅ Đã tạo file zip thành công!"
    echo "📊 Kích thước: $SIZE"
    echo "📍 File: $(pwd)/$ZIP_FILE"
    echo ""
    echo "📝 Lưu ý:"
    echo "1. File này KHÔNG bao gồm vendor/, node_modules/, .env"
    echo "2. Sau khi upload lên hosting, chạy: composer install --no-dev"
    echo "3. Upload riêng file public/uploads.zip và giải nén vào public/"
    echo "4. Upload file favicon.png vào public/"
else
    echo "❌ Lỗi: Không thể tạo file zip"
    exit 1
fi

























