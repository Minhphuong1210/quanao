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
            href="/admin" >
            <i class="fas fa-chart-line me-2"></i> Dashboard
        </a>
        <a class="nav-link  <?= (strpos($_SERVER['REQUEST_URI'], 'admin/category/index') !== false) ? 'active' : '' ?>"
            href="/admin/category/index">
            <i class="fas fa-tags me-2"></i> Quản lý Category
        </a>
        <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin/product') !== false) ? 'active' : '' ?>"
            href="/admin/product" >
            <i class="fas fa-box me-2"></i> Sản phẩm
        </a>

        <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin/nha-cung-cap') !== false) ? 'active' : '' ?>"
            href="/admin/nha_cung_cap" >
            <i class="fas fa-cog me-2"></i> Nhà cung cấp
        </a>

        <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin/size') !== false) ? 'active' : '' ?>"
            href="/admin/sizes" >
            <i class="fas fa-cog me-2"></i> Kích thước
        </a>

        <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin/size') !== false) ? 'active' : '' ?>"
            href="/admin/colors" >
            <i class="fas fa-cog me-2"></i> Màu sắc
        </a>


        <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin/order') !== false) ? 'active' : '' ?>"
            href="/admin/order" >
            <i class="fas fa-shopping-cart me-2"></i> Đơn hàng
        </a>
        <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin/user') !== false) ? 'active' : '' ?>"
            href="/admin/user" >
            <i class="fas fa-users me-2"></i> Người dùng
        </a>
        <hr class="text-muted my-2">
        <!-- <a class="nav-link load-content <?= (strpos($_SERVER['REQUEST_URI'], 'admin/settings') !== false) ? 'active' : '' ?>"
            href="/admin/settings" >
            <i class="fas fa-cog me-2"></i> Cài đặt
        </a> -->
        <a class="nav-link text-danger" href="/admin/logout">
            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
        </a>
    </nav>
</aside>