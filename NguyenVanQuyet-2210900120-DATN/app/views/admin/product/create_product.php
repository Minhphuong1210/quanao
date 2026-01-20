<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
define('DEBUG_MODE', true);

function debug_log($message)
{
    if (DEBUG_MODE) {
        error_log(date('[Y-m-d H:i:s] ') . $message);
    }
}

$error = '';
$categories = $sizes = $colors = $suppliers = [];

debug_log("=== DEBUG: Bắt đầu tải trang create_product ===");

try {
    $pdo = Database::getInstance();
    debug_log("DEBUG: Kết nối database thành công");

    // Danh mục active
    $sql_cat = "SELECT id, name FROM category WHERE active = 1 ORDER BY name";
    $stmt_cat = $pdo->prepare($sql_cat);
    $stmt_cat->execute();
    $category = $stmt_cat->fetchAll();
    debug_log("DEBUG: Loaded " . count($category) . " categories");

    // Sizes active
    $sql_sizes = "SELECT id, name, slug FROM sizes WHERE active = 1 ORDER BY name";
    $stmt = $pdo->prepare($sql_sizes);
    $stmt->execute();
    $sizes = $stmt->fetchAll();
    debug_log("DEBUG: Loaded " . count($sizes) . " sizes");

    // Colors active
    $sql_colors = "SELECT id, name, ma_mau, slug FROM colors WHERE active = 1 ORDER BY name";
    $stmt = $pdo->prepare($sql_colors);
    $stmt->execute();
    $colors = $stmt->fetchAll();
    debug_log("DEBUG: Loaded " . count($colors) . " colors");

    // Nhà cung cấp
    $sql_ncc = "SELECT id, name, vi_tri FROM nha_cung_cap ORDER BY name";
    $stmt_ncc = $pdo->prepare($sql_ncc);
    $stmt_ncc->execute();
    $nha_cung_cap = $stmt_ncc->fetchAll();
    debug_log("DEBUG: Loaded " . count($nha_cung_cap) . " nhà cung cấp");
} catch (PDOException $e) {
    $error = 'Lỗi kết nối hoặc tải dữ liệu: ' . htmlspecialchars($e->getMessage());
    debug_log("ERROR: Load form data error: " . $e->getMessage());
}

// ==============================
// XỬ LÝ FORM SUBMIT
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    debug_log("=== DEBUG: Bắt đầu xử lý POST ===");
    debug_log("POST data: " . print_r($_POST, true));
    debug_log("FILES data: " . print_r($_FILES, true));

    // Lấy và làm sạch dữ liệu
    $name          = trim($_POST['name'] ?? '');
    $price         = floatval($_POST['price'] ?? 0);
    $description   = trim($_POST['description'] ?? '');
    $content       = trim($_POST['content'] ?? '');
    $category_id   = (int)($_POST['category_id'] ?? 0);
    $active        = (int)($_POST['active'] ?? 0);
    $hien_trang_chu   = (int)($_POST['hien_trang_chu'] ?? 0);
    $san_pham_noi_bat = (int)($_POST['san_pham_noi_bat'] ?? 0);
    $nha_cung_cap_id = (int)($_POST['nha_cung_cap_id'] ?? 0);
    $variants = $_POST['variants'] ?? [];

    // Validation
    // $errors = [];

    // if (strlen($name) < 3 || strlen($name) > 225) {
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

    $valid_variants = 0;
    foreach ($variants as $v) {
        $size_id  = (int)($v['size_id']  ?? 0);
        $color_id = (int)($v['color_id'] ?? 0);
        if ($size_id > 0 && $color_id > 0) {
            $valid_variants++;
        }
    }
    // if ($valid_variants === 0) {
    //     $errors[] = 'Phải có ít nhất 1 biến thể (size + màu) hợp lệ.';
    // }

    if (!empty($errors)) {
        $error = implode('<br>', $errors);
        debug_log("DEBUG: Validation errors: " . $error);
    } else {
        debug_log("DEBUG: Validation passed");

        // XỬ LÝ UPLOAD ẢNH
        $public_path = realpath(__DIR__ . '/../../../../public');
        if ($public_path === false) {
            $error = 'Không xác định được đường dẫn thư mục public.';
            debug_log("ERROR: Cannot find public path");
        }

        $upload_dir  = $public_path . '/uploads/products/';
        $base_path   = 'public/uploads/products/';

        if (empty($error) && !is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true) && !is_dir($upload_dir)) {
                $error = 'Không tạo được thư mục uploads/products.';
            }
        }

        $main_image     = '';
        $image_array    = [];
        $image_array_str = '';

        // Ảnh chính
        if (empty($error)) {
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($ext, $allowed_ext)) {
                    $error = 'Ảnh chính chỉ chấp nhận jpg, jpeg, png, gif, webp.';
                } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                    $error = 'Ảnh chính không được vượt quá 5MB.';
                } else {
                    $filename = date('YmdHis') . '_' . uniqid() . '.' . $ext;
                    $target   = $upload_dir . $filename;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                        $main_image = $base_path . $filename;
                    } else {
                        $error = 'Không lưu được ảnh chính. Kiểm tra quyền thư mục.';
                    }
                }
            } else {
                $error = 'Vui lòng chọn ảnh chính cho sản phẩm.';
            }
        }

        if (empty($error) && isset($_FILES['image_array']) && $_FILES['image_array']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            // Kiểm tra nếu có file được upload
            if (is_array($_FILES['image_array']['name'])) {
                foreach ($_FILES['image_array']['name'] as $i => $original_name) {
                    // Kiểm tra lỗi upload
                    if ($_FILES['image_array']['error'][$i] !== UPLOAD_ERR_OK) {
                        debug_log("DEBUG: File {$i} upload error: " . $_FILES['image_array']['error'][$i]);
                        continue;
                    }

                    // Kiểm tra xem có file thực sự không
                    if (empty($original_name)) {
                        continue;
                    }

                    $tmp_name = $_FILES['image_array']['tmp_name'][$i];
                    $size     = $_FILES['image_array']['size'][$i];
                    $ext      = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

                    // Kiểm tra định dạng và kích thước
                    if (!in_array($ext, $allowed_ext)) {
                        debug_log("DEBUG: File {$i} invalid extension: {$ext}");
                        continue;
                    }

                    if ($size > 5 * 1024 * 1024) {
                        debug_log("DEBUG: File {$i} too large: {$size} bytes");
                        continue;
                    }

                    // Tạo tên file mới
                    $filename = date('YmdHis') . '_' . uniqid() . '_' . $i . '.' . $ext;
                    $target   = $upload_dir . $filename;

                    if (move_uploaded_file($tmp_name, $target)) {
                        $image_array[] = $base_path . $filename;
                        debug_log("DEBUG: Uploaded array image {$i}: {$filename}");
                    } else {
                        debug_log("DEBUG: Failed to move uploaded file {$i}");
                    }
                }
            }

            debug_log("DEBUG: Total array images uploaded: " . count($image_array));
        }

        $image_array_str = implode(',', $image_array);

        // LƯU VÀO DATABASE
        if (empty($error) && $main_image) {
            $pdo->beginTransaction();
            try {
                // Tạo slug
                $slug_base = createSlug($name);
                $slug = $slug_base;
                $counter = 1;
                while (true) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug = ?");
                    $stmt->execute([$slug]);
                    if ($stmt->fetchColumn() == 0) break;
                    $slug = $slug_base . '-' . $counter++;
                }

                // Insert sản phẩm
                $sql = "INSERT INTO products 
                        (name, price, category_id, image, description, content, 
                         active, hien_trang_chu, san_pham_noi_bat, nha_cung_cap_id, slug, view, image_array)
                        VALUES 
                        (:name, :price, :category_id, :image, :description, :content, 
                         :active, :hien_trang_chu, :san_pham_noi_bat, :nha_cung_cap_id, :slug, 0, :image_array)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name'             => $name,
                    ':price'            => $price,
                    ':category_id'      => $category_id,
                    ':image'            => $main_image,
                    ':description'      => $description,
                    ':content'          => $content,
                    ':active'           => $active,
                    ':hien_trang_chu'   => $hien_trang_chu,
                    ':san_pham_noi_bat' => $san_pham_noi_bat,
                    ':nha_cung_cap_id'  => $nha_cung_cap_id,
                    ':slug'             => $slug,
                    ':image_array'      => $image_array_str
                ]);

                $product_id = $pdo->lastInsertId();

                // Lưu biến thể
                $variant_count = 0;
                if (!empty($variants)) {
                    $variant_sql = "INSERT INTO product_detail (product_id, size_id, color_id, stock) 
                                    VALUES (:pid, :sid, :cid, :stock)";
                    $vstmt = $pdo->prepare($variant_sql);

                    foreach ($variants as $variant) {
                        $size_id  = (int)($variant['size_id']  ?? 0);
                        $color_id = (int)($variant['color_id'] ?? 0);
                        $stock    = max(0, (int)($variant['stock'] ?? 0));

                        if ($size_id <= 0 || $color_id <= 0) continue;

                        $vstmt->execute([
                            ':pid'   => $product_id,
                            ':sid'   => $size_id,
                            ':cid'   => $color_id,
                            ':stock' => $stock
                        ]);
                        $variant_count++;
                    }
                }

                $pdo->commit();

                // Lưu thông báo flash
                $_SESSION['success_message'] = "Thêm sản phẩm thành công! ID: $product_id - " . htmlspecialchars($name);

                // Đóng kết nối
                $pdo = null;

                // REDIRECT về danh sách sản phẩm
                debug_log("=== REDIRECT TO /admin/product ===");
                if (ob_get_length()) {
                    debug_log("WARNING: Output buffer có nội dung trước redirect: " . ob_get_length() . " bytes");
                }
                header('Location: /admin/product');
                exit();
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = 'Lỗi lưu dữ liệu: ' . htmlspecialchars($e->getMessage());
                debug_log("ERROR: " . $e->getMessage());

                // Xóa ảnh nếu lỗi
                if ($main_image && file_exists($public_path . '/' . $main_image)) {
                    @unlink($public_path . '/' . $main_image);
                }
                foreach ($image_array as $img) {
                    if (file_exists($public_path . '/' . $img)) {
                        @unlink($public_path . '/' . $img);
                    }
                }
            }
        }
    }
}

// Hàm tạo slug
function createSlug($string)
{
    $search = array(
        '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
        '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
        '#(ì|í|ị|ỉ|ĩ)#',
        '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
        '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
        '#(ỳ|ý|ỵ|ỷ|ỹ)#',
        '#(đ)#',
        '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
        '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
        '#(Ì|Í|Ị|Ỉ|Ĩ)#',
        '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|ỡ)#',
        '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
        '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
        '#(Đ)#',
        '/[^a-zA-Z0-9\-\_]/',
    );

    $replace = array(
        'a',
        'e',
        'i',
        'o',
        'u',
        'y',
        'd',
        'A',
        'E',
        'I',
        'O',
        'U',
        'Y',
        'D',
        '-'
    );

    $string = preg_replace($search, $replace, $string);
    $string = preg_replace('/(-)+/', '-', $string);
    $string = strtolower($string);
    $string = trim($string, '-');

    return $string;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - Thêm Sản Phẩm</title>
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
            border: 1px solid var(--border-light);
        }

        .form-control,
        .form-select {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: var(--text-light) !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
        }

        .variant-row {
            background: rgba(30, 41, 59, 0.5);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .variant-row:hover {
            border-color: var(--primary);
            background: rgba(30, 41, 59, 0.8);
        }

        .btn-add-variant {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border: none;
            color: white;
        }

        .btn-add-variant:hover {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        }

        .btn-remove-variant {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-remove-variant:hover {
            background: rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .form-check-input {
            background-color: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            cursor: pointer;
        }

        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--primary);
        }

        .debug-info {
            background: rgba(0, 0, 0, 0.3);
            border-left: 4px solid #6366f1;
            padding: 10px;
            margin: 10px 0;
            font-family: monospace;
            font-size: 12px;
            max-height: 200px;
            overflow-y: auto;
        }

        .required-star {
            color: #ff6b6b;
        }
    </style>
</head>

<body class="d-flex flex-column h-100">

    <?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
    <?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

    <main class="main-content flex-grow-1 d-flex flex-column">

        <div class="content-placeholder flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h3 class="text-light mb-2">Thêm Sản Phẩm Mới</h3>
                    <p class="text-muted mb-0">Thêm sản phẩm và các biến thể (size, màu sắc)</p>
                </div>
                <a href="/admin/product" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- <?php if (DEBUG_MODE): ?>
                <div class="debug-info">
                    <strong>DEBUG INFO:</strong> Check PHP error_log for detailed information<br>
                    <?php
                    if (!empty($error)) echo "Error: " . htmlspecialchars($error) . "<br>";
                    ?>
                </div>
            <?php endif; ?> -->

            <div class="card bg-dark border-0 rounded-3 shadow-lg p-4 p-lg-5">
                <form method="POST" enctype="multipart/form-data" id="productForm">

                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-light mb-4 border-bottom pb-3">
                                <i class="fas fa-info-circle me-2"></i>Thông tin cơ bản
                            </h5>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-4">
                                <label for="name" class="form-label text-light fw-semibold">
                                    Tên sản phẩm <span class="required-star">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                    class="form-control form-control-lg"
                                    placeholder="Nhập tên sản phẩm..."
                                    value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="price" class="form-label text-light fw-semibold">
                                    Giá (VNĐ) <span class="required-star">*</span>
                                </label>
                                <input type="number" name="price" id="price"
                                    class="form-control form-control-lg"
                                    placeholder="0"
                                    min="0"
                                    step="1"
                                    value="<?= htmlspecialchars($_POST['price'] ?? '') ?>"
                                    required>
                                <small class="text-muted mt-1">Nhập số nguyên</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="category_id" class="form-label text-light fw-semibold">
                                    <i class="fas fa-folder me-2"></i>Danh mục <span class="required-star">*</span>
                                </label>
                                <select name="category_id" id="category_id"
                                    class="form-select form-select-lg"
                                    required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($category as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"
                                            <?= ($_POST['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="nha_cung_cap_id" class="form-label text-light fw-semibold">
                                    <i class="fas fa-truck me-2"></i>Nhà cung cấp <span class="required-star">*</span>
                                </label>
                                <select name="nha_cung_cap_id" id="nha_cung_cap_id"
                                    class="form-select form-select-lg"
                                    required>
                                    <option value="">-- Chọn nhà cung cấp --</option>
                                    <?php foreach ($nha_cung_cap as $ncc): ?>
                                        <option value="<?= $ncc['id'] ?>"
                                            <?= ($_POST['nha_cung_cap_id'] ?? '') == $ncc['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($ncc['name']) ?>
                                            <?php if (!empty($ncc['vi_tri'])): ?>
                                                <span class="text-muted"> (<?= htmlspecialchars($ncc['vi_tri']) ?>)</span>
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <!-- Hiển thị ở trang chủ -->
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="hien_trang_chu" class="form-label text-light fw-semibold">
                                    <i class="fas fa-home me-2"></i>Hiển thị ở trang chủ
                                </label>
                                <select name="hien_trang_chu" id="hien_trang_chu" class="form-select form-select-lg">
                                    <option value="1" <?= ($_POST['hien_trang_chu'] ?? 1) == 1 ? 'selected' : '' ?>>Có</option>
                                    <option value="0" <?= ($_POST['hien_trang_chu'] ?? 1) == 0 ? 'selected' : '' ?>>Không</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sản phẩm nổi bật -->
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="san_pham_noi_bat" class="form-label text-light fw-semibold">
                                    <i class="fas fa-star me-2"></i>Sản phẩm nổi bật
                                </label>
                                <select name="san_pham_noi_bat" id="san_pham_noi_bat" class="form-select form-select-lg">
                                    <option value="1" <?= ($_POST['san_pham_noi_bat'] ?? 0) == 1 ? 'selected' : '' ?>>Có</option>
                                    <option value="0" <?= ($_POST['san_pham_noi_bat'] ?? 0) == 0 ? 'selected' : '' ?>>Không</option>
                                </select>
                            </div>
                        </div>

                        <!-- Trạng thái (active) -->
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="active" class="form-label text-light fw-semibold">
                                    <i class="fas fa-toggle-on me-2"></i>Trạng thái
                                </label>
                                <select name="active" id="active" class="form-select form-select-lg">
                                    <option value="1" <?= ($_POST['active'] ?? 1) == 1 ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="0" <?= ($_POST['active'] ?? 1) == 0 ? 'selected' : '' ?>>Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label text-light fw-semibold">Mô tả ngắn</label>
                        <textarea name="description" id="description"
                            class="form-control"
                            rows="3"
                            placeholder="Nhập mô tả ngắn về sản phẩm..."
                            maxlength="225"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        <div class="form-text text-muted">Tối đa 225 ký tự</div>
                    </div>

                    <div class="mb-4">
                        <label for="content" class="form-label text-light fw-semibold mb-3">
                            <i class="fas fa-align-left me-2"></i>Mô tả chi tiết
                        </label>
                        <textarea name="content" id="content"
                            class="form-control"
                            rows="10"
                            placeholder="Nhập mô tả chi tiết về sản phẩm..."><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-5">
                        <label for="image" class="form-label text-light fw-semibold">
                            <i class="fas fa-image me-2"></i>Ảnh sản phẩm chính <span class="required-star">*</span>
                        </label>
                        <input type="file" name="image" id="image"
                            class="form-control form-control-lg"
                            accept="image/*"
                            required>
                        <div class="form-text text-muted mt-1">Chỉ chấp nhận JPG, PNG, GIF, WEBP. Tối đa 5MB.</div>
                        <div id="imagePreviewContainer" class="mt-3">
                            <div class="border rounded-3 p-4 text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                                <i class="fas fa-image fs-1 text-muted mb-3"></i>
                                <p class="text-muted mb-0">Ảnh preview sẽ hiển thị tại đây</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="image_array" class="form-label text-light fw-semibold">
                            <i class="fas fa-images me-2"></i>Ảnh sản phẩm phụ (image_array)
                        </label>
                        <input type="file" name="image_array[]" id="image_array"
                            class="form-control form-control-lg"
                            accept="image/*"
                            multiple>
                        <div class="form-text text-muted mt-1">Có thể chọn nhiều ảnh. Chỉ chấp nhận JPG, PNG, GIF, WEBP. Mỗi ảnh tối đa 5MB.</div>
                        <div id="imageArrayPreviewContainer" class="mt-3 image-preview-container">
                            <div class="border rounded-3 p-4 text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                                <i class="fas fa-images fs-1 text-muted mb-3"></i>
                                <p class="text-muted mb-0">Ảnh phụ sẽ hiển thị tại đây</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-light mb-0">
                                <i class="fas fa-layer-group me-2"></i>Biến thể sản phẩm (Size & Màu sắc)
                            </h5>
                            <button type="button" id="addVariant" class="btn btn-add-variant">
                                <i class="fas fa-plus me-2"></i>Thêm biến thể
                            </button>
                        </div>

                        <div id="variantsContainer">
                            <div class="variant-row" data-index="0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label text-light">Size</label>
                                            <select name="variants[0][size_id]" class="form-select size-select">
                                                <option value="">-- Chọn size --</option>
                                                <?php foreach ($sizes as $size): ?>
                                                    <option value="<?= $size['id'] ?>"
                                                        <?= (isset($_POST['variants'][0]['size_id']) && $_POST['variants'][0]['size_id'] == $size['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($size['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label text-light">Màu sắc</label>
                                            <select name="variants[0][color_id]" class="form-select color-select">
                                                <option value="">-- Chọn màu --</option>
                                                <?php foreach ($colors as $color): ?>
                                                    <option value="<?= $color['id'] ?>"
                                                        <?= (isset($_POST['variants'][0]['color_id']) && $_POST['variants'][0]['color_id'] == $color['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($color['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label text-light">Số lượng</label>
                                            <input type="number" name="variants[0][stock]"
                                                class="form-control variant-stock"
                                                min="0"
                                                value="<?= htmlspecialchars($_POST['variants'][0]['stock'] ?? '0') ?>"
                                                placeholder="0">
                                        </div>
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-remove-variant" onclick="removeVariant(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top border-secondary">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-cubes fs-4 text-primary me-3"></i>
                                        <div>
                                            <h6 class="text-light mb-1">Tổng số lượng tồn kho</h6>
                                            <p class="text-muted mb-0">Tổng từ tất cả các biến thể</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="display-4 fw-bold text-success" id="totalStock">0</div>
                                    <input type="hidden" name="stock" id="totalStockInput" value="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-3 mt-5 pt-4 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                        <button type="reset" class="btn btn-secondary btn-lg px-5 py-3" onclick="resetForm()">
                            <i class="fas fa-redo me-2"></i>Nhập lại
                        </button>
                        <button type="submit" class="btn btn-success btn-lg px-5 py-3">
                            <i class="fas fa-save me-2"></i>Lưu Sản Phẩm
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preview ảnh chính
            document.getElementById('image').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const container = document.getElementById('imagePreviewContainer');

                if (file) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Ảnh không được lớn hơn 5MB!');
                        this.value = '';
                        return;
                    }

                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Chỉ chấp nhận file ảnh JPG, PNG, GIF hoặc WEBP!');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        container.innerHTML = `
                            <div class="text-center">
                                <img src="${e.target.result}" 
                                     class="img-fluid rounded-3 shadow" 
                                     style="max-height: 300px; border: 2px solid #6366f1;"
                                     alt="Preview ảnh sản phẩm">
                                <p class="text-muted mt-2">${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</p>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(file);
                } else {
                    container.innerHTML = `
                        <div class="border rounded-3 p-4 text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                            <i class="fas fa-image fs-1 text-muted mb-3"></i>
                            <p class="text-muted mb-0">Ảnh preview sẽ hiển thị tại đây</p>
                        </div>
                    `;
                }
            });

            // Preview ảnh phụ (image_array)
            document.getElementById('image_array').addEventListener('change', function(e) {
                const files = e.target.files;
                const container = document.getElementById('imageArrayPreviewContainer');

                if (files.length > 0) {
                    container.innerHTML = '';

                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];

                        if (file.size > 5 * 1024 * 1024) {
                            alert(`Ảnh ${file.name} vượt quá 5MB!`);
                            continue;
                        }

                        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        if (!validTypes.includes(file.type)) {
                            alert(`Ảnh ${file.name} không đúng định dạng!`);
                            continue;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'image-preview';
                            img.title = file.name;
                            container.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                } else {
                    container.innerHTML = `
                        <div class="border rounded-3 p-4 text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                            <i class="fas fa-images fs-1 text-muted mb-3"></i>
                            <p class="text-muted mb-0">Ảnh phụ sẽ hiển thị tại đây</p>
                        </div>
                    `;
                }
            });

            document.getElementById('name').focus();
            calculateTotalStock();

            document.querySelectorAll('.variant-stock').forEach(input => {
                input.addEventListener('input', calculateTotalStock);
            });

            // Hiển thị màu sắc trong dropdown
            initColorSelects();

            // Debug form values
            console.log('=== DEBUG FORM VALUES ===');
            console.log('nha_cung_cap_id element:', document.getElementById('nha_cung_cap_id'));
            console.log('category_id element:', document.getElementById('category_id'));
        });

        let variantIndex = <?php echo isset($_POST['variants']) ? count($_POST['variants']) : 1; ?>;

        document.getElementById('addVariant').addEventListener('click', function() {

console.log('123');

            const template = `
                <div class="variant-row" data-index="${variantIndex}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-light">Size</label>
                                <select name="variants[${variantIndex}][size_id]" class="form-select size-select">
                                    <option value="">-- Chọn size --</option>
                                    <?php foreach ($sizes as $size): ?>
                                        <option value="<?= $size['id'] ?>">
                                            <?= htmlspecialchars($size['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-light">Màu sắc</label>
                                <select name="variants[${variantIndex}][color_id]" class="form-select color-select">
                                    <option value="">-- Chọn màu --</option>
                                    <?php foreach ($colors as $color): ?>
                                        <option value="<?= $color['id'] ?>">
                                            <?= htmlspecialchars($color['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-light">Số lượng</label>
                                <input type="number" name="variants[${variantIndex}][stock]" 
                                    class="form-control variant-stock" 
                                    min="0" 
                                    value="0"
                                    placeholder="0">
                            </div>
                        </div>
                        
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-remove-variant" onclick="removeVariant(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('variantsContainer').insertAdjacentHTML('beforeend', template);

            const newStockInput = document.querySelector(`[name="variants[${variantIndex}][stock]"]`);
            newStockInput.addEventListener('input', calculateTotalStock);

            // Khởi tạo màu cho dropdown mới
            initColorSelects();

            variantIndex++;
            calculateTotalStock();
        });

        function removeVariant(button) {
            const variantRow = button.closest('.variant-row');
            if (document.querySelectorAll('.variant-row').length > 1) {
                variantRow.remove();
                calculateTotalStock();
            } else {
                alert('Phải có ít nhất một biến thể!');
            }
        }

        function calculateTotalStock() {
            let total = 0;
            const stockInputs = document.querySelectorAll('.variant-stock');

            stockInputs.forEach(input => {
                const value = parseInt(input.value) || 0;
                total += value;
            });

            document.getElementById('totalStock').textContent = total;
            document.getElementById('totalStockInput').value = total;
        }

        function resetForm() {
            calculateTotalStock();
        }

        function initColorSelects() {
            document.querySelectorAll('.color-select').forEach(select => {
                // Giữ lại giá trị đã chọn
                const selectedValue = select.value;

                // Xóa option hiện có trừ option đầu tiên
                const firstOption = select.options[0];
                select.innerHTML = '';
                select.appendChild(firstOption);

                // Thêm lại các option với màu sắc
                <?php foreach ($colors as $color): ?>
                    const option = document.createElement('option');
                    option.value = '<?= $color['id'] ?>';
                    option.textContent = '<?= htmlspecialchars($color['name']) ?>';

                    // Thêm màu nền nếu có mã màu
                    const maMau = '<?= $color['ma_mau'] ?>';
                    if (maMau && maMau !== 'NULL' && maMau !== 'null') {
                        option.style.backgroundColor = maMau;
                        option.style.color = getContrastColor(maMau);
                        option.title = 'Mã màu: ' + maMau;
                    }

                    // Chọn lại giá trị đã chọn trước đó
                    if (option.value === selectedValue) {
                        option.selected = true;
                    }

                    select.appendChild(option);
                <?php endforeach; ?>
            });
        }

        // Hàm xác định màu chữ tương phản
        function getContrastColor(hexColor) {
            let hex = hexColor.toLowerCase();

            // Nếu không phải hex, trả về màu mặc định
            if (!hex.startsWith('#')) {
                return '#FFFFFF';
            }

            // Tính độ sáng
            const r = parseInt(hex.substr(1, 2), 16);
            const g = parseInt(hex.substr(3, 2), 16);
            const b = parseInt(hex.substr(5, 2), 16);

            const brightness = (r * 299 + g * 587 + b * 114) / 1000;

            return brightness > 128 ? '#000000' : '#FFFFFF';
        }

        document.getElementById('productForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const price = document.getElementById('price').value;
            const category = document.getElementById('category_id').value;
            const nhaCungCap = document.getElementById('nha_cung_cap_id').value;
            const image = document.getElementById('image').value;

            // Debug values trước khi submit
            console.log('Form submit validation:');
            console.log('name:', name);
            console.log('price:', price);
            console.log('category:', category);
            console.log('nha_cung_cap:', nhaCungCap);
            console.log('image:', image);

            if (!name) {
                e.preventDefault();
                alert('Vui lòng nhập tên sản phẩm');
                document.getElementById('name').focus();
                return false;
            }

            if (!price || parseInt(price) <= 0) {
                e.preventDefault();
                alert('Vui lòng nhập giá sản phẩm hợp lệ');
                document.getElementById('price').focus();
                return false;
            }

            if (!category) {
                e.preventDefault();
                alert('Vui lòng chọn danh mục sản phẩm');
                document.getElementById('category_id').focus();
                return false;
            }

            if (!nhaCungCap) {
                e.preventDefault();
                alert('Vui lòng chọn nhà cung cấp');
                document.getElementById('nha_cung_cap_id').focus();
                return false;
            }

            if (!image) {
                e.preventDefault();
                alert('Vui lòng chọn ảnh sản phẩm chính');
                document.getElementById('image').focus();
                return false;
            }

            let hasValidVariant = false;
            document.querySelectorAll('.variant-row').forEach(row => {
                const sizeSelect = row.querySelector('.size-select');
                const colorSelect = row.querySelector('.color-select');
                if (sizeSelect.value && colorSelect.value) {
                    hasValidVariant = true;
                }
            });

            if (!hasValidVariant) {
                e.preventDefault();
                alert('Phải có ít nhất 1 biến thể (size + màu) hợp lệ');
                return false;
            }

            return true;
        });
    </script>
</body>

</html>