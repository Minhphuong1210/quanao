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
            <div class="alert alert-danger rounded-3 mb-4"><?= $error ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success rounded-3 mb-4"><?= $success ?></div>
        <?php endif; ?>

        <div class="content-placeholder flex-grow-1">
           
<!-- đây là nội dung của category  -->


<h4><?= $pageTitle ?></h4>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

    <!-- Search form -->
    <div class="d-flex flex-grow-1 gap-2">
        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-primary" id="searchBtn"><i class="fas fa-search me-1"></i> Tìm</button>
    </div>

    <!-- Add category button -->
    <a href="<?= BASE_URL ?>admin/category/create" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Thêm danh mục
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

        #searchInput, #searchBtn, a.btn-success {
            width: 100%;
        }
    }
</style>


<table class="table table-hover table-striped rounded-3 text-light">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Mô tả</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php if(empty($categoriesPage)): ?>
            <tr>
                <td colspan="3" class="text-center">Không có dữ liệu</td>
            </tr>
        <?php else: ?>
            <?php foreach($categoriesPage as $cat): ?>
            <tr>
                <td><?= $cat['id'] ?></td>
                <td><?= htmlspecialchars($cat['name']) ?></td>
                <td><?= htmlspecialchars($cat['slug'] ?? '') ?></td>

                <td>
    <a href="<?= BASE_URL ?>admin/category/edit/<?= $cat['id'] ?>">Sửa</a>
    <a href="<?= BASE_URL ?>admin/category/delete/<?= $cat['id'] ?>"
       onclick="return confirm('Bạn có chắc muốn xóa không?')">
        Xóa
    </a>
</td>

            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php if($pages > 1): ?>
<nav>
    <ul class="pagination justify-content-center">
        <?php for($i=1; $i<=$pages; $i++): ?>
            <li class="page-item <?= $i==$page?'active':'' ?>">
                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="page-link"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<script>
    // Search
    document.getElementById('searchBtn').addEventListener('click', function(){
        const search = document.getElementById('searchInput').value;
        // Reload page với param search
        window.location.href = '?search=' + encodeURIComponent(search);
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
        // document.addEventListener('DOMContentLoaded', function() {
        //     const contentPlaceholder = document.querySelector('.content-placeholder');
        //     const navLinks = document.querySelectorAll('.load-content');
        //     const alertsContainer = document.querySelector('.main-content');

        //     navLinks.forEach(link => {
        //         link.addEventListener('click', function(e) {
        //             e.preventDefault();
        //             const loadUrl = this.getAttribute('data-load');
        //             if (!loadUrl) return;

        //             // Show loading spinner
        //             contentPlaceholder.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i><h4>Đang tải...</h4></div>';

        //             // Fetch content via AJAX
        //             fetch(loadUrl)
        //                 .then(response => {
        //                     if (!response.ok) throw new Error('Lỗi tải nội dung');
        //                     return response.text();
        //                 })
        //                 .then(html => {
        //                     // Inject HTML vào placeholder
        //                     contentPlaceholder.innerHTML = html;

        //                     // Update active class
        //                     navLinks.forEach(l => l.classList.remove('active'));
        //                     this.classList.add('active');

        //                     // Re-init Bootstrap components (modals, tables, etc.)
        //                     const modals = document.querySelectorAll('[data-bs-toggle="modal"], .modal');
        //                     modals.forEach(m => {
        //                         const modal = bootstrap.Modal.getInstance(m) || new bootstrap.Modal(m);
        //                         // Không show modal tự động, chỉ re-init
        //                     });

        //                     // Clear old alerts
        //                     document.querySelectorAll('.alert').forEach(alert => alert.remove());

        //                     // Scroll to top
        //                     window.scrollTo(0, 0);
        //                 })
        //                 .catch(error => {
        //                     console.error('Lỗi AJAX:', error);
        //                     contentPlaceholder.innerHTML = '<div class="alert alert-danger text-center py-5"><i class="fas fa-exclamation-triangle me-2"></i>Lỗi tải nội dung. <a href="' + this.href + '" class="alert-link">Thử lại</a></div>';
        //                 });
        //         });
        //     });

        //     // Auto-load default content nếu placeholder trống (ví dụ: dashboard)
        //     if (contentPlaceholder.innerHTML.includes('Nội dung sẽ được load') || contentPlaceholder.innerHTML.includes('Đang tải nội dung')) {
        //         const defaultLink = document.querySelector('.load-content[href="/admin"]');
        //         if (defaultLink) {
        //             defaultLink.click();
        //         }
        //     }
        // });
    </script>
</body>

</html>