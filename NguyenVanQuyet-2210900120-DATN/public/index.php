<?php

session_start();  // Bắt đầu session cho auth

// Định nghĩa constants cho paths
define('BASE_PATH', dirname(__DIR__));  // Root dự án (app/, public/)
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', __DIR__);
define('VIEWS_PATH', APP_PATH . '/views');
define('BASE_URL', 'http://localhost:8000/');
require_once BASE_PATH . '/app/controllers/user/HomeController.php';
require_once BASE_PATH . '/app/controllers/user/UserController.php';

// Autoload classes (controllers, models)
spl_autoload_register(function ($class) {
    $file = APP_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});


// Lấy URL path (e.g., /admin/product/edit/1)
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$path = trim(str_replace($scriptName, '', $requestUri), '/');

// Parse path thành parts (e.g., ['admin', 'product', 'edit', '1'])
$pathParts = explode('/', $path);
$controllerName = ucfirst(array_shift($pathParts) ?? 'Home') . 'Controller';
$action = (array_shift($pathParts) ?? 'index') . 'Action';  // Thêm 'Action' để tránh conflict
$params = $pathParts;  // Params như ID

// Xử lý AJAX: Nếu ?ajax=1, chỉ load content, không layout
$isAjax = isset($_GET['ajax']) && $_GET['ajax'] == 1;

// Load controller
$controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    http_response_code(404);
    die('Controller not found: ' . $controllerName);
}
require $controllerFile;

$controller = new $controllerName();
if (!method_exists($controller, $action)) {
    http_response_code(404);
    die('Action not found: ' . $action);
}

// Gọi action với params
call_user_func_array([$controller, $action], $params);

// Nếu không phải AJAX và không exit, load default layout (nếu cần)
if (!$isAjax && !headers_sent()) {
    // Giả sử controller set $content, $title, $pageTitle
    // Nhưng vì AJAX, controller phải handle riêng



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


    case 'admin/category/index':
        $controller->categoryIndex(); 
        break;
    case 'admin/category/create':
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
?>