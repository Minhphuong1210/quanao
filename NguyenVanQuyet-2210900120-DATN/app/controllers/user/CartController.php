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
                'slug' => $product['slug'] ?? '',
                'price' => $product['price'],
                'image' => $image,
                'size_id' => $size_id,
                'name_size' => $size_name,
                'color_id' => $color_id,
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
        $response['cart_key'] = $key;

        echo json_encode($response);
        exit;
    }

    public function showCart()
    {
        $cart = $_SESSION['cart'] ?? [];
        $subTotal = 0;

        include BASE_PATH . '/app/views/user/home/cart.php';
    }

    // Cập nhật số lượng sản phẩm
    public function updateQuantity()
    {
        header('Content-Type: application/json');

        $response = [
            'success' => false,
            'message' => '',
            'new_subtotal' => 0,
            'new_grandtotal' => 0,
            'item_total' => 0
        ];

        if (!isset($_SESSION['cart'])) {
            $response['message'] = 'Giỏ hàng không tồn tại';
            echo json_encode($response);
            exit;
        }

        $key = $_POST['key'] ?? null;
        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

        if (!$key || !isset($_SESSION['cart'][$key])) {
            $response['message'] = 'Sản phẩm không tồn tại trong giỏ hàng';
            echo json_encode($response);
            exit;
        }

        if ($quantity < 1) {
            $quantity = 1;
        }

        // Cập nhật số lượng
        $_SESSION['cart'][$key]['quantity'] = $quantity;

        // Tính toán lại
        $item = $_SESSION['cart'][$key];
        $item_total = $item['price'] * $quantity;

        $subtotal = 0;
        foreach ($_SESSION['cart'] as $cart_item) {
            $subtotal += $cart_item['price'] * $cart_item['quantity'];
        }

        $response['success'] = true;
        $response['message'] = 'Đã cập nhật số lượng';
        $response['item_total'] = $item_total;
        $response['new_subtotal'] = $subtotal;
        $response['new_grandtotal'] = $subtotal;
        $response['new_quantity'] = $quantity;

        echo json_encode($response);
        exit;
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeItem()
    {
        header('Content-Type: application/json');

        $response = [
            'success' => false,
            'message' => '',
            'new_subtotal' => 0,
            'new_grandtotal' => 0
        ];

        if (!isset($_SESSION['cart'])) {
            $response['message'] = 'Giỏ hàng không tồn tại';
            echo json_encode($response);
            exit;
        }

        $key = $_POST['key'] ?? null;

        if (!$key || !isset($_SESSION['cart'][$key])) {
            $response['message'] = 'Sản phẩm không tồn tại trong giỏ hàng';
            echo json_encode($response);
            exit;
        }

        // Lưu thông tin sản phẩm trước khi xóa (cho thông báo)
        $product_name = $_SESSION['cart'][$key]['name'];

        // Xóa sản phẩm
        unset($_SESSION['cart'][$key]);

        // Tính toán lại tổng tiền
        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Tính tổng số lượng sản phẩm
        $total_items = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_items += $item['quantity'];
        }

        $response['success'] = true;
        $response['message'] = "Đã xóa '{$product_name}' khỏi giỏ hàng";
        $response['new_subtotal'] = $subtotal;
        $response['new_grandtotal'] = $subtotal;
        $response['total_items'] = $total_items;
        $response['cart_count'] = count($_SESSION['cart']);

        echo json_encode($response);
        exit;
    }

    // Xóa toàn bộ giỏ hàng
    public function clearCart()
    {
        header('Content-Type: application/json');

        $response = [
            'success' => false,
            'message' => ''
        ];

        if (isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
            $response['success'] = true;
            $response['message'] = 'Đã xóa toàn bộ giỏ hàng';
        } else {
            $response['message'] = 'Giỏ hàng không tồn tại';
        }

        echo json_encode($response);
        exit;
    }

    // Cập nhật màu sắc/kích cỡ sản phẩm
    public function updateVariant()
    {
        header('Content-Type: application/json');

        $response = [
            'success' => false,
            'message' => '',
            'new_key' => ''
        ];

        if (!isset($_SESSION['cart'])) {
            $response['message'] = 'Giỏ hàng không tồn tại';
            echo json_encode($response);
            exit;
        }

        $old_key = $_POST['key'] ?? null;
        $product_id = $_POST['product_id'] ?? null;
        $new_size_id = $_POST['size_id'] ?? null;
        $new_color_id = $_POST['color_id'] ?? null;
        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

        if (!$old_key || !isset($_SESSION['cart'][$old_key])) {
            $response['message'] = 'Sản phẩm không tồn tại trong giỏ hàng';
            echo json_encode($response);
            exit;
        }

        if (!$product_id) {
            $response['message'] = 'Không xác định được sản phẩm';
            echo json_encode($response);
            exit;
        }

        if ($quantity < 1) {
            $quantity = 1;
        }

        // Lấy thông tin sản phẩm cũ
        $old_item = $_SESSION['cart'][$old_key];

        // Tạo key mới
        $new_key = $product_id;
        if ($new_size_id) {
            $new_key .= '_' . $new_size_id;
        }
        if ($new_color_id) {
            $new_key .= '_' . $new_color_id;
        }

        // Kiểm tra nếu variant mới đã tồn tại trong giỏ hàng
        if (isset($_SESSION['cart'][$new_key]) && $new_key !== $old_key) {
            // Cộng dồn số lượng vào sản phẩm đã có
            $_SESSION['cart'][$new_key]['quantity'] += $quantity;

            // Xóa sản phẩm cũ
            unset($_SESSION['cart'][$old_key]);

            $response['success'] = true;
            $response['message'] = 'Đã cập nhật thông tin sản phẩm';
            $response['new_key'] = $new_key;
            echo json_encode($response);
            exit;
        }

        // Lấy thông tin màu và size mới
        $sizeModel = new Size();
        $colorModel = new Color();

        $new_size_name = null;
        if ($new_size_id) {
            $size = $sizeModel->find($new_size_id);
            if ($size) {
                $new_size_name = $size['name'];
            }
        }

        $new_color_name = null;
        if ($new_color_id) {
            $color = $colorModel->find($new_color_id);
            if ($color) {
                $new_color_name = $color['name'];
            }
        }

        // Tạo item mới
        $_SESSION['cart'][$new_key] = [
            'product_id' => $product_id,
            'name' => $old_item['name'],
            'slug' => $old_item['slug'] ?? '',
            'price' => $old_item['price'],
            'image' => $old_item['image'],
            'size_id' => $new_size_id,
            'name_size' => $new_size_name,
            'color_id' => $new_color_id,
            'name_color' => $new_color_name,
            'quantity' => $quantity,
        ];

        // Xóa item cũ nếu key khác nhau
        if ($new_key !== $old_key) {
            unset($_SESSION['cart'][$old_key]);
        }

        $response['success'] = true;
        $response['message'] = 'Đã cập nhật thông tin sản phẩm';
        $response['new_key'] = $new_key;

        echo json_encode($response);
        exit;
    }

    // Lấy thông tin variant của sản phẩm (cho modal chỉnh sửa)
    public function getProductVariants($product_id)
    {
        header('Content-Type: application/json');

        $response = [
            'success' => false,
            'message' => '',
            'colors' => [],
            'sizes' => []
        ];

        if (!$product_id) {
            $response['message'] = 'Không xác định được sản phẩm';
            echo json_encode($response);
            exit;
        }

        // Lấy thông tin sản phẩm
        $productModel = new Product();
        $product = $productModel->find($product_id);

        if (!$product) {
            $response['message'] = 'Sản phẩm không tồn tại';
            echo json_encode($response);
            exit;
        }

        // Lấy danh sách màu sắc
        $colorModel = new Color();
        $colors = $colorModel->getAll();

        // Lấy danh sách kích cỡ
        $sizeModel = new Size();
        $sizes = $sizeModel->getAll();

        // Lấy thông tin tồn kho từ product_detail
        $productDetailModel = new Product_Detail();
        $variants = $productDetailModel->getByProduct($product_id);

        $response['success'] = true;
        $response['product_name'] = $product['name'];
        $response['colors'] = $colors;
        $response['sizes'] = $sizes;
        $response['variants'] = $variants;

        echo json_encode($response);
        exit;
    }

    public function getVariantStock()
    {
        header('Content-Type: application/json');

        $response = [
            'success' => false,
            'message' => '',
            'stock' => 0
        ];

        $product_id = $_GET['product_id'] ?? null;
        $color_id = $_GET['color_id'] ?? null;
        $size_id = $_GET['size_id'] ?? null;

        if (!$product_id) {
            $response['message'] = 'Không xác định được sản phẩm';
            echo json_encode($response);
            exit;
        }

        $productDetailModel = new Product_Detail();

        // Tìm variant cụ thể
        $variant = $productDetailModel->findByAttributes($product_id, $color_id, $size_id);

        if ($variant) {
            $response['success'] = true;
            $response['stock'] = $variant['stock'] ?? 0;
            $response['price'] = $variant['price'] ?? 0;
        } else {
            $response['message'] = 'Variant không tồn tại';
            $response['stock'] = 0;
        }

        echo json_encode($response);
        exit;
    }

    // Lấy tổng số lượng sản phẩm trong giỏ hàng (dùng cho header)
    public function getCartCount()
    {
        header('Content-Type: application/json');

        $total_items = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $total_items += $item['quantity'];
            }
        }

        echo json_encode([
            'success' => true,
            'total_items' => $total_items,
            'cart_count' => isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0
        ]);
        exit;
    }
}
