<?php
// ===============================
// KHỞI TẠO BIẾN
// ===============================
$error = $success = '';
$category = $sizes = $colors = [];

try {
    $pdo = Database::getInstance();

    // Lấy danh mục (chỉ lấy active)
    $sql_cat = "SELECT id, name FROM category WHERE active = 1 ORDER BY name";
    $stmt_cat = $pdo->prepare($sql_cat);
    $stmt_cat->execute();
    $category = $stmt_cat->fetchAll();

    // Lấy danh sách sizes (chỉ lấy active)
    $sql_size = "SELECT id, name FROM sizes WHERE active = 1 ORDER BY name";
    $stmt_size = $pdo->prepare($sql_size);
    $stmt_size->execute();
    $sizes = $stmt_size->fetchAll();

    // Lấy danh sách colors (chỉ lấy active)
    $sql_color = "SELECT id, name, ma_mau FROM colors WHERE active = 1 ORDER BY name";
    $stmt_color = $pdo->prepare($sql_color);
    $stmt_color->execute();
    $colors = $stmt_color->fetchAll();
} catch (PDOException $e) {
    $error = 'Lỗi tải dữ liệu: ' . $e->getMessage();
}

// ===============================
// XỬ LÝ FORM SUBMIT
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $price       = intval(str_replace('.', '', $_POST['price'] ?? '0'));
    $description = trim($_POST['description'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $stock       = intval($_POST['stock'] ?? 0);
    $status      = $_POST['status'] ?? 'active';
    
    // Lấy danh sách biến thể (variants)
    $variants = $_POST['variants'] ?? [];

    if (empty($name) || $price <= 0 || empty($category_id)) {
        $error = 'Vui lòng nhập đầy đủ tên, giá và danh mục!';
    }

    // ===============================
    // XÁC ĐỊNH THƯ MỤC PUBLIC
    // ===============================
    $image_path = '';

    if (empty($error)) {
        $public_path = realpath(__DIR__ . '/../../../../public');

        if ($public_path === false) {
            $error = 'Không xác định được thư mục public.';
        }
    }

    // ===============================
    // UPLOAD ẢNH
    // ===============================
    if (empty($error)) {
        $upload_dir = $public_path . '/uploads/products/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES['image']['tmp_name'];
            $file_size = $_FILES['image']['size'];
            $file_ext  = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($file_ext, $allowed_ext)) {
                $error = 'Chỉ cho phép ảnh JPG, JPEG, PNG, GIF, WEBP.';
            } elseif ($file_size > 5 * 1024 * 1024) {
                $error = 'Dung lượng ảnh tối đa 5MB.';
            } else {
                $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
                $target_path  = $upload_dir . $new_filename;

                if (move_uploaded_file($file_tmp, $target_path)) {
                    $image_path = 'public/uploads/products/' . $new_filename;
                } else {
                    $error = 'Không thể lưu ảnh. Kiểm tra quyền thư mục public/uploads.';
                }
            }
        } else {
            $error = 'Vui lòng chọn ảnh sản phẩm.';
        }
    }

    // ===============================
    // LƯU VÀO DATABASE
    // ===============================
    if (empty($error) && !empty($image_path)) {
        $pdo->beginTransaction();
        
        try {
            // 1. Thêm sản phẩm chính vào bảng products
            $sql = "INSERT INTO products 
                    (name, price, description, category_id, stock, status, image, created_at)
                    VALUES 
                    (:name, :price, :description, :category_id, :stock, :status, :image, NOW())";

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

            $product_id = $pdo->lastInsertId();

            // 2. Thêm các biến thể vào bảng product_detail
            if (!empty($variants)) {
                $variant_sql = "INSERT INTO product_detail 
                               (product_id, size_id, color_id, stock) 
                               VALUES 
                               (:product_id, :size_id, :color_id, :stock)";
                
                $variant_stmt = $pdo->prepare($variant_sql);
                
                foreach ($variants as $variant) {
                    if (!empty($variant['size_id']) && !empty($variant['color_id'])) {
                        $variant_stmt->execute([
                            ':product_id' => $product_id,
                            ':size_id'    => intval($variant['size_id']),
                            ':color_id'   => intval($variant['color_id']),
                            ':stock'      => intval($variant['stock'] ?? 0)
                        ]);
                    }
                }
            }

            $pdo->commit();
            $success = 'Thêm sản phẩm và biến thể thành công! ID: ' . $product_id;
            $_POST = [];

        } catch (PDOException $e) {
            $pdo->rollBack();
            
            // Xóa ảnh nếu lỗi DB
            if (!empty($image_path) && file_exists($public_path . '/uploads/products/' . basename($image_path))) {
                unlink($public_path . '/uploads/products/' . basename($image_path));
            }

            $error = 'Lỗi database: ' . $e->getMessage();
        }
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
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

        .form-control, .form-select, .select2-container--default .select2-selection {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: var(--text-light) !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
        }

        .color-box {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 4px;
            margin-right: 8px;
            vertical-align: middle;
            border: 1px solid rgba(255,255,255,0.3);
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
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            <div class="card bg-dark border-0 rounded-3 shadow-lg p-4 p-lg-5">
                <form method="POST" enctype="multipart/form-data" id="productForm">

                    <!-- Thông tin cơ bản -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-light mb-4 border-bottom pb-3">
                                <i class="fas fa-info-circle me-2"></i>Thông tin cơ bản
                            </h5>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label for="name" class="form-label text-light fw-semibold">
                                    Tên sản phẩm <span class="text-danger">*</span>
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
                                    Giá (VNĐ) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="price" id="price" 
                                    class="form-control form-control-lg"
                                    placeholder="0"
                                    value="<?= $_POST['price'] ?? '' ?>" 
                                    required>
                            </div>
                        </div>
                    </div>

                    <!-- Danh mục và Trạng thái -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="category_id" class="form-label text-light fw-semibold">
                                    Danh mục <span class="text-danger">*</span>
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
                                <label for="status" class="form-label text-light fw-semibold">
                                    Trạng thái
                                </label>
                                <select name="status" id="status" 
                                    class="form-select form-select-lg">
                                    <option value="active" <?= ($_POST['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>
                                        Hoạt động
                                    </option>
                                    <option value="inactive" <?= ($_POST['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                                        Không hoạt động
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-4">
                        <label for="description" class="form-label text-light fw-semibold">Mô tả</label>
                        <textarea name="description" id="description" 
                            class="form-control"
                            rows="4" 
                            placeholder="Nhập mô tả chi tiết sản phẩm..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    </div>

                    <!-- Ảnh sản phẩm -->
                    <div class="mb-5">
                        <label for="image" class="form-label text-light fw-semibold">
                            Ảnh sản phẩm <span class="text-danger">*</span>
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

                    <!-- BIẾN THỂ SẢN PHẨM -->
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
                            <!-- Biến thể mẫu -->
                            <div class="variant-row" data-index="0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label text-light">Size</label>
                                            <select name="variants[0][size_id]" class="form-select size-select">
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
                                            <select name="variants[0][color_id]" class="form-select color-select">
                                                <option value="">-- Chọn màu --</option>
                                                <?php foreach ($colors as $color): ?>
                                                    <option value="<?= $color['id'] ?>" 
                                                        data-hex="<?= htmlspecialchars($color['ma_mau'] ?? '#000000') ?>">
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
                                                class="form-control" 
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
                        </div>
                    </div>

                    <!-- Nút hành động -->
                    <div class="d-flex flex-wrap justify-content-end gap-3 mt-5 pt-4 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                        <button type="reset" class="btn btn-secondary btn-lg px-5 py-3">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        // Format giá khi nhập
        document.getElementById('price').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                e.target.value = parseInt(value).toLocaleString('vi-VN');
            }
        });

        // Preview ảnh
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

        // Quản lý biến thể
        let variantIndex = 1;
        
        document.getElementById('addVariant').addEventListener('click', function() {
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
                                        <option value="<?= $color['id'] ?>" 
                                            data-hex="<?= htmlspecialchars($color['ma_mau'] ?? '#000000') ?>">
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
                                    placeholder="0"
                                    oninput="calculateTotalStock()">
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
            variantIndex++;
            
            // Re-init select2 cho màu sắc mới
            setTimeout(function() {
                $('.color-select:last').select2({
                    templateResult: formatColor,
                    templateSelection: formatColor,
                    width: '100%'
                });
            }, 100);
            
            // Re-calculate stock
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

        // Tính tổng số lượng từ các biến thể
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

        // Custom select2 cho màu sắc
        $(document).ready(function() {
            $('.color-select').select2({
                templateResult: formatColor,
                templateSelection: formatColor,
                width: '100%'
            });
            
            function formatColor(color) {
                if (!color.id) {
                    return color.text;
                }
                const hexColor = $(color.element).data('hex');
                const $color = $(
                    '<span><span class="color-box" style="background-color:' + hexColor + '"></span>' + color.text + '</span>'
                );
                return $color;
            }
            
            // Tính tổng stock khi thay đổi
            $(document).on('input', '.variant-stock', function() {
                calculateTotalStock();
            });
            
            // Khởi tạo tính toán ban đầu
            calculateTotalStock();
        });

        // Focus vào tên sản phẩm khi load trang
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('name').focus();
        });
    </script>
</body>

</html>