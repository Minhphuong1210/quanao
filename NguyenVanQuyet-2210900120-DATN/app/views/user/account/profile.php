<?php include BASE_PATH . '/app/views/user/layout/header.php'; ?>

<div class="container py-4">
    <h4 class="mb-4"> Thông tin cá nhân</h4>
    <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>


    <form method="post" action="<?= BASE_URL ?>account/profile/update">
        <div class="mb-3">
            <label>Họ tên</label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($user['name']) ?>">
        </div>

        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="tel" class="form-control"
                   value="<?= htmlspecialchars($user['tel']) ?>">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <div class="mb-3">
            <label>Địa chỉ</label>
            <textarea name="address" class="form-control"><?= htmlspecialchars($user['address']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Mật khẩu mới (để trống nếu không đổi)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button class="btn btn-primary"> Cập nhật</button>
    </form>
</div>

<?php include BASE_PATH . '/app/views/user/layout/footer.php'; ?>
