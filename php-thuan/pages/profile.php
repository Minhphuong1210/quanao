<?php 
require_once 'includes/auth.php';
Auth::requireLogin(); 
$user = Auth::getCurrentUser();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thông tin cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Thông tin cá nhân</h2>
    <p><strong>Họ tên:</strong> <?= htmlspecialchars($user['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($user['tel'] ?? 'Chưa cập nhật') ?></p>
    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($user['address'] ?? 'Chưa cập nhật') ?></p>
    <!-- Có thể thêm form chỉnh sửa sau -->
    <a href="<?= BASE_URL ?>/" class="btn btn-secondary">Quay về trang chủ</a>
</div>
</body>
</html>