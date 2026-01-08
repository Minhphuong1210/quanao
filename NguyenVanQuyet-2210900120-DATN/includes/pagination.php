<?php
/**
 * File pagination.php
 * Đặt trong: admin/includes/pagination.php
 * Sử dụng: include_once __DIR__ . '/includes/pagination.php';
 *
 * Cách dùng trong trang list (ví dụ index.php):
 *   - Trước khi include: định nghĩa $total (tổng bản ghi), $limit (số bản ghi/trang)
 *   - Sau khi include: dùng $pagination_html để in ra phân trang
 */

if (!isset($total) || !isset($limit)) {
    die('Error: $total và $limit phải được định nghĩa trước khi include pagination.php');
}

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$totalPages = ceil($total / $limit);

// Giữ lại các query param khác (ví dụ: search, filter...) khi chuyển trang
$currentQuery = $_GET;
unset($currentQuery['page']); // xóa page cũ để thêm mới
$queryString = http_build_query($currentQuery);
$baseUrl = '?' . ($queryString ? $queryString . '&' : '');

ob_start();
?>

<?php if ($totalPages > 1): ?>
<div class="pagination-wrapper">
    <div class="pagination">
        <!-- Nút Đầu và Trước -->
        <?php if ($page > 1): ?>
            <a href="<?= $baseUrl ?>page=1" class="page-btn">« Đầu</a>
            <a href="<?= $baseUrl ?>page=<?= $page - 1 ?>" class="page-btn">‹ Trước</a>
        <?php else: ?>
            <span class="page-btn disabled">« Đầu</span>
            <span class="page-btn disabled">‹ Trước</span>
        <?php endif; ?>

        <!-- Các số trang xung quanh trang hiện tại -->
        <?php
        $start = max(1, $page - 2);
        $end = min($totalPages, $page + 2);

        if ($start > 1): ?>
            <span class="page-dots">...</span>
        <?php endif; ?>

        <?php for ($i = $start; $i <= $end; $i++): ?>
            <a href="<?= $baseUrl ?>page=<?= $i ?>" class="page-btn <?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($end < $totalPages): ?>
            <span class="page-dots">...</span>
        <?php endif; ?>

        <!-- Nút Sau và Cuối -->
        <?php if ($page < $totalPages): ?>
            <a href="<?= $baseUrl ?>page=<?= $page + 1 ?>" class="page-btn">Sau ›</a>
            <a href="<?= $baseUrl ?>page=<?= $totalPages ?>" class="page-btn">Cuối »</a>
        <?php else: ?>
            <span class="page-btn disabled">Sau ›</span>
            <span class="page-btn disabled">Cuối »</span>
        <?php endif; ?>
    </div>

    <div class="page-info">
        Trang <strong><?= $page ?></strong> / <strong><?= $totalPages ?></strong>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Tổng <strong><?= number_format($total) ?></strong> bản ghi
    </div>
</div>
<?php endif; ?>

<?php
$pagination_html = ob_get_clean();
?>

<style>
    .pagination-wrapper {
        margin: 30px 0;
        text-align: center;
    }

    .pagination {
        display: inline-block;
        margin-bottom: 10px;
    }

    .page-btn {
        display: inline-block;
        padding: 10px 15px;
        margin: 0 4px;
        background: #f8f9fa;
        color: #333;
        text-decoration: none;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 15px;
        min-width: 45px;
        transition: all 0.3s;
    }

    .page-btn:hover:not(.disabled):not(.active) {
        background: #e9ecef;
        border-color: #adb5bd;
    }

    .page-btn.active {
        background: #007bff;
        color: white;
        border-color: #007bff;
        cursor: default;
    }

    .page-btn.disabled {
        color: #aaa;
        cursor: not-allowed;
        background: #f1f1f1;
    }

    .page-dots {
        display: inline-block;
        padding: 10px 8px;
        color: #666;
        font-weight: bold;
    }

    .page-info {
        color: #666;
        font-size: 14px;
        margin-top: 8px;
    }
</style>