<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - Thêm bài viết</title>

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

        <h4 class="mb-4">Thêm bài viết</h4>

        <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">

            <div class="mb-3">
                <label class="form-label">Tên bài viết</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Danh mục</label>
                <select name="category_post_id" class="form-select">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh đại diện</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả ngắn</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Nội dung</label>
              
                <textarea name="content" id="content" class="form-control" rows="6"></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Lưu bài viết
                </button>
                <a href="<?= BASE_URL ?>admin/post" class="btn btn-secondary">
                    Quay lại
                </a>
            </div>

        </form>

    </div>
</main>

<?php include BASE_PATH . '/app/views/admin/layout/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.querySelector('#content');
    if (!textarea) return;

    ClassicEditor
        .create(textarea, {
            language: 'vi',
            placeholder: 'Nhập nội dung bài viết...'
        })
        .catch(error => console.error(error));
});
</script>

</body>
</html>
