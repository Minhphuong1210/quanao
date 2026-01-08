<?php
require_once BASE_PATH . '/app/models/Product.php';
class HomeController 
{
    public function index(){
        $productModel = new Product();
        $product_banner = $productModel->getActiveBanner();



        $products = $productModel->getHomeProducts();

        $product_featured = $productModel->getFeaturedProducts();

        $product_active = $productModel->getActive();

        include BASE_PATH . '/app/views/user/home/home.php';
    }
}
