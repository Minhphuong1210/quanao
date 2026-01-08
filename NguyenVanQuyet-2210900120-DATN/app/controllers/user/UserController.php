<?php
require_once __DIR__ . '/../../models/Category.php';

class UserController {
    public function __construct() {
        
    }

    // Index user: Trang chủ shop, hiển thị sản phẩm nổi bật
    public function index() {
        $title = 'Chào mừng đến Quanao Shop';
        ob_start();
        include __DIR__ . '/../views/user/index.php';  // View shop home
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout-user.php';  // Layout user (không sidebar, có header shop)
    }

    public function shopIndex() {
        // Lấy sản phẩm từ DB
        $products = [];  // Gọi model lấy data
        $title = 'Cửa hàng';
        ob_start();
        include __DIR__ . '/../views/shop/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout-user.php';
    }

    // Cart cho user
    public function cart() {
        $title = 'Giỏ hàng';
        // Logic cart session
        ob_start();
        include __DIR__ . '/../views/cart/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout-user.php';
    }
}
?>