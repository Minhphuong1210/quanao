<?php 
class CartController 
{
    public function addTocart(){
        header('Content-Type: application/json');

        $response = [
            'success' => false,
            'message' => '',
            'total_items' => 0
        ];

    
        $product_id = $_POST['product_id'] ?? null;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $size_id = $_POST['size_id'] ?? null;
        $color_id = $_POST['color_id'] ?? null;

        if (!$product_id) {
            $response['message'] = "Không xác định được sản phẩm";
            echo json_encode($response);
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $key = $product_id;
        if ($size_id) $key .= "_$size_id";
        if ($color_id) $key .= "_$color_id";
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$key] = [
                'product_id' => $product_id,
                'size_id' => $size_id,
                'color_id' => $color_id,
                'quantity' => $quantity
            ];
        }
        $total_items = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_items += $item['quantity'];
        }

        $response['success'] = true;
        $response['message'] = "Đã thêm sản phẩm vào giỏ hàng";
        $response['total_items'] = $total_items;

        echo json_encode($response);
        exit;
    }
}
