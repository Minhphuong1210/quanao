<?php include BASE_PATH . '/app/views/user/layout/header.php'; ?>
    <!-- Products Offer Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <a href="#!" class="d-flex align-items-center justify-content-between border bg-white rounded p-4">
                        <div>
                            <p class="text-muted mb-3">Find The Best Camera for You!</p>
                            <h3 class="text-primary">Smart Camera</h3>
                            <h1 class="display-3 text-secondary mb-0">40% <span
                                    class="text-primary fw-normal">Off</span></h1>
                        </div>
                        <img src="img/product-1.png" class="img-fluid" alt="">
                    </a>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                    <a href="#!" class="d-flex align-items-center justify-content-between border bg-white rounded p-4">
                        <div>
                            <p class="text-muted mb-3">Find The Best Whatches for You!</p>
                            <h3 class="text-primary">Smart Whatch</h3>
                            <h1 class="display-3 text-secondary mb-0">20% <span
                                    class="text-primary fw-normal">Off</span></h1>
                        </div>
                        <img src="img/product-2.png" class="img-fluid" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Products Offer End -->

    <?php 
require_once BASE_PATH . '/app/models/category.php';
require_once BASE_PATH . '/app/models/Product.php';
$categoryModel = new category();
$productModel = new Product();
$danh_muc = $categoryModel->getAll();


?>


    <!-- Shop Page Start -->
    <div class="container-fluid shop py-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="product-categories mb-4">
                        <h4>Danh mục sản phẩm</h4>
                        <ul class="list-unstyled categories-bars">
    <?php foreach ($danh_muc as $dm): ?>
        <li>
            <div class="categories-item">
                <a href="<?= BASE_URL ?>category/<?= $dm['slug'] ?>" class="text-dark">
                    <i class="fas fa-folder text-secondary me-2"></i>
                    <?= htmlspecialchars($dm['name']) ?>
                </a>
                <span>(<?= $productModel->countByCategory($dm['id']) ?>)</span>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
                    </div>
                    <!-- <div class="price mb-4">
                        <h4 class="mb-2">Price</h4>
                        <input type="range" class="form-range w-100" id="rangeInput" name="rangeInput" min="0" max="500"
                            value="0" oninput="amount.value=rangeInput.value">
                        <output id="amount" name="amount" min-velue="0" max-value="500" for="rangeInput">0</output>
                        <div class=""></div>
                    </div> -->
                    <div class="product-color mb-3">
                        <h4>Select By Color</h4>
                        <ul class="list-unstyled">
                            <li>
                                <div class="product-color-item">
                                    <a href="#!" class="text-dark"><i class="fas fa-apple-alt text-secondary me-2"></i>
                                        Gold</a>
                                    <span>(1)</span>
                                </div>
                            </li>
                            <li>
                                <div class="product-color-item">
                                    <a href="#!" class="text-dark"><i class="fas fa-apple-alt text-secondary me-2"></i>
                                        Green</a>
                                    <span>(1)</span>
                                </div>
                            </li>
                            <li>
                                <div class="product-color-item">
                                    <a href="#!" class="text-dark"><i class="fas fa-apple-alt text-secondary me-2"></i>
                                        White</a>
                                    <span>(1)</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="additional-product mb-4">
                        <h4>Additional Products</h4>
                        <div class="additional-product-item">
                            <input type="radio" class="me-2" id="Categories-1" name="Categories-1" value="Beverages">
                            <label for="Categories-1" class="text-dark"> Accessories</label>
                        </div>
                        <div class="additional-product-item">
                            <input type="radio" class="me-2" id="Categories-2" name="Categories-1" value="Beverages">
                            <label for="Categories-2" class="text-dark"> Electronics & Computer</label>
                        </div>
                        <div class="additional-product-item">
                            <input type="radio" class="me-2" id="Categories-3" name="Categories-1" value="Beverages">
                            <label for="Categories-3" class="text-dark"> Laptops & Desktops</label>
                        </div>
                        <div class="additional-product-item">
                            <input type="radio" class="me-2" id="Categories-4" name="Categories-1" value="Beverages">
                            <label for="Categories-4" class="text-dark"> Mobiles & Tablets</label>
                        </div>
                        <div class="additional-product-item">
                            <input type="radio" class="me-2" id="Categories-5" name="Categories-1" value="Beverages">
                            <label for="Categories-5" class="text-dark"> SmartPhone & Smart TV</label>
                        </div>
                    </div>
                    <!-- <div class="featured-product mb-4">
                        <h4 class="mb-3">Featured products</h4>
                        <div class="featured-product-item">
                            <div class="rounded me-4" style="width: 100px; height: 100px;">
                                <img src="img/product-3.png" class="img-fluid rounded" alt="Image">
                            </div>
                            <div>
                                <h6 class="mb-2">SmartPhone</h6>
                                <div class="d-flex mb-2">
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <div class="d-flex mb-2">
                                    <h5 class="fw-bold me-2">2.99 $</h5>
                                    <h5 class="text-danger text-decoration-line-through">4.11 $</h5>
                                </div>
                            </div>
                        </div>
                        <div class="featured-product-item">
                            <div class="rounded me-4" style="width: 100px; height: 100px;">
                                <img src="img/product-4.png" class="img-fluid rounded" alt="Image">
                            </div>
                            <div>
                                <h6 class="mb-2">Smart Camera</h6>
                                <div class="d-flex mb-2">
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <div class="d-flex mb-2">
                                    <h5 class="fw-bold me-2">2.99 $</h5>
                                    <h5 class="text-danger text-decoration-line-through">4.11 $</h5>
                                </div>
                            </div>
                        </div>
                        <div class="featured-product-item">
                            <div class="rounded me-4" style="width: 100px; height: 100px;">
                                <img src="img/product-5.png" class="img-fluid rounded" alt="Image">
                            </div>
                            <div>
                                <h6 class="mb-2">Camera Leance</h6>
                                <div class="d-flex mb-2">
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <div class="d-flex mb-2">
                                    <h5 class="fw-bold me-2">2.99 $</h5>
                                    <h5 class="text-danger text-decoration-line-through">4.11 $</h5>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center my-4">
                            <a href="#!" class="btn btn-primary px-4 py-3 rounded-pill w-100">Vew More</a>
                        </div>
                    </div> -->
                    <!-- <a href="#!">
                        <div class="position-relative">
                            <img src="img/product-banner-2.jpg" class="img-fluid w-100 rounded" alt="Image">
                            <div class="text-center position-absolute d-flex flex-column align-items-center justify-content-center rounded p-4"
                                style="width: 100%; height: 100%; top: 0; right: 0; background: rgba(242, 139, 0, 0.3);">
                                <h5 class="display-6 text-primary">SALE</h5>
                                <h4 class="text-secondary">Get UP To 50% Off</h4>
                                <a href="#!" class="btn btn-primary rounded-pill px-4">Shop Now</a>
                            </div>
                        </div>
                    </a> -->
                    <!-- <div class="product-tags py-4">
                        <h4 class="mb-3">PRODUCT TAGS</h4>
                        <div class="product-tags-items bg-light rounded p-3">
                            <a href="#!" class="border rounded py-1 px-2 mb-2">New</a>
                            <a href="#!" class="border rounded py-1 px-2 mb-2">brand</a>
                            <a href="#!" class="border rounded py-1 px-2 mb-2">black</a>
                            <a href="#!" class="border rounded py-1 px-2 mb-2">white</a>
                            <a href="#!" class="border rounded py-1 px-2 mb-2">tablats</a>
                            <a href="#!" class="border rounded py-1 px-2 mb-2">phone</a>
                            <a href="#!" class="border rounded py-1 px-2 mb-2">camera</a>
                            <a href="#!" class="border rounded py-1 px-2 mb-2">drone</a>
                            <a href="#!" class="border rounded py-1 px-2 mb-2">talevision</a>
                            <a href="#!" class="border rounded py-1 px-2 mb-2">slaes</a>
                        </div>
                    </div> -->
                </div>
                <div class="col-lg-9 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="tab-content">
                    <div id="tab-5" class="tab-pane fade show p-0 active">
                    <div class="row g-4 product">
    <?php foreach ($product_active as $index => $product): ?>
        <div class="col-lg-4">
            <div class="product-item rounded wow fadeInUp" data-wow-delay="0.<?= ($index + 1) * 2 ?>s">
                <div class="product-item-inner border rounded">
                    <div class="product-item-inner-item">
                        <img src="<?= BASE_URL . $product['image'] ?>" class="img-fluid w-100 rounded-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php if ($product['new'] ?? false): ?>
                            <div class="product-new">New</div>
                        <?php endif; ?>
                        <div class="product-details">
                            <a href="<?= BASE_URL ?>product/<?= $product['slug'] ?>"><i class="fa fa-eye fa-1x"></i></a>
                        </div>
                    </div>
                    <div class="text-center rounded-bottom p-4">
                        <!-- <a href="#!" class="d-block mb-2"><?= htmlspecialchars($product['category_name'] ?? 'SmartPhone') ?></a> -->
                        <a href="<?= BASE_URL ?>product/<?= $product['slug'] ?>" class="d-block h4"><?= htmlspecialchars($product['name']) ?></a>
                        <?php if (!empty($product['old_price'])): ?>
                            <del class="me-2 fs-5">$<?= number_format($product['old_price'], 2) ?></del>
                        <?php endif; ?>
                        <span class="text-primary fs-5">$<?= number_format($product['price'], 2) ?></span>
                    </div>
                </div>
                <div class="product-item-add border border-top-0 rounded-bottom text-center p-4 pt-0">
                    <a href="<?= BASE_URL ?>cart/add/<?= $product['id'] ?>" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-4">
                        <i class="fas fa-shopping-cart me-2"></i> Add To Cart
                    </a>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            <?php for ($i=0; $i<5; $i++): ?>
                                <i class="fas fa-star <?= $i < ($product['rating'] ?? 4) ? 'text-primary' : '' ?>"></i>
                            <?php endfor; ?>
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
    <?php endforeach; ?>

    <!-- Pagination -->
    <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
        <div class="pagination d-flex justify-content-center mt-5">
            <?php if($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?>" class="rounded">&laquo;</a>
            <?php endif; ?>

            <?php for($p=1; $p<=$totalPages; $p++): ?>
                <a href="?page=<?= $p ?>" class="rounded <?= $p == $currentPage ? 'active' : '' ?>"><?= $p ?></a>
            <?php endfor; ?>

            <?php if($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>" class="rounded">&raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</div>

</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include BASE_PATH . '/app/views/user/layout/footer.php'; ?>