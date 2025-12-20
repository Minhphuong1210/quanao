<?php
// includes/auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php'; // Đảm bảo có $pdo và BASE_URL

class Auth {
    private static $pdo;

    public static function init() {
        global $pdo;
        self::$pdo = $pdo;
    }

    /**
     * Đăng ký người dùng mới
     */
    public static function register($name, $email, $password, $password_confirm) {
        if (empty($name) || empty($email) || empty($password)) {
            return 'Vui lòng điền đầy đủ thông tin.';
        }

        if ($password !== $password_confirm) {
            return 'Mật khẩu nhập lại không khớp.';
        }

        if (strlen($password) < 6) {
            return 'Mật khẩu phải ít nhất 6 ký tự.';
        }

        // Kiểm tra name hoặc email đã tồn tại
        $stmt = self::$pdo->prepare("SELECT id FROM users WHERE name = ? OR email = ?");
        $stmt->execute([$name, $email]);
        if ($stmt->rowCount() > 0) {
            return 'Tên đăng nhập hoặc email đã được sử dụng.';
        }

        // Hash mật khẩu và lưu
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = self::$pdo->prepare("INSERT INTO users (name, email, password, active) VALUES (?, ?, ?, 1)");
        if ($stmt->execute([$name, $email, $hashed])) {
            return true; // Thành công
        }

        return 'Đăng ký thất bại, vui lòng thử lại.';
    }

    /**
     * Đăng nhập người dùng
     */
    public static function login($login, $password) {
        if (empty($login) || empty($password)) {
            return 'Vui lòng nhập đầy đủ thông tin.';
        }

        $stmt = self::$pdo->prepare("SELECT * FROM users WHERE name = ? OR email = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['active'] != 1) {
                return 'Tài khoản của bạn đã bị khóa.';
            }

            // Lưu session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['logged_in'] = true;

            return true;
        }

        return 'Tên đăng nhập hoặc mật khẩu không đúng.';
    }

    /**
     * Kiểm tra đã đăng nhập chưa
     */
    public static function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Lấy thông tin user hiện tại
     */
    public static function getCurrentUser() {
        if (!self::isLoggedIn()) {
            return null;
        }

        $stmt = self::$pdo->prepare("SELECT id, name, email, tel, address, tien, active FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }

    /**
     * Đăng xuất
     */
    public static function logout() {
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email'], $_SESSION['logged_in']);
        session_destroy();
    }


    public static function redirectIfLoggedIn($url = '/') {
        if (self::isLoggedIn()) {
            header("Location: " . BASE_URL . $url);
            exit;
        }
    }

    
    public static function requireLogin($url = '/login.php') {
        if (!self::isLoggedIn()) {
            header("Location: " . BASE_URL . $url);
            exit;
        }
    }
}

// Khởi động Auth
Auth::init();
?>