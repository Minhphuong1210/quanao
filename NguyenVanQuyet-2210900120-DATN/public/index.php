<?php

require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/UserController.php';  

$request = $_SERVER['REQUEST_URI'];
$url = parse_url($request, PHP_URL_PATH);
$url = trim($url, '/');

$isAdmin = strpos($url, 'admin/') === 0;
$path = $isAdmin ? str_replace('admin/', '', $url) : $url;

if ($isAdmin) {
    $controller = new AdminController();
} else {
    $controller = new UserController();
}

switch ($path) {
    case '':
    case 'index':
        $controller->index();  
        break;

    case 'admin/category':
    case 'admin/category/index':
        $controller->categoryIndex(); 
        break;
    case 'admin/category/create':
        $controller->categoryCreate();
        break;
    // ... (tương tự edit/delete)

    // Routes user (ví dụ shop)
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