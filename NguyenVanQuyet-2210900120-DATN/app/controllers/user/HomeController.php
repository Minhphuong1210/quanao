<?php
require_once BASE_PATH . '/app/models/Product.php';
class HomeController
{
    public function index()
    {
        $productModel = new Product();
        $product_banner = $productModel->getActiveBanner();

        $products = $productModel->getHomeProducts();

        $product_featured = $productModel->getFeaturedProducts();

        $product_active = $productModel->getActive();

        include BASE_PATH . '/app/views/user/home/home.php';
    }

    public function tatCaSanPham()
    {
        $productModel = new Product();
        // Lấy trang hiện tại từ URL, mặc định = 1
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 9; // số sản phẩm/trang

        $data = $productModel->getActive($page, $limit);

        $product_active = $data['products']; // sản phẩm trang hiện tại
        $totalPages = $data['pages']; // tổng số trang
        $currentPage = $data['page']; // trang hiện tại
        include BASE_PATH . '/app/views/user/home/tatCaSanPham.php';
    }

    public function sanPhamTheoDanhMuc($slug)
    {

        $productModel = new Product();
        // Lấy trang hiện tại từ URL, mặc định = 1
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 9; // số sản phẩm/trang

        $data = $productModel->getByCategorySlug($slug, $page, $limit);

        $product_active = $data['products']; // sản phẩm trang hiện tại
        $totalPages = $data['pages']; // tổng số trang
        $currentPage = $data['page'];

        // trang hiện tại
        include BASE_PATH . '/app/views/user/home/tatCaSanPham.php';
    }
    public function sanPhamTheoNhaCungCap($id)
    {
        $productModel = new Product();

        $page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 9;

        $data = $productModel->getByNhaCungCap((int)$id, $page, $limit);

        $product_active    = $data['products'];
        $totalPages        = $data['pages'];
        $currentPage       = $data['page'];
        $currentSupplierId = (int)$id;

        include BASE_PATH . '/app/views/user/home/tatCaSanPham.php';
    }





    public function sanPhamTheoMauSac($slug)
    {
        $productModel = new Product();

        $page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 9;

        $data = $productModel->getByColorSlug($slug, $page, $limit);

        $product_active = $data['products'];
        $totalPages     = $data['pages'];
        $currentPage    = $data['page'];
        $currentColorSlug = $slug;

        include BASE_PATH . '/app/views/user/home/tatCaSanPham.php';
    }
}
