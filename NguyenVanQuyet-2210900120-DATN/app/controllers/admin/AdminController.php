<?php
require_once BASE_PATH . '/app/models/Product.php';
require_once BASE_PATH . '/app/models/category.php';
require_once BASE_PATH . '/app/models/NhaCungcap.php';
require_once BASE_PATH . '/app/helpers/admin_auth.php';
require_once BASE_PATH . '/app/models/Order.php';
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
       $orderModel = new Order();

        $title = 'Dashboard';
        $pageTitle = 'Dashboard'; 

        $stats = [
            'total_categories' => $this->categoryModel->getCount(),
            'total_products' => $this->productModel->getCount(),
            'total_orders' => 0, 
            'total_users' => 0,
        ];

        // Giả lập recent orders cho dashboard
        $recentOrders = $this->getRecentOrders(5);

        $summary = $orderModel->getSummary();
        $byStatus = $orderModel->getOrderByStatus();
        $revenueByDate = $orderModel->getRevenueByDate();

        include BASE_PATH . '/app/views/admin/layout/layout-admin.php';
    }

    /* ================= CATEGORY ================= */

    public function categoryIndex()
    {
        $title = 'Quản lý Category';
        $pageTitle = 'Quản lý Category';

        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
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

        $category = null; // Cho form chung
        $isEdit = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $str = mb_strtolower($name, 'UTF-8'); // Bỏ dấu
            $viet = [
                'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
                'd' => 'đ',
                'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
                'i' => 'í|ì|ỉ|ĩ|ị',
                'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
                'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
                'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            ];
            foreach ($viet as $ascii => $regex) {
                $str = preg_replace("/($regex)/u", $ascii, $str);
            }

            $slug = preg_replace('/[^a-z0-9]+/', '-', $str);

            $slug = $slug . '-' . time();

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
            $str = mb_strtolower($name, 'UTF-8'); // Bỏ dấu
            $viet = [
                'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
                'd' => 'đ',
                'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
                'i' => 'í|ì|ỉ|ĩ|ị',
                'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
                'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
                'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            ];
            foreach ($viet as $ascii => $regex) {
                $str = preg_replace("/($regex)/u", $ascii, $str);
            }

            $slug = preg_replace('/[^a-z0-9]+/', '-', $str);

            $slug = $slug . '-' . time();

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
        $pageTitle = 'Sản phẩm'; // Để sidebar active

        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $perPage = 5; // số dòng mỗi trang

        // Lấy tất cả sản phẩm từ model (bao gồm inactive)
        $products = $this->productModel->getAll(false);

        // Lọc theo search (tìm theo tên sản phẩm hoặc tên category)
        if ($search !== '') {
            $products = array_filter($products, function ($p) use ($search) {
                $categoryName = $this->categoryModel->find($p['category_id'])['name'] ?? '';
                $supplierName = $p['nha_cung_cap_name'] ?? '';
                return str_contains(strtolower($p['name']), strtolower($search)) ||
                str_contains(strtolower($categoryName), strtolower($search)) ||
                str_contains(strtolower($supplierName), strtolower($search));
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
        $nhaCungCapModels = new NhaCungCap();

        $sizes = $this->getSizes();
        $colors = $this->getColors();
        $suppliers = $this->getSuppliers();
        $error = '';
        $success = '';

// lấy nhà cung cấp nữa là xong

        $nhaCungCaps = $nhaCungCapModels->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form

            $name = trim($_POST['name'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $description = trim($_POST['description'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $category_id = (int) ($_POST['category_id'] ?? 0);
            $nha_cung_cap_id = (int) ($_POST['nha_cung_cap_id'] ?? 0);
            $active = (int) ($_POST['active'] ?? 0);
            $hien_trang_chu = (int) ($_POST['hien_trang_chu'] ?? 0);
            $san_pham_noi_bat = (int) ($_POST['san_pham_noi_bat'] ?? 0);
            $variants = $_POST['variants'] ?? [];

            // Validation
            // $errors = [];
            // if (strlen($name) < 1 || strlen($name) > 225) {
            //     $errors[] = 'Tên sản phẩm phải từ 3 đến 225 ký tự.';
            // }
            // if ($price <= 0) {
            //     $errors[] = 'Giá sản phẩm phải lớn hơn 0.';
            // }
            // if ($category_id <= 0) {
            //     $errors[] = 'Vui lòng chọn danh mục.';
            // }
            // if ($nha_cung_cap_id <= 0) {
            //     $errors[] = 'Vui lòng chọn nhà cung cấp.';
            // }

            // // Kiểm tra ít nhất 1 biến thể hợp lệ
            // $valid_variants = 0;
            // foreach ($variants as $v) {
            //     $size_id = (int)($v['size_id'] ?? 0);
            //     $color_id = (int)($v['color_id'] ?? 0);
            //     if ($size_id > 0 && $color_id > 0) {
            //         $valid_variants++;
            //     }
            // }
            // if ($valid_variants === 0) {
            //     $errors[] = 'Phải có ít nhất 1 biến thể (size + màu) hợp lệ.';
            // }

            // if (!empty($errors)) {
            //     $error = implode('<br>', $errors);
            // } else {
            // Xử lý upload ảnh
            $main_image = $this->uploadMainImage($_FILES['image'] ?? []);
            $image_array = $this->uploadExtraImages($_FILES['image_array'] ?? []);
            $image_array_str = implode(',', $image_array);

            if (empty($main_image)) {
                $error = 'Vui lòng chọn ảnh chính cho sản phẩm.';
            } else {
                // Tạo dữ liệu sản phẩm
                $productData = [
                    'name' => $name,
                    'price' => $price,
                    'description' => $description,
                    'content' => $content,
                    'category_id' => $category_id,
                    'nha_cung_cap_id' => $nha_cung_cap_id,
                    'active' => $active,
                    'hien_trang_chu' => $hien_trang_chu,
                    'san_pham_noi_bat' => $san_pham_noi_bat,
                    'image' => $main_image,
                    'image_array' => $image_array_str,
                ];

                // Tạo sản phẩm
                $productId = $this->productModel->create($productData);

                if ($productId) {
                    // Thêm các biến thể
                    $variantData = [];
                    foreach ($variants as $variant) {
                        $size_id = (int) ($variant['size_id'] ?? 0);
                        $color_id = (int) ($variant['color_id'] ?? 0);
                        $stock = (int) ($variant['stock'] ?? 0);

                        if ($size_id > 0 && $color_id > 0) {
                            $variantData[] = [
                                'size_id' => $size_id,
                                'color_id' => $color_id,
                                'stock' => $stock,
                            ];
                        }
                    }

                    if (!empty($variantData)) {
                        $this->productModel->addVariants($productId, $variantData);
                    }

                    $success = "Thêm sản phẩm thành công!<br>";
                    $success .= "ID sản phẩm: <strong>$productId</strong><br>";
                    $success .= "Danh mục ID: <strong>$category_id</strong><br>";
                    $success .= "Nhà cung cấp ID: <strong>$nha_cung_cap_id</strong><br>";
                    $success .= "Đã thêm <strong>" . count($variantData) . "</strong> biến thể.";

                    // Reset form sau khi thành công
                    $_POST = [];
                    $variants = [];

                    header('Location: /admin/product');
                    exit;

                } else {
                    $error = 'Lỗi khi tạo sản phẩm.';
                    // Xóa ảnh đã upload nếu có lỗi
                    if ($main_image && file_exists(BASE_PATH . '/public/' . $main_image)) {
                        unlink(BASE_PATH . '/public/' . $main_image);
                    }
                    foreach ($image_array as $img) {
                        if (file_exists(BASE_PATH . '/public/' . $img)) {
                            unlink(BASE_PATH . '/public/' . $img);
                        }
                    }
                }
            }
            // }
        }

        // Load view
        include BASE_PATH . '/app/views/admin/product/create_product.php';
    }

    public function productEdit($slug)
    {
        $product = $this->productModel->findBySlug($slug);

        if (!$product) {
            $_SESSION['error'] = 'Không tìm thấy sản phẩm với slug: ' . htmlspecialchars($slug);
            header('Location: /admin/product');
            exit;
        }

        // Xử lý POST (submit form update)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $data = [
                'name' => trim($_POST['name'] ?? $product['name']),
                'price' => (int) ($_POST['price'] ?? $product['price']),
                'description' => trim($_POST['description'] ?? $product['description']),
                'content' => trim($_POST['content'] ?? $product['content']),
                'category_id' => (int) ($_POST['category_id'] ?? $product['category_id']),
                'nha_cung_cap_id' => (int) ($_POST['nha_cung_cap_id'] ?? $product['nha_cung_cap_id']),
                'active' => (int) ($_POST['active'] ?? $product['active']),
                'hien_trang_chu' => (int) ($_POST['hien_trang_chu'] ?? $product['hien_trang_chu']),
                'san_pham_noi_bat' => (int) ($_POST['san_pham_noi_bat'] ?? $product['san_pham_noi_bat']),
            ];

            // Nếu tên thay đổi → tạo slug mới
            if ($data['name'] !== $product['name']) {
                $data['slug'] = $this->productModel->generateSlug($data['name']);
            }

            // Xử lý variants từ form
            $variants = [];
            if (!empty($_POST['variants'])) {
                foreach ($_POST['variants'] as $v) {
                    if (!empty($v['size_id']) && !empty($v['color_id'])) {
                        $variants[] = [
                            'size_id' => (int) $v['size_id'],
                            'color_id' => (int) $v['color_id'],
                            'stock' => (int) ($v['stock'] ?? 0),
                        ];
                    }
                }
            }

            // Xử lý ảnh chính (nếu upload mới)
            if (!isset($_POST['keepMainImage']) || $_POST['keepMainImage'] != '1') {
                if (!empty($_FILES['image']['name'])) {
                    $data['image'] = $this->uploadMainImage($_FILES['image'], $product['image']);
                } else {
                    $data['image'] = $product['image']; // giữ nguyên nếu không upload
                }
            } else {
                $data['image'] = $product['image'];
            }

         

            // Ảnh phụ
            $image_array = [];

            if (!empty($_FILES['image_array']['name'][0])) {

                // Xóa ảnh phụ cũ
                if (!empty($product['image_array'])) {
                    foreach (explode(',', $product['image_array']) as $oldImg) {
                        $oldPath = public_path(trim($oldImg));
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                }

               
                $image_array = $this->uploadExtraImages($_FILES['image_array']);

            } else {
                // Không upload → giữ nguyên
                $image_array = !empty($product['image_array'])
                ? explode(',', $product['image_array'])
                : [];
            }

            $data['image_array'] = implode(',', $image_array);

            // Gọi edit trong model
            $success = $this->productModel->edit($product['id'], $data, $variants);

            if ($success) {
                $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
            } else {
                $_SESSION['error'] = 'Cập nhật thất bại. Kiểm tra log.';
            }

            // Redirect về chính trang edit để xem kết quả mới
            header('Location: /admin/product/edit/' . $data['slug'] ?? $product['slug']);
            exit;
        }

        // Nếu là GET: hiển thị form (code cũ của bạn)
        $variants = $this->productModel->getVariants($product['id']);
        $categories = $this->categoryModel->getAll();
        $sizes = $this->productModel->getAllSizes();
        $colors = $this->productModel->getAllColors();
        $suppliers = $this->productModel->getAllSuppliers();

        $data = [
            'product' => $product,
            'variants' => $variants,
            'categories' => $categories,
            'sizes' => $sizes,
            'colors' => $colors,
            'suppliers' => $suppliers,
        ];

        include BASE_PATH . '/app/views/admin/product/edit_product.php';
    }

    private function uploadSubImage($file)
    {
        $uploadDir = 'uploads/products/sub/';
        if (!is_dir(public_path($uploadDir))) {
            mkdir(public_path($uploadDir), 0777, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('sub_', true) . '.' . $ext;

        move_uploaded_file(
            $file['tmp_name'],
            public_path($uploadDir . $fileName)
        );

        return $uploadDir . $fileName;
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

        // Lưu đường dẫn ảnh để xóa sau
        $main_image = $product['image'] ?? '';
        $image_array = !empty($product['image_array']) ? explode(',', $product['image_array']) : [];

        if ($this->productModel->delete($id)) {
            // Xóa ảnh
            if ($main_image && file_exists(BASE_PATH . '/public/' . $main_image)) {
                unlink(BASE_PATH . '/public/' . $main_image);
            }
            foreach ($image_array as $img) {
                if (file_exists(BASE_PATH . '/public/' . trim($img))) {
                    unlink(BASE_PATH . '/public/' . trim($img));
                }
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

    /* ================= HELPER METHODS ================= */

    private function uploadMainImage($file, $oldImage = '')
    {
        if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return $oldImage;
        }

        $uploadDir = BASE_PATH . '/public/uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Kiểm tra định dạng file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($file['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            return $oldImage;
        }

        // Kiểm tra kích thước file (tối đa 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return $oldImage;
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Xóa ảnh cũ nếu có
            if ($oldImage && file_exists(BASE_PATH . '/public/' . $oldImage)) {
                unlink(BASE_PATH . '/public/' . $oldImage);
            }
            return 'uploads/products/' . $fileName;
        }

        return $oldImage;
    }

    private function uploadExtraImages($files)
    {
        $uploadedImages = [];

        if (!isset($files['name']) || empty($files['name'][0])) {
            return $uploadedImages;
        }

        $uploadDir = BASE_PATH . '/public/uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        foreach ($files['name'] as $key => $name) {
            if ($files['error'][$key] === UPLOAD_ERR_OK) {
                $tmpName = $files['tmp_name'][$key];
                $fileType = mime_content_type($tmpName);

                if (in_array($fileType, $allowedTypes) && $files['size'][$key] <= 5 * 1024 * 1024) {
                    $fileName = uniqid() . '_' . basename($name);
                    $targetPath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $uploadedImages[] = 'uploads/products/' . $fileName;
                    }
                }
            }
        }

        return $uploadedImages;
    }

    private function getSizes()
    {
        return $this->productModel->getAllSizes();
    }

    private function getColors()
    {
        return $this->productModel->getAllColors();
    }

    private function getSuppliers()
    {
        return $this->productModel->getAllSuppliers();
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
