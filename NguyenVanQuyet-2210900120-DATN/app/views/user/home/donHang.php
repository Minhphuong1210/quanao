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
    <?php include BASE_PATH . '/app/views/user/layout/searvicesStart.php'; ?>
    <!-- Searvices End -->


    <!-- Checkout Page Start -->
    <div class="container-fluid bg-light overflow-hidden py-5">
        <div class="container py-5">
            <h1 class="mb-4 wow fadeInUp" data-wow-delay="0.1s">Thông tin dơn hàng</h1>
            <form id="checkoutForm" action="<?=BASE_URL?>postCheckout" method="POST">
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


    <hr>

    <div class="form-item">
        <textarea class="form-control"
                  cols="30"
                  rows="11"
                  name="note"
                  placeholder="Ghi chú đơn hàng (Không bắt buộc)"><?= old('note') ?></textarea>
    </div>

    <div class="mt-4">
    <h5 class="mb-3">Phương thức thanh toán</h5>

    <div class="form-check">
        <input class="form-check-input"
               type="radio"
               name="payment_method"
               id="pay_cod"
               value="cod"
               checked>
        <label class="form-check-label" for="pay_cod">
            Thanh toán khi nhận hàng (COD)
        </label>
    </div>

    <div class="form-check mt-2">
        <input class="form-check-input"
               type="radio"
               name="payment_method"
               id="pay_vnpay"
               value="vnpay">
        <label class="form-check-label" for="pay_vnpay">
            Thanh toán Online (VNPAY)
        </label>
    </div>
</div>


</div>


                    <div class="col-md-12 col-lg-6 col-xl-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="table-responsive">
                        <table class="table">
    <thead>
        <tr class="text-center">
            <th scope="col" class="text-start">Tên sản phẩm</th>
            <th scope="col">Mã sản phẩm</th>
            <th scope="col">Đơn giá</th>
            <th scope="col">Số lượng</th>
            <th scope="col">Thành tiền</th>
        </tr>
    </thead>

    <tbody>
    <?php if (!empty($cart)): ?>
        <?php foreach ($cart as $key => $item):
            $total = $item['price'] * $item['quantity'];
            $subtotal += $total;
        ?>
            <tr class="text-center cart-item">
                <th scope="row" class="text-start py-4">
                    <?= htmlspecialchars($item['name']) ?>

                    <?php if (!empty($item['name_size'])): ?>
                        <br><small>Kích thước: <?= $item['name_size'] ?></small>
                    <?php endif; ?>

                    <?php if (!empty($item['name_color'])): ?>
                        <br><small>Màu sắc: <?= $item['name_color'] ?></small>
                    <?php endif; ?>
                </th>

                <td class="py-4"><?= $item['product_id'] ?></td>

                <td class="py-4">
                    <?= number_format($item['price'], 0, ',', '.') ?> ₫
                </td>

                <td class="py-4">
                    <?= $item['quantity'] ?>
                </td>

                <td class="py-4">
                    <?= number_format($total, 0, ',', '.') ?> ₫
                </td>
            </tr>
        <?php endforeach; ?>

        <!-- TỔNG TIỀN -->
        <tr>
            <td colspan="3"></td>
            <td class="py-4">
                <p class="mb-0 text-dark text-uppercase py-2">Tổng cộng</p>
            </td>
            <td class="py-4">
                <div class="py-2 text-center border-bottom border-top">
                    <p class="mb-0 text-dark">
                        <?= number_format($subtotal, 0, ',', '.') ?> ₫
                    </p>
                </div>
            </td>
        </tr>

    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center py-4">
                Giỏ hàng của bạn đang trống
            </td>
        </tr>
    <?php endif; ?>
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
    <script>
// document.addEventListener('DOMContentLoaded', function () {
//     const form = document.getElementById('checkoutForm');
//     const paymentRadios = document.querySelectorAll('input[name="payment_method"]');

//     paymentRadios.forEach(radio => {
//         radio.addEventListener('change', function () {
//             if (this.value === 'vnpay') {
//                 form.action = "<?= BASE_URL ?>vnpay/create";
//             } else {
//                 form.action = "<?= BASE_URL ?>postCheckout";
//             }
//         });
//     });
// });
</script>

<?php
unset($_SESSION['old'], $_SESSION['errors']);
include BASE_PATH . '/app/views/user/layout/footer.php';
?>