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

class CheckOutController
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
        $this->order_Detail = new OrderDetail()

    }

public function index(){
    $getOrder = $this->order->getOrder();


    


}

}
?>