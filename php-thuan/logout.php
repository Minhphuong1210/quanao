<?php
// logout.php

// Bắt đầu session để hủy
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cách 1: Nếu bạn đang dùng class Auth (khuyến nghị)
require_once 'includes/auth.php';
Auth::logout();

// Cách 2: Nếu chưa dùng Auth class, hủy session thủ công (vẫn hoạt động tốt)
// unset($_SESSION['user_id']);
// unset($_SESSION['user_name']);
// unset($_SESSION['user_email']);
// unset($_SESSION['logged_in']);
// session_destroy();

// Xóa toàn bộ session để chắc chắn
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập hoặc trang chủ
// Bạn có thể thay bằng trang chủ nếu muốn
header('Location: login.php?logout=success');
exit;
?>