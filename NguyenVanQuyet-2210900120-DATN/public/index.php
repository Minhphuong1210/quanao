<?php
session_start();

define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/');
require_once BASE_PATH . '/app/controllers/user/HomeController.php';
require_once BASE_PATH . '/app/controllers/user/UserController.php';
// require_once BASE_PATH . '/app/controllers/admin/AdminController.php';  

$request = $_SERVER['REQUEST_URI'];
$url = parse_url($request, PHP_URL_PATH);
$url = trim($url, '/');

$isAdmin = strpos($url, 'admin/') === 0;
$path = $isAdmin ? str_replace('admin/', '', $url) : $url;



switch ($path) {
    // case '/':
    case '':
        $homeController = new HomeController();
    $homeController->index(); 
       
        break;

    case 'admin/category':
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