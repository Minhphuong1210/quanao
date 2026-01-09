<?php 

/**
 * Check xem người này đã login hay chưa, đã có quyền để vào được admin hay chưa 
 * 
 * 
 * 
 */


 function checkAdminLogin($currentUrl = null) {
    if ($currentUrl === null) {
        $currentUrl = $_SERVER['REQUEST_URI'];
    }

    // Loại bỏ query string nếu có
    $currentUrl = strtok($currentUrl, '?');

    // Nếu đang ở trang login thì không redirect
    if ($currentUrl === '/admin/login') {
        return;
    }

    // Nếu chưa login, redirect về login
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: /admin/login');
        exit;
    }
}