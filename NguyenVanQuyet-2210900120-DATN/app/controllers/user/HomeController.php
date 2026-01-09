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

        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit = 9;

        $data = $productModel->getByNhaCungCap((int) $id, $page, $limit);

        $product_active = $data['products'];
        $totalPages = $data['pages'];
        $currentPage = $data['page'];
        $currentSupplierId = (int) $id;

        include BASE_PATH . '/app/views/user/home/tatCaSanPham.php';
    }

    public function sanPhamTheoMauSac($slug)
    {
        $productModel = new Product();

        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit = 9;

        $data = $productModel->getByColorSlug($slug, $page, $limit);

        $product_active = $data['products'];
        $totalPages = $data['pages'];
        $currentPage = $data['page'];
        $currentColorSlug = $slug;

        include BASE_PATH . '/app/views/user/home/tatCaSanPham.php';
    }

    public function xemChiTietSanPham($slug)
    {
        $productModel = new Product();
$categoryModel = new category;
        $product = $productModel->getBySlug($slug);

        if (!$product) {
            echo "Không có sản phẩm này đâu";
            die();
        }

        // sau khi ấn vào đây thì view sản phẩm cũng sẽ tăng lên
        $product['view']++;

     
        // echo '<pre>';
        // print_r($product); // hiển thị mảng hoặc object
        // echo '</pre>';
        // die(); // dừng để xem kết quả

        $productModel->saveView($product['id'], $product['view']);
        $category = $categoryModel->find($product['category_id']);
        // var_dump($slug_category);
        // $categorySlug = $product['category_slug'] ?? ''; 
        $related = $productModel->getByCategorySlug($category['slug'], 1, 4); // lấy 4 sản phẩm
        $relatedProducts = $related['products'];


        include BASE_PATH . '/app/views/user/home/chiTietSanPham.php';
    }

}
