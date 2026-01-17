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
require_once BASE_PATH . '/app/controllers/user/CheckoutController.php';
require_once BASE_PATH . '/app/controllers/user/AuthControllerUser.php';
require_once BASE_PATH . '/app/controllers/admin/AdminController.php';
require_once BASE_PATH . '/app/controllers/admin/AuthController.php';
require_once BASE_PATH . '/app/controllers/admin/CheckOutAdminController.php';


// $requestUri = $_SERVER['REQUEST_URI'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'];
$url = trim(str_replace($scriptName, '', $requestUri), '/');

$parts = explode('/', $url);

if (isset($parts[0]) && $parts[0] === 'admin') {

    $adminController = new AdminController();
    $checkOutAdminController = new CheckOutAdminController();
    // /admin/login
    if (isset($parts[1]) && $parts[1] === 'login') {
        $authController = new AuthController();
        $authController->login();
        exit();
    }

    // /admin/category/...
    if (isset($parts[1]) && $parts[1] === 'category') {

        if (!isset($parts[2]) || $parts[2] === 'index') {
            $adminController->categoryIndex();
        } elseif ($parts[2] === 'create') {
            $adminController->categoryCreate();
        } elseif ($parts[2] === 'edit') {
            $adminController->categoryEdit($parts[3]);
        } elseif ($parts[2] === 'delete') {
            $adminController->categoryDelete($parts[3]);
        } else {
            die('404 Admin Category!');
        }
        exit();
    }

    // /admin/product/...
    if (isset($parts[1]) && $parts[1] === 'product') {
        if (!isset($parts[2]) || $parts[2] === '') {
            $adminController->productIndex();
        } elseif ($parts[2] === 'create') {
            $adminController->productCreate();
        } elseif ($parts[2] === 'edit') {
            $adminController->productEdit($parts[3]);
        } elseif ($parts[2] === 'delete') {
            $adminController->productDelete($parts[3]);
        } else {
            die('404 Admin Product!'); // Sửa từ 'Category' thành 'Product'
        }
        exit();
    }
// /admin/order

if (isset($parts[1]) && $parts[1] === 'order') {

    // /admin/order
    if (!isset($parts[2]) || $parts[2] === '') {
        $checkOutAdminController->index();

    // /admin/order/detail/5
    } elseif ($parts[2] === 'detail' && isset($parts[3])) {
        $checkOutAdminController->detail((int)$parts[3]);

    // POST /admin/order/update-status
    } elseif ($parts[2] === 'update-status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $checkOutAdminController->updateStatus();

    } 
    elseif ($parts[2] === 'detail' && isset($parts[3])) {
        $checkOutAdminController->detail($parts[3]);
    }else {
        http_response_code(404);
        die('404 Admin Order!');
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
    $id = (int) $parts[1]; // Lấy id động
    $homeController = new HomeController();
    $homeController->sanPhamTheoNhaCungCap($id);
    exit;
}
if (isset($parts[0]) && $parts[0] === 'chi-tiet-san-pham' && isset($parts[1])) {
    $slug = $parts[1]; // Lấy id động
    $homeController = new HomeController();
    $homeController->xemChiTietSanPham($slug);
    exit;
}

// đơn hàng

if (
    isset($parts[1], $parts[2], $parts[3]) &&
    $parts[1] === 'orders' &&
    $parts[2] === 'cancel'
) {
    $checkOutController = new CheckoutController();
    $checkOutController->huyDonHang($parts[3]);
    exit;
}
if (
    isset($parts[1], $parts[2]) &&
    $parts[1] === 'orders'
) {
    $checkOutController = new CheckoutController();
    $checkOutController->xemDonHang($parts[2]);
    exit;
}

if (
    isset($parts[0], $parts[1]) &&
    $parts[0] === 'account' &&
    $parts[1] === 'profile' &&
    $_SERVER['REQUEST_METHOD'] === 'GET'
) {
    $authController = new AuthControllerUser();
    $authController->profile();
    exit;
}
if (
    isset($parts[0], $parts[1], $parts[2]) &&
    $parts[0] === 'account' &&
    $parts[1] === 'profile' &&
    $parts[2] === 'update' &&
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    $authController = new AuthControllerUser();
    $authController->updateProfile();
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
    // cái này là để check đăng nhập của admin
    case 'postLogin':
        $authController = new AuthController();

        $authController->postLogin();
        break;

    case 'cart':
        $cartController = new CartController();
        $cartController->showCart();
        break;
    case 'postCart':
        $cartController = new CartController();
        $cartController->addTocart();
        break;

    case 'updateCart':
        $cartController = new CartController();
        $cartController->updateCart();
        break;

    case 'deleteCart':
        $cartController = new CartController();
        $cartController->deleteCart();
        break;
    case "checkout":
        $checkOutController = new CheckoutController();
        $checkOutController->getOrder();
        break;

    case "postCheckout":
        $checkOutController = new CheckoutController();
        $checkOutController->postCheckout();
        break;
    case "loginUser":
        $authController = new AuthControllerUser();
        $authController->loginUser();
        break;
    case "register":
        $authController = new AuthControllerUser();
        $authController->register();
        break;

    case "forgotPassword":
        $authController = new AuthControllerUser();
        $authController->forgotPassword();
        break;

    case "logout":
        $authController = new AuthControllerUser();
        $authController->logout();
        break;
    case "theo-doi-don-hang":
        $checkOutController = new CheckoutController();
        $checkOutController->theoDoiDonHang();
        break;

    default:
        die('404 - Not Found!');
}
