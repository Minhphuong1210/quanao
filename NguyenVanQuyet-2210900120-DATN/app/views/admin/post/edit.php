<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - Sửa màu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=BASE_URL?>assets/admin/css/style.css">
</head>

<body class="d-flex flex-column h-100">

<?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
<?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

<main class="main-content flex-grow-1 d-flex flex-column">
    <div class="content-placeholder flex-grow-1">

        <h4 class="mb-4">Sửa màu sắc</h4>

        <form method="post"
      action="<?=BASE_URL?>admin/post/edit/<?=$item['id']?>"
      enctype="multipart/form-data"
      class="w-75">

<div class="mb-3">
    <label>Tiêu đề</label>
    <input type="text" name="name"
           class="form-control"
           value="<?=htmlspecialchars($item['name'])?>"
           required>
</div>

<div class="mb-3">
    <label>Slug</label>
    <input type="text" name="slug"
           class="form-control"
           value="<?=htmlspecialchars($item['slug'])?>">
</div>

<div class="mb-3">
    <label>Danh mục</label>
    <select name="category_post_id" class="form-select" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?=$cat['id']?>"
                <?=$cat['id'] == $item['category_post_id'] ? 'selected' : ''?>>
                <?=$cat['name']?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label>Ảnh đại diện</label><br>
    <?php if (!empty($item['image'])): ?>
        <img src="<?=BASE_URL . $item['image']?>" width="120" class="mb-2">
    <?php endif; ?>
    <input type="file" name="image" class="form-control">
</div>

<div class="mb-3">
    <label>Mô tả ngắn</label>
    <textarea name="description" class="form-control" rows="3"><?=htmlspecialchars($item['description'])?></textarea>
</div>

<div class="mb-3">
    <label>Nội dung</label>
    <textarea name="content" class="form-control"  id="content" rows="8"><?=htmlspecialchars($item['content'])?></textarea>
</div>

<div class="mb-3">
    <label>Trạng thái</label>
    <select name="active" class="form-select">
        <option value="1" <?=$item['active'] == 1 ? 'selected' : ''?>>Hiển thị</option>
        <option value="0" <?=$item['active'] == 0 ? 'selected' : ''?>>Ẩn</option>
    </select>
</div>

<button class="btn btn-primary">
    <i class="fas fa-save"></i> Lưu
</button>
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
