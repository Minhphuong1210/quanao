<?php

require_once 'includes/init.php';          // Có session_start và new Cart()
require_once 'includes/database.php';      // Có $pdo (hoặc tên file db của bạn)

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$product_id = intval($_GET['id']);
$quantity = isset($_GET['quantity']) ? max(1, intval($_GET['quantity'])) : 1;

$stmt = $pdo->prepare("SELECT id, name, price, image, slug FROM products WHERE id = ? AND active = 1");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    $_SESSION['error'] = "Sản phẩm không tồn tại hoặc đã hết hàng!";
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

$image_path = !empty($product['image'])
    ? '/quanao/php-thuan/assets/images/' . $product['image']
    : '/quanao/php-thuan/assets/img/no-image.jpg';

// Thêm vào giỏ hàng
$cart->addItem(
    $product['id'],
    $product['name'],
    $product['price'],
    $image_path,
    $quantity
);

// Thông báo thành công
$_SESSION['success'] = "Đã thêm \"{$product['name']}\" vào giỏ hàng!";

// Quay về trang trước đó (trang chủ hoặc trang chi tiết)
$referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $referer");
exit;