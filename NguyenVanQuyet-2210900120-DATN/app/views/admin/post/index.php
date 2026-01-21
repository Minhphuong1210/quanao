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

    <h4 class="mb-3">Quản lý bài viết</h4>

<a href="<?= BASE_URL ?>admin/post/create" class="btn btn-success mb-3">
    <i class="fas fa-plus"></i> Thêm bài viết
</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Danh mục</th>
            <th>Lượt xem</th>
            <th>Trạng thái</th>
            <th width="150">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= htmlspecialchars($item['category_name']) ?></td>
            <td><?= $item['view'] ?></td>
            <td>
                <?= $item['active'] ? '<span class="badge bg-success">Hiển thị</span>' : '<span class="badge bg-secondary">Ẩn</span>' ?>
            </td>
            <td>
                <a href="<?= BASE_URL ?>admin/post/edit/<?= $item['id'] ?>" class="text-primary">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="<?= BASE_URL ?>admin/post/delete/<?= $item['id'] ?>"
                   class="text-danger ms-2"
                   onclick="return confirm('Ẩn bài viết này?')">
                    <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
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
