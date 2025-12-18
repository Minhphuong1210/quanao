<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Lấy page từ URL, mặc định là home
$page = $_GET['page'] ?? 'home';

// Đường dẫn đến file trang
$file = __DIR__ . '/pages/' . $page . '.php';

// Nếu file không tồn tại → 404
if (!file_exists($file)) {
    http_response_code(404);
    echo '404 Not Found';
    exit;
}

// Load trang
require $file;