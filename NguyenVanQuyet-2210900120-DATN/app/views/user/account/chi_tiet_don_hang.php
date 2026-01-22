<?php include BASE_PATH . '/app/views/user/layout/header.php'; ?>

<div class="container py-4">
    <h4 class="mb-4"> Chi tiết đơn hàng</h4>
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

    <!-- Thông tin đơn -->
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Mã đơn:</strong> <?= htmlspecialchars($order['ma_don_hang']) ?></p>
            <p><strong>Ngày tạo:</strong> <?= date('d/m/Y H:i', strtotime($order['ngay_tao'])) ?></p>
            <p><strong>Người nhận:</strong> <?= htmlspecialchars($order['name']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
            <p><strong>Thanh toán:</strong> <?= htmlspecialchars($order['payment']) ?></p>
            <p>
                <strong>Trạng thái:</strong>
                <?php
                switch ($order['status']) {
                    case OrderStatus::PENDING:   echo '<span class="badge bg-warning">Chờ xử lý</span>'; break;
                    case OrderStatus::CONFIRMED: echo '<span class="badge bg-primary">Đã xác nhận</span>'; break;
                    case OrderStatus::SHIPPING:  echo '<span class="badge bg-info">Đang giao</span>'; break;
                    case OrderStatus::COMPLETED: echo '<span class="badge bg-success">Hoàn thành</span>'; break;
                    case OrderStatus::CANCELLED: echo '<span class="badge bg-danger">Đã huỷ</span>'; break;
                }
                ?>
            </p>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <table class="table table-bordered align-middle">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Sản phẩm</th>
            <th>Màu</th>
            <th>Size</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tạm tính</th>
        </tr>
        </thead>
        <tbody>
<?php foreach ($orderDetails as $i => $item): ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><?= htmlspecialchars($item['name_product']) ?></td>
        <td><?= htmlspecialchars($item['color_name'] ?? '-') ?></td>
        <td><?= htmlspecialchars($item['size_name'] ?? '-') ?></td>
        <td><?= $item['quantity'] ?></td>
        <td><?= number_format($item['total'], 0, ',', '.') ?>₫</td>
        <td class="text-danger">
            <?= number_format($item['total'] * $item['quantity'], 0, ',', '.') ?>₫
        </td>
    </tr>

    <!-- FORM ĐÁNH GIÁ -->
    <?php if ($order['status'] == OrderStatus::COMPLETED): ?>
    <tr>
        <td colspan="7">
            <form action="<?= BASE_URL ?>danh-gia-san-pham" method="post" enctype="multipart/form-data"
                  class="border rounded p-3 bg-light">

                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

                <div class="row g-3">
                    <!-- Sao -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Đánh giá</label>
                        <select name="star" class="form-select" required>
                            <option value="">Chọn sao</option>
                            <option value="5">★★★★★ (Rất tốt)</option>
                            <option value="4">★★★★☆</option>
                            <option value="3">★★★☆☆</option>
                            <option value="2">★★☆☆☆</option>
                            <option value="1">★☆☆☆☆</option>
                        </select>
                    </div>

                    <!-- Nhận xét -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nhận xét</label>
                        <textarea name="comment" class="form-control" rows="3"
                                  placeholder="Cảm nhận của bạn về sản phẩm..." required></textarea>
                    </div>

                    <!-- Ảnh -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Ảnh (tuỳ chọn)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-primary">
                        Gửi đánh giá
                    </button>
                </div>
            </form>
        </td>
    </tr>
    <?php endif; ?>

<?php endforeach; ?>
</tbody>

    </table>

    <div class="text-end">
        <h5>Tổng tiền:
            <span class="text-danger">
                <?= number_format($order['tong_tien'], 0, ',', '.') ?>₫
            </span>
        </h5>
    </div>

    <a href="<?= BASE_URL ?>theo-doi-don-hang" class="btn btn-secondary mt-3">
        ← Quay lại danh sách đơn hàng
    </a>
</div>

<?php include BASE_PATH . '/app/views/user/layout/footer.php'; ?>
