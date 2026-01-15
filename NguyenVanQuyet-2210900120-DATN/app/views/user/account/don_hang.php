<?php
include BASE_PATH . '/app/views/user/layout/header.php';
?>

<style>
    .pagination {
    display: flex !important;
    flex-wrap: wrap;
}

.pagination .page-item {
    display: inline-block !important;
}

.pagination .page-link {
    display: block;
}

</style>

<div class="container py-4">
    <h4 class="mb-4">📦 Đơn hàng của tôi</h4>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            Bạn chưa có đơn hàng nào.
        </div>
    <?php else: ?>
        <table class="table table-bordered align-middle">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Mã đơn</th>
                <th>Ngày tạo</th>
                <th>Thanh toán</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $i => $order): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($order['ma_don_hang']) ?></strong></td>
                    <td><?= date('d/m/Y H:i', strtotime($order['ngay_tao'])) ?></td>
                    <td><?= htmlspecialchars($order['payment']) ?></td>
                    <td class="text-danger">
                        <?= number_format($order['tong_tien'], 0, ',', '.') ?>₫
                    </td>
                    <td>
<?php
switch ($order['status']) {
    case OrderStatus::PENDING:
        echo '<span class="badge bg-warning">Chờ xử lý</span>';
        break;
    case OrderStatus::CONFIRMED:
        echo '<span class="badge bg-primary">Đã xác nhận</span>';
        break;
    case OrderStatus::SHIPPING:
        echo '<span class="badge bg-info">Đang giao</span>';
        break;
    case OrderStatus::COMPLETED:
        echo '<span class="badge bg-success">Hoàn thành</span>';
        break;
    case OrderStatus::CANCELLED:
        echo '<span class="badge bg-danger">Đã huỷ</span>';
        break;
}
?>
</td>
<td>
    <a href="<?= BASE_URL ?>account/orders/<?= $order['id'] ?>"
       class="btn btn-sm btn-outline-primary">
        Xem
    </a>

    <?php if ($order['status'] === OrderStatus::PENDING): ?>
        <a href="<?= BASE_URL ?>account/orders/cancel/<?= $order['id'] ?>"
           class="btn btn-sm btn-outline-danger"
           onclick="return confirm('Bạn có chắc muốn huỷ đơn này?')">
            Huỷ
        </a>
    <?php endif; ?>
</td>



                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($totalPages > 1): ?>
<nav class="mt-4">
    <ul class="pagination justify-content-center">

        <!-- Prev -->
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>">«</a>
        </li>

        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $p ?>">
                    <?= $p ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>">»</a>
        </li>

    </ul>
</nav>
<?php endif; ?>



    <?php endif; ?>
</div>



<?php
include BASE_PATH . '/app/views/user/layout/footer.php';
?>