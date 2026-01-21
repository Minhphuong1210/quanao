<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quanao Admin - Quản lý màu sắc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/admin/css/style.css">
</head>

<body class="d-flex flex-column h-100">

<?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
<?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

<main class="main-content flex-grow-1 d-flex flex-column">
    <div class="content-placeholder flex-grow-1">

        <h4 class="mb-3">Quản lý màu sắc</h4>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?= BASE_URL ?>admin/colors/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm màu
            </a>
        </div>

        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên màu</th>
                    <th>Mã màu</th>
                    <th>Slug</th>
                    <th width="140">Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">Không có dữ liệu</td>
                </tr>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>
                            <span style="display:inline-block;width:20px;height:20px;
                                background:<?= htmlspecialchars($item['ma_mau']) ?>;
                                border:1px solid #ccc"></span>
                            <?= htmlspecialchars($item['ma_mau']) ?>
                        </td>
                        <td><?= htmlspecialchars($item['slug']) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>admin/colors/edit/<?= $item['id'] ?>" class="text-primary me-2">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <a href="<?= BASE_URL ?>admin/colors/delete/<?= $item['id'] ?>"
                               class="text-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa màu này?')">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <?php if ($lastPage > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $lastPage; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>

    </div>
</main>

<?php include BASE_PATH . '/app/views/admin/layout/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
