<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - <?= $title ?? 'Dashboard' ?></title>
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
            <div class="alert alert-danger rounded-3 mb-4"><?= $error ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success rounded-3 mb-4"><?= $success ?></div>
        <?php endif; ?>

        <div class="content-placeholder flex-grow-1">
            <!-- app/views/admin/product/edit.php -->
            <div class="content-placeholder flex-grow-1">

                <h4 class="text-light mb-4 text-center"><?= $pageTitle ?? 'Chỉnh Sửa Sản Phẩm' ?></h4>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger rounded-3 mb-4 shadow-sm">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <div class="card bg-dark border-0 rounded-4 shadow-lg mx-auto" style="max-width: 800px; border: 1px solid rgba(255,255,255,0.1);">
                    <div class="card-body p-4 p-lg-5">

                        <form method="POST" enctype="multipart/form-data" id="productForm">

                            <!-- Tên sản phẩm -->
                            <div class="mb-4">
                                <label for="name" class="form-label text-light fw-semibold">
                                    Tên sản phẩm <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                    class="form-control form-control-lg bg-secondary text-light border-0 shadow-sm"
                                    placeholder="Nhập tên sản phẩm..."
                                    value="<?= htmlspecialchars($product['name'] ?? $_POST['name'] ?? '') ?>" required>
                                <div class="form-text text-muted">Tên sản phẩm sẽ hiển thị trên trang web.</div>
                            </div>

                            <!-- Giá & Tồn kho -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="price" class="form-label text-light fw-semibold">
                                        Giá (VNĐ) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="price" id="price"
                                        class="form-control form-control-lg bg-secondary text-light border-0 shadow-sm"
                                        placeholder="0"
                                        value="<?= $_POST['price'] ?? number_format($product['price'] ?? 0, 0, '', '.') ?>" required>
                                    <div class="form-text text-muted">Giá bán, tự động định dạng dấu chấm.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="stock" class="form-label text-light fw-semibold">
                                        Số lượng tồn kho
                                    </label>
                                    <input type="number" name="stock" id="stock"
                                        class="form-control form-control-lg bg-secondary text-light border-0 shadow-sm"
                                        value="<?= $_POST['stock'] ?? ($product['stock'] ?? 0) ?>" min="0" placeholder="0">
                                    <div class="form-text text-muted">Đặt 0 nếu hết hàng.</div>
                                </div>
                            </div>

                            <!-- Danh mục & Trạng thái -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <label for="category_id" class="form-label text-light fw-semibold">
                                        Danh mục <span class="text-danger">*</span>
                                    </label>
                                    <select name="category_id" id="category_id"
                                        class="form-select form-select-lg bg-secondary text-light border-0 shadow-sm" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>"
                                                <?= (($_POST['category_id'] ?? $product['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="status" class="form-label text-light fw-semibold">
                                        Trạng thái <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" id="status"
                                        class="form-select form-select-lg bg-secondary text-light border-0 shadow-sm" required>
                                        <option value="active" <?= (($_POST['status'] ?? $product['active'] ?? 'active') === 'active') ? 'selected' : '' ?>>
                                            Hoạt động
                                        </option>
                                        <option value="inactive" <?= (($_POST['status'] ?? $product['active'] ?? '') === 'inactive') ? 'selected' : '' ?>>
                                            Không hoạt động
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Mô tả -->
                            <div class="mb-4">
                                <label for="description" class="form-label text-light fw-semibold">Mô tả</label>
                                <textarea name="description" id="description"
                                    class="form-control bg-secondary text-light border-0 shadow-sm"
                                    rows="6" placeholder="Nhập mô tả chi tiết sản phẩm..."><?= htmlspecialchars($_POST['description'] ?? $product['description'] ?? '') ?></textarea>
                                <div class="form-text text-muted">Hỗ trợ Markdown hoặc HTML nếu cần.</div>
                            </div>

                            <!-- Ảnh hiện tại + Upload ảnh mới -->
                            <div class="mb-4">
                                <label class="form-label text-light fw-semibold">
                                    Ảnh sản phẩm hiện tại
                                </label>
                                <?php if (!empty($product['image'])): ?>
                                    <div class="mb-3 text-center">
                                        <img src="/uploads/products/<?= htmlspecialchars($product['image']) ?>"
                                            alt="Ảnh hiện tại"
                                            class="img-thumbnail rounded-4 shadow-lg"
                                            style="max-height: 350px; border: 3px solid #6366f1;">
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted text-center">Chưa có ảnh</p>
                                <?php endif; ?>

                                <label for="image" class="form-label text-light fw-semibold">
                                    Thay đổi ảnh sản phẩm
                                </label>
                                <input type="file" name="image" id="image"
                                    class="form-control form-control-lg bg-secondary text-light border-0 shadow-sm"
                                    accept="image/*">
                                <div class="form-text text-muted">JPG, PNG. Tối đa 5MB. Để trống nếu không muốn thay đổi.</div>

                                <div id="imagePreviewContainer" class="mt-4 text-center"></div>
                            </div>

                            <!-- Nút hành động -->
                            <div class="d-flex flex-wrap justify-content-end gap-3 mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-5 py-3 shadow">
                                    <i class="fas fa-save me-2"></i> Cập Nhật Sản Phẩm
                                </button>
                                <a href="/admin/product" class="btn btn-secondary btn-lg px-5 py-3 shadow">
                                    <i class="fas fa-times me-2"></i> Hủy
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <script>
                // Preview ảnh mới khi chọn thay đổi
                document.getElementById('image').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const container = document.getElementById('imagePreviewContainer');
                    container.innerHTML = '';

                    if (file) {
                        if (file.size > 5 * 1024 * 1024) {
                            alert('Ảnh không được lớn hơn 5MB!');
                            this.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-thumbnail rounded-4 shadow-lg';
                            img.style.maxHeight = '350px';
                            img.style.border = '3px solid #6366f1';
                            img.alt = 'Preview ảnh mới';
                            container.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Format giá tự động (khi người dùng sửa)
                document.getElementById('price').addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value) {
                        e.target.value = parseInt(value).toLocaleString('vi-VN');
                    } else {
                        e.target.value = '';
                    }
                });

                // Focus vào tên sản phẩm
                document.getElementById('name').focus();
            </script>
</body>

</html>