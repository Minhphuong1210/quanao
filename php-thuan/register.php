<?php 
require_once 'includes/auth.php'; 

Auth::redirectIfLoggedIn('/');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .card-header {
            background: #5a67d8;
            border-bottom: none;
        }
        .btn-primary {
            background: #5a67d8;
            border: none;
        }
        .btn-primary:hover {
            background: #4c51bf;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i> Đăng ký tài khoản</h3>
                </div>
                <div class="card-body p-5">

                    <!-- Thông báo lỗi -->
                    <?php if (isset($_SESSION['register_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($_SESSION['register_error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['register_error']); ?>
                    <?php endif; ?>

                    <!-- Thông báo thành công (hiếm khi dùng ở đây, nhưng để sẵn) -->
                    <?php if (isset($_SESSION['register_success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($_SESSION['register_success']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['register_success']); ?>
                    <?php endif; ?>

                    <form action="process_register.php" method="POST" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="bi bi-person-circle"></i> Tên đăng nhập
                            </label>
                            <input type="text" name="username" id="username" class="form-control form-control-lg" 
                                   placeholder="Nhập tên đăng nhập" required minlength="3" maxlength="50" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                            <div class="form-text">Từ 3-50 ký tự</div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg" 
                                   placeholder="example@gmail.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Mật khẩu
                            </label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg" 
                                   placeholder="Ít nhất 6 ký tự" required minlength="6">
                        </div>

                        <div class="mb-4">
                            <label for="password_confirm" class="form-label">
                                <i class="bi bi-lock-fill"></i> Nhập lại mật khẩu
                            </label>
                            <input type="password" name="password_confirm" id="password_confirm" class="form-control form-control-lg" 
                                placeholder="Nhập lại mật khẩu" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-person-check"></i> Đăng ký ngay
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">
                            Đã có tài khoản? 
                            <a href="login.php" class="text-primary fw-bold">Đăng nhập ngay</a>
                        </p>
                        <p class="mt-3">
                            <a href="<?= BASE_URL ?>/" class="text-muted"><i class="bi bi-arrow-left"></i> Quay về trang chủ</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>