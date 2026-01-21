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
    <?php include __DIR__ . '/sidebar.php'; ?>
    <?php include __DIR__ . '/header.php'; ?>

    <main class="main-content flex-grow-1 d-flex flex-column">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger rounded-3 mb-4"><?=$error?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success rounded-3 mb-4"><?=$success?></div>
        <?php endif; ?>

        <div class="content-placeholder flex-grow-1">
           

        <div class="container-fluid">

<!-- SUMMARY -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Tổng đơn hàng</h6>
                <h3><?= number_format($summary['total_orders']) ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Tổng doanh thu</h6>
                <h3><?= number_format($summary['total_revenue']) ?> đ</h3>
            </div>
        </div>
    </div>
</div>

<!-- CHART -->
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Đơn hàng theo trạng thái</h6>
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Doanh thu theo ngày</h6>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const statusLabels = <?= json_encode(array_column($byStatus, 'status')) ?>;
const statusData = <?= json_encode(array_column($byStatus, 'total')) ?>;

const revenueLabels = <?= json_encode(array_column($revenueByDate, 'order_date')) ?>;
const revenueData = <?= json_encode(array_column($revenueByDate, 'revenue')) ?>;

new Chart(document.getElementById('statusChart'), {
type: 'pie',
data: {
    labels: statusLabels,
    datasets: [{
        data: statusData
    }]
}
});

new Chart(document.getElementById('revenueChart'), {
type: 'bar',
data: {
    labels: revenueLabels,
    datasets: [{
        label: 'Doanh thu',
        data: revenueData
    }]
}
});
</script>


        </div>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   
</body>

</html>