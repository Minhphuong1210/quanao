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

        // Lấy dữ liệu cho form
        $categories = $this->categoryModel->getAll();

        // Lấy danh sách nhà cung cấp, sizes, colors từ database
        $sizes = $this->getSizes();
        $colors = $this->getColors();
        $suppliers = $this->getSuppliers();

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $name = trim($_POST['name'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $description = trim($_POST['description'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $category_id = (int)($_POST['category_id'] ?? 0);
            $nha_cung_cap_id = (int)($_POST['nha_cung_cap_id'] ?? 0);
            $active = (int)($_POST['active'] ?? 0);
            $hien_trang_chu = (int)($_POST['hien_trang_chu'] ?? 0);
            $san_pham_noi_bat = (int)($_POST['san_pham_noi_bat'] ?? 0);
            $variants = $_POST['variants'] ?? [];

            // Validation
            $errors = [];
            if (strlen($name) < 3 || strlen($name) > 225) {
                $errors[] = 'Tên sản phẩm phải từ 3 đến 225 ký tự.';
            }
            if ($price <= 0) {
                $errors[] = 'Giá sản phẩm phải lớn hơn 0.';
            }
            if ($category_id <= 0) {
                $errors[] = 'Vui lòng chọn danh mục.';
            }
            if ($nha_cung_cap_id <= 0) {
                $errors[] = 'Vui lòng chọn nhà cung cấp.';
            }

            // Kiểm tra ít nhất 1 biến thể hợp lệ
            $valid_variants = 0;
            foreach ($variants as $v) {
                $size_id = (int)($v['size_id'] ?? 0);
                $color_id = (int)($v['color_id'] ?? 0);
                if ($size_id > 0 && $color_id > 0) {
                    $valid_variants++;
                }
            }
            if ($valid_variants === 0) {
                $errors[] = 'Phải có ít nhất 1 biến thể (size + màu) hợp lệ.';
            }

            if (!empty($errors)) {
                $error = implode('<br>', $errors);
            } else {
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
                        'image_array' => $image_array_str
                    ];

                    // Tạo sản phẩm
                    $productId = $this->productModel->create($productData);

                    if ($productId) {
                        // Thêm các biến thể
                        $variantData = [];
                        foreach ($variants as $variant) {
                            $size_id = (int)($variant['size_id'] ?? 0);
                            $color_id = (int)($variant['color_id'] ?? 0);
                            $stock = (int)($variant['stock'] ?? 0);

                            if ($size_id > 0 && $color_id > 0) {
                                $variantData[] = [
                                    'size_id' => $size_id,
                                    'color_id' => $color_id,
                                    'stock' => $stock
                                ];
                            }
                        }

                        if (!empty($variantData)) {
                            $this->productModel->addVariants($productId, $variantData);
                        }

                        $_SESSION['success'] = "Thêm sản phẩm thành công! ID: $productId";
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
            }
        }

        // Load view
        include BASE_PATH . '/app/views/admin/product/create_product.php';
    }

    public function productEdit($slug)
    {
        $title = 'Chỉnh Sửa Sản Phẩm';
        $pageTitle = 'Sản phẩm';

        // Lấy sản phẩm theo slug
        $product = $this->productModel->findBySlug($slug);
        if (!$product) {
            $_SESSION['error'] = 'Không tìm thấy sản phẩm với slug: ' . htmlspecialchars($slug);
            header('Location: /admin/product');
            exit;
        }

        // Dữ liệu hỗ trợ cho form
        $categories = $this->categoryModel->getAll();
        $sizes      = $this->getSizes();
        $colors     = $this->getColors();
        $suppliers  = $this->getSuppliers();

        // Lấy biến thể hiện tại
        $variants = $this->productModel->getVariants($product['id']);

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form (ưu tiên POST, fallback về giá trị hiện tại)
            $name             = trim($_POST['name']             ?? $product['name']);
            $price            = floatval($_POST['price']        ?? $product['price']);
            $description      = trim($_POST['description']      ?? $product['description']);
            $content          = trim($_POST['content']          ?? $product['content']);
            $category_id      = (int)($_POST['category_id']     ?? $product['category_id']);
            $nha_cung_cap_id  = (int)($_POST['nha_cung_cap_id'] ?? $product['nha_cung_cap_id']);
            $active           = (int)($_POST['active']          ?? $product['active']);
            $hien_trang_chu   = (int)($_POST['hien_trang_chu']  ?? $product['hien_trang_chu']);
            $san_pham_noi_bat = (int)($_POST['san_pham_noi_bat'] ?? $product['san_pham_noi_bat']);

            // Xử lý biến thể
            $postVariants = $_POST['variants'] ?? [];
            $newVariants = [];
            foreach ($postVariants as $v) {
                $size_id  = (int)($v['size_id']  ?? 0);
                $color_id = (int)($v['color_id'] ?? 0);
                $stock    = (int)($v['stock']    ?? 0);
                if ($size_id > 0 && $color_id > 0) {
                    $newVariants[] = [
                        'size_id'  => $size_id,
                        'color_id' => $color_id,
                        'stock'    => $stock
                    ];
                }
            }

            // Validation
            $errors = [];
            if (mb_strlen($name) < 3 || mb_strlen($name) > 225) {
                $errors[] = 'Tên sản phẩm phải từ 3 đến 225 ký tự.';
            }
            if ($price <= 0) {
                $errors[] = 'Giá sản phẩm phải lớn hơn 0.';
            }
            if ($category_id <= 0) {
                $errors[] = 'Vui lòng chọn danh mục.';
            }
            if ($nha_cung_cap_id <= 0) {
                $errors[] = 'Vui lòng chọn nhà cung cấp.';
            }
            if (empty($newVariants)) {
                $errors[] = 'Phải có ít nhất 1 biến thể (size + màu) hợp lệ.';
            }

            if (!empty($errors)) {
                $error = implode('<br>', $errors);
            } else {
                // ─────────────────────────────────────────────────────
                // SỬA PHẦN NÀY: XỬ LÝ ẢNH CHÍNH
                // ─────────────────────────────────────────────────────
                $main_image = $product['image']; // giữ mặc định

                // DEBUG: Kiểm tra file upload
                error_log("DEBUG - Image upload check:");
                error_log("File name: " . ($_FILES['image']['name'] ?? 'empty'));
                error_log("File error: " . ($_FILES['image']['error'] ?? 'no file'));
                error_log("File size: " . ($_FILES['image']['size'] ?? 0));

                // Kiểm tra xem có upload ảnh mới không
                if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadedMain = $this->uploadMainImage($_FILES['image'], $product['image']);
                    error_log("DEBUG - uploadMainImage returned: " . $uploadedMain);

                    if ($uploadedMain && $uploadedMain !== $product['image']) {
                        $main_image = $uploadedMain;
                        error_log("DEBUG - Main image updated to: " . $main_image);
                    } else {
                        error_log("DEBUG - Main image NOT updated, using old: " . $product['image']);
                    }
                }

                // ─────────────────────────────────────────────────────
                // SỬA PHẦN NÀY: XỬ LÝ ẢNH PHỤ
                // ─────────────────────────────────────────────────────
                $image_array_str = $product['image_array'] ?: '';

                // Kiểm tra xem có upload ảnh phụ mới không
                if (!empty($_FILES['image_array']['name'][0]) && $_FILES['image_array']['error'][0] === UPLOAD_ERR_OK) {
                    // Upload ảnh phụ mới
                    $uploadedExtras = $this->uploadExtraImages($_FILES['image_array']);
                    error_log("DEBUG - uploadExtraImages count: " . count($uploadedExtras));

                    if (!empty($uploadedExtras)) {
                        // Nếu có ảnh cũ, ghép với ảnh mới (thay vì xóa)
                        if (!empty($product['image_array'])) {
                            $oldExtras = explode(',', $product['image_array']);
                            $uploadedExtras = array_merge($oldExtras, $uploadedExtras);
                        }
                        $image_array_str = implode(',', array_filter($uploadedExtras));
                        error_log("DEBUG - Image array updated to: " . $image_array_str);
                    }
                }

                // Chuẩn bị dữ liệu cập nhật
                $data = [
                    'name'             => $name,
                    'price'            => $price,
                    'description'      => $description,
                    'content'          => $content,
                    'category_id'      => $category_id,
                    'nha_cung_cap_id'  => $nha_cung_cap_id,
                    'active'           => $active,
                    'hien_trang_chu'   => $hien_trang_chu,
                    'san_pham_noi_bat' => $san_pham_noi_bat,
                    'image'            => $main_image,
                    'image_array'      => $image_array_str,
                    'slug'             => $product['slug'], // Giữ slug cũ nếu không thay đổi tên
                ];

                // Nếu tên thay đổi, cần tạo slug mới
                if ($name !== $product['name']) {
                    // Gọi phương thức tạo slug từ Product model
                    $newSlug = $this->generateSlugFromName($name, $product['id']);
                    $data['slug'] = $newSlug;
                    error_log("DEBUG - Name changed, new slug: " . $newSlug);
                }

                try {
                    // DEBUG: Kiểm tra dữ liệu trước khi cập nhật
                    error_log("DEBUG - Data to update: " . print_r($data, true));

                    // Sử dụng phương thức update của Product model
                    $updated = $this->productModel->update($product['id'], $data);

                    if ($updated) {
                        // Xóa biến thể cũ → thêm biến thể mới
                        $this->productModel->deleteAllVariants($product['id']);
                        if (!empty($newVariants)) {
                            $this->productModel->addVariants($product['id'], $newVariants);
                        }

                        $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';

                        // Redirect theo slug mới (nếu có thay đổi)
                        header('Location: /admin/product');

                        exit;
                    } else {
                        $error = 'Cập nhật sản phẩm thất bại. Vui lòng thử lại.';
                        error_log("ERROR - Product update failed");
                    }
                } catch (Exception $e) {
                    $error = 'Lỗi hệ thống: ' . $e->getMessage();
                    error_log("Product edit error: " . $e->getMessage());
                }
            }

            // Nếu có lỗi → giữ lại dữ liệu form để hiển thị lại
            if ($error) {
                $product = array_merge($product, [
                    'name'             => $name,
                    'price'            => $price,
                    'description'      => $description,
                    'content'          => $content,
                    'category_id'      => $category_id,
                    'nha_cung_cap_id'  => $nha_cung_cap_id,
                    'active'           => $active,
                    'hien_trang_chu'   => $hien_trang_chu,
                    'san_pham_noi_bat' => $san_pham_noi_bat,
                    'image'            => $main_image,
                    'image_array'      => $image_array_str,
                ]);
                $variants = $newVariants; // dùng biến thể từ POST
            }
        }

        // Truyền dữ liệu cho view
        include BASE_PATH . '/app/views/admin/product/edit_product.php';
    }
    private function generateSlugFromName($name, $excludeId = 0)
    {
        // Tạo slug từ tên
        $slug = $this->productModel->generateSlug($name);

        // Kiểm tra slug có tồn tại không (trừ sản phẩm hiện tại)
        $counter = 1;
        $originalSlug = $slug;

        while ($this->productModel->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
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
