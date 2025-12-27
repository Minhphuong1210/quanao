<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/auth.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f9; }
        .admin-wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #2c3e50; color: white; padding: 20px 0; }
        .sidebar .logo { font-size: 24px; font-weight: bold; text-align: center; padding: 20px 0; }
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar ul li { padding: 10px 20px; }
        .sidebar ul li a { color: white; text-decoration: none; display: block; padding: 10px; border-radius: 5px; }
        .sidebar ul li a:hover { background: #34495e; }
        .main { flex: 1; padding: 20px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; background: white; padding: 15px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; border-radius: 8px; }
        .topbar input { padding: 10px; width: 300px; border: 1px solid #ddd; border-radius: 5px; }
        .topbar .user { font-weight: bold; color: #2c3e50; }
        .content h2 { margin-top: 0; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        table th, table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        table th { background: #3498db; color: white; }
        table tr:hover { background: #f8f9fa; }
        .btn { padding: 8px 12px; margin: 0 5px; text-decoration: none; border-radius: 5px; font-size: 14px; }
        .btn-edit { background: #f39c12; color: white; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-add { display: inline-block; margin-top: 20px; padding: 12px 20px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">BABY'S CLOTHES</div>
        <ul>
            <li><a href="<?= BASE_URL ?>/admin/index.php">Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/views/admin/category/index.php">Category</a></li>
            <li><a href="<?= BASE_URL ?>/admin/product/index.php">Products</a></li>
            <li><a href="<?= BASE_URL ?>/admin/order/index.php">Orders</a></li>
            <li><a href="<?= BASE_URL ?>/admin/user/index.php">Users</a></li>
            <li><a href="<?= BASE_URL ?>/logout.php">Logout</a></li>
        </ul>
    </aside>

    <div class="main">
        <!-- <header class="topbar">
            <input type="text" placeholder="Tìm kiếm...">
            <div class="user">Admin</div>
        </header> -->

        <section class="content">
            <?= isset($content) ? $content : '' ?>
        </section>
    </div>
</div>

</body>
</html>