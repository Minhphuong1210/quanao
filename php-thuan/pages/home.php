<?php
require_once 'includes/database.php';  // tên file chữ thường, đúng như bạn có

$pdo = Database::getInstance();
?>
<!-- Searvices Start -->
<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="0.1s">
            <div class="p-4">
                <div class="d-inline-flex align-items-center">
                    <i class="fa fa-sync-alt fa-2x text-primary"></i>
                    <div class="ms-4">
                        <h6 class="text-uppercase mb-2">Free Return</h6>
                        <p class="mb-0">30 days money back guarantee!</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.2s">
            <div class="p-4">
                <div class="d-flex align-items-center">
                    <i class="fab fa-telegram-plane fa-2x text-primary"></i>
                    <div class="ms-4">
                        <h6 class="text-uppercase mb-2">Free Shipping</h6>
                        <p class="mb-0">Free shipping on all order</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.3s">
            <div class="p-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-life-ring fa-2x text-primary"></i>
                    <div class="ms-4">
                        <h6 class="text-uppercase mb-2">Support 24/7</h6>
                        <p class="mb-0">We support online 24 hrs a day</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.4s">
            <div class="p-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-credit-card fa-2x text-primary"></i>
                    <div class="ms-4">
                        <h6 class="text-uppercase mb-2">Receive Gift Card</h6>
                        <p class="mb-0">Recieve gift all over oder $50</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.5s">
            <div class="p-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-lock fa-2x text-primary"></i>
                    <div class="ms-4">
                        <h6 class="text-uppercase mb-2">Secure Payment</h6>
                        <p class="mb-0">We Value Your Security</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.6s">
            <div class="p-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-blog fa-2x text-primary"></i>
                    <div class="ms-4">
                        <h6 class="text-uppercase mb-2">Online Service</h6>
                        <p class="mb-0">Free return products in 30 days</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Searvices End -->

<!-- Products Offer Start -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                <a href="#!" class="d-flex align-items-center justify-content-between border bg-white rounded p-4">
                    <div>
                        <p class="text-muted mb-3">The Best Clothing For Boys</p>
                        <h3 class="text-primary">Best Shirt</h3>
                        <h1 class="display-3 text-secondary mb-0">40% <span
                                class="text-primary fw-normal">Off</span></h1>
                    </div>
                    <img src="/quanao/php-thuan/assets/img/" class="img-fluid" alt="">
                </a>
            </div>
            <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                <a href="#!" class="d-flex align-items-center justify-content-between border bg-white rounded p-4">
                    <div>
                        <p class="text-muted mb-3">The Best Clothing For Boys</p>
                        <h3 class="text-primary">Best Dress</h3>
                        <h1 class="display-3 text-secondary mb-0">20% <span
                                class="text-primary fw-normal">Off</span></h1>
                    </div>
                    <img src="/quanao/php-thuan/assets/img/" class="img-fluid" alt="">
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Products Offer End -->


<!-- Our Products Start -->
<div class="container-fluid py-5 product">
    <div class="container">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h4 class="text-primary border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp" data-wow-delay="0.1s">
                Our Products
            </h4>
        </div>

        <div class="row g-4">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE active = 1 ORDER BY id DESC LIMIT 12");
                $stmt->execute();
                $products = $stmt->fetchAll();

                if (empty($products)) {
                    echo '<div class="col-12 text-center py-5"><p class="text-muted">Chưa có sản phẩm nào.</p></div>';
                } else {
                    $delay = 0.1;
                    foreach ($products as $row):
                        $image = $row['image'] ? $row['image'] : 'no-img.png';
                        $price = number_format($row['price'], 0, ',', '.') . '<sup>đ</sup>';
                        $label_new = ($row['hien_trang_chu'] == 1) ? '<div class="product-new">New</div>' : '';
                        $detail_link = 'chi-tiet-san-pham.php?slug=' . urlencode($row['slug']);
            ?>
                        <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="<?= $delay ?>s">
                            <div class="product-item rounded">
                                <div class="product-item-inner border rounded">
                                    <div class="product-item-inner-item">
                                        <img src="/quanao/php-thuan/assets/img/<?= htmlspecialchars($image) ?>"
                                            class="img-fluid w-100 rounded-top"
                                            alt="<?= htmlspecialchars($row['name']) ?>">
                                        <?= $label_new ?>
                                        <div class="product-details">
                                            <a href="<?= $detail_link ?>"><i class="fa fa-eye fa-1x"></i></a>
                                        </div>
                                    </div>
                                    <div class="text-center rounded-bottom p-4">
                                        <a href="<?= $detail_link ?>" class="d-block h4">
                                            <?= htmlspecialchars($row['name']) ?>
                                        </a>
                                        <span class="text-primary fs-5"><?= $price ?></span>
                                    </div>
                                </div>
                                <div class="product-item-add border border-top-0 rounded-bottom text-center p-4 pt-0">
                                    <a href="/quanao/php-thuan/views/user/cart/addtocart.php?id=<?= $row['id'] ?>"
                                        class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-4">
                                        <i class="fas fa-shopping-cart me-2"></i> Add To Cart
                                    </a>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex">
                                            <i class="fas fa-star text-primary"></i>
                                            <i class="fas fa-star text-primary"></i>
                                            <i class="fas fa-star text-primary"></i>
                                            <i class="fas fa-star text-primary"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="d-flex">
                                            <a href="#!" class="text-primary d-flex align-items-center justify-content-center me-3">
                                                <span class="rounded-circle btn-sm-square border"><i class="fas fa-random"></i></span>
                                            </a>
                                            <a href="#!" class="text-primary d-flex align-items-center justify-content-center me-0">
                                                <span class="rounded-circle btn-sm-square border"><i class="fas fa-heart"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php
                        $delay += 0.1;
                        if ($delay > 0.7) $delay = 0.1;
                    endforeach;
                }
            } catch (Exception $e) {
                echo "<div class='col-12 text-center text-danger'>Lỗi tải sản phẩm: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            ?>
        </div>
    </div>
</div>
<!-- Our Products End -->

<!-- Product Banner Start -->
<!-- <div class="container-fluid py-5">
    <div class="container">
        <div class="row g-4">
            <?php
            try {
                $stmt = $pdo->prepare("
                    SELECT * FROM products 
                    WHERE active = 1 
                    AND (san_pham_noi_bat = 1 OR hien_trang_chu = 1)
                    ORDER BY san_pham_noi_bat DESC, id DESC 
                    LIMIT 2
                ");
                $stmt->execute();
                $banners = $stmt->fetchAll();

                if (count($banners) < 2) {
                    $limit = 2 - count($banners);
                    $stmt2 = $pdo->prepare("SELECT * FROM products WHERE active = 1 LIMIT $limit");
                    $stmt2->execute();
                    $banners = array_merge($banners, $stmt2->fetchAll());
                }

                if (isset($banners[0])):
                    $b1 = $banners[0];
                    $img1 = $b1['image'] ? $b1['image'] : 'product-banner.jpg';
                    $price1 = number_format($b1['price'], 0, ',', '.') . 'đ';
                    $name1 = htmlspecialchars($b1['name']);
                    $link1 = 'chi-tiet-san-pham.php?slug=' . urlencode($b1['slug']);
            ?>
                
            <?php
                endif;

                if (isset($banners[1])):
                    $b2 = $banners[1];
                    $img2 = $b2['image'] ? $b2['image'] : 'product-banner-2.jpg';
                    $name2 = htmlspecialchars($b2['name']);
                    $link2 = 'chi-tiet-san-pham.php?slug=' . urlencode($b2['slug']);
            ?>

            <?php
                endif;
            } catch (Exception $e) {
                echo "<p class='text-danger text-center col-12'>Lỗi tải banner: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>
    </div>
</div> -->
<!-- Product Banner End -->
<!-- Product Banner End -->

<!-- Product List Start -->
<div class="container-fluid py-5 product">
    <div class="container">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h4 class="text-primary border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp" data-wow-delay="0.1s">
                Sản Phẩm Nổi Bật
            </h4>
        </div>

        <div class="row g-4">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE active = 1 AND san_pham_noi_bat = 1 ORDER BY id DESC");
                $stmt->execute();
                $products = $stmt->fetchAll();

                if (empty($products)) {
                    echo '<div class="col-12 text-center py-5"><p class="text-muted">Chưa có sản phẩm nổi bật nào.</p></div>';
                } else {
                    $delay = 0.1;
                    foreach ($products as $row):
                        $image = $row['image'] ? $row['image'] : 'no-img.png';
                        $price = number_format($row['price'], 0, ',', '.') . '<sup>đ</sup>';
                        $label_featured = ($row['san_pham_noi_bat'] == 1) ? '<div class="product-featured">Featured</div>' : ''; // Thêm label nếu cần
                        $detail_link = 'chi-tiet-san-pham.php?slug=' . urlencode($row['slug']);

                        // Kiểm tra file tồn tại (từ thư mục gốc script)
                        $full_image_path = 'assets/img/' . $row['image'];
                        if (!empty($row['image']) && file_exists($full_image_path)) {
                            $image_src = '/quanao/php-thuan/' . $full_image_path;
                        } else {
                            $image_src = '/quanao/php-thuan/assets/img/no-img.png';
                        }
            ?>
                        <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="<?= $delay ?>s">
                            <div class="product-item rounded">
                                <div class="product-item-inner border rounded">
                                    <div class="product-item-inner-item">
                                        <img src="<?= htmlspecialchars($image_src) ?>"
                                            class="img-fluid w-100 rounded-top"
                                            alt="<?= htmlspecialchars($row['name']) ?>">
                                        <?= $label_featured ?>
                                        <div class="product-details">
                                            <a href="<?= $detail_link ?>"><i class="fa fa-eye fa-1x"></i></a>
                                        </div>
                                    </div>
                                    <div class="text-center rounded-bottom p-4">
                                        <a href="<?= $detail_link ?>" class="d-block h4">
                                            <?= htmlspecialchars($row['name']) ?>
                                        </a>
                                        <span class="text-primary fs-5"><?= $price ?></span>
                                    </div>
                                </div>
                                <div class="product-item-add border border-top-0 rounded-bottom text-center p-4 pt-0">
                                    <a href="/quanao/php-thuan/views/user/cart/addtocart.php?id=<?= $row['id'] ?>"
                                        class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-4">
                                        <i class="fas fa-shopping-cart me-2"></i> Add To Cart
                                    </a>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex">
                                            <i class="fas fa-star text-primary"></i>
                                            <i class="fas fa-star text-primary"></i>
                                            <i class="fas fa-star text-primary"></i>
                                            <i class="fas fa-star text-primary"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="d-flex">
                                            <a href="#!" class="text-primary d-flex align-items-center justify-content-center me-3">
                                                <span class="rounded-circle btn-sm-square border"><i class="fas fa-random"></i></span>
                                            </a>
                                            <a href="#!" class="text-primary d-flex align-items-center justify-content-center me-0">
                                                <span class="rounded-circle btn-sm-square border"><i class="fas fa-heart"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php
                        $delay += 0.1;
                        if ($delay > 0.7) $delay = 0.1;
                    endforeach;
                }
            } catch (Exception $e) {
                echo "<div class='col-12 text-center text-danger'>Lỗi tải sản phẩm nổi bật: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            ?>
        </div>
    </div>
</div>
<!-- Product List End -->

<!-- Bestseller Products Start -->
<!-- Bestseller Products Start -->
<div class="container-fluid products pb-5">
    <div class="container products-mini py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 700px;">
            <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp"
                data-wow-delay="0.1s">Bestseller Products</h4>
            <p class="mb-0 wow fadeInUp" data-wow-delay="0.2s">
                Những sản phẩm được yêu thích nhất và bán chạy trong thời gian gần đây.
            </p>
        </div>

        <div class="row g-4">
            <?php
            try {
                $stmt = $pdo->prepare("
                    SELECT * FROM products 
                    WHERE active = 1 
                    ORDER BY san_pham_noi_bat DESC, view DESC 
                    LIMIT 6
                ");
                $stmt->execute();
                $bestsellers = $stmt->fetchAll();

                $delays = [0.1, 0.3, 0.5, 0.1, 0.3, 0.5];
                $index = 0;

                foreach ($bestsellers as $row):
                    $image = $row['image'] ? $row['image'] : 'no-img.png';
                    $name = htmlspecialchars($row['name']);
                    $price = number_format($row['price'], 0, ',', '.') . '<sup>đ</sup>';
                    $detail_link = 'chi-tiet-san-pham.php?slug=' . urlencode($row['slug']);
                    $delay = $delays[$index % 6];
            ?>
                    <div class="col-md-6 col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="<?= $delay ?>s">
                        <div class="products-mini-item border">
                            <div class="row g-0">
                                <div class="col-5">
                                    <div class="products-mini-img border-end h-100 position-relative">
                                        <img src="/quanao/php-thuan/assets/img/<?= htmlspecialchars($image) ?>"
                                            class="img-fluid w-100 h-100" alt="<?= $name ?>">
                                        <div class="products-mini-icon rounded-circle bg-primary">
                                            <a href="<?= $detail_link ?>"><i class="fa fa-eye fa-1x text-white"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="products-mini-content p-3">
                                        <a href="<?= $detail_link ?>" class="d-block mb-2 text-muted small">
                                            Sản phẩm nổi bật
                                        </a>
                                        <a href="<?= $detail_link ?>" class="d-block h4 text-dark">
                                            <?= $name ?>
                                        </a>
                                        <!-- Nếu bạn có giá cũ (old_price), bỏ comment bên dưới -->
                                        <!-- <del class="me-2 fs-5 text-muted">$1,250.00</del> -->
                                        <span class="text-primary fs-5"><?= $price ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="products-mini-add border p-3">
                                <a href="/quanao/php-thuan/views/user/cart/addtocart.php?id=<?= $row['id'] ?>"
                                    class="btn btn-primary border-secondary rounded-pill py-2 px-4 w-100 mb-3">
                                    <i class="fas fa-shopping-cart me-2"></i> Add To Cart
                                </a>
                                <div class="d-flex justify-content-center">
                                    <a href="#!" class="text-primary d-flex align-items-center justify-content-center me-3">
                                        <span class="rounded-circle btn-sm-square border"><i class="fas fa-random"></i></span>
                                    </a>
                                    <a href="#!" class="text-primary d-flex align-items-center justify-content-center">
                                        <span class="rounded-circle btn-sm-square border"><i class="fas fa-heart"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    $index++;
                endforeach;
                if (empty($bestsellers)): ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">Chưa có sản phẩm bestseller nào.</p>
                    </div>
            <?php endif;
            } catch (Exception $e) {
                echo "<div class='col-12 text-center text-danger'>Lỗi tải bestseller: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            ?>
        </div>
    </div>
</div>
<!-- Bestseller Products End -->
<!-- Bestseller Products End -->