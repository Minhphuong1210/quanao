<?php
include BASE_PATH . '/app/views/user/layout/header.php';

// Xác định sản phẩm có biến thể không
$hasVariants = !empty($colors) && !empty($sizes);

// Hàm xử lý đường dẫn ảnh
function processImagePath($image_path, $base_url)
{
    if (empty($image_path)) {
        return $base_url . 'assets/img/no-image.jpg';
    }
    $image_path = str_replace(['uploads/products/', 'public/uploads/products/'], '', $image_path);
    $image_path = ltrim($image_path, '/');
    return $base_url . 'uploads/products/' . $image_path;
}

// Ảnh chính
$main_image = $product['image'] ?? '';
$main_image_path = processImagePath($main_image, BASE_URL);

// Ảnh phụ - SỬA parse cho định dạng comma-separated (không có dấu ")
$image_list = [];
if (!empty($product['image_array'])) {
    // Thử JSON trước (nếu sau này đổi sang JSON)
    $decoded = json_decode($product['image_array'], true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $image_list = array_filter($decoded, fn($img) => is_string($img) && trim($img) !== '');
        $image_list = array_values($image_list);
    } else {
        // Định dạng hiện tại: path1.jpg,path2.jpg (comma separated, không dấu ngoặc kép)
        $clean = trim($product['image_array'], ' []"\''); // loại bỏ khoảng trắng, [, ], ", '
        if ($clean !== '') {
            $parts = array_map('trim', explode(',', $clean));
            $image_list = array_filter($parts, fn($img) => $img !== '');
        }
    }
}
$hasSubImages = !empty($image_list);
?>

<style>
    .product-gallery {
        position: sticky;
        top: 20px;
    }

    .main-image-container {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
        background: #f8f9fa;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .main-image {
        width: 100%;
        height: 400px;
        object-fit: contain;
        background: white;
    }

    .thumbnail-container {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .thumbnail-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s ease;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .thumbnail-wrapper:hover,
    .thumbnail-wrapper.active {
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }

    .thumbnail-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .variant-group {
        background: #fafafa;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #eee;
        margin-bottom: 20px;
    }

    .variant-title {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 12px;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .variant-title .required {
        color: #dc3545;
    }

    .selected-info {
        font-size: 14px;
        color: #0d6efd;
        font-weight: 500;
    }

    .variant-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 5px;
    }

    .color-option {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 2px solid #ddd;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .color-option:hover {
        transform: scale(1.08);
        border-color: #0d6efd;
    }

    .color-option input:checked+.color-display {
        box-shadow: 0 0 0 3px #fff, 0 0 0 5px #0d6efd;
    }

    .color-display {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: block;
        box-shadow: inset 0 0 0 1px rgba(0, 0, 0, .1);
    }

    .size-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        height: 40px;
        padding: 0 15px;
        border-radius: 8px;
        border: 2px solid #ddd;
        background: #fff;
        font-weight: 500;
        font-size: 14px;
        color: #333;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .size-btn:hover {
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .size-btn.selected {
        background: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 15px;
        margin: 20px 0;
    }

    .quantity-btn {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        background: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .quantity-btn:hover {
        background: #f8f9fa;
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .quantity-input {
        width: 70px;
        text-align: center;
        font-weight: 600;
        font-size: 16px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 6px;
    }

    .product-price {
        font-size: 32px;
        font-weight: 700;
        color: #dc3545;
        margin: 20px 0;
    }

    .original-price {
        font-size: 20px;
        color: #6c757d;
        text-decoration: line-through;
        margin-left: 10px;
    }

    .related-product-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
    }

    .related-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .related-product-img {
        height: 200px;
        object-fit: cover;
        background: #f8f9fa;
    }

    @media (max-width: 768px) {
        .product-gallery {
            position: static;
            margin-bottom: 20px;
        }

        .main-image {
            height: 300px;
        }

        .thumbnail-wrapper {
            width: 60px;
            height: 60px;
        }

        .product-price {
            font-size: 28px;
        }
    }

    @media (max-width: 576px) {
        .main-image {
            height: 250px;
        }

        .variant-options {
            justify-content: center;
        }

        .product-price {
            font-size: 24px;
        }
    }

  
.product-content {
    font-size: 15px;
    line-height: 1.7;
    color: #333;
}

/* fix text editor */
.product-content p {
    margin-bottom: 1rem;
}

.product-content img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 10px auto;
}

.product-content ul,
.product-content ol {
    padding-left: 20px;
}

.product-content table {
    width: 100%;
    border-collapse: collapse;
}

.product-content table td,
.product-content table th {
    border: 1px solid #ddd;
    padding: 8px;
}

.product-content iframe {
    max-width: 100%;
}



</style>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="product-gallery">
                <div class="main-image-container">
                    <img id="mainImage" src="<?= $main_image_path ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                        class="main-image" onerror="this.src='<?= BASE_URL ?>assets/img/no-image.jpg';">
                </div>

                <?php if ($hasSubImages): ?>
                    <div class="thumbnail-container">
                        <div class="thumbnail-wrapper active" onclick="changeMainImage('<?= $main_image_path ?>', this)">
                            <img src="<?= $main_image_path ?>" class="thumbnail-img" alt="Ảnh chính"
                                onerror="this.src='<?= BASE_URL ?>assets/img/no-image.jpg';">
                        </div>
                        <?php foreach ($image_list as $index => $img): ?>
                            <?php $thumbnail_path = processImagePath($img, BASE_URL); ?>
                            <div class="thumbnail-wrapper" onclick="changeMainImage('<?= $thumbnail_path ?>', this)">
                                <img src="<?= $thumbnail_path ?>" class="thumbnail-img" alt="Ảnh <?= $index + 1 ?>"
                                    onerror="this.src='<?= BASE_URL ?>assets/img/no-image.jpg';">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-6">
            <h1 class="h2 fw-bold mb-3"><?= htmlspecialchars($product['name']) ?></h1>

            <div class="d-flex align-items-center mb-3">
                <span class="badge bg-secondary me-3">Mã SP: #<?= $product['id'] ?></span>
                <span class="text-muted"><i class="fas fa-eye me-1"></i> <?= number_format($product['view'] ?? 0) ?> lượt xem</span>
            </div>

            <div class="product-price">
                <?= number_format($product['price'], 0, ',', '.') ?> ₫
                <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                    <span class="original-price"><?= number_format($product['sale_price'], 0, ',', '.') ?> ₫</span>
                <?php endif; ?>
            </div>

            <?php if (!empty($product['description'])): ?>
                <div class="card mb-4 border-light">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Mô tả sản phẩm</h6>
                        <p class="card-text"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <form id="addToCartForm">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="variant_id" id="variantId" value="">

                        <?php if ($hasVariants): ?>
                            <div class="mb-4">
                                <div class="variant-title">
                                    <span>Màu sắc <span class="required">*</span></span>
                                    <span id="selectedColorInfo" class="selected-info"></span>
                                </div>
                                <div class="variant-options mb-2">
                                    <?php foreach ($colors as $color): ?>
                                        <label class="color-option" title="<?= htmlspecialchars($color['name']) ?>">
                                            <input type="radio" name="color_id" value="<?= $color['id'] ?>" required
                                                data-color-name="<?= htmlspecialchars($color['name']) ?>"
                                                data-color-code="<?= $color['ma_mau'] ?>" hidden>
                                            <span class="color-display" style="background-color: <?= $color['ma_mau'] ?>;"></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <div id="colorError" class="text-danger small d-none">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Vui lòng chọn màu sắc
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="variant-title">
                                    <span>Kích thước <span class="required">*</span></span>
                                    <span id="selectedSizeInfo" class="selected-info"></span>
                                </div>
                                <div class="variant-options mb-2">
                                    <?php foreach ($sizes as $size): ?>
                                        <label class="size-option">
                                            <input type="radio" name="size_id" value="<?= $size['id'] ?>" required
                                                data-size-name="<?= htmlspecialchars($size['name']) ?>" hidden>
                                            <span class="size-btn"><?= $size['name'] ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <div id="sizeError" class="text-danger small d-none">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Vui lòng chọn kích thước
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success mb-4">
                                <i class="fas fa-check-circle me-2"></i><strong>Sản phẩm chỉ có một phiên bản duy nhất.</strong>
                            </div>
                        <?php endif; ?>

                        <?php if ($hasVariants): ?>
                            <div class="quantity-control">
                                <strong>Số lượng:</strong>
                                <div class="d-flex align-items-center">
                                    <button type="button" class="quantity-btn" id="decreaseQty">-</button>
                                    <input type="number" name="quantity" id="quantityInput" value="1" min="1" max="99"
                                        class="form-control quantity-input mx-2">
                                    <button type="button" class="quantity-btn" id="increaseQty">+</button>
                                </div>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="quantity" value="1">
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold mt-3" id="addToCartBtn">
                            <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ hàng
                        </button>

                        <div class="mt-3" id="addToCartMessage"></div>
                    </form>
                </div>
            </div>

            <div class="row g-3 text-center">
                <div class="col-4">
                    <div class="card border-0 bg-light">
                        <div class="card-body py-3">
                            <i class="fas fa-shipping-fast fa-2x text-primary mb-2"></i>
                            <h6 class="mb-0">Miễn phí vận chuyển</h6>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card border-0 bg-light">
                        <div class="card-body py-3">
                            <i class="fas fa-sync-alt fa-2x text-primary mb-2"></i>
                            <h6 class="mb-0">Đổi trả 7 ngày</h6>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card border-0 bg-light">
                        <div class="card-body py-3">
                            <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                            <h6 class="mb-0">Bảo hành 1 năm</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($product['content'])): ?>
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-2 text-primary"></i> Chi tiết sản phẩm</h5>
                    </div>
                    <div class="card-body product-content"><?= $product['content'] ?></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($relatedProducts)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-4"><i class="fas fa-random me-2 text-primary"></i> Sản phẩm cùng danh mục</h4>
                <div class="row g-4">
                    <?php foreach ($relatedProducts as $rp):
                        if ($rp['id'] == $product['id']) continue;
                        $rpImage = !empty($rp['image']) ? processImagePath($rp['image'], BASE_URL) : BASE_URL . 'assets/img/no-img.png';
                    ?>
                        <div class="col-6 col-md-3">
                            <a href="<?= BASE_URL ?>product/detail/<?= $rp['slug'] ?>" class="text-decoration-none">
                                <div class="card related-product-card border-0 shadow-sm h-100">
                                    <div class="position-relative">
                                        <img src="<?= $rpImage ?>" class="card-img-top related-product-img"
                                            alt="<?= htmlspecialchars($rp['name']) ?>"
                                            onerror="this.src='<?= BASE_URL ?>assets/img/no-img.png';">
                                        <?php if (!empty($rp['sale_price']) && $rp['sale_price'] < $rp['price']): ?>
                                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title text-dark mb-2"><?= htmlspecialchars($rp['name']) ?></h6>
                                        <p class="mb-0 text-danger fw-bold"><?= number_format($rp['price'], 0, ',', '.') ?> ₫</p>
                                        <?php if (!empty($rp['sale_price']) && $rp['sale_price'] < $rp['price']): ?>
                                            <small class="text-muted"><del><?= number_format($rp['sale_price'], 0, ',', '.') ?> ₫</del></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
   
    <div class="my-4">
    <h4 class="mb-4"><i class="fas fa-random me-2 text-primary"></i> Đánh giá sản phẩm</h4>
    <?php foreach ($comments as $c): ?>
    <div class="border-bottom mb-3 pb-3">
        <strong><?= htmlspecialchars($c['user_name']) ?></strong>

        <?php if ($c['star']): ?>
            <div class="text-warning">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star<?= $i <= $c['star'] ? '' : '-o' ?>"></i>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <p><?= nl2br(htmlspecialchars($c['comment'])) ?></p>
    </div>
<?php endforeach; ?>
    </div>


</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.changeMainImage = (src, el) => {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumbnail-wrapper').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
        };

        const qtyInput = document.getElementById('quantityInput');
        if (qtyInput) {
            document.getElementById('decreaseQty')?.addEventListener('click', () => {
                let v = parseInt(qtyInput.value);
                if (v > 1) qtyInput.value = v - 1;
            });
            document.getElementById('increaseQty')?.addEventListener('click', () => {
                let v = parseInt(qtyInput.value);
                if (v < 99) qtyInput.value = v + 1;
            });
        }

        document.querySelectorAll('input[name="color_id"]').forEach(r => r.addEventListener('change', function() {
            const n = this.dataset.colorName,
                c = this.dataset.colorCode;
            document.getElementById('selectedColorInfo').textContent = n;
            document.getElementById('selectedColorInfo').style.color = c;
            document.getElementById('colorError').classList.add('d-none');
            document.querySelectorAll('.color-option').forEach(o => o.classList.remove('selected'));
            this.closest('.color-option').classList.add('selected');
            checkFormValidity();
        }));

        document.querySelectorAll('input[name="size_id"]').forEach(r => r.addEventListener('change', function() {
            document.getElementById('selectedSizeInfo').textContent = this.dataset.sizeName;
            document.getElementById('sizeError').classList.add('d-none');
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
            this.nextElementSibling.classList.add('selected');
            checkFormValidity();
        }));

        function checkFormValidity() {
            const c = !!document.querySelector('input[name="color_id"]:checked');
            const s = !!document.querySelector('input[name="size_id"]:checked');
            const btn = document.getElementById('addToCartBtn');
            btn.disabled = !(c && s);
            btn.classList.toggle('btn-primary', c && s);
            btn.classList.toggle('btn-secondary', !(c && s));
        }

        const form = document.getElementById('addToCartForm');
        if (form) form.addEventListener('submit', async e => {
            e.preventDefault();

            <?php if ($hasVariants): ?>
                if (!document.querySelector('input[name="color_id"]:checked')) {
                    document.getElementById('colorError').classList.remove('d-none');
                    return;
                }
                if (!document.querySelector('input[name="size_id"]:checked')) {
                    document.getElementById('sizeError').classList.remove('d-none');
                    return;
                }
            <?php endif; ?>

            const btn = document.getElementById('addToCartBtn'),
                msg = document.getElementById('addToCartMessage');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang thêm...';

            try {
                const res = await fetch('<?= BASE_URL ?>postCart', {
                    method: 'POST',
                    body: new FormData(form)
                });
                const data = await res.json();
                if (data.success) {
                    msg.innerHTML = `<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>${data.message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
                    if (data.total_items !== undefined) {
                        document.querySelectorAll('.cart-count, #cartCount, .cart-badge').forEach(b => {
                            b.textContent = data.total_items;
                            b.classList.add('animate__animated', 'animate__bounce');
                            setTimeout(() => b.classList.remove('animate__animated', 'animate__bounce'), 1000);
                        });
                    }
                    setTimeout(() => msg.innerHTML = '', 3000);
                } else {
                    msg.innerHTML = `<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>${data.message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
                }
            } catch {
                msg.innerHTML = `<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>Có lỗi xảy ra. Vui lòng thử lại!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
            } finally {
                btn.disabled = false;
                btn.innerHTML = orig;
            }
        });

        checkFormValidity();
    });
</script>

<?php include BASE_PATH . '/app/views/user/layout/footer.php'; ?>