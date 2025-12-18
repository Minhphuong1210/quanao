<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from themewagon.github.io/Electro-Bootstrap/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 16 Dec 2025 13:01:58 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8">
    <title>Electro - Electronics Website Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet (CDN) -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">

    <!-- Libraries Stylesheet (LOCAL) -->
    <link rel="stylesheet" href="/quanao/php-thuan/assets/lib/animate/animate.min.css">
    <link rel="stylesheet" href="/quanao/php-thuan/assets/lib/owlcarousel/assets/owl.carousel.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="/quanao/php-thuan/assets/css/bootstrap.min.css">

    <!-- Template Stylesheet -->
    <link rel="stylesheet" href="/quanao/php-thuan/assets/css/style.css">
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
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

            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <div class="dropdown">
                        <a href="#!" class="dropdown-toggle text-muted me-2" data-bs-toggle="dropdown"><small>
                                USD</small></a>
                        <div class="dropdown-menu rounded">
                            <a href="#!" class="dropdown-item"> Euro</a>
                            <a href="#!" class="dropdown-item"> Dolar</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="#!" class="dropdown-toggle text-muted mx-2" data-bs-toggle="dropdown"><small>
                                English</small></a>
                        <div class="dropdown-menu rounded">
                            <a href="#!" class="dropdown-item"> English</a>
                            <a href="#!" class="dropdown-item"> Turkish</a>
                            <a href="#!" class="dropdown-item"> Spanol</a>
                            <a href="#!" class="dropdown-item"> Italiano</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle text-muted ms-2" data-bs-toggle="dropdown" aria-expanded="false">
                            <small><i class="fa fa-home me-2"></i> My Dashboard</small>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end rounded shadow">
                            <li><a class="dropdown-item" href="login.php"><i class="fa fa-sign-in-alt me-2"></i> Login</a></li>
                            <li><a class="dropdown-item" href="register.php"><i class="fa fa-user-plus me-2"></i> Register</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#!"><i class="fa fa-heart me-2"></i> Wishlist</a></li>
                            <li><a class="dropdown-item" href="#!"><i class="fa fa-shopping-cart me-2"></i> My Cart</a></li>
                            <li><a class="dropdown-item" href="#!"><i class="fa fa-bell me-2"></i> Notifications</a></li>
                            <li><a class="dropdown-item" href="#!"><i class="fa fa-cog me-2"></i> Account Settings</a></li>
                            <li><a class="dropdown-item" href="profile.php"><i class="fa fa-user me-2"></i> My Account</a></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-sign-out-alt me-2"></i> Log Out</a></li>
                        </ul>
                    </div>
                </div>
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