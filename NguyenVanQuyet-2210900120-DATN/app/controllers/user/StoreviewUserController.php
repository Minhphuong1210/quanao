<?php

require_once BASE_PATH . '/app/models/Comment.php';
require_once BASE_PATH . '/app/models/Order.php';
require_once BASE_PATH . '/app/models/Order_Detail.php';
require_once BASE_PATH . '/app/helpers/admin_auth.php';

class StoreviewUserController
{
    protected Comment $commentModel;
    protected Order $orderModel;
    protected OrderDetail $order_DetailModel;

    public function __construct()
    {
        checkUserLogin(); // bắt buộc đăng nhập
        $this->commentModel     = new Comment();
        $this->orderModel       = new Order();
        $this->order_DetailModel = new OrderDetail();
    }

    /**
     * POST /danh-gia-san-pham
     */
    public function storeReview()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL);
            exit;
        }

        $userId    = $_SESSION['user_id'];
        $productId = (int)($_POST['product_id'] ?? 0);
        $orderId   = (int)($_POST['order_id'] ?? 0);
        $star      = (int)($_POST['star'] ?? 0);
        $comment   = trim($_POST['comment'] ?? '');


        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['user_id'] != $userId) {
            $_SESSION['error'] = 'Đơn hàng không hợp lệ';
            $this->back();
        }

    
        if ($order['status'] != OrderStatus::COMPLETED) {
            $_SESSION['error'] = 'Chỉ có thể đánh giá khi đơn hàng đã hoàn thành';
            $this->back();
        }

        if (!$this->order_DetailModel->existsProductInOrder($orderId, $productId)) {
            $_SESSION['error'] = 'Sản phẩm không thuộc đơn hàng này';
            $this->back();
        }

      
        if ($this->commentModel->isReviewed($productId, $userId)) {
            $_SESSION['error'] = 'Bạn đã đánh giá sản phẩm này rồi';
            $this->back();
        }

    
        if ($star < 1 || $star > 5 || empty($comment)) {
            $_SESSION['error'] = 'Dữ liệu đánh giá không hợp lệ';
            $this->back();
        }

      

        $imageName = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = BASE_PATH . '/public/uploads/reviews/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = time() . '_' . basename($_FILES['image']['name']);
            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $uploadDir . $imageName
            );
        }

    

        $this->commentModel->create([
            'order_id'   => $orderId,
            'product_id' => $productId,
            'user_id'    => $userId,
            'comment'    => $comment,
            'star'       => $star,
            'type'       => 'product',
            'image'      => $imageName,
            'parent_id'  => null
        ]);

        $_SESSION['success'] = 'Đánh giá sản phẩm thành công';
        $this->back();
    }



    private function back()
    {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL));
        exit;
    }
}
