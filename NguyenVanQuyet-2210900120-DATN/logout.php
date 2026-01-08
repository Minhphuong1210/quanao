<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/auth.php';
Auth::logout();

session_unset();
session_destroy();

header('Location: index.php?logout=success');
exit;
?>