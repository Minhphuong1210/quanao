<?php
require_once __DIR__ . '/../../../includes/auth.php';
// checkAdmin(); // Bỏ comment nếu bạn có hàm kiểm tra quyền admin

require_once __DIR__ . '/../../../models/category.php';

// Kiểm tra ID có được truyền và hợp lệ không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php?error=invalid_id');
    exit;
}

$id = (int)$_GET['id'];

$model = new Category();

$category = $model->find($id);

if (!$category) {
    header('Location: index.php?error=not_found');
    exit;
}

if ($category['active'] == -1) {
    header('Location: index.php?error=already_deleted');
    exit;
}

$result = $model->delete($id);

if ($result) {
    header('Location: index.php?msg=deleted');
} else {
    header('Location: index.php?error=delete_failed');
}

exit;