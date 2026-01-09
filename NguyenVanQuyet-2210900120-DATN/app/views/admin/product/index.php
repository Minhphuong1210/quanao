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

            <!-- Nội dung của product index -->

            <h4><?= $pageTitle ?></h4>

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

                <!-- Search form cho sản phẩm -->
                <div class="d-flex flex-grow-1 gap-2">
                    <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên sản phẩm hoặc danh mục..." value="<?= htmlspecialchars($search ?? '') ?>">
                    <button class="btn btn-primary" id="searchBtn"><i class="fas fa-search me-1"></i> Tìm</button>
                </div>

                <!-- Add product button -->
                <a href="/admin/product/create" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Thêm sản phẩm
                </a>

            </div>

            <style>
                /* Đảm bảo search input và button gọn đẹp */
                #searchInput {
                    min-width: 200px;
                    flex-grow: 1;
                }

                #searchBtn {
                    white-space: nowrap;
                }

                /* Responsive: khi màn hình nhỏ, stack dọc */
                @media (max-width: 576px) {
                    .d-flex.flex-wrap.justify-content-between {
                        flex-direction: column;
                        align-items: stretch;
                        gap: 0.5rem;
                    }

                    #searchInput,
                    #searchBtn,
                    a.btn-success {
                        width: 100%;
                    }
                }
            </style>

            <table class="table table-hover table-dark table-striped rounded-3 text-light">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá (VNĐ)</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productsPage ?? [])): ?>
                        <tr>
                            <td colspan="8" class="text-center">Không có dữ liệu sản phẩm</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productsPage as $product):
                            // Handle missing keys an toàn
                            $stock = $product['stock'] ?? 0;
                            $price = $product['price'] ?? 0;
                            $status = $product['status'] ?? 'inactive';
                            $image = $product['image'] ?? '';
                            $name = $product['name'] ?? 'N/A';
                            $category_id = $product['category_id'] ?? 0;
                            $category = $categoryModel->find($category_id) ?? ['name' => 'N/A'];
                        ?>
                            <tr>
                                <td><?= $product['id'] ?? 'N/A' ?></td>
                                <td>
                                    <?php if (!empty($image)): ?>
                                        <img
                                            src="<?= BASE_URL . htmlspecialchars($image) ?>"
                                            alt="<?= htmlspecialchars($name) ?>"
                                            width="50"
                                            height="50"
                                            class="img-thumbnail rounded">
                                    <?php else: ?>
                                        <span class="text-muted">Chưa có ảnh</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($name) ?></td>
                                <td><?= htmlspecialchars($category['name']) ?></td>
                                <td class="text-end"><?= number_format($price) ?></td>
                                <td>
                                    <span class="badge <?= ($stock > 0) ? 'bg-success' : 'bg-warning text-dark' ?>">
                                        <?= $stock ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= ($status === 'active') ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>   
                                <td>
                                    <a href="<?= BASE_URL ?>/admin/product/edit/<?= $product['id'] ?? 0 ?>" class="btn btn-sm btn-warning">Sửa</a>
                                    <form method="POST" action="<?= BASE_URL ?>/admin/product/delete/<?= $product['id'] ?? 0 ?>" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn xóa sản phẩm này?');">
                                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if (($pages ?? 1) > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= ($pages ?? 1); $i++): ?>
                            <li class="page-item <?= $i == ($page ?? 1) ? 'active' : '' ?>">
                                <a href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>" class="page-link"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>

            <script>
                // Search cho product
                document.getElementById('searchBtn').addEventListener('click', function() {
                    const search = document.getElementById('searchInput').value;
                    window.location.href = window.location.pathname + '?search=' + encodeURIComponent(search);
                });

                // Enter key để search
                document.getElementById('searchInput').addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        document.getElementById('searchBtn').click();
                    }
                });
            </script>

        </div>
    </main>

    <?php include BASE_PATH . '/app/views/admin/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        // JS cho SPA-like: Load content động từ sidebar (không chuyển trang)
        document.addEventListener('DOMContentLoaded', function() {
            const contentPlaceholder = document.querySelector('.content-placeholder');
            const navLinks = document.querySelectorAll('.load-content');
            const alertsContainer = document.querySelector('.main-content');

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const loadUrl = this.getAttribute('data-load');
                    if (!loadUrl) return;

                    // Show loading spinner
                    contentPlaceholder.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i><h4>Đang tải...</h4></div>';

                    // Fetch content via AJAX
                    fetch(loadUrl)
                        .then(response => {
                            if (!response.ok) throw new Error('Lỗi tải nội dung');
                            return response.text();
                        })
                        .then(html => {
                            // Inject HTML vào placeholder
                            contentPlaceholder.innerHTML = html;

                            // Update active class
                            navLinks.forEach(l => l.classList.remove('active'));
                            this.classList.add('active');

                            // Re-init Bootstrap components (modals, tables, etc.)
                            const modals = document.querySelectorAll('[data-bs-toggle="modal"], .modal');
                            modals.forEach(m => {
                                const modal = bootstrap.Modal.getInstance(m) || new bootstrap.Modal(m);
                                // Không show modal tự động, chỉ re-init
                            });

                            // Clear old alerts
                            document.querySelectorAll('.alert').forEach(alert => alert.remove());

                            // Scroll to top
                            window.scrollTo(0, 0);
                        })
                        .catch(error => {
                            console.error('Lỗi AJAX:', error);
                            contentPlaceholder.innerHTML = '<div class="alert alert-danger text-center py-5"><i class="fas fa-exclamation-triangle me-2"></i>Lỗi tải nội dung. <a href="' + this.href + '" class="alert-link">Thử lại</a></div>';
                        });
                });
            });

            // Auto-load default content nếu placeholder trống (ví dụ: dashboard)
            if (contentPlaceholder.innerHTML.includes('Nội dung sẽ được load') || contentPlaceholder.innerHTML.includes('Đang tải nội dung')) {
                const defaultLink = document.querySelector('.load-content[href="/admin"]');
                if (defaultLink) {
                    defaultLink.click();
                }
            }
        });
    </script>
</body>

</html>