<?php
include BASE_PATH . '/app/views/user/layout/header.php';
?>

<style>
.ua-login-wrapper {
    min-height: calc(100vh - 120px);
    display: flex;
    align-items: center;
    justify-content: center;
}

.ua-login-box {
    width: 380px;
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,.2);
}

.ua-login-box h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.ua-login-group {
    margin-bottom: 15px;
}

.ua-login-group label {
    font-weight: 600;
    font-size: 14px;
    display: block;
    margin-bottom: 6px;
}

.ua-login-group input {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.ua-login-group input:focus {
    outline: none;
    border-color: #667eea;
}

.ua-login-btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: #667eea;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: .3s;
}

.ua-login-btn:hover {
    background: #5a67d8;
}

.ua-login-links {
    margin-top: 15px;
    text-align: center;
    font-size: 14px;
}

.ua-login-links a {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

.ua-login-links a:hover {
    text-decoration: underline;
}
</style>

<div class="ua-login-wrapper">
    <div class="ua-login-box">
        <h2>Đăng nhập</h2>

        <form method="POST" action="">
            <div class="ua-login-group">
                <label>Tel</label>
                <input type="tel" name="tel" placeholder="Nhập số điện thoại" required>
            </div>

            <div class="ua-login-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>

            <button class="ua-login-btn">Đăng nhập</button>

            <div class="ua-login-links">
                <a href="/forgot-password">Quên mật khẩu?</a><br>
                <a href="/register">Chưa có tài khoản? Đăng ký</a>
            </div>
        </form>
    </div>
</div>

<?php
include BASE_PATH . '/app/views/user/layout/footer.php';
?>
