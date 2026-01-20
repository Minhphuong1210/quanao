<?php
include BASE_PATH . '/app/views/user/layout/header.php';
?>

    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Cheackout Page</h1>
        <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
            <li class="breadcrumb-item"><a href="#!">Home</a></li>
            <li class="breadcrumb-item"><a href="#!">Pages</a></li>
            <li class="breadcrumb-item active text-white">Cheackout</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

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


    <!-- Checkout Page Start -->
    <div class="container-fluid bg-light overflow-hidden py-5">
        <div class="container py-5">
            <h1 class="mb-4 wow fadeInUp" data-wow-delay="0.1s">Thông tin dơn hàng</h1>
            <form action="<?=BASE_URL?>postCheckout" method="POST">
                <div class="row g-5">
                <div class="col-md-12 col-lg-6 col-xl-6 wow fadeInUp" data-wow-delay="0.1s">
    <div class="row">
        <div class="col-md-12 col-lg-6">
            <div class="form-item w-100">
                <label class="form-label my-3">Tên<sup>*</sup></label>
                <input type="text" class="form-control" name="ten" required
                       value="<?= old('ten') ?>">
                <?php if (error('ten')): ?>
                    <small class="text-danger"><?= error('ten') ?></small>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-12 col-lg-6">
            <div class="form-item w-100">
                <label class="form-label my-3">Họ<sup>*</sup></label>
                <input type="text" class="form-control" name="ho" required
                       value="<?= old('ho') ?>">
                <?php if (error('ho')): ?>
                    <small class="text-danger"><?= error('ho') ?></small>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="form-item">
        <label class="form-label my-3">Địa chỉ<sup>*</sup></label>
        <input type="text" class="form-control" name="dia_chi" required
               placeholder="Số nhà, tên đường"
               value="<?= old('dia_chi') ?>">
        <?php if (error('dia_chi')): ?>
            <small class="text-danger"><?= error('dia_chi') ?></small>
        <?php endif; ?>
    </div>

    <div class="form-item">
        <label class="form-label my-3">Số điện thoại<sup>*</sup></label>
        <input type="tel" class="form-control" name="so_dien_thoai" required
               value="<?= old('so_dien_thoai') ?>">
        <?php if (error('so_dien_thoai')): ?>
            <small class="text-danger"><?= error('so_dien_thoai') ?></small>
        <?php endif; ?>
    </div>

    <div class="form-item">
        <label class="form-label my-3">Địa chỉ Email<sup>*</sup></label>
        <input type="email" class="form-control" name="email" required
               value="<?= old('email') ?>">
        <?php if (error('email')): ?>
            <small class="text-danger"><?= error('email') ?></small>
        <?php endif; ?>
    </div>

    <!-- <div class="form-check my-3">
        <input type="checkbox"
               class="form-check-input"
               id="Account-1"
               name="Accounts"
               value="1"
               <?= old('Accounts') ? 'checked' : '' ?>>
        <label class="form-check-label" for="Account-1">
            Tạo tài khoản?
        </label>
    </div> -->

    <hr>

    <div class="form-item">
        <textarea class="form-control"
                  cols="30"
                  rows="11"
                  name="note"
                  placeholder="Ghi chú đơn hàng (Không bắt buộc)"><?= old('note') ?></textarea>
    </div>
</div>


                    <div class="col-md-12 col-lg-6 col-xl-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col" class="text-start">Tên</th>
                                        <th scope="col">Mã sản phẩm</th>
                                        <th scope="col">Giá</th>
                                        <th scope="col">Số lượng </th>
                                        <th scope="col">Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tbody>
            <?php if (!empty($cart)): ?>
    <?php foreach ($cart as $key => $item):
    $total = $item['price'] * $item['quantity'];
    $subtotal += $total;
    ?>
						    <tr class="text-center cart-item">
						        <th scope="row" class="text-start py-4">
						            <?=htmlspecialchars($item['name'])?>

						            <?php if ($item['name_size']): ?>
						                <br><small>Size: <?=$item['name_size']?></small>
						            <?php endif; ?>

            <?php if ($item['name_color']): ?>
                <br><small>Màu: <?=$item['name_color']?></small>
            <?php endif; ?>
        </th>

        <td class="py-4"><?=$item['product_id']?></td>

        <td class="py-4">
            $<?=number_format($item['price'], 2)?>
        </td>

        <td class="py-4">
            <?=$item['quantity']?>
        </td>

        <td class="py-4">
            $<?=number_format($total, 2)?>
        </td>
    </tr>
    <?php endforeach; ?>


    <!-- TOTAL -->
    <tr>
        <td colspan="3"></td>
        <td class="py-4">
            <p class="mb-0 text-dark text-uppercase py-2">Tổng tiền</p>
        </td>
        <td class="py-4">
            <div class="py-2 text-center border-bottom border-top">
                <p class="mb-0 text-dark">
                    $<?=number_format($subtotal, 2)?>
                </p>
            </div>
        </td>
    </tr>

                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            Giỏ hàng trống
                        </td>
                    </tr>
                <?php endif; ?>
</tbody>


                                    <!-- <tr>
                                        <th scope="row">
                                        </th>
                                        <td class="py-4">
                                            <p class="mb-0 text-dark py-4">Shipping</p>
                                        </td>
                                        <td colspan="3" class="py-4">
                                            <div class="form-check text-start">
                                                <input type="checkbox" class="form-check-input bg-primary border-0"
                                                    id="Shipping-1" name="Shipping-1" value="Shipping">
                                                <label class="form-check-label" for="Shipping-1">Free Shipping</label>
                                            </div>
                                            <div class="form-check text-start">
                                                <input type="checkbox" class="form-check-input bg-primary border-0"
                                                    id="Shipping-2" name="Shipping-1" value="Shipping">
                                                <label class="form-check-label" for="Shipping-2">Flat rate:
                                                    $15.00</label>
                                            </div>
                                            <div class="form-check text-start">
                                                <input type="checkbox" class="form-check-input bg-primary border-0"
                                                    id="Shipping-3" name="Shipping-1" value="Shipping">
                                                <label class="form-check-label" for="Shipping-3">Local Pickup:
                                                    $8.00</label>
                                            </div>
                                        </td>
                                    </tr> -->
                                    <tr>
                                        <th scope="row">
                                        </th>
                                        <td class="py-4">
                                            <p class="mb-0 text-dark text-uppercase py-2">Tổng tiền</p>
                                        </td>
                                        <td class="py-4"></td>
                                        <td class="py-4"></td>
                                        <td class="py-4">
                                            <div class="py-2 text-center border-bottom border-top">
                                                <p class="mb-0 text-dark"> $<?=number_format($subtotal, 2)?></p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                            <button type="submit"
                                class="btn btn-primary border-secondary py-3 px-4 text-uppercase w-100 text-primary">Mua hàng</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php
unset($_SESSION['old'], $_SESSION['errors']);
include BASE_PATH . '/app/views/user/layout/footer.php';
?>