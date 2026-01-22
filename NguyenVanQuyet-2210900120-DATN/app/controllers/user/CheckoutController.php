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
        $paymentMethod = $_POST['payment_method'] ?? 'cod';
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
        $orderId = $this->processOrder($ten, $ho, $diaChi, $soDienThoai, $email, $note,$paymentMethod);

        // sau khi mua thành công thì sẽ trả về trang cảm đơn đã mua hàng
        $donHang = $orderModel->findById($orderId);

        $_SESSION['pending_order_id'] = $orderId;

        if ($paymentMethod === 'vnpay') {
            header('Location: ' . BASE_URL . 'vnpay/create');
            exit;
        }

        include BASE_PATH . '/app/views/user/home/thankYou.php';

    }
    // hàm này là để xử lý đơn hàng kia
    public function processOrder($ten, $ho, $diaChi, $soDienThoai, $email, $note,$paymentMethod)
    {
        $orderModel = new Order();
        $orderDetailModel = new OrderDetail();
        $productModel = new Product();
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        $totalDiscount = 0;
        // lây tổng tiền
        foreach ($cart as $item) {

            $price = $item['price'];
            $qty   = $item['quantity'];
        
          
            $giamGia = $productModel->getNccGiamGiaByProductId($item['product_id']);
        
            $origin = $price * $qty;
            $discountMoney = $price * ($giamGia / 100) * $qty;
        
            $total += ($origin - $discountMoney);
            $totalDiscount += $discountMoney;
        }
        // nối chuỗi tên và họ vào
        $name = trim($ho . ' ' . $ten);
        // xử lý tạo mã đơn hàng
        $ma_hon_hang = $orderModel->renMaDonHang();

        $voucher_id = null; // xử lý lưu vào bảng order ở đây

        $orderId = $orderModel->createOrder($name, $ma_hon_hang, $diaChi, $_SESSION['user_id'], OrderStatus::PENDING, PaymentStatus::UNPAID, $total, $voucher_id, $email, $soDienThoai, $note);

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
                'total' => $total,
            ]);
        }

        // 6. Xoá giỏ hàng sau khi đặt xong
        if ($paymentMethod === 'cod') {
            unset($_SESSION['cart']);
        }

        return $orderId;

    }

// xem tất cả các đơn hàng
    public function theoDoiDonHang()
    {
        if (!isset($_SESSION['user_logged_in'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $orderModel = new Order();
        $userId = $_SESSION['user_id'];

        // PHÂN TRANG
        $limit = 5;
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $orders = $orderModel->getOrdersByUserPaginate($userId, $limit, $offset);

        $totalOrders = $orderModel->countOrdersByUser($userId);
        $totalPages = ceil($totalOrders / $limit);

        include BASE_PATH . '/app/views/user/account/don_hang.php';
    }

    public function xemDonHang($id)
    {

        if (!isset($_SESSION['user_logged_in'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $orderModel = new Order();
        $orderDetailModel = new OrderDetail();

        $order = $orderModel->getByIdAndUser($id, $userId);

        if (!$order) {
            die('404 - Đơn hàng không tồn tại');
        }

        $orderDetails = $orderDetailModel->getByOrderId($id);

        include BASE_PATH . '/app/views/user/account/chi_tiet_don_hang.php';
    }

    public function huyDonHang($id)
    {
        if (!isset($_SESSION['user_logged_in'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $orderModel = new Order();

        $success = $orderModel->cancelOrder($id, $userId);

        if ($success) {
            header('Location: ' . BASE_URL . 'theo-doi-don-hang');
            exit;
        }

        die('Không thể huỷ đơn hàng');
    }

//tạo thanh toán onl
public function vnpayCreate()
{
    if (!isset($_SESSION['pending_order_id'])) {
        die('Không có đơn hàng cần thanh toán');
    }

// die('123');

    $orderId = $_SESSION['pending_order_id'];

    $orderModel = new Order();
    $order = $orderModel->findById($orderId);

    if (!$order) {
        die('Đơn hàng không tồn tại');
    }

    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = BASE_URL . "vnpay/return";
    $vnp_TmnCode = "WDVC7784"; 
    $vnp_HashSecret = "UY9FB9GZQANDS66EHLY95NEC6FAHI8HX"; 

    $vnp_TxnRef = (string)$orderId; // Ép string
    $vnp_OrderInfo = "Thanh toan don hang " . $orderId; 
    $vnp_Amount = (int)($order['tong_tien'] * 100); // Ép int, nhân 100
    $vnp_Locale = 'vn';
    // $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
    $vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes'));

    $inputData = [
        "vnp_Version"    => "2.1.0",
        "vnp_TmnCode"    => $vnp_TmnCode,
        "vnp_Amount"     => $vnp_Amount,
        "vnp_Command"    => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode"   => "VND",
        "vnp_IpAddr"     => '113.161.45.10',
        "vnp_Locale"     => $vnp_Locale,
        "vnp_OrderInfo"  => $vnp_OrderInfo,
        "vnp_OrderType"  => "other", // hoặc "other"
        "vnp_ReturnUrl"  => $vnp_Returnurl,
        "vnp_TxnRef"     => $vnp_TxnRef,
        "vnp_ExpireDate" => $vnp_ExpireDate,
    ];

    ksort($inputData);
$query = "";
$i = 0;
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $vnp_Url . "?" . $query;
if (isset($vnp_HashSecret)) {
    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
}
    
    header('Location: ' . $vnp_Url);
    exit;

}


// nhận kết quả về thanh toán onl

    public function vnpayReturn()
    {
        $vnp_HashSecret = "UY9FB9GZQANDS66EHLY95NEC6FAHI8HX";
        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash !== $vnp_SecureHash) {
            die('Sai chữ ký VNPAY');
        }

        $orderId = $inputData['vnp_TxnRef'] ?? null;
        if (!$orderId) {
            die('Thiếu mã đơn hàng');
        }

        $orderModel = new Order();
        $order = $orderModel->findById($orderId);
        if (!$order) {
            die('Đơn hàng không tồn tại');
        }

        if (
            $inputData['vnp_ResponseCode'] === '00' &&
            $inputData['vnp_TransactionStatus'] === '00'
        ) {
            $orderModel->updatePaymentStatus(
                $orderId,
                PaymentStatus::PAID,
                // OrderStatus::CONFIRMED
            );

            unset($_SESSION['cart'], $_SESSION['pending_order_id']);
            header('Location: ' . BASE_URL . 'theo-doi-don-hang?paid=1');
            exit;
        }

        header('Location: ' . BASE_URL . 'theo-doi-don-hang?paid=0');
        exit;
    }

}
