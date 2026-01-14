<?php
require_once BASE_PATH . '/app/helpers/admin_auth.php';
require_once BASE_PATH . '/app/helpers/validate.php';
require_once BASE_PATH . '/app/models/Order.php';
require_once BASE_PATH . '/app/models/Order_Detail.php';
require_once BASE_PATH . '/app/enums/OrderStatus.php';
require_once BASE_PATH . '/app/enums/PaymentStatus.php';

class CheckoutController
{
/**
 * Khởi tạo để check xem người này đã đăng nhập hay chưa
 */

 protected $orderModel;
 protected $orderDetailModel;

    public function __construct()
    {
        // Kiểm tra đăng nhập user hay chưa nghiêm ngặt
        checkUserLogin();
        
         // khởi tạo
        

    }

    public function getOrder()
    {
        $cart = $_SESSION['cart'] ?? [];
        $subtotal = 0;
        include BASE_PATH . '/app/views/user/home/donHang.php';

    }

    public function postCheckout()
    {

        $orderModel = new Order();
        $orderDetailModel = new OrderDetail();

        // lấy các thông tin kia lên
        $cart = $_SESSION['cart'] ?? [];
        $errors = [];
        $ten = checkXss($_POST['ten'] ?? '');
        $ho = checkXss($_POST['ho'] ?? '');
        $diaChi = checkXss($_POST['dia_chi'] ?? '');
        $soDienThoai = checkXss($_POST['so_dien_thoai'] ?? '');
        $email = checkXss($_POST['email'] ?? '');
        $accounts = isset($_POST['Accounts']) ? 1 : 0;
        $note = checkXss($_POST['note'] ?? '');

        if ($ten === '') {
            $errors['ten'] = 'Vui lòng nhập tên';
        }

        if ($ho === '') {
            $errors['ho'] = 'Vui lòng nhập họ';
        }

        if ($diaChi === '') {
            $errors['dia_chi'] = 'Vui lòng nhập địa chỉ';
        }

        if ($soDienThoai === '') {
            $errors['so_dien_thoai'] = 'Vui lòng nhập số điện thoại';
        }

        if ($email === '') {
            $errors['email'] = 'Vui lòng nhập email';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // nếu mà chạy qua validate thì sẽ đến các bước mua hàng
        $orderId=$this->processOrder($ten, $ho, $diaChi, $soDienThoai, $email, $note);

    // sau khi mua thành công thì sẽ trả về trang cảm đơn đã mua hàng
    $donHang=$orderModel->findById($orderId);
    include BASE_PATH . '/app/views/user/home/thankYou.php'; 

    }
    // hàm này là để xử lý đơn hàng kia
    public function processOrder($ten, $ho, $diaChi, $soDienThoai, $email, $note)
    {
        $orderModel = new Order();
        $orderDetailModel = new OrderDetail();


        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
// lây tổng tiền 
foreach ($cart as $item) {

$total +=  $item['quantity'] * $item['price'];

   
}



        // nối chuỗi tên và họ vào
        $name = trim($ho . ' ' . $ten);
        // xử lý tạo mã đơn hàng
        $ma_hon_hang = $orderModel->renMaDonHang();

        $voucher_id = null; // xử lý lưu vào bảng order ở đây

        $orderId = $orderModel->createOrder($name, $ma_hon_hang, $diaChi, $_SESSION['user_id'], OrderStatus::PENDING, PaymentStatus::UNPAID,$total, $voucher_id, $email, $soDienThoai, $note);

        if (!$orderId) {
            throw new Exception('Không tạo được đơn hàng');
        }

        // sau khi xong thì sẽ tạo đến đơn hàng chi tiết của nó

        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            throw new Exception('Giỏ hàng trống');
        }

        // 5. Lưu từng sản phẩm vào order_detail
        foreach ($cart as $item) {
            $orderDetailModel->create([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'color_id' => $item['id_color'],
                'size_id' => $item['id_size'],
                'image_product' => $item['image'],
                'name_product' => $item['name'],
                'price_product' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['quantity'] * $item['price'],
            ]);
        }

        // 6. Xoá giỏ hàng sau khi đặt xong
        unset($_SESSION['cart']);

        return $orderId;

    }

}
