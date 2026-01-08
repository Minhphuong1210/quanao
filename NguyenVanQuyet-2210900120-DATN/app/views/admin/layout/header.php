<header class="header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <button class="btn btn-link text-light d-lg-none me-3" onclick="toggleSidebar()">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        <h4 class="mb-0 fw-bold"><?= $pageTitle ?? 'Dashboard' ?></h4>
    </div>
    <div class="d-flex align-items-center">
        <div class="dropdown me-3">
            <button class="btn btn-link text-light" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-bell me-1"></i> <span class="badge bg-danger">3</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Thông báo mới</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <a class="d-flex align-items-center text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                <img src="https://via.placeholder.com/32x32/6366f1/ffffff?text=<?= substr($adminName ?? 'A', 0, 1) ?>" class="rounded-circle me-2" width="32" alt="Avatar">
                <span><?= $adminName ?? 'Admin' ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="/admin/profile">Hồ sơ</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="/admin/logout">Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</header>