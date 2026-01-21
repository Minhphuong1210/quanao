<?php
include BASE_PATH . '/app/views/user/layout/header.php';
?>

<style>
.ua-register-wrapper {
    min-height: calc(100vh - 120px);
    display: flex;
    align-items: center;
    justify-content: center;
}

.ua-register-box {
    width: 420px;
    background: #fff;
    border-radius: 14px;
    padding: 32px;
    box-shadow: 0 25px 45px rgba(0,0,0,.18);
}

.ua-register-box h2 {
    text-align: center;
    margin-bottom: 22px;
    color: #333;
}

.ua-register-group {
    margin-bottom: 15px;
}

.ua-register-group label {
    font-weight: 600;
    font-size: 14px;
    display: block;
    margin-bottom: 6px;
}

.ua-register-group input {
    width: 100%;
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.ua-register-group input:focus {
    outline: none;
    border-color: #667eea;
}

.ua-register-btn {
    width: 100%;
    padding: 13px;
    border: none;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    margin-top: 10px;
    transition: .3s;
}

.ua-register-btn:hover {
    opacity: .9;
}

.ua-register-links {
    margin-top: 18px;
    text-align: center;
    font-size: 14px;
}

.ua-register-links a {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

.ua-register-links a:hover {
    text-decoration: underline;
}
</style>

<div class="ua-register-wrapper">
    <div class="ua-register-box">
        <h2>Đăng ký tài khoản</h2>

        <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

        <form method="POST" action="/register">
    
            <div class="ua-register-group">
                <label>Họ và tên</label>
                <input type="text" name="name" placeholder="Nhập họ và tên" required>
            </div>

            <div class="ua-register-group">
                <label>Số điện thoại</label>
                <input type="tel" name="tel" placeholder="Nhập số điện thoại" required>
            </div>

            <div class="ua-register-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>

            <div class="ua-register-group">
                <label>Địa chỉ</label>
                <input type="text" name="address" placeholder="Nhập địa chỉ" required>
            </div>

            <div class="ua-register-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Nhập email" required>
            </div>

            <button class="ua-register-btn">Đăng ký</button>

            <div class="ua-register-links">
                <a href="/login">Đã có tài khoản? Đăng nhập</a>
            </div>
        </form>
    </div>
</div>

<?php
include BASE_PATH . '/app/views/user/layout/footer.php';
?>
