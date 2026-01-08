<?php
// app/views/admin/product/create-product.php
// Form tạo sản phẩm mới cho admin (dùng trong productCreate() của AdminController)
?>

<div class="row g-4">
    <div class="col-12">
        <h3>Thêm Sản Phẩm Mới</h3>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger rounded-3 mb-3"><?= $error ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success rounded-3 mb-3"><?= $success ?></div>
        <?php endif; ?>
        <div class="card bg-dark border-0 rounded-3 p-4">
            <form method="POST" enctype="multipart/form-data" id="productForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($data['name'] ?? '') ?>" required>
                    <div class="form-text">Tên sản phẩm hiển thị trên trang.</div>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" name="price" id="price" class="form-control" value="<?= $data['price'] ?? '' ?>" step="0.01" min="0" required>
                    <div class="form-text">Giá bán sản phẩm.</div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea name="description" id="description" class="form-control" rows="4"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                    <div class="form-text">Mô tả chi tiết sản phẩm (hỗ trợ HTML nếu cần).</div>
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        <option value="">Chọn danh mục</option>
                        <?php foreach ($categories ?? [] as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($data['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Số lượng tồn kho</label>
                    <input type="number" name="stock" id="stock" class="form-control" value="<?= $data['stock'] ?? '0' ?>" min="0">
                    <div class="form-text">Số lượng sản phẩm có sẵn.</div>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh sản phẩm</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    <div class="form-text">Chọn ảnh JPG/PNG (tối đa 2MB). Ảnh sẽ lưu vào uploads/products/.</div>
                    <?php if (isset($data['image'])): ?>
                        <img src="<?= htmlspecialchars($data['image']) ?>" alt="Preview" class="img-thumbnail mt-2" style="max-width: 200px;">
                    <?php endif; ?>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" name="active" id="active" class="form-check-input" <?= isset($data['active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="active">Kích hoạt sản phẩm</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" name="hien_trang_chu" id="hien_trang_chu" class="form-check-input" <?= isset($data['hien_trang_chu']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="hien_trang_chu">Hiển thị trang chủ</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" name="san_pham_hien_nhu_baner" id="banner" class="form-check-input" <?= isset($data['san_pham_hien_nhu_baner']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="banner">Hiển thị như banner</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" name="san_pham_noi_bat" id="noi_bat" class="form-check-input" <?= isset($data['san_pham_noi_bat']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="noi_bat">Sản phẩm nổi bật</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success me-2">Lưu Sản Phẩm</button>
                <a href="/admin/product" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>

<script>
    // JS đơn giản: Preview ảnh khi chọn
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.querySelector('.img-thumbnail');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.className = 'img-thumbnail mt-2';
                    preview.style.maxWidth = '200px';
                    document.querySelector('.mb-3').appendChild(preview);
                }
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Validation form cơ bản
    document.getElementById('productForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const price = parseFloat(document.getElementById('price').value);
        const categoryId = document.getElementById('category_id').value;
        if (!name || price <= 0 || !categoryId) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ tên, giá > 0, và chọn danh mục!');
        }
    });
</script>