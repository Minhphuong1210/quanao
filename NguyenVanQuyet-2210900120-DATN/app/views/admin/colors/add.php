<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - Thêm màu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/admin/css/style.css">
</head>

<body class="d-flex flex-column h-100">

<?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
<?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

<main class="main-content flex-grow-1 d-flex flex-column">

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger mb-3">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="content-placeholder flex-grow-1">

        <h4 class="mb-4">Thêm màu sắc</h4>

        <form action="<?= BASE_URL ?>admin/colors/create" method="post" class="w-50">

            <!-- Tên màu -->
            <div class="mb-3">
                <label class="form-label">Tên màu</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <!-- Mã màu -->
            <div class="mb-3">
                <label class="form-label">Mã màu</label>
                <input type="color" name="ma_mau" class="form-control form-control-color" value="#000000">
            </div>

            <!-- Slug -->
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" required>
            </div>

            <!-- Trạng thái -->
            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="active" class="form-select">
                    <option value="1">Hoạt động</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm màu
            </button>

            <a href="<?= BASE_URL ?>admin/colors" class="btn btn-secondary ms-2">
                Quay lại
            </a>
        </form>

    </div>
</main>

<?php include BASE_PATH . '/app/views/admin/layout/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
