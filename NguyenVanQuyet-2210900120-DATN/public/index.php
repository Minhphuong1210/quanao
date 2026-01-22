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
require_once BASE_PATH . '/app/controllers/admin/UserController.php';
require_once BASE_PATH . '/app/controllers/admin/ColorController.php';
require_once BASE_PATH . '/app/controllers/admin/NhaCungCapController.php';
require_once BASE_PATH . '/app/controllers/admin/SizeController.php';
require_once BASE_PATH . '/app/controllers/admin/CategoryPostController.php';
require_once BASE_PATH . '/app/controllers/admin/PostController.php';


require_once BASE_PATH . '/app/controllers/admin/CheckOutAdminController.php';

// $requestUri = $_SERVER['REQUEST_URI'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'];
$url = trim(str_replace($scriptName, '', $requestUri), '/');

$parts = explode('/', $url);

if (isset($parts[0]) && $parts[0] === 'admin') {

    $adminController = new AdminController();
    $checkOutAdminController = new CheckOutAdminController();
    $userController = new UserController();
    $colorController = new ColorController();
    $nhaCungCapController = new NhaCungCapController();
    $sizeController = new SizeController();
    $categoryPostController = new CategoryPostController();
    $postController = new PostController();
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


    if (isset($parts[1]) && $parts[1] === 'user') {
        if (!isset($parts[2]) || $parts[2] === '') {
            $userController->index();
        } elseif ($parts[2] === 'create') {
            $userController->create();
        }elseif ($parts[2] === 'store') {
            $userController->store();
        }
         elseif ($parts[2] === 'edit') {
            $userController->edit($parts[3]);
        } elseif ($parts[2] === 'update') {
            $userController->update($parts[3]);
        }
         elseif ($parts[2] === 'delete') {
            $userController->delete($parts[3]);
        } else {
            die('404 Admin user!'); // Sửa từ 'Category' thành 'Product'
        }
        exit();
    }


    if (isset($parts[1]) && $parts[1] === 'colors') {
        if (!isset($parts[2]) || $parts[2] === '') {
            $colorController->index();
        } elseif ($parts[2] === 'create') {
            $colorController->create();
        }elseif ($parts[2] === 'store') {
            $colorController->store();
        }
         elseif ($parts[2] === 'edit') {
            $colorController->edit($parts[3]);
        } elseif ($parts[2] === 'update') {
            $colorController->update($parts[3]);
        }
         elseif ($parts[2] === 'delete') {
            $colorController->delete($parts[3]);
        } else {
            die('404 Admin user!'); // Sửa từ 'Category' thành 'Product'
        }
        exit();
    }

    if (isset($parts[1]) && $parts[1] === 'nha_cung_cap') {
        if (!isset($parts[2]) || $parts[2] === '') {
            $nhaCungCapController->index();
        } elseif ($parts[2] === 'create') {
            $nhaCungCapController->create();
        }elseif ($parts[2] === 'store') {
            $nhaCungCapController->store();
        }
         elseif ($parts[2] === 'edit') {
            $nhaCungCapController->edit($parts[3]);
        } elseif ($parts[2] === 'update') {
            $nhaCungCapController->update($parts[3]);
        }
         elseif ($parts[2] === 'delete') {
            $nhaCungCapController->delete($parts[3]);
        } else {
            die('404 Admin user!'); // Sửa từ 'Category' thành 'Product'
        }
        exit();
    }

    
    if (isset($parts[1]) && $parts[1] === 'sizes') {
        if (!isset($parts[2]) || $parts[2] === '') {
            $sizeController->index();
        } elseif ($parts[2] === 'create') {
            $sizeController->create();
        }elseif ($parts[2] === 'store') {
            $sizeController->store();
        }
         elseif ($parts[2] === 'edit') {
            $sizeController->edit($parts[3]);
        } elseif ($parts[2] === 'update') {
            $sizeController->update($parts[3]);
        }
         elseif ($parts[2] === 'delete') {
            $sizeController->delete($parts[3]);
        } else {
            die('404 Admin user!'); // Sửa từ 'Category' thành 'Product'
        }
        exit();
    }


    if (isset($parts[1]) && $parts[1] === 'category-post') {
        if (!isset($parts[2]) || $parts[2] === '') {
            $categoryPostController->index();
        }
        // /admin/category-post/create
        elseif ($parts[2] === 'create') {
            $categoryPostController->create();
        }
        // /admin/category-post/edit/{id}
        elseif ($parts[2] === 'edit' && isset($parts[3])) {
            $categoryPostController->edit((int)$parts[3]);
        }
        // /admin/category-post/delete/{id}
        elseif ($parts[2] === 'delete' && isset($parts[3])) {
            $categoryPostController->delete((int)$parts[3]);
        }
        // /admin/category-post/restore/{id}
        elseif ($parts[2] === 'restore' && isset($parts[3])) {
            $categoryPostController->restore((int)$parts[3]);
        }
        else {
            die('404 CategoryPost Admin!');
        }
        exit();
    }


    if ( isset($parts[1]) && $parts[1] === 'post'){
        if (!isset($parts[2])) {
            $postController->index();
        } elseif ($parts[2] === 'create') {
            $postController->create();
        } elseif ($parts[2] === 'edit') {
            $postController->edit($parts[3]);
        } elseif ($parts[2] === 'delete') {
            $postController->delete($parts[3]);
        }
        exit;
    }
    

    // /admin/order

    if (isset($parts[1]) && $parts[1] === 'order') {

        // /admin/order
        if (!isset($parts[2]) || $parts[2] === '') {
            $checkOutAdminController->index();

            // /admin/order/detail/5
        } elseif ($parts[2] === 'detail' && isset($parts[3])) {
            $checkOutAdminController->detail((int) $parts[3]);

            // POST /admin/order/update-status
        } elseif ($parts[2] === 'update-status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $checkOutAdminController->updateStatus();

        } elseif ($parts[2] === 'detail' && isset($parts[3])) {
            $checkOutAdminController->detail($parts[3]);
        } else {
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
// thanh toán bằng vn pay 

if (isset($parts[0]) && $parts[0] === 'vnpay' && isset($parts[1])) {
   if($parts[1] === 'create'){
    $checkOutController = new CheckoutController();
    $checkOutController->vnpayCreate();
    exit();
   }
   if($parts[1] === 'return'){
    $checkOutController = new CheckoutController();
    $checkOutController->vnpayReturn();
   }
   exit();
}
// đây là của bài viết

if (isset($parts[0]) && $parts[0] === 'tin-tuc' && isset($parts[1])) {
    $slug = $parts[1]; // Lấy slug động
    $homeController = new HomeController();
    $homeController->danhMucTinTuc($slug); 
    exit;
}

if (isset($parts[0]) && $parts[0] === 'chi-tiet-bai-viet' && isset($parts[1])) {
    $slug = $parts[1]; // Lấy slug động
    $homeController = new HomeController();
    $homeController->chiTietBaiViet($slug); 
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
    case "gioiThieuVeChungToi":
        $homeController = new HomeController();
        $homeController->gioiThieuVeChungToi();
        break;
    
    case "danhMucTinTuc":
        $homeController = new HomeController();
        $homeController->danhMucTinTuc();
        break;

    default:
        die('404 - Not Found!');
}
