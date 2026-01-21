<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quanao Admin - Quản lý danh mục bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/admin/css/style.css">
</head>

<body class="d-flex flex-column h-100">

<?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
<?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

<main class="main-content flex-grow-1 d-flex flex-column">
    <div class="content-placeholder flex-grow-1">

        <h4 class="mb-3">Quản lý danh mục bài viết</h4>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?= BASE_URL ?>admin/category-post/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm danh mục
            </a>
        </div>

        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th width="60">ID</th>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th width="120">Trạng thái</th>
                    <th width="160">Thao tác</th>
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
                        <td><?= htmlspecialchars($item['slug']) ?></td>
                        <td>
                            <?php if ($item['active']): ?>
                                <span class="badge bg-success">Hiển thị</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Đã ẩn</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>admin/category-post/edit/<?= $item['id'] ?>" 
                               class="text-primary me-2">
                                <i class="fas fa-edit"></i> Sửa
                            </a>

                            <?php if ($item['active']): ?>
                                <a href="<?= BASE_URL ?>admin/category-post/delete/<?= $item['id'] ?>"
                                   class="text-danger"
                                   onclick="return confirm('Bạn có chắc muốn ẩn danh mục này?')">
                                    <i class="fas fa-trash"></i> Ẩn
                                </a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>admin/category-post/restore/<?= $item['id'] ?>"
                                   class="text-success"
                                   onclick="return confirm('Khôi phục danh mục này?')">
                                    <i class="fas fa-undo"></i> Khôi phục
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

    </div>
</main>

<?php include BASE_PATH . '/app/views/admin/layout/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
