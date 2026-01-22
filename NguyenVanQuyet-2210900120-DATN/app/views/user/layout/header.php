<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <title>QUYET-KIDDO FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&amp;family=Roboto:wght@400;500;700&amp;display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet"
          href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Libraries CSS -->
    <link rel="stylesheet" href="<?=BASE_URL?>assets/lib/animate/animate.min.css">
    <link rel="stylesheet" href="<?=BASE_URL?>assets/lib/owlcarousel/assets/owl.carousel.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?=BASE_URL?>assets/css/bootstrap.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?=BASE_URL?>assets/css/style.css">
    <link rel="icon" type="image/jpeg"
      href="<?=BASE_URL?>uploads/logo/logo.jpg">

<link rel="apple-touch-icon"
      href="<?=BASE_URL?>uploads/logo/logo.jpg">
<style>
.nav-custom {
    display: flex;
    align-items: center;
}

/* Nhóm menu bên trái */
.nav-left {
    margin-right: 25px;
}

/* Nhóm menu bên phải */
.nav-right {
    margin-left: auto;
}
.navbar-nav {
    width: 100%;
    display: flex;
    justify-content: space-between;
}
.navbar-nav .nav-link {
    padding: 20px 18px;
    font-weight: 500;
}

.navbar-nav .nav-link.active {
    color: white !important;
}
/* Logo mặc định – Desktop */
/* Wrapper logo */
.logo {
    display: flex;
    align-items: center;
}

/* LOGO – Desktop lớn */
.logo-img {
    height: 120px;      /* TO HƠN RÕ */
    width: auto;
    object-fit: contain;
}

/* Laptop */
@media (max-width: 1200px) {
    .logo-img {
        height: 100px;
    }
}

/* Tablet */
@media (max-width: 992px) {
    .logo-img {
        height: 80px;
    }
}

/* Mobile */
@media (max-width: 576px) {
    .logo-img {
        height: 60px;
    }
}

/* =========================
   MOBILE FIX (<= 576px)
========================= */
@media (max-width: 576px) {

/* Giảm padding container */
.container-fluid.px-5 {
    padding-left: 15px !important;
    padding-right: 15px !important;
}

/* Ẩn topbar desktop */
.container-fluid.border-bottom {
    display: none !important;
}

/* Logo mobile */
.logo-img {
    height: 55px;
    max-width: 100%;
}

/* Header logo + search + cart xếp dọc */
.container-fluid.py-4 {
    padding-top: 10px !important;
    padding-bottom: 10px !important;
}

/* Search */
.container-fluid .col-lg-6 {
    margin-top: 10px;
}

.container-fluid input.form-control {
    padding: 10px 15px;
    font-size: 14px;
}

.container-fluid button.btn {
    padding: 10px 16px;
}

/* Cart */
.container-fluid .col-lg-3.text-lg-end {
    margin-top: 10px;
    text-align: center !important;
}

/* Navbar */
.nav-bar .row {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

.navbar {
    padding: 8px 0;
}

.navbar-brand h1 {
    font-size: 18px;
    line-height: 1.2;
}

/* Menu mobile */
.navbar-nav {
    flex-direction: column !important;
    align-items: flex-start !important;
}

.navbar-nav .nav-link {
    padding: 10px 0;
    width: 100%;
}

/* Hotline */
.btn-secondary {
    width: 100%;
    text-align: center;
    margin-top: 10px;
}
}



</style>

</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid px-5 d-none border-bottom d-lg-block">
        <div class="row gx-0 align-items-center">
            <div class="col-lg-4 text-center text-lg-start mb-lg-0">
                <div class="d-inline-flex align-items-center" style="">
                    <a href="#!" class="text-muted me-2"> Giúp </a><small> / </small>
                    <a href="#!" class="text-muted mx-2"> Hỗ trợ</a><small> / </small>
                    <a href="#!" class="text-muted ms-2"> Liên hệ</a>

                </div>
            </div>
            <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
                <small class="text-dark">Liên hệ:</small>
                <a href="#!" class="text-muted">0975142461</a>
            </div>

            <div class="col-lg-4 d-flex justify-content-end align-items-center">
            <?php
$isLogin = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
?>

<?php if (!$isLogin): ?>
    <!-- CHƯA ĐĂNG NHẬP -->
    <a href="<?=BASE_URL?>loginUser" class="nav-item nav-link">
        <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
    </a>

    <a href="<?=BASE_URL?>register" class="nav-item nav-link">
        <i class="fas fa-user-plus me-1"></i> Đăng ký
    </a>

<?php else: ?>
    <!-- ĐÃ ĐĂNG NHẬP -->
    <div class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle"
           data-bs-toggle="dropdown">
            <i class="fas fa-user-circle me-1"></i>
            <?=htmlspecialchars($_SESSION['user_name'])?>
        </a>

        <div class="dropdown-menu dropdown-menu-end m-0">
            <a href="<?=BASE_URL?>account/profile" class="dropdown-item">
                <i class="fas fa-id-card me-2"></i> Thông tin cá nhân
            </a>

            <a href="<?=BASE_URL?>theo-doi-don-hang" class="dropdown-item">
                <i class="fas fa-box me-2"></i> Đơn hàng của tôi
            </a>

            <div class="dropdown-divider"></div>

            <a href="<?=BASE_URL?>logout" class="dropdown-item text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
            </a>
        </div>
    </div>
<?php endif; ?>

            </div>

        </div>
    </div>
    <div class="container-fluid px-5 py-4 d-none d-lg-block">
        <div class="row gx-0 align-items-center text-center">
            <div class="col-md-4 col-lg-3 text-center text-lg-start">
                <div class="d-inline-flex align-items-center">
                    <a href="#" class="navbar-brand p-0">
                        <!-- <h1 class="display-5 text-primary m-0"><i
                                class="fas fa-shopping-bag text-secondary me-2"></i>Electro</h1> -->
                        <!-- <img src="img/logo.png" alt="Logo"> -->

                        <h1 class="logo m-0">
    <a href="/">
        <img src="<?=BASE_URL . '/uploads/logo/logo.jpg'?>" alt="Electro" class="logo-img">
    </a>
</h1>

                    </a>
                </div>
            </div>
            <div class="col-md-4 col-lg-6 text-center">
                <div class="position-relative ps-4">
                    <div class="d-flex border rounded-pill">
                        <input class="form-control border-0 rounded-pill w-100 py-3" type="text"
                            data-bs-target="#!dropdownToggle123" placeholder="Tìm kiếm tại đây">
                        <button type="button" class="btn btn-primary rounded-pill py-3 px-5" style="border: 0;"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 text-center text-lg-end">
                <div class="d-inline-flex align-items-center">

                <?php
$cart = $_SESSION['cart'] ?? [];
$totalCart = 0;

if (!empty($cart)) {
    foreach ($cart as $item) {
        $qty = (int) ($item['quantity'] ?? 0);
        $price = (int) ($item['price'] ?? 0);
        $totalCart += $qty * $price;
    }
}
?>

<a href="<?=BASE_URL?>cart"
   class="text-muted d-flex align-items-center justify-content-center">
    <span class="rounded-circle btn-md-square border">
        <i class="fas fa-shopping-cart"></i>
    </span>

    <span class="text-dark ms-2">
        <?=number_format($totalCart, 0, ',', '.')?>₫
    </span>
</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar & Hero Start -->
    <div class="container-fluid nav-bar p-0">
        <div class="row gx-0 bg-primary px-5 align-items-center">

            <div class="col-12 col-lg-12">
            <nav class="navbar navbar-expand-lg navbar-light bg-primary">

<!-- LOGO MOBILE -->
<a href="<?=BASE_URL?>" class="navbar-brand d-flex align-items-center d-lg-none">
    <img src="<?=BASE_URL?>uploads/logo/logo.jpg" alt="Logo" style="height:40px">
    <span class="text-white ms-2 fw-bold">QUYET-KIDDO</span>
</a>

<!-- NÚT MENU MOBILE (BẮT BUỘC) -->
<button class="navbar-toggler ms-auto" type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarCollapse"
        aria-controls="navbarCollapse"
        aria-expanded="false"
        aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>

<!-- MENU -->
<div class="collapse navbar-collapse" id="navbarCollapse">
    <div class="navbar-nav w-100 nav-custom">

        <a href="<?=BASE_URL?>" class="nav-item nav-link active">Trang chủ</a>

        <?php
        require_once BASE_PATH . '/app/models/category.php';
        require_once BASE_PATH . '/app/models/CategoryPost.php';

        $categoryModel = new category();
        $categoryPostModel = new CategoryPost();
        $danh_muc = $categoryModel->getAll();
        $categoryPos = $categoryPostModel->getAll()
        ?>

        <!-- TẤT CẢ SẢN PHẨM -->
        <div class="nav-item dropdown">
            <a href="<?=BASE_URL?>tat-ca-san-pham"
               class="nav-link dropdown-toggle"
               data-bs-toggle="dropdown">
                Tất cả sản phẩm
            </a>
            <div class="dropdown-menu m-0">
                <?php foreach ($danh_muc as $dm): ?>
                    <a href="<?=BASE_URL?>category/<?=$dm['slug']?>"
                       class="dropdown-item">
                        <?=htmlspecialchars($dm['name'])?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle"
               data-bs-toggle="dropdown">
                Tin tức
            </a>
            <div class="dropdown-menu m-0">
                <?php foreach ($categoryPos as $categoryPosItem): ?>
                    <a href="<?=BASE_URL?>tin-tuc/<?=$categoryPosItem['slug']?>"
                       class="dropdown-item">
                        <?=htmlspecialchars($categoryPosItem['name'])?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <a href="<?=BASE_URL?>product/sample-product"
           class="nav-item nav-link">
            Về chúng tôi
        </a>

        <a href="<?=BASE_URL?>contact"
           class="nav-item nav-link">
            Liên hệ
        </a>

        <!-- LOGIN -->
        <?php if (!$isLogin): ?>
            <a href="<?=BASE_URL?>login" class="nav-item nav-link">
                Đăng nhập
            </a>
            <a href="<?=BASE_URL?>register" class="nav-item nav-link">
                Đăng ký
            </a>
        <?php else: ?>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle"
                   data-bs-toggle="dropdown">
                    <?=htmlspecialchars($_SESSION['user_name'])?>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="<?=BASE_URL?>account/profile"
                       class="dropdown-item">Tài khoản</a>

                       <!-- <a href="<?=BASE_URL?>account/profile"
                       class="dropdown-item">Thông tin cá nhân</a> -->

                       <a href="<?=BASE_URL?>theo-doi-don-hang"
                       class="dropdown-item">Đơn hàng của tôi</a>

                    <a href="<?=BASE_URL?>logout"
                       class="dropdown-item text-danger">Đăng xuất</a>
                </div>
            </div>
        <?php endif; ?>

        <!-- HOTLINE MOBILE -->
        <a href="tel:0975142461"
           class="btn btn-secondary mt-2 d-lg-none">
             0975 142 461
        </a>

    </div>
</div>
</nav>

            </div>
        </div>
    </div>
