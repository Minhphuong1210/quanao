<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - Sửa danh mục bài viết</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/admin/css/style.css">
</head>

<body class="d-flex flex-column h-100">

<?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
<?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

<main class="main-content flex-grow-1 d-flex flex-column">
    <div class="content-placeholder flex-grow-1">

        <h4 class="mb-4">Sửa danh mục bài viết</h4>

        <form method="post"
              action="<?= BASE_URL ?>admin/category-post/edit/<?= $item['id'] ?>"
              class="w-50 mx-auto">

            <!-- Tên danh mục -->
            <div class="mb-3">
                <label class="form-label">Tên danh mục</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="<?= htmlspecialchars($item['name']) ?>"
                       required>
            </div>

            <!-- Slug -->
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text"
                       name="slug"
                       class="form-control"
                       value="<?= htmlspecialchars($item['slug']) ?>">
            </div>

            <!-- Trạng thái -->
            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="active" class="form-select">
                    <option value="1" <?= $item['active'] == 1 ? 'selected' : '' ?>>
                        Hiển thị
                    </option>
                    <option value="0" <?= $item['active'] == 0 ? 'selected' : '' ?>>
                        Ẩn
                    </option>
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Cập nhật
                </button>

                <a href="<?= BASE_URL ?>admin/category-post" class="btn btn-secondary ms-2">
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
