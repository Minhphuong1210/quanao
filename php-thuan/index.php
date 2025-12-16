<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Nếu không có page → mặc định là home
$page = $_GET['page'] ?? 'home';

$file = __DIR__ . '/pages/' . $page . '.php';

if (!file_exists($file)) {
    http_response_code(404);
    exit('404 Not Found');
}

require $file;
