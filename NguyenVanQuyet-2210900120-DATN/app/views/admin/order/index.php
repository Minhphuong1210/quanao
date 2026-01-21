
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - <?=$title ?? 'Dashboard'?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/admin/css/style.css">

</head>

<body class="d-flex flex-column h-100">

    <?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
    <?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

    <main class="main-content flex-grow-1 d-flex flex-column">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?=$error?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?=$success?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="content-placeholder flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                <h4 class="text-light mb-2">Quản lý Đơn hàng</h4>
<p class="text-muted mb-0">Danh sách đơn hàng của khách</p>

                </div>
                <!-- <a href="/admin/product/create" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                </a> -->
            </div>

            <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?=$_SESSION['success']?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?=$_SESSION['error']?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>


            <!-- Search box -->
            <div class="card  border-0 mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control"
                                    placeholder="Tìm kiếm theo mã đơn hàng..."
                                    value="<?=htmlspecialchars($search ?? '')?>">
                                <button class="btn btn-primary" type="button" id="searchBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products table -->
            <div class="table-responsive rounded-3 overflow-hidden">
                <table class="table table-hover  table-striped mb-0">
                    <thead>
                        <tr>
                        <th>Mã đơn</th>
<th>Khách hàng</th>
<th>SĐT</th>
<th>Thanh toán</th>
<th>Tổng tiền</th>
<th>Trạng thái</th>
<th>Ngày tạo</th>
<th>Xem chi tiết</th>
<th>Thao tác</th>



                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <h5 class="mb-2">Không có sản phẩm nào</h5>
                                        <p class="mb-0">Hãy thêm sản phẩm đầu tiên của bạn</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order):

?>
                               <tr>
    <td>#<?=$order['ma_don_hang']?></td>
    <td><?=htmlspecialchars($order['name'])?></td>
    <td><?=htmlspecialchars($order['phone'])?></td>
    <td><?=htmlspecialchars($order['payment'])?></td>
    <td class="text-success fw-bold">
        <?=number_format($order['tong_tien'])?> ₫
    </td>
    <td>
        <span class="badge bg-info">
            <?=htmlspecialchars($order['status'])?>
        </span>
    </td>
    <td><?=date('d/m/Y', strtotime($order['ngay_tao']))?></td>
    <td>
        <a href="<?=BASE_URL?>admin/order/detail/<?=$order['id']?>"
           class="btn btn-sm btn-primary">
           Chi tiết
        </a>
    </td>
    <td>
    <?php
$allowedStatuses = OrderStatus::allowedNextStatuses($order['status']);
?>

    <?php if (empty($allowedStatuses)): ?>
        <span class="badge bg-success">
            <?=htmlspecialchars($order['status'])?>
        </span>
    <?php else: ?>
        <form method="POST"
              action="<?=BASE_URL?>admin/order/update-status">
            <input type="hidden" name="order_id" value="<?=$order['id']?>">

            <select name="status"
                    class="form-select form-select-sm bg-dark text-light border-secondary"
                    onchange="this.form.submit()">

                <option disabled selected>
                    <?=htmlspecialchars($order['status'])?>
                </option>

                <?php foreach ($allowedStatuses as $status): ?>
                    <option value="<?=$status?>">
                        <?=ucfirst($status)?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    <?php endif; ?>
</td>

</tr>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (($pages ?? 1) > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if (($page ?? 1) > 1): ?>
                            <li class="page-item">
                                <a href="?page=<?=($page ?? 1) - 1?>&search=<?=urlencode($search ?? '')?>"
                                    class="page-link bg-dark text-light border-secondary">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= ($pages ?? 1); $i++): ?>
                            <li class="page-item <?=$i == ($page ?? 1) ? 'active' : ''?>">
                                <a href="?page=<?=$i?>&search=<?=urlencode($search ?? '')?>"
                                    class="page-link <?=$i == ($page ?? 1) ? 'bg-primary border-primary' : 'bg-dark text-light border-secondary'?>">
                                    <?=$i?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if (($page ?? 1) < ($pages ?? 1)): ?>
                            <li class="page-item">
                                <a href="?page=<?=($page ?? 1) + 1?>&search=<?=urlencode($search ?? '')?>"
                                    class="page-link bg-dark text-light border-secondary">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </main>

    <?php include BASE_PATH . '/app/views/admin/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script>
        // Search functionality
        document.getElementById('searchBtn').addEventListener('click', function() {
            const search = document.getElementById('searchInput').value.trim();
            const url = new URL(window.location);

            if (search) {
                url.searchParams.set('search', search);
                url.searchParams.set('page', '1'); // Reset về trang 1 khi search
            } else {
                url.searchParams.delete('search');
            }

            window.location.href = url.toString();
        });

        // Enter key để search
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchBtn').click();
            }
        });

        // Xác nhận xóa
        function confirmDelete(form, productName) {
            if (confirm(`Bạn có chắc chắn muốn xóa sản phẩm:\n\n"${productName}"\n\n⚠️ Hành động này không thể hoàn tác!`)) {
                // Thêm hiệu ứng loading
                const deleteBtn = form.querySelector('button[type="submit"]');
                const originalHtml = deleteBtn.innerHTML;
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xóa...';
                deleteBtn.disabled = true;

                return true;
            }
            return false;
        }

        // Xử lý khi form xóa bị cancel (restore button state)
        document.addEventListener('submit', function(e) {
            if (e.target.matches('form[onsubmit*="confirmDelete"]')) {
                const form = e.target;
                setTimeout(() => {
                    const deleteBtn = form.querySelector('button[type="submit"]');
                    if (deleteBtn && deleteBtn.disabled) {
                        deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Xóa';
                        deleteBtn.disabled = false;
                    }
                }, 1000);
            }
        });
    </script> -->
</body>

</html>