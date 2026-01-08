<?php
// views/admin/product/index.php (danh sách sản phẩm - CRUD list)
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Danh sách Sản Phẩm</h2>
    <a href="/admin/product/create" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Thêm Sản Phẩm Mới
    </a>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $success ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $error ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm bg-dark">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tất cả Sản Phẩm (Bao gồm inactive)</h5>
        <input type="text" class="form-control w-25" placeholder="Tìm kiếm..." id="searchInput">
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dark table-hover" id="productTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Giá (VNĐ)</th>
                        <th>Category</th>
                        <th>Tồn Kho</th>
                        <th>Trạng Thái</th>
                        <th>Ảnh</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['id'] ?? '') ?></td>
                                <td><?= htmlspecialchars($product['name'] ?? '') ?></td>
                                <td><?= number_format($product['price'] ?? 0, 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
                                <td><span class="badge <?= ($product['stock'] ?? 0) > 0 ? 'bg-success' : 'bg-danger' ?>"><?= $product['stock'] ?? 0 ?></span></td>
                                <td><span class="badge <?= ($product['status'] ?? '') === 'active' ? 'bg-success' : 'bg-secondary' ?>"><?= ucfirst($product['status'] ?? '') ?></span></td>
                                <td>
                                    <?php if (!empty($product['image'])): ?>
                                        <img src="/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="50" height="50" class="rounded me-1" style="object-fit: cover;">
                                    <?php else: ?>
                                        <span class="text-muted small">No image</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/admin/product/edit/<?= htmlspecialchars($product['id'] ?? '') ?>" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="/admin/product/delete/<?= htmlspecialchars($product['id'] ?? '') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xác nhận xóa sản phẩm này? (Ảnh sẽ bị xóa vĩnh viễn)')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-box-open fa-3x mb-3 opacity-50"></i>
                                <p>Chưa có sản phẩm nào. <a href="/admin/product/create">Thêm ngay!</a></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($products)): ?>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">Hiển thị <?= count($products) ?> sản phẩm</small>
                <nav>
                    <!-- Pagination nếu cần, giả sử đơn giản -->
                    <ul class="pagination pagination-sm">
                        <li class="page-item"><a class="page-link" href="#">Trước</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Simple search filter cho table (client-side)
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#productTable tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>