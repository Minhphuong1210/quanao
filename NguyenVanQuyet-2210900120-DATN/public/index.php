<?php

session_start(); // Bắt đầu session cho auth

// Định nghĩa constants cho paths
define('BASE_PATH', dirname(__DIR__)); // Root dự án (app/, public/)
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', __DIR__);
define('VIEWS_PATH', APP_PATH . '/views');
define('BASE_URL', 'http://localhost:8000/');
require_once BASE_PATH . '/app/controllers/user/HomeController.php';
require_once BASE_PATH . '/app/controllers/user/UserController.php';
require_once BASE_PATH . '/app/controllers/admin/AdminController.php';


$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$url = trim(str_replace($scriptName, '', $requestUri), '/');

$parts = explode('/', $url);



if (isset($parts[0]) && $parts[0] === 'admin') {
    $adminController = new AdminController();
    

    if (isset($parts[1]) && $parts[1] === 'category') {
        if (!isset($parts[2]) || $parts[2] === 'index') {
            $adminController->categoryIndex();
        } elseif ($parts[2] === 'create') {
            $adminController->categoryCreate();
        } else {
            die('404 Admin Category!');
        }
    } elseif (isset($parts[1]) && $parts[1] === 'login') {
        $adminController->login();
    } else {
        die('404 Admin!');
    }
    exit;
}

// --- Kiểm tra category dynamic ---
if (isset($parts[0]) && $parts[0] === 'category' && isset($parts[1])) {
    $slug = $parts[1]; // Lấy slug động
    $homeController = new HomeController();
    $homeController->sanPhamTheoDanhMuc($slug); // Gọi function category($slug) trong HomeController
    exit;
}

// đây là của mua-sac

if (isset($parts[0]) && $parts[0] === 'mau-sac' && isset($parts[1])) {
    $slug = $parts[1]; // Lấy slug động
    $homeController = new HomeController();
    $homeController->sanPhamTheoDanhMuc($slug); 
    exit;
}

if (isset($parts[0]) && $parts[0] === 'nha-cung-cap' && isset($parts[1])) {
    $slug = $parts[1]; // Lấy slug động
    $homeController = new HomeController();
    $homeController->sanPhamTheoDanhMuc($slug); 
    exit;
}


// đây là các đường dẫn tính k chỉ fix cứng thế này 

    $isAdmin = strpos($url, 'admin/') === 0;
    $path = $isAdmin ? str_replace('admin/', '', $url) : $url;
    
    switch ($path) {
        // case '/':
        case '':
            $homeController = new HomeController();
            $homeController->index();
            break;
    
        case 'tat-ca-san-pham':
            $homeController = new HomeController();
            $homeController->tatCaSanPham();
            break;
    
    
        case 'admin':
    
            $adminController = new AdminController();
            $adminController->categoryIndex();
            break;
        case 'admin/login':
            $controller->categoryCreate();
            break;
        case 'shop':
        case 'shop/index':
            $controller->shopIndex();
            break;
        case 'cart':
            $controller->cart();
            break;
    
        default:
            die('404 - Not Found!');
}


