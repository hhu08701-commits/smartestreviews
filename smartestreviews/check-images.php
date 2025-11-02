<?php
/**
 * Script kiểm tra và sửa lỗi ảnh không hiển thị trên production
 * Chạy: php check-images.php
 */

$basePath = __DIR__;

echo "=== KIỂM TRA CẤU HÌNH ẢNH ===\n\n";

// 1. Kiểm tra thư mục uploads
$uploadDirs = [
    'public/uploads/posts',
    'public/uploads/products',
    'public/uploads/slideshow',
    'public/uploads/hot-products',
];

echo "1. Kiểm tra thư mục uploads:\n";
foreach ($uploadDirs as $dir) {
    $fullPath = $basePath . '/' . $dir;
    if (!is_dir($fullPath)) {
        echo "   ❌ Thiếu thư mục: $dir\n";
        if (mkdir($fullPath, 0755, true)) {
            echo "   ✅ Đã tạo thư mục: $dir\n";
        }
    } else {
        $perm = substr(sprintf('%o', fileperms($fullPath)), -4);
        echo "   ✅ $dir (permissions: $perm)\n";
    }
}

// 2. Kiểm tra .env
echo "\n2. Kiểm tra .env file:\n";
$envFile = $basePath . '/.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (preg_match('/APP_URL=(.*)/', $envContent, $matches)) {
        $appUrl = trim($matches[1]);
        echo "   APP_URL: $appUrl\n";
        
        // Kiểm tra http vs https
        if (strpos($appUrl, 'http://') !== false) {
            echo "   ⚠️  Đang dùng HTTP, nên dùng HTTPS cho production\n";
        }
        
        // Kiểm tra localhost
        if (strpos($appUrl, 'localhost') !== false || strpos($appUrl, '127.0.0.1') !== false) {
            echo "   ❌ APP_URL đang trỏ về localhost! Cần đổi thành domain thật\n";
        }
    } else {
        echo "   ❌ Không tìm thấy APP_URL trong .env\n";
    }
} else {
    echo "   ❌ Không tìm thấy file .env\n";
}

// 3. Kiểm tra file ảnh mẫu
echo "\n3. Kiểm tra file ảnh:\n";
$sampleFiles = [
    'public/uploads/posts',
    'public/uploads/products',
];

foreach ($sampleFiles as $dir) {
    $fullPath = $basePath . '/' . $dir;
    if (is_dir($fullPath)) {
        $files = scandir($fullPath);
        $imageFiles = array_filter($files, function($f) {
            return preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f);
        });
        $count = count($imageFiles);
        echo "   $dir: $count files\n";
        if ($count > 0) {
            $sample = array_slice($imageFiles, 0, 3);
            foreach ($sample as $file) {
                echo "      - $file\n";
            }
        }
    }
}

// 4. Hướng dẫn
echo "\n=== HƯỚNG DẪN KHẮC PHỤC ===\n\n";
echo "1. Kiểm tra APP_URL trong .env trên server:\n";
echo "   APP_URL=https://smartestreviews.us\n\n";
echo "2. Đảm bảo file ảnh đã được upload lên server:\n";
echo "   - Kiểm tra thư mục public/uploads/ trên server\n";
echo "   - Nếu thiếu, upload lại các file ảnh\n\n";
echo "3. Kiểm tra permissions:\n";
echo "   chmod -R 755 public/uploads\n\n";
echo "4. Clear cache trên server:\n";
echo "   php artisan config:clear\n";
echo "   php artisan cache:clear\n";
echo "   php artisan view:clear\n\n";


