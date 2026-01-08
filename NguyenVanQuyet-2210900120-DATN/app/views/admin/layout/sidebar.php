<?php
// views/layout/sidebar.php
// Đường dẫn đúng cho routes (không dùng file path, dùng URL route như /admin/product)
?>
<aside class="sidebar d-none d-lg-flex flex-column p-3">
    <div class="text-center mb-4 p-3 border-bottom border-light">
        <i class="fas fa-tshirt fa-3x text-primary mb-2"></i>
        <h5 class="mb-0 fw-bold text-white">Quanao Panel</h5>
        <small class="text-muted">Admin</small>
    </div>
    <nav class="nav flex-column flex-grow-1">
        <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin') === 0 && !strpos($_SERVER['REQUEST_URI'], '/category') && !strpos($_SERVER['REQUEST_URI'], '/product')) ? 'active' : '' ?>"
            href="/admin" data-load="/admin?ajax=1">
            <i class="fas fa-chart-line me-2"></i> Dashboard
        </a>
        <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin/category') !== false) ? 'active' : '' ?>"
            href="/admin/category" data-load="/admin/category?ajax=1">
            <i class="fas fa-tags me-2"></i> Quản lý Category
        </a>
        <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin/product') !== false) ? 'active' : '' ?>"
            href="/admin/product" data-load="/admin/product?ajax=1">
            <i class="fas fa-box me-2"></i> Sản phẩm
        </a>
        <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin/order') !== false) ? 'active' : '' ?>"
            href="/admin/order" data-load="/admin/order?ajax=1">
            <i class="fas fa-shopping-cart me-2"></i> Đơn hàng
        </a>
        <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin/user') !== false) ? 'active' : '' ?>"
            href="/admin/user" data-load="/admin/user?ajax=1">
            <i class="fas fa-users me-2"></i> Người dùng
        </a>
        <hr class="text-muted my-2">
        <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin/settings') !== false) ? 'active' : '' ?>"
            href="/admin/settings" data-load="/admin/settings?ajax=1">
            <i class="fas fa-cog me-2"></i> Cài đặt
        </a>
        <a class="nav-link text-danger" href="/admin/logout">
            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
        </a>
    </nav>
</aside>