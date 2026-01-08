<?php
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../models/category.php';

$model = new Category();


$limit = 15;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$total = $model->countAll();
$totalPages = $total > 0 ? ceil($total / $limit) : 1;
$categories = $model->paginate($offset, $limit);

ob_start();
?>

<h2>Quản lý danh mục</h2>

<?php if (!empty($_GET['msg']) || !empty($_GET['error'])): ?>
    <div class="alert <?= !empty($_GET['msg']) ? 'success' : 'error' ?>">
        <?php
        if (!empty($_GET['msg'])) {
            switch ($_GET['msg']) {
                case 'created':
                    echo '✓ Thêm danh mục mới thành công!';
                    break;
                case 'updated':
                    echo '✓ Cập nhật danh mục thành công!';
                    break;
                case 'deleted':
                    echo '✓ Xóa danh mục thành công!';
                    break;
                default:
                    echo '✓ Thao tác thành công!';
            }
        } elseif (!empty($_GET['error'])) {
            echo '✗ Có lỗi xảy ra, vui lòng thử lại!';
        }
        ?>
    </div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th width="80">ID</th>
            <th>Tên danh mục</th>
            <th width="180" style="text-align:center;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($categories)): ?>
            <tr>
                <td colspan="3" style="text-align:center; padding:30px; color:#666;">
                    Chưa có danh mục nào.
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><?= htmlspecialchars($cat['name']) ?></td>
                    <td style="text-align:center;">
                        <a href="update_category.php?id=<?= $cat['id'] ?>" class="btn btn-edit">Sửa</a>
                        <a href="delete_category.php?id=<?= $cat['id'] ?>" class="btn btn-delete"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục <?= addslashes($cat['name']) ?>?\nDanh mục sẽ bị ẩn khỏi website.')">
                            Xóa
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<a href="create_category.php" class="btn-add">+ Thêm danh mục mới</a>

<!-- Phân trang -->
<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=1" class="page-link">« Đầu</a>
            <a href="?page=<?= $page - 1 ?>" class="page-link">‹ Trước</a>
        <?php endif; ?>

        <?php
        $start = max(1, $page - 2);
        $end = min($totalPages, $page + 2);
        for ($i = $start; $i <= $end; $i++):
        ?>
            <a href="?page=<?= $i ?>" class="page-link <?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" class="page-link">Sau ›</a>
            <a href="?page=<?= $totalPages ?>" class="page-link">Cuối »</a>
        <?php endif; ?>

        <span class="page-info">
            Trang <?= $page ?> / <?= $totalPages ?> (Tổng <?= number_format($total) ?> danh mục)
        </span>
    </div>
<?php endif; ?>

<!-- Modal -->
<div class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:1000;">
    <div class="modal-content" style="background:#fff; padding:30px; border-radius:10px; max-width:600px; width:90%; position:relative;">
        <span class="modal-close" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:28px;">&times;</span>
        <h2 id="modal-title"></h2>
        <div id="modal-body"></div>
    </div>
</div>

<style>
    .alert {
        padding: 15px;
        margin: 20px 0;
        border-radius: 8px;
        font-weight: bold;
    }

    .alert.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Pagination */
    .pagination {
        margin: 30px 0;
        text-align: center;
        font-size: 0;
        /* Xóa khoảng trắng inline-block */
    }

    .page-link {
        display: inline-block;
        padding: 10px 15px;
        margin: 0 4px;
        background: #f8f9fa;
        color: #333;
        text-decoration: none;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 14px;
        min-width: 44px;
    }

    .page-link:hover:not(.active) {
        background: #e9ecef;
    }

    .page-link.active {
        background: #007bff;
        color: white;
        border-color: #007bff;
        cursor: default;
    }

    .page-info {
        display: inline-block;
        margin-left: 20px;
        font-size: 14px;
        color: #666;
        vertical-align: middle;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        function openModal(title, html) {
            $('#modal-title').text(title);
            var $form = $('<div>').html(html).find('form');
            $('#modal-body').html($form.length ? $form.parent().html() : html);
            $('.modal-overlay').fadeIn(200);
        }

        function closeModal() {
            $('.modal-overlay').fadeOut(200);
        }

        $('.btn-add').click(function(e) {
            e.preventDefault();
            $.get(this.href)
                .done(data => openModal('Thêm danh mục mới', data))
                .fail(() => alert('Không thể tải form thêm!'));
        });

        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();
            $.get(this.href)
                .done(data => openModal('Sửa danh mục', data))
                .fail(() => alert('Không thể tải form sửa!'));
        });

        $('.modal-close, .modal-overlay').click(function(e) {
            if (e.target === this) closeModal();
        });

        $('.modal-content').click(e => e.stopPropagation());

        $(document).on('submit', '#modal-body form', function(e) {
            e.preventDefault();
            var $form = $(this);
            $.post($form.attr('action'), $form.serialize())
                .done(() => {
                    closeModal();
                    location.reload();
                })
                .fail(() => alert('✗ Có lỗi xảy ra khi lưu dữ liệu!'));
        });
    });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../../admin/layout.php';
?>