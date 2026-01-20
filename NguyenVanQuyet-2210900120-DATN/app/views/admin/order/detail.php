<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - Chi tiết đơn hàng</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --text-light: #f1f5f9;
            --border-light: rgba(255, 255, 255, 0.1);
        }

        body {
            background: linear-gradient(135deg, var(--bg-dark) 0%, #1e293b 100%);
            color: var(--text-light);
            font-family: 'Inter', system-ui, sans-serif;
            margin: 0;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border-light);
            height: 100vh;
            position: fixed;
            width: 280px;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        /* HEADER */
        .header {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-light);
            padding: 16px 24px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 280px;
            padding: 24px;
            min-height: 100vh;
        }

        /* CARD */
        .card {
            background: white;
            border: 1px solid var(--border-light);
            border-radius: 14px;
        }

        /* TABLE */
        .table-responsive {
            overflow-x: auto;
        }

        .table-dark {
            background-color: rgba(30, 41, 59, 0.9);
        }

        .table-dark th {
            background-color: rgba(15, 23, 42, 0.95);
            border-color: var(--border-light);
        }

        .table-dark td {
            border-color: rgba(255,255,255,0.05);
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.1);
        }

        /* MOBILE */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

<?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
<?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

<div class="main-content">

    <!-- ===== THÔNG TIN ĐƠN ===== -->
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="mb-3"> Đơn hàng #<?= htmlspecialchars($order['ma_don_hang']) ?></h4>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['name']) ?></p>
                    <p><strong>SĐT:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                    <p><strong>Thanh toán:</strong> <?= htmlspecialchars($order['payment']) ?></p>
                </div>

                <div class="col-md-6">
                    <p>
                        <strong>Trạng thái:</strong>
                        <span class="badge bg-info"><?= htmlspecialchars($order['status']) ?></span>
                    </p>
                    <p><strong>Ngày tạo:</strong>
                        <?= date('d/m/Y H:i', strtotime($order['ngay_tao'])) ?>
                    </p>
                    <p>
                        <strong>Tổng tiền:</strong>
                        <span class="text-warning fw-bold">
                            <?= number_format($order['tong_tien']) ?> ₫
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== CHI TIẾT SẢN PHẨM ===== -->
    <div class="card">
        <div class="card-header bg-primary">
            <h5 class="mb-0"> Sản phẩm trong đơn</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Màu</th>
                        <th>Size</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($details)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Không có sản phẩm
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($details as $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['image_product'])): ?>
                                    <img src="<?= BASE_URL . $item['image_product'] ?>"
                                         width="60" class="rounded">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['name_product']) ?></td>
                            <td><?= $item['color_name'] ?? '—' ?></td>
                            <td><?= $item['size_name'] ?? '—' ?></td>
                            <td><?= number_format($item['price_product']) ?> ₫</td>
                            <td><?= (int)$item['quantity'] ?></td>
                            <td class="text-warning fw-bold">
                                <?= number_format($item['total']) ?> ₫
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== BACK ===== -->
    <div class="mt-4">
        <a href="<?= BASE_URL ?>admin/order" class="btn btn-secondary">
            ← Quay lại danh sách
        </a>
    </div>

</div>

</body>
</html>
