<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - <?= $title ?? 'Quản lý người dùng' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/admin/css/style.css">
</head>

<body class="d-flex flex-column h-100">

<?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
<?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

<main class="main-content flex-grow-1 d-flex flex-column">
    <div class="content-placeholder flex-grow-1">

        <!-- TIÊU ĐỀ -->
        <h4 class="mb-3">Quản lý nhà cung cấp </h4>

        <!-- SEARCH + ADD -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

            <!-- Search -->
            <div class="d-flex flex-grow-1 gap-2">
                <input type="text"
                       id="searchInput"
                       class="form-control"
                       placeholder="Tìm theo tên / SĐT / email..."
                       value="<?= htmlspecialchars($search ?? '') ?>">
                <button class="btn btn-primary" id="searchBtn">
                    <i class="fas fa-search me-1"></i> Tìm
                </button>
            </div>

            <!-- Add user -->
            <a href="<?= BASE_URL ?>admin/nha_cung_cap/create" class="btn btn-success">
                <i class="fas fa-user-plus me-1"></i> Thêm nhà cung cấp 
            </a>
        </div>

        <!-- TABLE USER -->
        <table class="table table-hover table-striped rounded-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>name</th>
                    <th>vị trí</th>
                    
                    <th width="140">Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">Không có dữ liệu</td>
                </tr>
            <?php else: ?>
                <?php foreach ($items as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['vi_tri']) ?></td>
                        
                        <td>
                            <a href="<?= BASE_URL ?>admin/nha_cung_cap/edit/<?= $user['id'] ?>" class="text-primary me-2">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <a href="<?= BASE_URL ?>admin/nha_cung_cap/delete/<?= $user['id'] ?>"
                               class="text-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa nhà cung cấp này?')">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <!-- PAGINATION -->
        <?php if ($page > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $page; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link"
                               href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>

    </div>
</main>

<?php include BASE_PATH . '/app/views/admin/layout/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('searchBtn').addEventListener('click', function () {
        const search = document.getElementById('searchInput').value;
        window.location.href = '?search=' + encodeURIComponent(search);
    });
</script>

</body>
</html>
