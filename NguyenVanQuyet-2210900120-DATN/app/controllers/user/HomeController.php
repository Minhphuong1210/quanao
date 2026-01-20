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
        $productModel = new Product();
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 9;
        $data = $productModel->getActive($page, $limit);
        $product_active = $data['products'];

        include BASE_PATH . '/app/views/user/home/home.php';
    }

    public function tatCaSanPham()
    {
        $productModel = new Product();
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 9;

        $data = $productModel->getActive($page, $limit);
        $product_active = $data['products'];
        $totalPages = $data['pages'];
        $currentPage = $data['page'];

        include BASE_PATH . '/app/views/user/home/tatCaSanPham.php';
    }

    public function sanPhamTheoDanhMuc($slug)
    {
        $productModel = new Product();
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 9;

        $data = $productModel->getByCategorySlug($slug, $page, $limit);
        $product_active = $data['products'];
        $totalPages = $data['pages'];
        $currentPage = $data['page'];

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

        // Tăng lượt xem
        $product['view']++;
        $productModel->saveView($product['id'], $product['view']);

        // Lấy thông tin danh mục
        $category = $categoryModel->find($product['category_id']);

        // Lấy sản phẩm liên quan
        $related = $productModel->getByCategorySlug($category['slug'], 1, 4);
        $relatedProducts = $related['products'];

        // Lấy thông tin biến thể đầy đủ (kèm hình ảnh)
        $allVariants = $productDetailModel->getAllVariants($product['id']);

        // Lấy màu sắc có sẵn (với thông tin ảnh)
        $colors = $productDetailModel->getAvailableColors($product['id']);

        // Lấy kích cỡ có sẵn
        $sizes = $productDetailModel->getAvailableSizes($product['id']);

        // 🎯 QUAN TRỌNG: Xử lý ảnh theo đúng yêu cầu
        // 1. Lấy ảnh chính (mặc định) từ trường 'image'
        $main_image = $product['image'] ?? '';

        // 2. Lấy danh sách ảnh phụ từ 'image_array' - sử dụng phương thức mới
        $sub_images = $productDetailModel->getSubImages($product['id']);

        // 3. Hoặc lấy tất cả ảnh (phân biệt rõ ràng)
        $all_product_images = $productDetailModel->getAllProductImages($product['id']);
        // $all_product_images['main_image'] - ảnh chính
        // $all_product_images['sub_images'] - mảng ảnh phụ

        // Truyền dữ liệu ra view
        include BASE_PATH . '/app/views/user/home/chiTietSanPham.php';
    }

    // AJAX: Lấy kích cỡ theo màu đã chọn
    // AJAX: Lấy kích cỡ theo màu đã chọn
    public function getSizesByColor($product_id, $color_id)
    {
        $productDetailModel = new Product_Detail();
        $sizes = $productDetailModel->getSizesWithStock($product_id, $color_id);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'sizes' => $sizes
        ]);
        exit();
    }

    // AJAX: Kiểm tra tồn kho
    public function checkStock($product_id, $color_id, $size_id)
    {
        $productDetailModel = new Product_Detail();
        $variant = $productDetailModel->checkVariantAvailability($product_id, $color_id, $size_id);

        if ($variant) {
            echo json_encode([
                'success' => true,
                'stock' => $variant['stock'],
                'variant_id' => $variant['id'],
                'is_available' => $variant['is_available']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Biến thể không tồn tại'
            ]);
        }
        exit();
    }

    // AJAX: Lấy thông tin variant chi tiết
    public function getVariantDetail($product_id, $color_id, $size_id)
    {
        $productDetailModel = new Product_Detail();
        $variant = $productDetailModel->getProductDetail($product_id, $color_id, $size_id);

        if ($variant) {
            echo json_encode([
                'success' => true,
                'variant' => $variant
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy biến thể'
            ]);
        }
        exit();
    }

public function gioiThieuVeChungToi(){
    include BASE_PATH . '/app/views/user/home/gioiThieuVeChungToi.php';
}

public function danhMucTinTuc(){
    include BASE_PATH . '/app/views/user/home/danhMucTinTuc.php';
}

}
