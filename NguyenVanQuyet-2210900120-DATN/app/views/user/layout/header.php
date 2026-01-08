<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <title>Electro - Electronics Website Template</title>
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
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/animate/animate.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/owlcarousel/assets/owl.carousel.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">

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


</style>

</head>

<body>

    <!-- Spinner Start -->
    <!-- <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div> -->
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid px-5 d-none border-bottom d-lg-block">
        <div class="row gx-0 align-items-center">
            <div class="col-lg-4 text-center text-lg-start mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a href="#!" class="text-muted me-2"> Help</a><small> / </small>
                    <a href="#!" class="text-muted mx-2"> Support</a><small> / </small>
                    <a href="#!" class="text-muted ms-2"> Contact</a>

                </div>
            </div>
            <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
                <small class="text-dark">Call Us:</small>
                <a href="#!" class="text-muted">(+012) 1234 567890</a>
            </div>

           
        </div>
    </div>
    <div class="container-fluid px-5 py-4 d-none d-lg-block">
        <div class="row gx-0 align-items-center text-center">
            <div class="col-md-4 col-lg-3 text-center text-lg-start">
                <div class="d-inline-flex align-items-center">
                    <a href="#" class="navbar-brand p-0">
                        <h1 class="display-5 text-primary m-0"><i
                                class="fas fa-shopping-bag text-secondary me-2"></i>Electro</h1>
                        <!-- <img src="img/logo.png" alt="Logo"> -->
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-lg-6 text-center">
                <div class="position-relative ps-4">
                    <div class="d-flex border rounded-pill">
                        <input class="form-control border-0 rounded-pill w-100 py-3" type="text"
                            data-bs-target="#!dropdownToggle123" placeholder="Search Looking For?">
                        <select class="form-select text-dark border-0 border-start rounded-0 p-3" style="width: 200px;">
                            <option value="All Category">All Category</option>
                            <option value="Pest Control-2">Category 1</option>
                            <option value="Pest Control-3">Category 2</option>
                            <option value="Pest Control-4">Category 3</option>
                            <option value="Pest Control-5">Category 4</option>
                        </select>
                        <button type="button" class="btn btn-primary rounded-pill py-3 px-5" style="border: 0;"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 text-center text-lg-end">
                <div class="d-inline-flex align-items-center">
                    <a href="#!" class="text-muted d-flex align-items-center justify-content-center me-3"><span
                            class="rounded-circle btn-md-square border"><i class="fas fa-random"></i></i></a>
                    <a href="#!" class="text-muted d-flex align-items-center justify-content-center me-3"><span
                            class="rounded-circle btn-md-square border"><i class="fas fa-heart"></i></a>
                    <a href="#!" class="text-muted d-flex align-items-center justify-content-center"><span
                            class="rounded-circle btn-md-square border"><i class="fas fa-shopping-cart"></i></span>
                        <span class="text-dark ms-2">$0.00</span></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar & Hero Start -->
    <div class="container-fluid nav-bar p-0">
        <div class="row gx-0 bg-primary px-5 align-items-center">
          
            <div class="col-12 col-lg-12">
                <nav class="navbar navbar-expand-lg navbar-light bg-primary ">
                    <a href="#" class="navbar-brand d-block d-lg-none">
                        <h1 class="display-5 text-secondary m-0"><i
                                class="fas fa-shopping-bag text-white me-2"></i>Electro</h1>
                        <!-- <img src="img/logo.png" alt="Logo"> -->
                    </a>
                  
                    <div class="collapse navbar-collapse" id="navbarCollapse">
    <div class="navbar-nav d-flex w-100 py-0 nav-custom">

        <!-- Home -->
        <a href="<?= BASE_URL ?>" class="nav-item nav-link active">Trang chủ</a>

        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                Tất cả sản phẩm
            </a>
            <div class="dropdown-menu m-0">
                <a href="<?= BASE_URL ?>bestseller" class="dropdown-item">Bestseller</a>
               
            </div>
        </div>

        <!-- Shop -->
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                Tin tức
            </a>
            <div class="dropdown-menu m-0">
                <a href="<?= BASE_URL ?>bestseller" class="dropdown-item">Bestseller</a>
               
            </div>
        </div>

        <!-- Single product (ví dụ) -->
        <a href="<?= BASE_URL ?>product/sample-product" class="nav-item nav-link">
            Về chúng tôi
        </a>

        <!-- Pages -->
       

        <!-- Contact -->
        <a href="<?= BASE_URL ?>contact" class="nav-item nav-link me-2">Liên hệ</a>

        <!-- All Category (mobile only) -->
        <div class="nav-item dropdown d-block d-lg-none mb-3">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                All Category
            </a>
            <div class="dropdown-menu m-0">
                <ul class="list-unstyled categories-bars">
                    <li>
                        <div class="categories-bars-item">
                            <a href="<?= BASE_URL ?>category/accessories">Accessories</a>
                            <span>(3)</span>
                        </div>
                    </li>
                    <li>
                        <div class="categories-bars-item">
                            <a href="<?= BASE_URL ?>category/electronics-computer">
                                Electronics & Computer
                            </a>
                            <span>(5)</span>
                        </div>
                    </li>
                    <li>
                        <div class="categories-bars-item">
                            <a href="<?= BASE_URL ?>category/laptop-desktop">
                                Laptops & Desktops
                            </a>
                            <span>(2)</span>
                        </div>
                    </li>
                    <li>
                        <div class="categories-bars-item">
                            <a href="<?= BASE_URL ?>category/mobile-tablet">
                                Mobiles & Tablets
                            </a>
                            <span>(8)</span>
                        </div>
                    </li>
                    <li>
                        <div class="categories-bars-item">
                            <a href="<?= BASE_URL ?>category/smart-device">
                                SmartPhone & Smart TV
                            </a>
                            <span>(5)</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <!-- Hotline -->
    <a href="tel:+01234567890"
       class="btn btn-secondary py-2 px-4 px-lg-3 mb-3 mb-md-3 mb-lg-0">
        <i class="fa fa-mobile-alt me-2"></i> +0123 456 7890
    </a>
</div>

                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->