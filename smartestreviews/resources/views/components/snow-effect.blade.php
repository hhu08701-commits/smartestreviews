<!-- Snow Effect -->
<div id="snow-container" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 9999; overflow: hidden;"></div>

<script>
(function() {
    'use strict';
    
    // Snow effect configuration
    const config = {
        count: 80,           // Số lượng bông tuyết
        speed: 1.5,          // Tốc độ rơi (tăng từ 0.5 lên 1.5)
        size: {              // Kích thước bông tuyết
            min: 3,
            max: 10
        },
        wind: {              // Gió (độ lệch ngang)
            min: -0.8,
            max: 0.8
        },
        opacity: {           // Độ trong suốt
            min: 0.5,
            max: 1
        }
    };
    
    // Màu sắc đa dạng
    const colors = [
        '#FF6B9D', // Hồng
        '#C77DFF', // Tím
        '#4ECDC4', // Xanh ngọc
        '#FFE66D', // Vàng
        '#95E1D3', // Xanh lá nhạt
        '#F38181', // Đỏ hồng
        '#AA96DA', // Tím nhạt
        '#FCBAD3', // Hồng nhạt
        '#A8E6CF', // Xanh lá
        '#FFD3A5', // Cam nhạt
        '#FFA07A', // Cam
        '#98D8C8', // Xanh ngọc nhạt
        '#F7DC6F', // Vàng nhạt
        '#BB8FCE', // Tím hồng
        '#85C1E2', // Xanh dương nhạt
        '#F8B88B', // Cam hồng
        '#FFB6C1', // Hồng nhạt
        '#DDA0DD', // Mận
        '#B0E0E6', // Xanh bột
        '#FFDAB9', // Đào
        '#E6E6FA', // Oải hương
        '#FFE4E1', // Hồng phấn
        '#F0E68C', // Khaki
        '#FFB347', // Cam vàng
        '#87CEEB', // Xanh bầu trời
        '#DDA0DD', // Mận
        '#F5DEB3', // Lúa mì
        '#FF69B4', // Hồng nóng
        '#20B2AA', // Xanh biển nhạt
        '#FF1493', // Hồng đậm
        '#FFFFFF' // Trắng (một ít)
    ];
    
    const container = document.getElementById('snow-container');
    if (!container) return;
    
    const snowflakes = [];
    let animationId;
    
    // Tạo bông tuyết
    function createSnowflake() {
        const snowflake = document.createElement('div');
        const size = Math.random() * (config.size.max - config.size.min) + config.size.min;
        const color = colors[Math.floor(Math.random() * colors.length)];
        
        snowflake.style.position = 'absolute';
        snowflake.style.width = snowflake.style.height = size + 'px';
        snowflake.style.backgroundColor = color;
        snowflake.style.borderRadius = '50%';
        snowflake.style.opacity = Math.random() * (config.opacity.max - config.opacity.min) + config.opacity.min;
        
        // Tạo hiệu ứng glow với màu tương ứng
        const rgb = hexToRgb(color);
        if (rgb) {
            snowflake.style.boxShadow = `0 0 ${size * 1.5}px rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0.8), 0 0 ${size * 2.5}px rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0.4)`;
        } else {
            snowflake.style.boxShadow = `0 0 ${size * 1.5}px ${color}80, 0 0 ${size * 2.5}px ${color}40`;
        }
        
        snowflake.style.pointerEvents = 'none';
        snowflake.style.userSelect = 'none';
        snowflake.style.transition = 'opacity 0.3s ease';
        
        // Vị trí ban đầu
        snowflake.x = Math.random() * window.innerWidth;
        snowflake.y = -10;
        snowflake.speed = Math.random() * 1.0 + config.speed; // Tăng biến thiên tốc độ
        snowflake.wind = Math.random() * (config.wind.max - config.wind.min) + config.wind.min;
        snowflake.rotation = Math.random() * 360;
        snowflake.rotationSpeed = Math.random() * 2 - 1;
        
        container.appendChild(snowflake);
        snowflakes.push(snowflake);
        
        return snowflake;
    }
    
    // Chuyển đổi hex sang RGB
    function hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }
    
    // Cập nhật vị trí bông tuyết
    function updateSnowflake(snowflake) {
        snowflake.y += snowflake.speed;
        snowflake.x += snowflake.wind;
        snowflake.rotation += snowflake.rotationSpeed;
        
        // Xoay bông tuyết
        snowflake.style.transform = `translate(${snowflake.x}px, ${snowflake.y}px) rotate(${snowflake.rotation}deg)`;
        
        // Nếu bông tuyết rơi ra ngoài màn hình, đặt lại ở trên
        if (snowflake.y > window.innerHeight) {
            snowflake.y = -10;
            snowflake.x = Math.random() * window.innerWidth;
        }
        
        // Nếu bông tuyết ra ngoài màn hình theo chiều ngang, đặt lại
        if (snowflake.x < -10) {
            snowflake.x = window.innerWidth + 10;
        } else if (snowflake.x > window.innerWidth + 10) {
            snowflake.x = -10;
        }
    }
    
    // Animation loop
    function animate() {
        snowflakes.forEach(updateSnowflake);
        animationId = requestAnimationFrame(animate);
    }
    
    // Khởi tạo bông tuyết
    function init() {
        // Xóa bông tuyết cũ nếu có
        snowflakes.forEach(s => s.remove());
        snowflakes.length = 0;
        
        // Tạo bông tuyết mới
        for (let i = 0; i < config.count; i++) {
            const snowflake = createSnowflake();
            // Phân bố đều theo chiều dọc
            snowflake.y = (i / config.count) * window.innerHeight - 10;
        }
        
        // Bắt đầu animation
        animate();
    }
    
    // Xử lý resize window
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            // Điều chỉnh vị trí bông tuyết khi resize
            snowflakes.forEach(snowflake => {
                if (snowflake.x > window.innerWidth) {
                    snowflake.x = window.innerWidth - 10;
                }
            });
        }, 250);
    });
    
    // Khởi tạo khi DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Cleanup khi component bị xóa
    window.addEventListener('beforeunload', function() {
        if (animationId) {
            cancelAnimationFrame(animationId);
        }
    });
})();
</script>

