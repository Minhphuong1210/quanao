<?php
require_once '../../../includes/init.php'; 
require_once '../../../pages/layout/header.php';
require_once __DIR__ . '/../../../models/Cart.php';
$cart = new Cart();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - MyShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="quanao/php-thuan/assets/css/style.css">
    <style>
        body {
            padding-top: 80px;
            background: #f8f9fa;
        }

        .cart-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <?php if ($cart->isEmpty()): ?>
            <div class="text-center py-5">
                <img src="https://static.vecteezy.com/system/resources/previews/016/462/240/non_2x/empty-shopping-cart-illustration-concept-on-white-background-vector.jpg"
                    alt="Giỏ trống" style="max-width:300px;" class="mb-4">
                <h4>Giỏ hàng đang trống</h4>
                <a href="../index.php" class="btn btn-primary btn-lg">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart->getItems() as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" class="cart-img rounded me-3" alt="<?= htmlspecialchars($item['name']) ?>">
                                        <div><?= htmlspecialchars($item['name']) ?></div>
                                    </div>
                                </td>
                                <td><?= number_format($item['price']) ?> ₫</td>
                                <td>
                                    <form action="../update_cart.php" method="post" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <div class="input-group w-75 mx-auto">
                                            <button type="submit" name="quantity" value="<?= $item['quantity'] - 1 ?>" class="btn btn-outline-secondary">-</button>
                                            <input type="text" class="form-control text-center" value="<?= $item['quantity'] ?>" readonly>
                                            <button type="submit" name="quantity" value="<?= $item['quantity'] + 1 ?>" class="btn btn-outline-secondary">+</button>
                                        </div>
                                    </form>
                                </td>
                                <td class="fw-bold"><?= number_format($item['price'] * $item['quantity']) ?> ₫</td>
                                <td>
                                    <a href="../remove_from_cart.php?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="d-flex justify-content-between">
                                <span>Tổng thanh toán</span>
                                <span class="text-danger"><?= number_format($cart->getTotalPrice()) ?> ₫</span>
                            </h5>
                            <a href="checkout.php" class="btn btn-primary btn-lg w-100 mt-3">Thanh toán</a>
                            <a href="../index.php" class="btn btn-outline-secondary w-100 mt-2">Tiếp tục mua sắm</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const spinner = document.getElementById('spinner');
        if (spinner) spinner.classList.remove('show');
    });
</script>
<?php require_once '../../../pages/layout/footer.php'; ?>