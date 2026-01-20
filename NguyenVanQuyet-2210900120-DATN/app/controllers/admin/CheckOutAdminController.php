<?php
require_once BASE_PATH . '/app/models/Product.php';
require_once BASE_PATH . '/app/models/category.php';
require_once BASE_PATH . '/app/models/Order.php';
require_once BASE_PATH . '/app/models/Order_Detail.php';
// require_once BASE_PATH . '/app/models/category.php';
// require_once BASE_PATH . '/app/models/category.php';
// require_once BASE_PATH . '/app/models/category.php';
// require_once BASE_PATH . '/app/models/category.php';


require_once BASE_PATH . '/app/helpers/admin_auth.php';

class CheckOutAdminController
{
    private category $categoryModel;
    private Product $productModel;
    private Order $order;
    private OrderDetail $order_Detail;

    public function __construct()
    {
        // Kiểm tra đăng nhập admin nghiêm ngặt
        checkAdminLogin();

        $this->categoryModel = new category();
        $this->productModel = new Product();
        $this->order = new Order();
        $this->order_Detail = new OrderDetail();

    }

    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
    
        $orders = $this->order->getAllPaginate($limit, $offset);
        $total  = $this->order->countAll();
        $pages  = ceil($total / $limit);
    
        include BASE_PATH . '/app/views/admin/order/index.php';
    }
    
    public function detail($id)
    {
        $order = $this->order->findById($id);
        $details = $this->order_Detail->getByOrderId($id);
    
        include BASE_PATH . '/app/views/admin/order/detail.php';
    }
    
    public function updateStatus()
    {
        $orderId   = $_POST['order_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;
    
        if (!$orderId || !$newStatus) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/order');
            exit;
        }
    
        // Lấy đơn hàng hiện tại
        $order = $this->order->findById($orderId);
    
        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ' . BASE_URL . 'admin/order');
            exit;
        }
    
        $currentStatus = $order['status'];
    
        // Đơn đã kết thúc thì không cho đổi
        if (OrderStatus::isFinal($currentStatus)) {
            $_SESSION['error'] = 'Đơn hàng đã kết thúc, không thể cập nhật';
            header('Location: ' . BASE_URL . 'admin/order');
            exit;
        }
    
        // Kiểm tra luồng hợp lệ
        if (!OrderStatus::canChange($currentStatus, $newStatus)) {
            $_SESSION['error'] = 'Chuyển trạng thái không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/order');
            exit;
        }
    
        // Update
        $success = $this->order->updateStatus($orderId, $newStatus);
    
        if ($success) {
            $_SESSION['success'] =
                'Cập nhật trạng thái thành công: ' .
                OrderStatus::label($newStatus);
        } else {
            $_SESSION['error'] = 'Cập nhật thất bại, vui lòng thử lại';
        }
    
        header('Location: ' . BASE_URL . 'admin/order');
        exit;
    }
    

}
?>