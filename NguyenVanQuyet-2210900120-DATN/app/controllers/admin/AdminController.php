<?php
require_once BASE_PATH . '/app/models/Product.php';
require_once BASE_PATH . '/app/models/category.php';
require_once BASE_PATH . '/app/helpers/admin_auth.php';

class AdminController
{
    private category $categoryModel;
    private Product $productModel;

    public function __construct()
    {
        // Kiểm tra đăng nhập admin nghiêm ngặt
        checkAdminLogin();

        $this->categoryModel = new category();
        $this->productModel = new Product();
    }

    /* ================= DASHBOARD ================= */

    public function index()
    {
        $title = 'Dashboard';
        $pageTitle = 'Dashboard';  // Để sidebar active

        $stats = [
            'total_categories' => $this->categoryModel->getCount(),
            'total_products' => $this->productModel->getCount(),
            'total_orders' => 0,  // Thay bằng OrderModel sau
            'total_users' => 0    // Thay bằng UserModel sau
        ];

        // Giả lập recent orders cho dashboard
        $recentOrders = $this->getRecentOrders(5);
        include BASE_PATH . '/app/views/admin/layout/layout-admin.php';
    }

    /* ================= CATEGORY ================= */

    public function categoryIndex()
    {
        $title = 'Quản lý Category';
        $pageTitle = 'Quản lý Category';

        // $categories = $this->categoryModel->getAll();
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 5; // số dòng mỗi trang

        // Lấy tất cả danh mục từ model
        $categories = $this->categoryModel->getAll();

        // Lọc theo search
        if ($search !== '') {
            $categories = array_filter($categories, function ($c) use ($search) {
                return str_contains(strtolower($c['name']), strtolower($search));
            });
        }

        // Pagination
        $total = count($categories);
        $pages = ceil($total / $perPage);
        $start = ($page - 1) * $perPage;
        $categoriesPage = array_slice($categories, $start, $perPage);


        include BASE_PATH . '/app/views/admin/category/category.php';
    }

    public function categoryCreate()
    {
        $title = 'Thêm Category Mới';
        $pageTitle = 'Quản lý Category';

        $category = null;  // Cho form chung
        $isEdit = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $slug = mb_strtolower($name, 'UTF-8'); // chuyển Unicode sang chữ thường
            $slug = preg_replace('/\s+/', '-', $slug); // thay khoảng trắng bằng '-'
            $slug = preg_replace('/[^\p{L}\p{N}-]+/u', '', $slug); // giữ chữ, số, '-' thôi
            $slug = trim($slug, '-');

            if (empty($name)) {
                $error = 'Tên category không được để trống!';
            } else {
                if ($this->categoryModel->create($name, $slug)) {
                    // Nếu là AJAX request
                    if (isset($_POST['ajax'])) {
                        echo json_encode(['success' => true, 'message' => 'Thêm category thành công!']);
                        exit;
                    }

                    // Flash message với session
                    $_SESSION['success'] = 'Thêm category thành công!';
                    header('Location: /admin/category');
                    exit;
                } else {
                    $error = 'Lỗi khi thêm category!';
                }
            }
        }


        include BASE_PATH . '/app/views/admin/category/add.php';
    }

    public function categoryEdit($id)
    {
        $title = 'Sửa Category';
        $pageTitle = 'Quản lý Category';

        $category = $this->categoryModel->find($id);
        if (!$category) {
            $_SESSION['error'] = 'Category không tồn tại!';
            header('Location: /admin/category');
            exit;
        }

        $isEdit = true;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            // $description = trim($_POST['description'] ?? '');
            $slug = mb_strtolower($name, 'UTF-8'); // chuyển Unicode sang chữ thường
            $slug = preg_replace('/\s+/', '-', $slug); // thay khoảng trắng bằng '-'
            $slug = preg_replace('/[^\p{L}\p{N}-]+/u', '', $slug); // giữ chữ, số, '-' thôi
            $slug = trim($slug, '-');


            if (empty($name)) {
                $error = 'Tên category không được để trống!';
            } else {
                if ($this->categoryModel->update($id, $name, $slug)) {
                    if (isset($_POST['ajax'])) {
                        echo json_encode(['success' => true, 'message' => 'Cập nhật category thành công!']);
                        exit;
                    }
                    $_SESSION['success'] = 'Cập nhật category thành công!';
                    header('Location: /admin/category');
                    exit;
                } else {
                    $error = 'Lỗi khi cập nhật category!';
                }
            }
        }

        include BASE_PATH . '/app/views/admin/category/edit.php';
    }

    public function categoryDelete($id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            if (isset($_POST['ajax'])) {
                echo json_encode(['success' => false, 'message' => 'Category không tồn tại!']);
                exit;
            }
            $_SESSION['error'] = 'Category không tồn tại!';
            header('Location: /admin/category');
            exit;
        }

        if ($this->categoryModel->delete($id)) {
            if (isset($_POST['ajax'])) {
                echo json_encode(['success' => true, 'message' => 'Xóa category thành công!']);
                exit;
            }
            $_SESSION['success'] = 'Xóa category thành công!';
        } else {
            if (isset($_POST['ajax'])) {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa category!']);
                exit;
            }
            $_SESSION['error'] = 'Lỗi khi xóa category!';
        }
        header('Location: /admin/category');
        exit;
    }

    /* ================= PRODUCT ================= */

    public function productIndex()
    {
        $title = 'Quản lý Sản Phẩm';
        $pageTitle = 'Sản phẩm';  // Để sidebar active

        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 5; // số dòng mỗi trang

        // Lấy tất cả sản phẩm từ model (bao gồm inactive)
        $products = $this->productModel->getAll(false);

        // Lọc theo search (tìm theo tên sản phẩm hoặc tên category)
        if ($search !== '') {
            $products = array_filter($products, function ($p) use ($search) {
                $categoryName = $this->categoryModel->find($p['category_id'])['name'] ?? '';
                return str_contains(strtolower($p['name']), strtolower($search)) ||
                    str_contains(strtolower($categoryName), strtolower($search));
            });
        }

        // Pagination (giống category)
        $total = count($products);
        $pages = ceil($total / $perPage);
        $start = ($page - 1) * $perPage;
        $productsPage = array_slice($products, $start, $perPage);

        // Truyền $categoryModel vào view để dùng find()
        $categoryModel = $this->categoryModel;

        include BASE_PATH . '/app/views/admin/product/index.php';
    }

    public function productCreate()
    {
        $title = 'Thêm Sản Phẩm Mới';
        $pageTitle = 'Sản phẩm';

        $categories = $this->categoryModel->getAll();
        $isEdit = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price       = floatval($_POST['price'] ?? 0);
            $category_id = intval($_POST['category_id'] ?? 0);
            $stock       = intval($_POST['stock'] ?? 0);
            $status      = $_POST['status'] ?? 'active';

            $errors = [];
            if (empty($name))        $errors[] = 'Tên sản phẩm không được để trống!';
            if ($price <= 0)         $errors[] = 'Giá phải lớn hơn 0!';
            if ($category_id <= 0)   $errors[] = 'Vui lòng chọn danh mục!';

            if (empty($errors)) {
                $image = $this->uploadImage($_FILES['image'] ?? [], '');

                $data = [
                    'name'        => $name,
                    'description' => $description,
                    'price'       => $price,
                    'category_id' => $category_id,
                    'stock'       => $stock,
                    'status'      => $status,
                    'image'       => $image,
                ];

                if ($this->productModel->create($data)) {
                    if (isset($_POST['ajax'])) {
                        echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm thành công!']);
                        exit;
                    }

                    $_SESSION['success'] = 'Thêm sản phẩm thành công!';
                    header('Location: /admin/product');
                    exit;
                } else {
                    $error = 'Lỗi khi thêm sản phẩm!';
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }

        // Load view riêng cho thêm mới
        include BASE_PATH . '/app/views/admin/product/create_product.php';
    }


    public function productEdit($id)
    {
        $title = 'Chỉnh Sửa Sản Phẩm';
        $pageTitle = 'Sản phẩm';

        $product = $this->productModel->find($id);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại!';
            header('Location: /admin/product');
            exit;
        }

        $categories = $this->categoryModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price       = str_replace('.', '', $_POST['price'] ?? '0'); // Xóa dấu chấm định dạng
            $price       = floatval($price);
            $category_id = intval($_POST['category_id'] ?? 0);
            $stock       = intval($_POST['stock'] ?? 0);
            $status      = $_POST['status'] ?? 'active';

            $errors = [];
            if (empty($name))              $errors[] = 'Tên sản phẩm không được để trống!';
            if ($price <= 0)               $errors[] = 'Giá phải lớn hơn 0!';
            if ($category_id <= 0)         $errors[] = 'Vui lòng chọn danh mục!';

            if (empty($errors)) {
                $image = $this->uploadImage($_FILES['image'] ?? [], $product['image']);

                $data = [
                    'name'        => $name,
                    'description' => $description,
                    'price'       => $price,
                    'category_id' => $category_id,
                    'stock'       => $stock,
                    'status'      => $status === 'active' ? 'active' : 'inactive', 
                    'image'       => $image,
                ];

                if ($this->productModel->update($id, $data)) {
                    $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
                    header('Location: /admin/product');
                    exit;
                } else {
                    $error = 'Lỗi khi cập nhật sản phẩm!';
                }
            } else {
                $error = implode('<br>', $errors);
                // Gán lại dữ liệu POST vào $product để form giữ giá trị khi lỗi
                $product = array_merge($product, [
                    'name'        => $name,
                    'description' => $description,
                    'price'       => $price,
                    'category_id' => $category_id,
                    'stock'       => $stock,
                    'status'      => $status,
                ]);
            }
        }

        include BASE_PATH . '/app/views/admin/product/edit.php';
    }

    public function productDelete($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            if (isset($_POST['ajax'])) {
                echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại!']);
                exit;
            }
            $_SESSION['error'] = 'Sản phẩm không tồn tại!';
            header('Location: /admin/product');
            exit;
        }

        if ($this->productModel->delete($id)) {
            // Xóa ảnh cũ nếu có
            if (!empty($product['image']) && file_exists(__DIR__ . '/../../' . $product['image'])) {
                unlink(__DIR__ . '/../../' . $product['image']);
            }
            if (isset($_POST['ajax'])) {
                echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công!']);
                exit;
            }
            $_SESSION['success'] = 'Xóa sản phẩm thành công!';
        } else {
            if (isset($_POST['ajax'])) {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa sản phẩm!']);
                exit;
            }
            $_SESSION['error'] = 'Lỗi khi xóa sản phẩm!';
        }
        header('Location: /admin/product');
        exit;
    }

    /* ================= HELPER ================= */

    private function uploadImage($file, $oldImage = '')
    {
        if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return $oldImage;
        }

        $uploadDir = __DIR__ . '/../../uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Xóa ảnh cũ nếu có
            if ($oldImage && file_exists(__DIR__ . '/../../' . $oldImage)) {
                unlink(__DIR__ . '/../../' . $oldImage);
            }
            return 'uploads/products/' . $fileName;
        }

        return $oldImage;  // Giữ cũ nếu fail
    }

    private function getRecentOrders($limit = 5)
    {
        // Giả lập, thay bằng OrderModel sau
        return [
            ['id' => 1, 'amount' => 500000, 'date' => date('Y-m-d'), 'status' => 'Hoàn thành'],
            // Thêm data thực từ DB
        ];
    }
}
