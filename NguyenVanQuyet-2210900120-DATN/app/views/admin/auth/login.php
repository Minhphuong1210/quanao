<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: #fff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-card h2 {
            margin-bottom: 1.5rem;
            font-weight: 700;
            color: #333;
            text-align: center;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
        }
        .btn-primary {
            background: #667eea;
            border: none;
        }
        .btn-primary:hover {
            background: #5a67d8;
        }
        .forgot-link {
            font-size: 0.9rem;
            color: #666;
        }
        .forgot-link:hover {
            color: #333;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Admin Login</h2>
    <form action="<?= BASE_URL ?>postLogin" method="POST">
        <div class="mb-3">
            <label for="tel" class="form-label">Số điện thoại</label>
            <input type="text" id="tel" name="tel" class="form-control" required placeholder="Nhập số điện thoại">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required placeholder="Nhập mật khẩu">
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" class="form-label">Remember me</label>
            </div>
            <a href="#" class="forgot-link">Forgot password?</a>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

<!-- Bootstrap JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
