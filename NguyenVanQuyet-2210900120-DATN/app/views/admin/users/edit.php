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

        <form method="post" class="w-50 mx-auto"action="<?= BASE_URL ?>admin/user/update/<?= $user['id'] ?>">

            <!-- Name -->
            <div class="mb-3">
                <label class="form-label">Tên</label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <!-- Tel -->
            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="tel" class="form-control"
                       value="<?= htmlspecialchars($user['tel']) ?>" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($user['email']) ?>">
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="address" class="form-control"
                       value="<?= htmlspecialchars($user['address']) ?>">
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Mật khẩu mới</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Bỏ trống nếu không đổi">
            </div>

            <!-- Is admin -->
            <div class="mb-3">
                <label class="form-label">Quyền</label>
                <select name="is_admin" class="form-select">
                    <option value="0" <?= $user['is_admin'] == 0 ? 'selected' : '' ?>>User</option>
                    <option value="1" <?= $user['is_admin'] == 1 ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <!-- Active -->
            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="active" class="form-select">
                    <option value="1" <?= $user['active'] == 1 ? 'selected' : '' ?>>Hoạt động</option>
                    <option value="0" <?= $user['active'] == 0 ? 'selected' : '' ?>>Khóa</option>
                </select>
            </div>

            <div class="text-center">
                <button class="btn btn-warning">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="<?= BASE_URL ?>admin/user" class="btn btn-secondary ms-2">
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
