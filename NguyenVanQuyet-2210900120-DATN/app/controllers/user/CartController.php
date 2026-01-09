<?php
require_once BASE_PATH . '/app/models/Product.php';
require_once BASE_PATH . '/app/models/Color.php';
require_once BASE_PATH . '/app/models/Size.php';
require_once BASE_PATH . '/app/models/category.php';
require_once BASE_PATH . '/app/models/Product_Detail.php';
class CartController
{
    public function addTocart()
    {

        header('Content-Type: application/json');

        $response = [
            'success' => false,
            'message' => '',
            'total_items' => 0,
        ];
        $product_id = $_POST['product_id'] ?? null;
        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
        $size_id = $_POST['size_id'] ?? null;
        $color_id = $_POST['color_id'] ?? null;

        if (!$product_id) {
            $response['message'] = 'Không xác định được sản phẩm';
            echo json_encode($response);
            exit;
        }

        if ($quantity < 1) {
            $quantity = 1;
        }

        $productModel = new Product();
        $sizeModel = new Size();
        $colorModel = new Color();

        $product = $productModel->find($product_id);
        if (!$product) {
            $response['message'] = 'Sản phẩm không tồn tại';
            echo json_encode($response);
            exit;
        }

        $size_name = null;
        if ($size_id) {
            $size = $sizeModel->find($size_id);
            if (!$size) {
                $response['message'] = 'Kích thước không hợp lệ';
                echo json_encode($response);
                exit;
            }
            $size_name = $size['name'];
        }

        $color_name = null;
        if ($color_id) {
            $color = $colorModel->find($color_id);
            if (!$color) {
                $response['message'] = 'Màu sắc không hợp lệ';
                echo json_encode($response);
                exit;
            }
            $color_name = $color['name'];
        }

        $image = $product['image'] ?? null;
        if (!empty($product['image_array'])) {
            $imgs = array_map('trim', explode('","', trim($product['image_array'], '"')));
            $image = $imgs[0] ?? $image;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $key = $product_id;
        if ($size_id) {
            $key .= '_' . $size_id;
        }

        if ($color_id) {
            $key .= '_' . $color_id;
        }

        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$key] = [
                'product_id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $image,

                'id_size' => $size_id,
                'name_size' => $size_name,

                'id_color' => $color_id,
                'name_color' => $color_name,

                'quantity' => $quantity,
            ];
        }

        $total_items = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_items += $item['quantity'];
        }

        $response['success'] = true;
        $response['message'] = 'Đã thêm sản phẩm vào giỏ hàng';
        $response['total_items'] = $total_items;

        echo json_encode($response);
        exit;
    }

    public function showCart()
    {

        $cart = $_SESSION['cart'] ?? [];
        $subTotal = 0;

        include BASE_PATH . '/app/views/user/home/cart.php';

    }

}
