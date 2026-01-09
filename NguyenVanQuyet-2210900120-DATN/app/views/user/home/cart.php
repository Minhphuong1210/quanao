<?php
include BASE_PATH . '/app/views/user/layout/header.php';
?>




<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">
        Giỏ hàng
    </h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
        <li class="breadcrumb-item active text-white">Giỏ hàng</li>
    </ol>
</div>

<div class="container-fluid py-5">
    <div class="container py-5">

        <?php if (empty($cart)): ?>
            <div class="alert alert-warning text-center">
                Giỏ hàng của bạn đang trống
            </div>
        <?php else: ?>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Mã SP</th>
                        <th>Đơn giá</th>
                        <th style="width:150px">Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($cart as $key => $item): 
                    $itemTotal = $item['price'] * $item['quantity'];
                    $subTotal += $itemTotal;
                ?>
                    <tr>
                      
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= BASE_URL ?>/<?= $item['image'] ?>" width="70" class="me-3 rounded">
                               
                            </div>
                        </td>

                    
<td>
<div>
                                    <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                                    <small class="text-muted">
                                        Size: <?= $item['name_size'] ?? '-' ?> |
                                        Màu: <?= $item['name_color'] ?? '-' ?>
                                    </small>
                                </div>
</td>

                        <td>
                            <p class="py-4 mb-0">#<?= $item['product_id'] ?></p>
                        </td>

                    
                        <td>
                            <p class="py-4 mb-0">
                                <?= number_format($item['price'], 0, ',', '.') ?> đ
                            </p>
                        </td>

                      
                        <td>
                            <div class="input-group quantity py-4" style="width: 120px;">
                                <button class="btn btn-sm btn-minus bg-light border"
                                    data-key="<?= $key ?>">
                                    <i class="fa fa-minus"></i>
                                </button>

                                <input type="text"
                                    class="form-control form-control-sm text-center border-0"
                                    value="<?= $item['quantity'] ?>"
                                    readonly>

                                <button class="btn btn-sm btn-plus bg-light border"
                                    data-key="<?= $key ?>">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </td>

                    
                        <td>
                            <p class="py-4 mb-0 fw-bold text-danger">
                                <?= number_format($itemTotal, 0, ',', '.') ?> đ
                            </p>
                        </td>

                      
                        <td class="py-4">
                            <button class="btn btn-md rounded-circle bg-light border text-danger btn-remove-cart"
                                data-key="<?= $key ?>">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>

     
        <div class="row g-4 justify-content-end mt-4">
            <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                <div class="bg-light rounded">
                    <div class="p-4">
                        <h4 class="mb-4">Tổng giỏ hàng</h4>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Tạm tính</span>
                            <strong><?= number_format($subTotal, 0, ',', '.') ?> đ</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Phí vận chuyển</span>
                            <span>Miễn phí</span>
                        </div>
                    </div>

                    <div class="border-top d-flex justify-content-between p-4">
                        <h5>Tổng cộng</h5>
                        <h5 class="text-danger">
                            <?= number_format($subTotal, 0, ',', '.') ?> đ
                        </h5>
                    </div>

                    <a href="<?= BASE_URL ?>/checkout"
                        class="btn btn-primary rounded-pill px-4 py-3 text-uppercase mb-4 ms-4">
                        Tiến hành thanh toán
                    </a>
                </div>
            </div>
        </div>

        <?php endif; ?>
    </div>
</div>

<?php
include BASE_PATH . '/app/views/user/layout/footer.php';
?>
