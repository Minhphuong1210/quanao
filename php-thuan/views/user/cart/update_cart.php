<?php
// update_cart.php - Phiên bản ổn định nhất, fix lỗi "Sản phẩm cũ không tồn tại"

session_start();

ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../models/Cart.php';

$cart = new Cart();

$response = [
    'success' => false,
    'message' => 'Hành động không hợp lệ',
    'total_items' => $cart->getTotalItems(),
    'total_price' => number_format($cart->getTotalPrice(), 0, ',', '.') . ' ₫'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$action = $_POST['action'] ?? '';
$old_key = $_POST['id'] ?? ''; // key cũ

if (empty($old_key)) {
    $response['message'] = 'ID không hợp lệ';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// Dùng trực tiếp $_SESSION['cart'] để tránh lệch pha với class Cart
if (!isset($_SESSION['cart'][$old_key])) {
    $response['message'] = 'Sản phẩm không tồn tại trong giỏ hàng';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

switch ($action) {
    case 'update':
        $quantity = max(1, intval($_POST['quantity'] ?? 1));
        $_SESSION['cart'][$old_key]['quantity'] = $quantity;
        $response['success'] = true;
        $response['message'] = 'Cập nhật số lượng thành công';
        break;

    case 'remove':
        unset($_SESSION['cart'][$old_key]);
        $response['success'] = true;
        $response['message'] = 'Đã xóa sản phẩm khỏi giỏ hàng';
        break;

    case 'change_variant':
        $new_key = $_POST['new_key'] ?? '';
        if (empty($new_key) || $new_key === $old_key) {
            $response['message'] = 'Variant mới không hợp lệ';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Lấy item cũ
        $item = $_SESSION['cart'][$old_key];

        // Nếu key mới đã tồn tại → cộng dồn số lượng
        if (isset($_SESSION['cart'][$new_key])) {
            $_SESSION['cart'][$new_key]['quantity'] += $item['quantity'];
        } else {
            $_SESSION['cart'][$new_key] = $item;
        }

        // Xóa key cũ
        unset($_SESSION['cart'][$old_key]);

        $response['success'] = true;
        $response['message'] = 'Đã đổi kích thước/màu sắc thành công!';
        break;

    default:
        $response['message'] = 'Hành động không hỗ trợ';
        break;
}

// Cập nhật lại tổng (dùng class Cart để tính chính xác)
$cart = new Cart(); // reload để tính mới
$response['total_items'] = $cart->getTotalItems();
$response['total_price'] = number_format($cart->getTotalPrice(), 0, ',', '.') . ' ₫';
$response['success'] = true; // nếu đến đây là thành công

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
