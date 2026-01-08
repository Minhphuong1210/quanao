<?php
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = Auth::login($_POST['login'], $_POST['password']);

    if ($result === true) {
        header('Location: index.php');
    } else {
        $_SESSION['login_error'] = $result;
        header('Location: login.php');
    }
    exit;
}
?>