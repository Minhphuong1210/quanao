<?php
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = Auth::register($_POST['username'], $_POST['email'], $_POST['password'], $_POST['password_confirm']);

    if ($result === true) {
        $_SESSION['register_success'] = 'Đăng ký thành công! Hãy đăng nhập.';
        header('Location: login.php');
    } else {
        $_SESSION['register_error'] = $result;
        header('Location: register.php');
    }
    exit;
}
?>