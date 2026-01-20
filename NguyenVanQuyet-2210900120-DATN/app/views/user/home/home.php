<?php include BASE_PATH . '/app/views/user/layout/header.php'; ?>
<!-- Carousel Start -->
<div class="container-fluid carousel bg-light px-0">
    <div class="row g-0 justify-content-end">
        <div class="col-12 col-lg-7 col-xl-9">
            <div class="header-carousel owl-carousel bg-light py-5">

                <!-- SLIDE 1 -->
                <div class="row g-0 header-carousel-item align-items-center">
                    <div class="col-xl-6 carousel-img wow fadeInLeft" data-wow-delay="0.1s">
                        <img src="<?= BASE_URL; ?>/assets/img/slider1.jpg"
                             class="img-fluid w-100"
                             alt="Thời trang trẻ em">
                    </div>
                    <div class="col-xl-6 carousel-content p-4">
                        <h4 class="text-uppercase fw-bold mb-4 wow fadeInRight"
                            data-wow-delay="0.1s"
                            style="letter-spacing: 3px;">
                            Giảm giá lên đến 400.000 VNĐ
                        </h4>

                        <h1 class="display-3 text-capitalize mb-4 wow fadeInRight"
                            data-wow-delay="0.3s">
                            Áp dụng cho quần áo & phụ kiện trẻ em chọn lọc
                        </h1>

                        <p class="text-dark wow fadeInRight"
                           data-wow-delay="0.5s">
                            Áp dụng điều khoản & điều kiện
                        </p>

                        <a class="btn btn-primary rounded-pill py-3 px-5 wow fadeInRight"
                           data-wow-delay="0.7s"
                           href="<?= BASE_URL; ?>tat-ca-san-pham">
                            Mua ngay
                        </a>
                    </div>
                </div>

                <!-- SLIDE 2 -->
                <div class="row g-0 header-carousel-item align-items-center">
                    <div class="col-xl-6 carousel-img wow fadeInLeft" data-wow-delay="0.1s">
                        <img src="<?= BASE_URL; ?>/assets/img/slider2.jpg"
                             class="img-fluid w-100"
                             alt="Thời trang trẻ em">
                    </div>
                    <div class="col-xl-6 carousel-content p-4">
                        <h4 class="text-uppercase fw-bold mb-4 wow fadeInRight"
                            data-wow-delay="0.1s"
                            style="letter-spacing: 3px;">
                            Ưu đãi lên đến 200.000 VNĐ
                        </h4>

                        <h1 class="display-3 text-capitalize mb-4 wow fadeInRight"
                            data-wow-delay="0.3s">
                            Áp dụng cho quần áo & phụ kiện trẻ em chọn lọc
                        </h1>

                        <p class="text-dark wow fadeInRight"
                           data-wow-delay="0.5s">
                            Áp dụng điều khoản & điều kiện
                        </p>

                        <a class="btn btn-primary rounded-pill py-3 px-5 wow fadeInRight"
                           data-wow-delay="0.7s"
                           href="<?= BASE_URL; ?>tat-ca-san-pham">
                            Mua ngay
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- BANNER PHẢI -->
        <div class="col-12 col-lg-5 col-xl-3 wow fadeInRight" data-wow-delay="0.1s">
            <div class="carousel-header-banner h-100">
                <img src="img/header-img.jpg"
                     class="img-fluid w-100 h-100"
                     style="object-fit: cover;"
                     alt="Sản phẩm nổi bật">

                <div class="carousel-banner">
                    <div class="carousel-banner-content text-center p-4">
                        <a href="<?= isset($product_banner['slug']) 
                            ? 'chi-tiet-san-pham/' . htmlspecialchars($product_banner['slug']) 
                            : '#!' ?>"
                           class="d-block text-white fs-3">
                            <?= isset($product_banner['name']) 
                                ? $product_banner['name'] 
                                : 'Không có sản phẩm' ?>
                        </a>

                        <span class="text-primary fs-5">
                            <?= isset($product_banner['price']) 
                                ? $product_banner['price'] 
                                : 'Chưa có giá' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Carousel End -->

<!-- Services Start -->
<?php include BASE_PATH . '/app/views/user/layout/searvicesStart.php'; ?>
<!-- Services End -->

<!-- Products Offer Start -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="row g-4">

            <!-- BANNER 1 -->
            <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                <a href="#!" class="d-flex align-items-center justify-content-between border bg-white rounded p-4">
                    <div>
                        <p class="text-muted mb-3">
                            Tìm những bộ trang phục đẹp nhất cho bé yêu!
                        </p>
                        <h3 class="text-primary">
                            Đầm hè cho bé gái
                        </h3>
                        <h1 class="display-3 text-secondary mb-0">
                            Giảm 40%
                        </h1>
                    </div>
                    <img src="<?= BASE_URL; ?>/assets/img/dress.jpg"
                         class="img-fluid"
                         alt="Đầm hè cho bé gái">
                </a>    
            </div>

            <!-- BANNER 2 -->
            <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                <a href="#!" class="d-flex align-items-center justify-content-between border bg-white rounded p-4">
                    <div>
                        <p class="text-muted mb-3">
                            Chọn quần jean chất lượng cho bé trai!
                        </p>
                        <h3 class="text-primary">
                            Quần jean bé trai
                        </h3>
                        <h1 class="display-3 text-secondary mb-0">
                            Giảm 20%
                        </h1>
                    </div>
                    <img src="<?= BASE_URL; ?>/assets/img/jean.jpg"
                         class="img-fluid"
                         alt="Quần jean bé trai">
                </a>
            </div>

        </div>
    </div>
</div>


<div class="container-fluid product py-5">
    <div class="container py-5">
        <div class="tab-class">
            <div class="row g-4">
                <div class="col-lg-4 text-start wow fadeInLeft" data-wow-delay="0.1s">
                    <h1>Sản phẩm nổi bật</h1>
                </div>
            </div>

            <div class="tab-content">
                <div id="tab-1" class="tab-pane fade show p-0 active">
                    <div class="row g-4">

                        <?php foreach ($product_featured as $product_featured_item): ?>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="product-item rounded wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="product-item-inner border rounded">

                                        <!-- Ảnh sản phẩm -->
                                        <div class="product-item-inner-item">
                                            <a href="<?= isset($product_featured_item['slug']) 
                                                ? 'chi-tiet-san-pham/' . htmlspecialchars($product_featured_item['slug']) 
                                                : '#!' ?>">
                                                <img src="<?= BASE_URL . $product_featured_item['image'] ?>"
                                                     class="img-fluid w-100 rounded-top"
                                                     alt="<?= htmlspecialchars($product_featured_item['name']) ?>">
                                            </a>
                                        </div>

                                        <!-- Thông tin sản phẩm -->
                                        <div class="text-center rounded-bottom p-4">

                                            <!-- Tên sản phẩm -->
                                            <a href="<?= isset($product_featured_item['slug']) 
                                                ? 'chi-tiet-san-pham/' . htmlspecialchars($product_featured_item['slug']) 
                                                : '#!' ?>"
                                               class="d-block mb-2 fw-medium">
                                                <?= htmlspecialchars($product_featured_item['name']) ?>
                                            </a>

                                            <!-- Giá -->
                                            <?php if (!empty($product_featured_item['old_price']) && $product_featured_item['old_price'] > 0): ?>
                                                <del class="me-2 fs-6 text-muted">
                                                    <?= number_format($product_featured_item['old_price'], 0, ',', '.') ?>₫
                                                </del>
                                            <?php endif; ?>

                                            <span class="text-primary fs-5 fw-bold">
                                                <?= number_format($product_featured_item['price'], 0, ',', '.') ?>₫
                                            </span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Our Products End -->

<!-- Product Banner Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="row g-4">

            <!-- Banner sản phẩm -->
            <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                <a href="<?= BASE_URL ?>tat-ca-san-pham">
                    <div class="bg-primary rounded position-relative">
                        <img src="img/product-banner.jpg"
                             class="img-fluid w-100 rounded"
                             alt="Bộ sưu tập thời trang trẻ em">
                        <div class="position-absolute top-0 start-0 w-100 h-100
                                    d-flex flex-column justify-content-center
                                    rounded p-4"
                             style="background: rgba(255, 255, 255, 0.5);">

                            <h3 class="display-5 text-primary">
                                Bộ sưu tập mới <br>
                                <span>Cho bé yêu</span>
                            </h3>

                            <p class="fs-4 text-muted">
                                Chỉ từ 899.000₫
                            </p>

                            <a href="<?= BASE_URL ?>tat-ca-san-pham"
                               class="btn btn-primary rounded-pill align-self-start py-2 px-4">
                                Mua ngay
                            </a>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Banner khuyến mãi -->
            <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.2s">
                <a href="<?= BASE_URL ?>tat-ca-san-pham">
                    <div class="text-center bg-primary rounded position-relative">
                        <img src="img/product-banner-2.jpg"
                             class="img-fluid w-100"
                             alt="Khuyến mãi thời trang trẻ em">

                        <div class="position-absolute top-0 start-0 w-100 h-100
                                    d-flex flex-column justify-content-center
                                    rounded p-4"
                             style="background: rgba(242, 139, 0, 0.5);">

                            <h2 class="display-2 text-secondary">
                                KHUYẾN MÃI
                            </h2>

                            <h4 class="display-5 text-white mb-4">
                                Giảm giá lên đến 50%
                            </h4>

                            <a href="<?= BASE_URL ?>tat-ca-san-pham"
                               class="btn btn-secondary rounded-pill align-self-center py-2 px-4">
                                Mua ngay
                            </a>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>

<!-- Product Banner End -->

<!-- Product List Satrt -->
<div class="container-fluid products productList overflow-hidden">
    <div class="container products-mini py-5">

        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h4 class="text-primary border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp"
                data-wow-delay="0.1s">
                Sản phẩm
            </h4>
            <h1 class="mb-0 display-3 wow fadeInUp" data-wow-delay="0.3s">
                Tất cả sản phẩm
            </h1>
        </div>

        <div class="productList-carousel owl-carousel pt-4 wow fadeInUp" data-wow-delay="0.3s">
            <?php foreach ($products as $product): ?>
                <div class="productImg-item products-mini-item border">
                    <div class="row g-0">

                        <!-- Ảnh sản phẩm -->
                        <div class="col-5">
                            <div class="products-mini-img border-end h-100">
                                <a href="<?= isset($product['slug']) ? 'chi-tiet-san-pham/' . htmlspecialchars($product['slug']) : '#!' ?>">
                                    <img src="<?= BASE_URL . $product['image'] ?>"
                                         class="img-fluid w-100 h-100"
                                         alt="<?= htmlspecialchars($product['name']) ?>">
                                </a>
                            </div>
                        </div>

                        <!-- Thông tin sản phẩm -->
                        <div class="col-7">
                            <div class="products-mini-content p-3">
                                <a href="<?= isset($product['slug']) ? 'chi-tiet-san-pham/' . htmlspecialchars($product['slug']) : '#!' ?>"
                                   class="d-block mb-2">
                                    <?= htmlspecialchars($product['name']) ?>
                                </a>

                                <!-- Giá cũ -->
                                <?php if (isset($product['old_price']) && $product['old_price'] > 0): ?>
                                    <del class="me-2 fs-5">
                                        <?= number_format($product['old_price'], 0, ',', '.') ?>₫
                                    </del>
                                <?php endif; ?>

                                <!-- Giá hiện tại -->
                                <span class="text-primary fs-5">
                                    <?= number_format($product['price'], 0, ',', '.') ?>₫
                                </span>
                            </div>
                        </div>

                    </div>

                    <!-- Hành động -->
                    <div class="products-mini-add border p-3">
                        <div class="d-flex">
                            <a href="<?= isset($product['slug']) ? 'chi-tiet-san-pham/' . htmlspecialchars($product['slug']) : '#!' ?>"
                               class="text-primary d-flex align-items-center justify-content-center me-3">
                                <span class="rounded-circle btn-sm-square border">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </a>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<!-- Product List End -->

<!-- Bestseller Products Start -->
<div class="container-fluid products pb-5">
    <div class="container products-mini py-5">

        <div class="mx-auto text-center mb-5" style="max-width: 700px;">
            <h4 class="text-primary mb-4 border-bottom border-primary border-2
                       d-inline-block p-2 title-border-radius wow fadeInUp"
                data-wow-delay="0.1s">
                Sản phẩm khác
            </h4>
            <p class="mb-0 wow fadeInUp" data-wow-delay="0.2s">
                Khám phá thêm nhiều sản phẩm dành cho bé
            </p>
        </div>

        <div class="row g-4">
            <?php
            $delay = 0.1;
            foreach ($product_active as $product):
            ?>
                <div class="col-md-6 col-lg-6 col-xl-4 wow fadeInUp"
                     data-wow-delay="<?= $delay ?>s">

                    <div class="products-mini-item border">
                        <div class="row g-0">

                            <!-- Ảnh sản phẩm -->
                            <div class="col-5">
                                <div class="products-mini-img border-end h-100">
                                    <a href="<?= isset($product['slug'])
                                        ? 'chi-tiet-san-pham/' . htmlspecialchars($product['slug'])
                                        : '#!' ?>">
                                        <img src="<?= BASE_URL . $product['image'] ?>"
                                             class="img-fluid w-100 h-100"
                                             alt="<?= htmlspecialchars($product['name']) ?>">
                                    </a>

                                    <div class="products-mini-icon rounded-circle bg-primary">
                                        <a href="<?= isset($product['slug'])
                                            ? 'chi-tiet-san-pham/' . htmlspecialchars($product['slug'])
                                            : '#!' ?>">
                                            <i class="fa fa-eye fa-1x text-white"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin -->
                            <div class="col-7">
                                <div class="products-mini-content p-3">
                                    <a href="<?= isset($product['slug'])
                                        ? 'chi-tiet-san-pham/' . htmlspecialchars($product['slug'])
                                        : '#!' ?>"
                                       class="d-block mb-2">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </a>

                                    <!-- Giá cũ -->
                                    <?php if (isset($product['old_price']) && $product['old_price'] > 0): ?>
                                        <del class="me-2 fs-5">
                                            <?= number_format($product['old_price'], 0, ',', '.') ?>₫
                                        </del>
                                    <?php endif; ?>

                                    <!-- Giá hiện tại -->
                                    <span class="text-primary fs-5">
                                        <?= number_format($product['price'], 0, ',', '.') ?>₫
                                    </span>
                                </div>
                            </div>

                        </div>

                        Hành động
                       
                    </div>
                </div>
            <?php
                $delay += 0.2;
            endforeach;
            ?>
        </div>

    </div>
</div>

<!-- Bestseller Products End -->

<?php include BASE_PATH . '/app/views/user/layout/footer.php'; ?>