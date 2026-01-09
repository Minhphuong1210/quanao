<?php
require_once BASE_PATH . '/app/models/Product.php';
require_once BASE_PATH . '/app/models/Color.php';
require_once BASE_PATH . '/app/models/Size.php';
require_once BASE_PATH . '/app/models/category.php';
require_once BASE_PATH . '/app/models/Product_Detail.php';



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
        $colorModel = new Color();
        $sizeModel = new Size();
        $productDetailModel = new Product_Detail();
        $product = $productModel->getBySlug($slug);

        if (!$product) {
            echo "Không có sản phẩm này đâu";
            die();
        }

        // sau khi ấn vào đây thì view sản phẩm cũng sẽ tăng lên
        $product['view']++;


        $productModel->saveView($product['id'], $product['view']);
        $category = $categoryModel->find($product['category_id']);
        $related = $productModel->getByCategorySlug($category['slug'], 1, 4); // lấy 4 sản phẩm
        $relatedProducts = $related['products'];
        $variants = $productDetailModel->getByProduct($product['id']);

        $unique_sizes = [];
        $unique_colors = [];
        
        foreach ($variants as $variant) {
            $unique_sizes[]  = $variant['size_id'];
            $unique_colors[] = $variant['color_id'];
        }
        
        $unique_sizes  = array_unique($unique_sizes);
        $unique_colors = array_unique($unique_colors);

        $sizes  = $sizeModel->getByIds($unique_sizes);
$colors = $colorModel->getByIds($unique_colors);

        include BASE_PATH . '/app/views/user/home/chiTietSanPham.php';
    }

}
