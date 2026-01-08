<?php 
require_once 'includes/auth.php'; 

// Nếu đã đăng nhập rồi thì chuyển về trang chủ
Auth::redirectIfLoggedIn('/');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập</title>
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
                    <h3 class="mb-0"><i class="bi bi-box-arrow-in-right me-2"></i> Đăng nhập</h3>
                </div>
                <div class="card-body p-5">

                    <!-- Thông báo lỗi -->
                    <?php if (isset($_SESSION['login_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($_SESSION['login_error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['login_error']); ?>
                    <?php endif; ?>

                    <!-- Thông báo thành công từ đăng ký -->
                    <?php if (isset($_SESSION['register_success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($_SESSION['register_success']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['register_success']); ?>
                    <?php endif; ?>

                    <form action="process_login.php" method="POST" novalidate>
                        <div class="mb-3">
                            <label for="login" class="form-label">
                                <i class="bi bi-person-circle"></i> Tên đăng nhập hoặc Email
                            </label>
                            <input type="text" name="login" id="login" class="form-control form-control-lg" 
                                   placeholder="Nhập tên đăng nhập hoặc email" required 
                                   value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Mật khẩu
                            </label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg" 
                                   placeholder="Nhập mật khẩu" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập ngay
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">
                            Chưa có tài khoản? 
                            <a href="register.php" class="text-primary fw-bold">Đăng ký ngay</a>
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