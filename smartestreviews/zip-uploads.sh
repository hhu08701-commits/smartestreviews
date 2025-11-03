#!/bin/bash

# Script để zip thư mục uploads để deploy

echo "📦 Đang zip thư mục uploads..."

cd "$(dirname "$0")/public"

if [ -d "uploads" ]; then
    zip -r uploads.zip uploads/
    
    if [ -f "uploads.zip" ]; then
        SIZE=$(du -h uploads.zip | cut -f1)
        echo "✅ Đã tạo file uploads.zip thành công!"
        echo "📊 Kích thước: $SIZE"
        echo "📍 Vị trí: $(pwd)/uploads.zip"
        echo ""
        echo "📝 Hướng dẫn deploy:"
        echo "1. Upload file uploads.zip lên hosting vào thư mục public/"
        echo "2. Trên hosting, chạy: cd public && unzip uploads.zip"
        echo "3. Chạy: chmod -R 755 public/uploads/"
    else
        echo "❌ Lỗi: Không thể tạo file uploads.zip"
        exit 1
    fi
else
    echo "❌ Lỗi: Không tìm thấy thư mục uploads/"
    exit 1
fi

