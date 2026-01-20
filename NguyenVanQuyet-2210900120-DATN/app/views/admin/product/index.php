
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - <?= $title ?? 'Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/admin/css/style.css">

</head>

<body class="d-flex flex-column h-100">

    <?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
    <?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

    <main class="main-content flex-grow-1 d-flex flex-column">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= $error ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= $success ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="content-placeholder flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="text-light mb-2"> 'Quản lý Sản phẩm' </h4>
                    <p class="text-muted mb-0">Danh sách sản phẩm trong cửa hàng</p>
                </div>
                <a href="/admin/product/create" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                </a>
            </div>

            <!-- Search box -->
            <div class="card  border-0 mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control"
                                    placeholder="Tìm kiếm theo tên sản phẩm..."
                                    value="<?= htmlspecialchars($search ?? '') ?>">
                                <button class="btn btn-primary" type="button" id="searchBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products table -->
            <div class="table-responsive rounded-3 overflow-hidden">
                <table class="table table-hover  table-striped mb-0">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th width="100">Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th width="150">Danh mục</th>
                            <th width="120">Giá (VNĐ)</th>
                            <th width="100">Trạng thái</th>
                            <th width="140" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productsPage ?? [])): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <h5 class="mb-2">Không có sản phẩm nào</h5>
                                        <p class="mb-0">Hãy thêm sản phẩm đầu tiên của bạn</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productsPage as $product):
                                $price = $product['price'] ?? 0;

                                // SỬA: Lấy giá trị cột 'active' thay vì 'status'
                                $active = isset($product['active']) ? (int)$product['active'] : 1;

                                $image = $product['image'] ?? '';
                                $name = $product['name'] ?? 'N/A';
                                $category_id = $product['category_id'] ?? 0;
                                $category = $categoryModel->find($category_id) ?? ['name' => 'N/A'];
                            ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#<?= $product['id'] ?? 'N/A' ?></span>
                                    </td>
                                    <td>
                                        <?php if (!empty($image)): ?>
                                            <?php
                                            $image_url = BASE_URL . $image;
                                            $image_exists = file_exists($_SERVER['DOCUMENT_ROOT'] . parse_url($image_url, PHP_URL_PATH));
                                            ?>
                                            <?php if ($image_exists): ?>
                                                <img src="<?= $image_url ?>"
                                                    alt="<?= htmlspecialchars($name) ?>"
                                                    width="60"
                                                    height="60"
                                                    class="img-thumbnail rounded"
                                                    style="object-fit: cover;">
                                            <?php else: ?>
                                                <div class="text-center">
                                                    <i class="fas fa-image fa-2x text-muted"></i>
                                                    <div class="text-warning small">File mất</div>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="text-center">
                                                <i class="fas fa-image fa-2x text-muted"></i>
                                                <div class="text-muted small">Chưa có ảnh</div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold mb-1"><?= htmlspecialchars($name) ?></div>
                                        <?php if (!empty($product['description'])): ?>
                                            <small class="text-muted" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                <?= htmlspecialchars($product['description']) ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        <?= number_format($price) ?> ₫
                                    </td>
                                    <td>
                                        <!-- SỬA: Kiểm tra $active (0/1) thay vì $status -->
                                        <span class="badge <?= $active == 1 ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= $active == 1 ? 'Hoạt động' : 'Không hoạt động' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-buttons justify-content-center">
                                            <!-- Sửa link này -->
                                            <a href="<?= BASE_URL ?>admin/product/edit/<?= htmlspecialchars($product['slug'] ?? '') ?>"
                                                class="btn btn-sm btn-warning"
                                                title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>

                                            <form method="POST"
                                                action="<?= BASE_URL ?>/admin/product/delete/<?= $product['id'] ?? 0 ?>"
                                                class="d-inline"
                                                onsubmit="return confirmDelete(this, '<?= htmlspecialchars(addslashes($name)) ?>')">
                                                <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Xóa sản phẩm">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (($pages ?? 1) > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if (($page ?? 1) > 1): ?>
                            <li class="page-item">
                                <a href="?page=<?= ($page ?? 1) - 1 ?>&search=<?= urlencode($search ?? '') ?>"
                                    class="page-link bg-dark text-light border-secondary">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= ($pages ?? 1); $i++): ?>
                            <li class="page-item <?= $i == ($page ?? 1) ? 'active' : '' ?>">
                                <a href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>"
                                    class="page-link <?= $i == ($page ?? 1) ? 'bg-primary border-primary' : 'bg-dark text-light border-secondary' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if (($page ?? 1) < ($pages ?? 1)): ?>
                            <li class="page-item">
                                <a href="?page=<?= ($page ?? 1) + 1 ?>&search=<?= urlencode($search ?? '') ?>"
                                    class="page-link bg-dark text-light border-secondary">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </main>

    <?php include BASE_PATH . '/app/views/admin/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script>
        // Search functionality
        document.getElementById('searchBtn').addEventListener('click', function() {
            const search = document.getElementById('searchInput').value.trim();
            const url = new URL(window.location);

            if (search) {
                url.searchParams.set('search', search);
                url.searchParams.set('page', '1'); // Reset về trang 1 khi search
            } else {
                url.searchParams.delete('search');
            }

            window.location.href = url.toString();
        });

        // Enter key để search
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchBtn').click();
            }
        });

        // Xác nhận xóa
        function confirmDelete(form, productName) {
            if (confirm(`Bạn có chắc chắn muốn xóa sản phẩm:\n\n"${productName}"\n\n⚠️ Hành động này không thể hoàn tác!`)) {
                // Thêm hiệu ứng loading
                const deleteBtn = form.querySelector('button[type="submit"]');
                const originalHtml = deleteBtn.innerHTML;
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xóa...';
                deleteBtn.disabled = true;

                return true;
            }
            return false;
        }

        // Xử lý khi form xóa bị cancel (restore button state)
        document.addEventListener('submit', function(e) {
            if (e.target.matches('form[onsubmit*="confirmDelete"]')) {
                const form = e.target;
                setTimeout(() => {
                    const deleteBtn = form.querySelector('button[type="submit"]');
                    if (deleteBtn && deleteBtn.disabled) {
                        deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Xóa';
                        deleteBtn.disabled = false;
                    }
                }, 1000);
            }
        });
    </script> -->
</body>

</html>