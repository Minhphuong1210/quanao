<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - <?= $title ?? 'Sửa user' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/admin/css/style.css">
</head>

<body class="d-flex flex-column h-100">

<?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
<?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

<main class="main-content flex-grow-1 d-flex flex-column">
    <div class="content-placeholder flex-grow-1">

        <h4><?= $pageTitle ?? 'Sửa user' ?></h4>

        <form method="post" class="w-50 mx-auto"action="<?= BASE_URL ?>admin/nha_cung_cap/edit/<?= $item['id'] ?>">

            <!-- Name -->
            <div class="mb-3">
                <label class="form-label">Tên</label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($item['name']) ?>" required>
            </div>

            <!-- Tel -->
            <div class="mb-3">
                <label class="form-label">Vị trí</label>
                <input type="text" name="vi_tri" class="form-control"
                       value="<?= $item['vi_tri'] ?>" required>
            </div>

            <div class="mb-3">
        <label class="form-label">Giảm giá (tính theo %) </label>
        <input type="text" name="giam_gia" class="form-control"  value="<?= htmlspecialchars($item['giam_gia']) ?>" required>
    </div>

            <div class="text-center">
                <button class="btn btn-warning">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="<?= BASE_URL ?>admin/nha_cung_cap" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

        </form>

    </div>
</main>

<?php include BASE_PATH . '/app/views/admin/layout/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
