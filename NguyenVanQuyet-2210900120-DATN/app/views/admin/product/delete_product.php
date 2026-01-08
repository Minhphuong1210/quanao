<?php
// app/views/admin/product/delete-product.php
// Trang xác nhận xóa sản phẩm (dùng trong productDelete() nếu muốn confirmation page, hoặc inline trong index)
?>

<div class="row g-4">
    <div class="col-12">
        <h3>Xác Nhận Xóa Sản Phẩm</h3>
        <div class="card bg-dark border-0 rounded-3 p-4">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Bạn có chắc chắn muốn xóa sản phẩm "<strong><?= htmlspecialchars($product['name'] ?? 'N/A') ?></strong>" (ID: <?= $product['id'] ?? 'N/A' ?>)?<br>
                Hành động này không thể hoàn tác!
            </div>
            <div class="d-flex justify-content-start gap-2">
                <form method="POST" action="/admin/product/delete/<?= $product['id'] ?? '' ?>" style="display:inline;">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Xóa vĩnh viễn?')">
                        <i class="fas fa-trash me-1"></i> Xóa
                    </button>
                </form>
                <a href="/admin/product" class="btn btn-secondary">Hủy</a>
            </div>
        </div>
    </div>
</div>