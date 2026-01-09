<?php
// Định nghĩa BASE_PATH nếu chưa có (tránh lỗi redefinition)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(dirname(dirname(__DIR__)))));
}

// Xử lý form submit
require_once BASE_PATH . '/config/database.php';  // Include file database.php của bạn

$error = $success = '';  // Biến để hiển thị thông báo

// Load category cho select (sử dụng Database singleton)
$category = [];
$pdo = Database::getInstance();  // Lấy PDO connection từ singleton
try {
    $sql_cat = "SELECT id, name FROM category ORDER BY name";  // Sửa tên bảng từ 'category' thành 'category'
    $stmt_cat = $pdo->prepare($sql_cat);
    $stmt_cat->execute();
    $category = $stmt_cat->fetchAll();
} catch (PDOException $e) {
    $error = 'Lỗi tải danh mục: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input cơ bản
    $name = trim($_POST['name'] ?? '');
    $price = intval(str_replace('.', '', $_POST['price'] ?? '0'));
    $description = trim($_POST['description'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $status = $_POST['status'] ?? 'active';

    if (empty($name) || $price <= 0 || empty($category_id) || empty($_FILES['image']['name'])) {
        $error = 'Vui lòng điền đầy đủ thông tin bắt buộc (tên, giá, danh mục, ảnh)!';
    } else {

        /* ================= UPLOAD ẢNH ================= */

        $image_path = ''; // path lưu DB (web path)

        // LẤY ĐƯỜNG DẪN THẬT TỚI public/
        $public_path = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
        $upload_dir  = $public_path . '/uploads/products/';

        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

            $file_tmp  = $_FILES['image']['tmp_name'];
            $file_size = $_FILES['image']['size'];
            $file_ext  = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_ext, $allowed_ext) && $file_size <= 5 * 1024 * 1024) {

                $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
                $target_path  = $upload_dir . $new_filename;

                if (move_uploaded_file($file_tmp, $target_path)) {
                    // LƯU PATH WEB
                    $image_path = '/uploads/products/' . $new_filename;
                } else {
                    $error = 'Không thể lưu file ảnh. Kiểm tra quyền thư mục uploads.';
                }

            } else {
                $error = 'Ảnh không hợp lệ (JPG/PNG/GIF, tối đa 5MB).';
            }

        } else {
            $error = 'Lỗi upload ảnh.';
        }

        /* ================= LƯU DB ================= */

        if (empty($error) && !empty($image_path)) {
            try {
                $sql = "INSERT INTO products 
                        (name, price, description, category_id, stock, status, image)
                        VALUES 
                        (:name, :price, :description, :category_id, :stock, :status, :image)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name'        => $name,
                    ':price'       => $price,
                    ':description' => $description,
                    ':category_id' => $category_id,
                    ':stock'       => $stock,
                    ':status'      => $status,
                    ':image'       => $image_path
                ]);

                $success = 'Thêm sản phẩm thành công!';
                $_POST = [];

            } catch (PDOException $e) {
                $error = 'Lỗi DB: ' . $e->getMessage();

                // rollback ảnh
                if (file_exists($upload_dir . basename($image_path))) {
                    unlink($upload_dir . basename($image_path));
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - <?= $title ?? 'Thêm Sản Phẩm' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --text-light: #f1f5f9;
            --border-light: rgba(255, 255, 255, 0.1);
        }

        body {
            background: linear-gradient(135deg, var(--bg-dark) 0%, #1e293b 100%);
            color: var(--text-light);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow-x: hidden;
        }

        .sidebar {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border-light);
            height: 100vh;
            position: fixed;
            width: 280px;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link {
            color: var(--text-light);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: var(--primary);
            color: white;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .header {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-light);
            padding: 16px 24px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .main-content {
            margin-left: 280px;
            padding: 24px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .content-placeholder {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            border: 1px solid var(--border-light);
            min-height: 400px;
        }

        .footer {
            background: rgba(15, 23, 42, 0.95);
            border-top: 1px solid var(--border-light);
            padding: 16px 0;
            text-align: center;
            margin-top: auto;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="d-flex flex-column h-100">

    <?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
    <?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

    <main class="main-content flex-grow-1 d-flex flex-column">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger rounded-3 mb-4 shadow-sm">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success rounded-3 mb-4 shadow-sm">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- app/views/admin/product/create.php -->
        <div class="content-placeholder flex-grow-1"> <!-- Giống cấu trúc index -->

            <h4 class="text-light mb-4">Thêm Sản Phẩm Mới</h4>

            <div class="card bg-dark border-0 rounded-3 shadow-lg p-4 p-lg-5" style="border: 1px solid rgba(255,255,255,0.1);">
                <form method="POST" enctype="multipart/form-data" id="productForm">

                    <!-- Tên sản phẩm -->
                    <div class="mb-4">
                        <label for="name" class="form-label text-light fw-semibold fs-5">
                            Tên sản phẩm <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control form-control-lg bg-secondary text-light border-0 shadow-sm"
                            placeholder="Nhập tên sản phẩm..."
                            value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                        <div class="form-text text-muted mt-2">Tên sản phẩm sẽ hiển thị trên trang web.</div>
                    </div>

                    <!-- Giá -->
                    <div class="mb-4">
                        <label for="price" class="form-label text-light fw-semibold fs-5">
                            Giá (VNĐ) <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="price" id="price" class="form-control form-control-lg bg-secondary text-light border-0 shadow-sm"
                            placeholder="0"
                            value="<?= $_POST['price'] ?? '' ?>" required>
                        <div class="form-text text-muted mt-2">Giá bán sản phẩm, tự động định dạng có dấu chấm.</div>
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-4">
                        <label for="description" class="form-label text-light fw-semibold fs-5">Mô tả</label>
                        <textarea name="description" id="description" class="form-control bg-secondary text-light border-0 shadow-sm"
                            rows="6" placeholder="Nhập mô tả chi tiết sản phẩm..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        <div class="form-text text-muted mt-2">Hỗ trợ Markdown hoặc HTML nếu cần.</div>
                    </div>

                    <!-- Danh mục -->
                    <div class="mb-4">
                        <label for="category_id" class="form-label text-light fw-semibold fs-5">
                            Danh mục <span class="text-danger">*</span>
                        </label>
                        <select name="category_id" id="category_id" class="form-select form-select-lg bg-secondary text-light border-0 shadow-sm" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($category as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($_POST['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tồn kho -->
                    <div class="mb-4">
                        <label for="stock" class="form-label text-light fw-semibold fs-5">Số lượng tồn kho</label>
                        <input type="number" name="stock" id="stock" class="form-control form-control-lg bg-secondary text-light border-0 shadow-sm"
                            value="<?= $_POST['stock'] ?? 0 ?>" min="0" placeholder="0">
                        <div class="form-text text-muted mt-2">Đặt 0 nếu hết hàng.</div>
                    </div>

                    <!-- Trạng thái -->
                    <div class="mb-4">
                        <label for="status" class="form-label text-light fw-semibold fs-5">
                            Trạng thái <span class="text-danger">*</span>
                        </label>
                        <select name="status" id="status" class="form-select form-select-lg bg-secondary text-light border-0 shadow-sm" required>
                            <option value="active" <?= ($_POST['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="inactive" <?= ($_POST['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                    </div>

                    <!-- Ảnh sản phẩm -->
                    <div class="mb-5">
                        <label for="image" class="form-label text-light fw-semibold fs-5">
                            Ảnh sản phẩm <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="image" id="image" class="form-control form-control-lg bg-secondary text-light border-0 shadow-sm"
                            accept="image/*" required>
                        <div class="form-text text-muted mt-2">Chỉ chấp nhận JPG, PNG. Tối đa 5MB.</div>
                        <div id="imagePreviewContainer" class="mt-4"></div>
                    </div>

                    <!-- Nút hành động -->
                    <div class="d-flex flex-wrap justify-content-end gap-3 mt-5">
                        <button type="submit" class="btn btn-success btn-lg px-5 py-3 shadow-sm">
                            <i class="fas fa-save me-2"></i> Lưu Sản Phẩm
                        </button>
                        <a href="/admin/product" class="btn btn-secondary btn-lg px-5 py-3 shadow-sm">
                            <i class="fas fa-times me-2"></i> Hủy
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script>
        // Preview ảnh khi chọn
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const container = document.getElementById('imagePreviewContainer');
            container.innerHTML = '';

            if (file) {
                // Kiểm tra kích thước file (tối đa 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ảnh không được lớn hơn 5MB!');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail rounded-3 shadow';
                    img.style.maxHeight = '300px';
                    img.style.border = '2px solid #6366f1';
                    img.alt = 'Preview ảnh sản phẩm';
                    container.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });

        // Format giá tự động có dấu chấm (VD: 1.500.000)
        document.getElementById('price').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Chỉ giữ số
            if (value) {
                e.target.value = parseInt(value).toLocaleString('vi-VN');
            }
        });

        // Focus vào tên sản phẩm khi load trang
        document.getElementById('name').focus();
    </script>
</body>

</html>