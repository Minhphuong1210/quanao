
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - Thêm Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=BASE_URL?>assets/admin/css/style.css">

</head>

<body class="d-flex flex-column h-100">

    <?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
    <?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>

    <main class="main-content flex-grow-1 d-flex flex-column">

        <div class="content-placeholder flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h3 class="text-light mb-2">Thêm Sản Phẩm Mới</h3>
                    <p class="text-muted mb-0">Thêm sản phẩm và các biến thể (size, màu sắc)</p>
                </div>
                <a href="/admin/product" class="btn btn-outline-dark">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?=htmlspecialchars($error)?>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- <?php if (DEBUG_MODE): ?>
                <div class="debug-info">
                    <strong>DEBUG INFO:</strong> Check PHP error_log for detailed information<br>
                    <?php
if (!empty($error)) {
    echo "Error: " . htmlspecialchars($error) . "<br>";
}

?>
                </div>
            <?php endif; ?> -->

            <div class="card  border-0 rounded-3 shadow-lg p-4 p-lg-5">
                <form method="POST" enctype="multipart/form-data" id="productForm" action ="<?=BASE_URL?>admin/product/create">

                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-light mb-4 border-bottom pb-3">
                                <i class="fas fa-info-circle me-2"></i>Thông tin cơ bản
                            </h5>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-4">
                                <label for="name" class="form-label text-light fw-semibold">
                                    Tên sản phẩm <span class="required-star">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                    class="form-control form-control-lg"
                                    placeholder="Nhập tên sản phẩm..."
                                    value="<?=htmlspecialchars($_POST['name'] ?? '')?>"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="price" class="form-label text-light fw-semibold">
                                    Giá (VNĐ) <span class="required-star">*</span>
                                </label>
                                <input type="number" name="price" id="price"
                                    class="form-control form-control-lg"
                                    placeholder="0"
                                    min="0"
                                    step="1"
                                    value="<?=htmlspecialchars($_POST['price'] ?? '')?>"
                                    required>
                                <small class="text-muted mt-1">Nhập số nguyên</small>
                            </div>
                        </div>
                    </div>



                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="category_id" class="form-label text-light fw-semibold">
                                    <i class="fas fa-folder me-2"></i>Danh mục <span class="required-star">*</span>
                                </label>
                                <select name="category_id" id="category_id"
                                    class="form-select form-select-lg"
                                    required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?=$cat['id']?>"
                                            <?=($_POST['category_id'] ?? '') == $cat['id'] ? 'selected' : ''?>>
                                            <?=htmlspecialchars($cat['name'])?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="nha_cung_cap_id" class="form-label text-light fw-semibold">
                                    <i class="fas fa-truck me-2"></i>Nhà cung cấp <span class="required-star">*</span>
                                </label>
                                <select name="nha_cung_cap_id" id="nha_cung_cap_id"
                                    class="form-select form-select-lg"
                                    required>
                                    <option value="">-- Chọn nhà cung cấp --</option>
                                    <?php foreach ($nhaCungCaps as $ncc): ?>
                                        <option value="<?=$ncc['id']?>"
                                            <?=($_POST['nha_cung_cap_id'] ?? '') == $ncc['id'] ? 'selected' : ''?>>
                                            <?=htmlspecialchars($ncc['name'])?>
                                            <?php if (!empty($ncc['vi_tri'])): ?>
                                                <span class="text-muted"> (<?=htmlspecialchars($ncc['vi_tri'])?>)</span>
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <!-- Hiển thị ở trang chủ -->
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="hien_trang_chu" class="form-label text-light fw-semibold">
                                    <i class="fas fa-home me-2"></i>Hiển thị ở trang chủ
                                </label>
                                <select name="hien_trang_chu" id="hien_trang_chu" class="form-select form-select-lg">
                                    <option value="1" <?=($_POST['hien_trang_chu'] ?? 1) == 1 ? 'selected' : ''?>>Có</option>
                                    <option value="0" <?=($_POST['hien_trang_chu'] ?? 1) == 0 ? 'selected' : ''?>>Không</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sản phẩm nổi bật -->
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="san_pham_noi_bat" class="form-label text-light fw-semibold">
                                    <i class="fas fa-star me-2"></i>Sản phẩm nổi bật
                                </label>
                                <select name="san_pham_noi_bat" id="san_pham_noi_bat" class="form-select form-select-lg">
                                    <option value="1" <?=($_POST['san_pham_noi_bat'] ?? 0) == 1 ? 'selected' : ''?>>Có</option>
                                    <option value="0" <?=($_POST['san_pham_noi_bat'] ?? 0) == 0 ? 'selected' : ''?>>Không</option>
                                </select>
                            </div>
                        </div>

                        <!-- Trạng thái (active) -->
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="active" class="form-label text-light fw-semibold">
                                    <i class="fas fa-toggle-on me-2"></i>Trạng thái
                                </label>
                                <select name="active" id="active" class="form-select form-select-lg">
                                    <option value="1" <?=($_POST['active'] ?? 1) == 1 ? 'selected' : ''?>>Hoạt động</option>
                                    <option value="0" <?=($_POST['active'] ?? 1) == 0 ? 'selected' : ''?>>Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label text-light fw-semibold">Mô tả ngắn</label>
                        <textarea name="description" id="description"
                            class="form-control"
                            rows="3"
                            placeholder="Nhập mô tả ngắn về sản phẩm..."
                            maxlength="225"><?=htmlspecialchars($_POST['description'] ?? '')?></textarea>
                        <div class="form-text text-muted">Tối đa 225 ký tự</div>
                    </div>

                    <div class="mb-4">
                        <label for="content" class="form-label text-light fw-semibold mb-3">
                            <i class="fas fa-align-left me-2"></i>Mô tả chi tiết
                        </label>
                        <textarea name="content" id="content"
                            class="form-control"
                            rows="100"
                            placeholder="Nhập mô tả chi tiết về sản phẩm..."><?=htmlspecialchars($_POST['content'] ?? '')?></textarea>
                    </div>

                    <div class="mb-5">
                        <label for="image" class="form-label text-light fw-semibold">
                            <i class="fas fa-image me-2"></i>Ảnh sản phẩm chính <span class="required-star">*</span>
                        </label>
                        <input type="file" name="image" id="image"
                            class="form-control form-control-lg"
                            accept="image/*"
                            required>
                        <div class="form-text text-muted mt-1">Chỉ chấp nhận JPG, PNG, GIF, WEBP. Tối đa 5MB.</div>
                        <div id="imagePreviewContainer" class="mt-3">
                            <div class="border rounded-3 p-4 text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                                <i class="fas fa-image fs-1 text-muted mb-3"></i>
                                <p class="text-muted mb-0">Ảnh preview sẽ hiển thị tại đây</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="image_array" class="form-label text-light fw-semibold">
                            <i class="fas fa-images me-2"></i>Ảnh sản phẩm phụ (image_array)
                        </label>
                        <input type="file" name="image_array[]" id="image_array"
                            class="form-control form-control-lg"
                            accept="image/*"
                            multiple>
                        <div class="form-text text-muted mt-1">Có thể chọn nhiều ảnh. Chỉ chấp nhận JPG, PNG, GIF, WEBP. Mỗi ảnh tối đa 5MB.</div>
                        <div id="imageArrayPreviewContainer" class="mt-3 image-preview-container">
                            <div class="border rounded-3 p-4 text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                                <i class="fas fa-images fs-1 text-muted mb-3"></i>
                                <p class="text-muted mb-0">Ảnh phụ sẽ hiển thị tại đây</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-light mb-0">
                                <i class="fas fa-layer-group me-2"></i>Biến thể sản phẩm (Size & Màu sắc)
                            </h5>
                            <button type="button" id="addVariant" class="btn btn-add-variant">
                                <i class="fas fa-plus me-2"></i>Thêm biến thể
                            </button>
                        </div>

                        <div id="variantsContainer">
                            <div class="variant-row" data-index="0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label text-light">Size</label>
                                            <select name="variants[0][size_id]" class="form-select size-select">
                                                <option value="">-- Chọn size --</option>
                                                <?php foreach ($sizes as $size): ?>
                                                    <option value="<?=$size['id']?>"
                                                        <?=(isset($_POST['variants'][0]['size_id']) && $_POST['variants'][0]['size_id'] == $size['id']) ? 'selected' : ''?>>
                                                        <?=htmlspecialchars($size['name'])?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label text-light">Màu sắc</label>
                                            <select name="variants[0][color_id]" class="form-select color-select">
                                                <option value="">-- Chọn màu --</option>
                                                <?php foreach ($colors as $color): ?>
                                                    <option value="<?=$color['id']?>"
                                                        <?=(isset($_POST['variants'][0]['color_id']) && $_POST['variants'][0]['color_id'] == $color['id']) ? 'selected' : ''?>>
                                                        <?=htmlspecialchars($color['name'])?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label text-light">Số lượng</label>
                                            <input type="number" name="variants[0][stock]"
                                                class="form-control variant-stock"
                                                min="0"
                                                value="<?=htmlspecialchars($_POST['variants'][0]['stock'] ?? '0')?>"
                                                placeholder="0">
                                        </div>
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-remove-variant" onclick="removeVariant(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top border-secondary">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-cubes fs-4 text-primary me-3"></i>
                                        <div>
                                            <h6 class="text-light mb-1">Tổng số lượng tồn kho</h6>
                                            <p class="text-muted mb-0">Tổng từ tất cả các biến thể</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="display-4 fw-bold text-success" id="totalStock">0</div>
                                    <input type="hidden" name="stock" id="totalStockInput" value="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-3 mt-5 pt-4 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                        <button type="reset" class="btn btn-secondary btn-lg px-5 py-3" onclick="resetForm()">
                            <i class="fas fa-redo me-2"></i>Nhập lại
                        </button>
                        <button type="submit" class="btn btn-success btn-lg px-5 py-3">
                            <i class="fas fa-save me-2"></i>Lưu Sản Phẩm
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
const COLORS = <?=json_encode($colors, JSON_UNESCAPED_UNICODE)?>;
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preview ảnh chính
            document.getElementById('image').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const container = document.getElementById('imagePreviewContainer');

                if (file) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Ảnh không được lớn hơn 5MB!');
                        this.value = '';
                        return;
                    }

                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Chỉ chấp nhận file ảnh JPG, PNG, GIF hoặc WEBP!');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        container.innerHTML = `
                            <div class="text-center">
                                <img src="${e.target.result}"
                                     class="img-fluid rounded-3 shadow"
                                     style="max-height: 300px; border: 2px solid #6366f1;"
                                     alt="Preview ảnh sản phẩm">
                                <p class="text-muted mt-2">${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</p>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(file);
                } else {
                    container.innerHTML = `
                        <div class="border rounded-3 p-4 text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                            <i class="fas fa-image fs-1 text-muted mb-3"></i>
                            <p class="text-muted mb-0">Ảnh preview sẽ hiển thị tại đây</p>
                        </div>
                    `;
                }
            });

            // Preview ảnh phụ (image_array)
            document.getElementById('image_array').addEventListener('change', function(e) {
                const files = e.target.files;
                const container = document.getElementById('imageArrayPreviewContainer');

                if (files.length > 0) {
                    container.innerHTML = '';

                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];

                        if (file.size > 5 * 1024 * 1024) {
                            alert(`Ảnh ${file.name} vượt quá 5MB!`);
                            continue;
                        }

                        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        if (!validTypes.includes(file.type)) {
                            alert(`Ảnh ${file.name} không đúng định dạng!`);
                            continue;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'image-preview';
                            img.title = file.name;
                            container.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                } else {
                    container.innerHTML = `
                        <div class="border rounded-3 p-4 text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                            <i class="fas fa-images fs-1 text-muted mb-3"></i>
                            <p class="text-muted mb-0">Ảnh phụ sẽ hiển thị tại đây</p>
                        </div>
                    `;
                }
            });

            document.getElementById('name').focus();
            calculateTotalStock();

            document.querySelectorAll('.variant-stock').forEach(input => {
                input.addEventListener('input', calculateTotalStock);
            });

            // Hiển thị màu sắc trong dropdown
            initColorSelects();

        });

        let variantIndex = <?php echo isset($_POST['variants']) ? count($_POST['variants']) : 1; ?>;

        document.addEventListener('DOMContentLoaded', function () {

let variantIndex = <?php echo isset($_POST['variants']) ? count($_POST['variants']) : 1; ?>;

const addBtn = document.getElementById('addVariant');
const container = document.getElementById('variantsContainer');

if (!addBtn || !container) {
    console.error('Không tìm thấy nút hoặc container biến thể');
    return;
}

addBtn.addEventListener('click', function () {

    const template = `
    <div class="variant-row" data-index="${variantIndex}">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label text-light">Size</label>
                    <select name="variants[${variantIndex}][size_id]" class="form-select size-select">
                        <option value="">-- Chọn size --</option>
                        <?php foreach ($sizes as $size): ?>
                            <option value="<?=$size['id']?>"><?=htmlspecialchars($size['name'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label text-light">Màu sắc</label>
                    <select name="variants[${variantIndex}][color_id]" class="form-select color-select">
                        <option value="">-- Chọn màu --</option>
                        <?php foreach ($colors as $color): ?>
                            <option value="<?=$color['id']?>"><?=htmlspecialchars($color['name'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label text-light">Số lượng</label>
                    <input type="number"
                        name="variants[${variantIndex}][stock]"
                        class="form-control variant-stock"
                        value="0" min="0">
                </div>
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-remove-variant" onclick="removeVariant(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    `;

    container.insertAdjacentHTML('beforeend', template);

    container
        .querySelector(`[name="variants[${variantIndex}][stock]"]`)
        .addEventListener('input', calculateTotalStock);

    initColorSelects();
    variantIndex++;
    calculateTotalStock();
});

});

        function removeVariant(button) {
            const variantRow = button.closest('.variant-row');
            if (document.querySelectorAll('.variant-row').length > 1) {
                variantRow.remove();
                calculateTotalStock();
            } else {
                alert('Phải có ít nhất một biến thể!');
            }
        }

        function calculateTotalStock() {
            let total = 0;
            const stockInputs = document.querySelectorAll('.variant-stock');

            stockInputs.forEach(input => {
                const value = parseInt(input.value) || 0;
                total += value;
            });

            document.getElementById('totalStock').textContent = total;
            document.getElementById('totalStockInput').value = total;
        }

        function resetForm() {
            calculateTotalStock();
        }

        function initColorSelects() {
    document.querySelectorAll('.color-select').forEach(select => {

        const selectedValue = select.value;
        select.innerHTML = '<option value="">-- Chọn màu --</option>';

        COLORS.forEach(color => {
            const option = document.createElement('option');
            option.value = color.id;
            option.textContent = color.name;

            if (color.ma_mau) {
                option.style.backgroundColor = color.ma_mau;
                option.style.color = getContrastColor(color.ma_mau);
            }

            if (option.value == selectedValue) {
                option.selected = true;
            }

            select.appendChild(option);
        });
    });
}


        // Hàm xác định màu chữ tương phản
        function getContrastColor(hexColor) {
            let hex = hexColor.toLowerCase();

            // Nếu không phải hex, trả về màu mặc định
            if (!hex.startsWith('#')) {
                return '#FFFFFF';
            }

            // Tính độ sáng
            const r = parseInt(hex.substr(1, 2), 16);
            const g = parseInt(hex.substr(3, 2), 16);
            const b = parseInt(hex.substr(5, 2), 16);

            const brightness = (r * 299 + g * 587 + b * 114) / 1000;

            return brightness > 128 ? '#000000' : '#FFFFFF';
        }

        document.getElementById('productForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const price = document.getElementById('price').value;
            const category = document.getElementById('category_id').value;
            const nhaCungCap = document.getElementById('nha_cung_cap_id').value;
            const image = document.getElementById('image').value;

            // Debug values trước khi submit
            console.log('Form submit validation:');
            console.log('name:', name);
            console.log('price:', price);
            console.log('category:', category);
            console.log('nha_cung_cap:', nhaCungCap);
            console.log('image:', image);

            if (!name) {
                e.preventDefault();
                alert('Vui lòng nhập tên sản phẩm');
                document.getElementById('name').focus();
                return false;
            }

            if (!price || parseInt(price) <= 0) {
                e.preventDefault();
                alert('Vui lòng nhập giá sản phẩm hợp lệ');
                document.getElementById('price').focus();
                return false;
            }

            if (!category) {
                e.preventDefault();
                alert('Vui lòng chọn danh mục sản phẩm');
                document.getElementById('category_id').focus();
                return false;
            }

            if (!nhaCungCap) {
                e.preventDefault();
                alert('Vui lòng chọn nhà cung cấp');
                document.getElementById('nha_cung_cap_id').focus();
                return false;
            }

            if (!image) {
                e.preventDefault();
                alert('Vui lòng chọn ảnh sản phẩm chính');
                document.getElementById('image').focus();
                return false;
            }

            let hasValidVariant = false;
            document.querySelectorAll('.variant-row').forEach(row => {
                const sizeSelect = row.querySelector('.size-select');
                const colorSelect = row.querySelector('.color-select');
                if (sizeSelect.value && colorSelect.value) {
                    hasValidVariant = true;
                }
            });

            if (!hasValidVariant) {
                e.preventDefault();
                alert('Phải có ít nhất 1 biến thể (size + màu) hợp lệ');
                return false;
            }

            return true;
        });
    </script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.querySelector('#content');

    if (!textarea) return;

    ClassicEditor
        .create(textarea, {
            language: 'vi',
            placeholder: 'Nhập mô tả chi tiết về sản phẩm...'
        })
        .catch(error => console.error(error));
});
</script>

</body>

</html>