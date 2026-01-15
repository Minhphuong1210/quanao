
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - Chỉnh Sửa Sản Phẩm</title>
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

        .current-image {
            position: relative;
            display: inline-block;
        }

        .current-image img {
            border: 3px solid var(--primary);
            border-radius: 8px;
        }

        .current-image-info {
            position: absolute;
            top: 5px;
            left: 5px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
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

        .slug-display {
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 20px;
            font-family: monospace;
            word-break: break-all;
        }

        .slug-label {
            color: #6366f1;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .slug-value {
            color: #e2e8f0;
            font-size: 16px;
        }

        .slug-url {
            color: #94a3b8;
            font-size: 14px;
            margin-top: 5px;
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
                    <h3 class="text-light mb-2">Chỉnh Sửa Sản Phẩm</h3>
                    <p class="text-muted mb-0">
                        <?php if ($product): ?>
                            Sản phẩm ID: <?= $product['id'] ?> - <?= htmlspecialchars($product['name']) ?>
                        <?php else: ?>
                            Sản phẩm không tồn tại
                        <?php endif; ?>
                    </p>
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

            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!$product): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Không tìm thấy sản phẩm
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            <?php else: ?>

                <div class="card bg-dark border-0 rounded-3 shadow-lg p-4 p-lg-5">
                    <form method="POST"
                        action="<?= BASE_URL ?>/admin/product/edit/<?= htmlspecialchars($product['slug']) ?>"
                        enctype="multipart/form-data"
                        id="productForm"> <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

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
                                        value="<?= htmlspecialchars($_POST['name'] ?? $product['name']) ?>"
                                        required>
                                    <div class="form-text text-muted mt-1">
                                        Khi thay đổi tên, slug sẽ được tự động cập nhật
                                    </div>
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
                                        value="<?= htmlspecialchars($_POST['price'] ?? $product['price']) ?>"
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
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>"
                                                <?= ($_POST['category_id'] ?? $product['category_id']) == $cat['id'] ? 'selected' : '' ?>>
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
                                        <?php foreach ($suppliers as $ncc): ?>
                                            <option value="<?= $ncc['id'] ?>"
                                                <?= ($_POST['nha_cung_cap_id'] ?? $product['nha_cung_cap_id']) == $ncc['id'] ? 'selected' : '' ?>>
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
                                        <option value="1" <?= ($_POST['hien_trang_chu'] ?? $product['hien_trang_chu']) == 1 ? 'selected' : '' ?>>Có</option>
                                        <option value="0" <?= ($_POST['hien_trang_chu'] ?? $product['hien_trang_chu']) == 0 ? 'selected' : '' ?>>Không</option>
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
                                        <option value="1" <?= ($_POST['san_pham_noi_bat'] ?? $product['san_pham_noi_bat']) == 1 ? 'selected' : '' ?>>Có</option>
                                        <option value="0" <?= ($_POST['san_pham_noi_bat'] ?? $product['san_pham_noi_bat']) == 0 ? 'selected' : '' ?>>Không</option>
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
                                        <option value="1" <?= ($_POST['active'] ?? $product['active']) == 1 ? 'selected' : '' ?>>Hoạt động</option>
                                        <option value="0" <?= ($_POST['active'] ?? $product['active']) == 0 ? 'selected' : '' ?>>Không hoạt động</option>
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
                                maxlength="225"><?= htmlspecialchars($_POST['description'] ?? $product['description']) ?></textarea>
                            <div class="form-text text-muted">Tối đa 225 ký tự</div>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label text-light fw-semibold mb-3">
                                <i class="fas fa-align-left me-2"></i>Mô tả chi tiết
                            </label>
                            <textarea name="content" id="content"
                                class="form-control"
                                rows="10"
                                placeholder="Nhập mô tả chi tiết về sản phẩm..."><?= htmlspecialchars($_POST['content'] ?? $product['content']) ?></textarea>
                        </div>

                        <!-- Ảnh chính -->
                        <div class="mb-5">
                            <label for="image" class="form-label text-light fw-semibold">
                                <i class="fas fa-image me-2"></i>Ảnh sản phẩm chính
                            </label>

                            <!-- Hiển thị ảnh hiện tại nếu có -->
                            <?php if (!empty($product['image'])): ?>
                                <div class="mb-3">
                                    <p class="text-muted">Ảnh hiện tại:</p>
                                    <div class="current-image">
                                        <img src="/<?= htmlspecialchars(ltrim($product['image'], '/')) ?>"
                                            class="img-fluid rounded-3 shadow"
                                            style="max-height: 200px; border: 2px solid #6366f1;"
                                            alt="Ảnh hiện tại"
                                            onerror="this.src='https://placehold.co/400x300/1e293b/94a3b8?text=Ảnh+không+tồn+tại'">
                                        <p class="text-muted mt-2 small"><?= basename($product['image']) ?></p>
                                    </div>
                                </div>
                                <p class="text-warning small mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Upload ảnh mới sẽ thay thế ảnh hiện tại
                                </p>
                            <?php endif; ?>

                            <input type="file" name="image" id="image"
                                class="form-control form-control-lg"
                                accept="image/*">
                            <div class="form-text text-muted mt-1">Chỉ chấp nhận JPG, PNG, GIF, WEBP. Tối đa 5MB.</div>
                            <div id="imagePreviewContainer" class="mt-3">
                                <?php if (!empty($product['image'])): ?>
                                    <div class="text-center">
                                        <img src="/<?= htmlspecialchars(ltrim($product['image'], '/')) ?>"
                                            class="img-fluid rounded-3 shadow"
                                            style="max-height: 300px; border: 2px solid #6366f1;"
                                            alt="Ảnh hiện tại"
                                            onerror="this.src='https://placehold.co/600x400/1e293b/94a3b8?text=Ảnh+không+tồn+tại'">
                                        <p class="text-muted mt-2">Ảnh hiện tại</p>
                                    </div>
                                <?php else: ?>
                                    <div class="border rounded-3 p-4 text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                                        <i class="fas fa-image fs-1 text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Ảnh preview sẽ hiển thị tại đây</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Ảnh phụ -->
                        <div class="mb-5">
                            <label for="image_array" class="form-label text-light fw-semibold">
                                <i class="fas fa-images me-2"></i>Ảnh sản phẩm phụ (image_array)
                            </label>

                            <!-- Hiển thị ảnh phụ hiện tại nếu có -->
                            <?php if (!empty($product['image_array'])): ?>
                                <div class="mb-3">
                                    <p class="text-muted">Ảnh phụ hiện tại:</p>
                                    <div class="image-preview-container mb-3">
                                        <?php
                                        $existingImages = explode(',', $product['image_array']);
                                        foreach ($existingImages as $img):
                                            if (trim($img)): ?>
                                                <img src="/<?= htmlspecialchars(ltrim($img, '/')) ?>"
                                                    class="image-preview"
                                                    alt="Ảnh phụ hiện tại"
                                                    onerror="this.style.display='none'"
                                                    style="border-color: #8b5cf6;">
                                        <?php endif;
                                        endforeach; ?>
                                    </div>
                                </div>
                                <p class="text-warning small mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Upload ảnh mới sẽ thêm vào danh sách ảnh hiện tại
                                </p>
                            <?php endif; ?>

                            <input type="file" name="image_array[]" id="image_array"
                                class="form-control form-control-lg"
                                accept="image/*"
                                multiple>
                            <div class="form-text text-muted mt-1">Có thể chọn nhiều ảnh. Chỉ chấp nhận JPG, PNG, GIF, WEBP. Mỗi ảnh tối đa 5MB.</div>
                            <div id="imageArrayPreviewContainer" class="mt-3 image-preview-container">
                                <?php if (!empty($product['image_array'])): ?>
                                    <?php
                                    $existingImages = explode(',', $product['image_array']);
                                    foreach ($existingImages as $img):
                                        if (trim($img)): ?>
                                            <img src="/<?= htmlspecialchars(ltrim($img, '/')) ?>"
                                                class="image-preview"
                                                alt="Ảnh phụ hiện tại"
                                                onerror="this.style.display='none'">
                                    <?php endif;
                                    endforeach; ?>
                                <?php else: ?>
                                    <div class="border rounded-3 p-4 text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                                        <i class="fas fa-images fs-1 text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Ảnh phụ sẽ hiển thị tại đây</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Biến thể sản phẩm -->
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
                                <?php
                                $variantIndex = 0;
                                $postVariants = $_POST['variants'] ?? [];

                                // Sử dụng biến thể từ POST nếu có, nếu không dùng biến thể hiện có
                                if (!empty($postVariants)) {
                                    foreach ($postVariants as $index => $variant):
                                ?>
                                        <div class="variant-row" data-index="<?= $index ?>">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label text-light">Size</label>
                                                        <select name="variants[<?= $index ?>][size_id]" class="form-select size-select">
                                                            <option value="">-- Chọn size --</option>
                                                            <?php foreach ($sizes as $size): ?>
                                                                <option value="<?= $size['id'] ?>"
                                                                    <?= $variant['size_id'] == $size['id'] ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($size['name']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label text-light">Màu sắc</label>
                                                        <select name="variants[<?= $index ?>][color_id]" class="form-select color-select">
                                                            <option value="">-- Chọn màu --</option>
                                                            <?php foreach ($colors as $color): ?>
                                                                <option value="<?= $color['id'] ?>"
                                                                    <?= $variant['color_id'] == $color['id'] ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($color['name']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label text-light">Số lượng</label>
                                                        <input type="number" name="variants[<?= $index ?>][stock]"
                                                            class="form-control variant-stock"
                                                            min="0"
                                                            value="<?= htmlspecialchars($variant['stock'] ?? '0') ?>"
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
                                    <?php
                                        $variantIndex++;
                                    endforeach;
                                } elseif (!empty($variants)) {
                                    // Hiển thị biến thể hiện có từ database
                                    foreach ($variants as $existingVariant):
                                    ?>
                                        <div class="variant-row" data-index="<?= $variantIndex ?>">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label text-light">Size</label>
                                                        <select name="variants[<?= $variantIndex ?>][size_id]" class="form-select size-select">
                                                            <option value="">-- Chọn size --</option>
                                                            <?php foreach ($sizes as $size): ?>
                                                                <option value="<?= $size['id'] ?>"
                                                                    <?= $existingVariant['size_id'] == $size['id'] ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($size['name']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label text-light">Màu sắc</label>
                                                        <select name="variants[<?= $variantIndex ?>][color_id]" class="form-select color-select">
                                                            <option value="">-- Chọn màu --</option>
                                                            <?php foreach ($colors as $color): ?>
                                                                <option value="<?= $color['id'] ?>"
                                                                    <?= $existingVariant['color_id'] == $color['id'] ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($color['name']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label text-light">Số lượng</label>
                                                        <input type="number" name="variants[<?= $variantIndex ?>][stock]"
                                                            class="form-control variant-stock"
                                                            min="0"
                                                            value="<?= htmlspecialchars($existingVariant['stock']) ?>"
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
                                    <?php
                                        $variantIndex++;
                                    endforeach;
                                } else {
                                    // Nếu không có biến thể nào, hiển thị một biến thể mặc định
                                    ?>
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
                                                    <input type="number" name="variants[0][stock]"
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
                                <?php
                                    $variantIndex = 1;
                                }
                                ?>
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
                                        <div class="display-4 fw-bold text-success" id="totalStock">
                                            <?php
                                            $totalStock = 0;
                                            if (!empty($postVariants)) {
                                                foreach ($postVariants as $variant) {
                                                    $totalStock += (int)($variant['stock'] ?? 0);
                                                }
                                            } elseif (!empty($variants)) {
                                                foreach ($variants as $variant) {
                                                    $totalStock += (int)$variant['stock'];
                                                }
                                            }
                                            echo $totalStock;
                                            ?>
                                        </div>
                                        <input type="hidden" name="stock" id="totalStockInput" value="<?= $totalStock ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-end gap-3 mt-5 pt-4 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                            <button type="reset" class="btn btn-secondary btn-lg px-5 py-3" onclick="resetForm()">
                                <i class="fas fa-redo me-2"></i>Nhập lại
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                                <i class="fas fa-save me-2"></i>Cập Nhật Sản Phẩm
                            </button>
                        </div>

                    </form>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let variantIndex = <?= isset($variantIndex) ? (int)$variantIndex : 0 ?>;
            document.getElementById('name')?.focus();
            calculateTotalStock();

            document.querySelectorAll('.variant-stock').forEach(input => {
                input.addEventListener('input', calculateTotalStock);
            });

            // Hiển thị màu sắc trong dropdown
            initColorSelects();

            console.log('=== DEBUG FORM VALUES ===');
            console.log('Product ID: <?= $product ? $product['id'] : '' ?>');
            console.log('Product Slug: <?= $product ? $product['slug'] : '' ?>');
            console.log('Variant Index:', variantIndex);
        });

        // Preview ảnh chính
        const imageInput = document.getElementById('image');
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
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
                    <?php if (!empty($product['image'])): ?>
                        container.innerHTML = `
                        <div class="text-center">
                            <img src="/<?= htmlspecialchars(ltrim($product['image'], '/')) ?>"
                                 class="img-fluid rounded-3 shadow" 
                                 style="max-height: 300px; border: 2px solid #6366f1;"
                                 alt="Ảnh hiện tại"
                                 onerror="this.src='https://placehold.co/600x400/1e293b/94a3b8?text=Ảnh+không+tồn+tại'">
                            <p class="text-muted mt-2">Ảnh hiện tại</p>
                        </div>
                    `;
                    <?php else: ?>
                        container.innerHTML = `
                        <div class="border rounded-3 p-4 text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                            <i class="fas fa-image fs-1 text-muted mb-3"></i>
                            <p class="text-muted mb-0">Ảnh preview sẽ hiển thị tại đây</p>
                        </div>
                    `;
                    <?php endif; ?>
                }
            });
        }
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
                <?php if (!empty($product['image_array'])): ?>
                    container.innerHTML = '';
                    <?php
                    $existingImages = explode(',', $product['image_array']);
                    foreach ($existingImages as $img):
                        if (trim($img)): ?>
                            container.innerHTML += `
                                <img src="/<?= htmlspecialchars(ltrim($img, '/')) ?>"
                                     class="image-preview"
                                     alt="Ảnh phụ hiện tại"
                                     onerror="this.style.display='none'">
                            `;
                    <?php endif;
                    endforeach; ?>
                <?php else: ?>
                    container.innerHTML = `
                        <div class="border rounded-3 p-4 text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                            <i class="fas fa-images fs-1 text-muted mb-3"></i>
                            <p class="text-muted mb-0">Ảnh phụ sẽ hiển thị tại đây</p>
                        </div>
                    `;
                <?php endif; ?>
            }
        });

        document.getElementById('addVariant')?.addEventListener('click', function() {
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
            const totalStockEl = document.getElementById('totalStock');
            if (!totalStockEl) return; // tránh lỗi nếu element không tồn tại

            let total = 0;
            document.querySelectorAll('.variant-stock').forEach(input => {
                total += parseInt(input.value) || 0;
            });

            totalStockEl.textContent = total;
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

            // Debug values trước khi submit
            console.log('Form submit validation:');
            console.log('name:', name);
            console.log('price:', price);
            console.log('category:', category);
            console.log('nha_cung_cap:', nhaCungCap);

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

            // Xác nhận trước khi cập nhật
            if (!confirm('Bạn có chắc chắn muốn cập nhật thông tin sản phẩm này?')) {
                e.preventDefault();
                return false;
            }

            return true;
        });
    </script>
</body>

</html>