<?php
// addtocart.php - AJAX thêm giỏ hàng, KHÔNG ghép variant vào tên sản phẩm

session_start();

ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once '../../../includes/init.php'; // Có $cart = new Cart()
require_once '../../../includes/database.php';

$pdo = Database::getInstance();

header('Content-Type: application/json; charset=utf-8');

$response = [
    'success' => false,
    'message' => 'Lỗi không xác định',
    'total_items' => 0,
    'total_price' => 0
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$product_id = intval($_POST['product_id'] ?? 0);
$quantity   = max(1, intval($_POST['quantity'] ?? 1));
$size_id    = intval($_POST['size_id'] ?? 0);
$color_id   = intval($_POST['color_id'] ?? 0);

if ($product_id <= 0) {
    $response['message'] = 'Sản phẩm không hợp lệ';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id = ? AND active = 1");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $response['message'] = 'Sản phẩm không tồn tại';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $image_path = !empty($product['image'])
        ? '/quanao/php-thuan/assets/images/' . $product['image']
        : '/quanao/php-thuan/assets/img/no-image.jpg';

    // *** QUAN TRỌNG: Chỉ dùng tên gốc, KHÔNG ghép variant ***
    $display_name = $product['name']; // <-- BỎ GHẸP (Size... - Màu...)

    // Key duy nhất cho variant
    $key = $product_id . '-' . $size_id . '-' . $color_id;

    // Thêm vào giỏ
    $cart->addItem($key, $display_name, $product['price'], $image_path, $quantity);

    $response['success'] = true;
    $response['message'] = "Đã thêm \"{$display_name}\" vào giỏ hàng!";
    $response['total_items'] = $cart->getTotalItems();
    $response['total_price'] = number_format($cart->getTotalPrice(), 0, ',', '.') . ' ₫';
} catch (Exception $e) {
    $response['message'] = 'Lỗi hệ thống';
    error_log("Addtocart error: " . $e->getMessage());
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
