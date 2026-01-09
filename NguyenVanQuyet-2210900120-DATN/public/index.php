<?php

session_start(); // Bắt đầu session cho auth

// Định nghĩa constants cho paths
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', __DIR__);
define('VIEWS_PATH', APP_PATH . '/views');
define('BASE_URL', 'http://localhost:8000/');
require_once BASE_PATH . '/app/controllers/user/HomeController.php';
require_once BASE_PATH . '/app/controllers/user/CartController.php';
require_once BASE_PATH . '/app/controllers/admin/AdminController.php';
require_once BASE_PATH . '/app/controllers/admin/AuthController.php';





$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$url = trim(str_replace($scriptName, '', $requestUri), '/');

$parts = explode('/', $url);



if (isset($parts[0]) && $parts[0] === 'admin') {
    $adminController = new AdminController();

    // /admin/login
    if (isset($parts[1]) && $parts[1] === 'login') {
        $authController = new AuthController();
        $authController->login();
        exit();
    }




    // /admin/category/...
    if (isset($parts[1]) && $parts[1] === 'category') {

    
        if (!isset($parts[2]) || $parts[2] === '/index') {
            $adminController->categoryIndex();
        } elseif ($parts[2] === 'create') {
            $adminController->categoryCreate();
        } elseif ($parts[2] === 'edit'){
            $adminController->categoryEdit($parts[3]);
        } elseif ($parts[2] === 'delete'){
            $adminController->categoryDelete($parts[3]);
        }
        
        else {
            die('404 Admin Category!');
        }
        exit();
    }

    if (isset($parts[1]) && $parts[1] === 'product') {

    
        if (!isset($parts[2]) || $parts[2] === '') {
            $adminController->productIndex();
        } elseif ($parts[2] === 'create') {
            $adminController->categoryCreate();
        } elseif ($parts[2] === 'edit'){
            $adminController->categoryEdit($parts[3]);
        }
        
        else {
            die('404 Admin Category!');
        }
        exit();
    }


    // /admin (mặc định)
    if (!isset($parts[1]) || $parts[1] === '') {
        $adminController->index();
        exit();
    }

    // Nếu không match gì
    die('404 Admin!');
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
    $slug = $parts[1];
    $homeController = new HomeController();
    $homeController->sanPhamTheoMauSac($slug);
    exit;
}

if (isset($parts[0]) && $parts[0] === 'nha-cung-cap' && isset($parts[1])) {
    $id = (int)$parts[1]; // Lấy id động
    $homeController = new HomeController();
    $homeController->sanPhamTheoNhaCungCap($id);
    exit;
}
if (isset($parts[0]) && $parts[0] === 'chi-tiet-san-pham' && isset($parts[1])) {
    $slug =$parts[1]; // Lấy id động
    $homeController = new HomeController();
    $homeController->xemChiTietSanPham($slug);
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
        case 'postLogin':
            $authController =new AuthController ();

            $authController->postLogin();
            break;
        case 'postCart':
            $cartController = new CartController();
            $cartController->addTocart();
            break;
        default:
            die('404 - Not Found!');
}


