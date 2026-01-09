<?php
include BASE_PATH . '/app/views/user/layout/header.php';

$imageString = $product['image_array'] ?? '';

$imageString = trim($imageString, '"');
$imageArray = array_map('trim', explode('","', $imageString));

?>

    <div class="container my-5">
        <div class="row">
            <!-- Gallery ảnh -->
            <div class="col-lg-6 mb-4">
                <div class="sticky-top" style="top: 20px;">
                    <img id="mainImage" src="uploads/products/<?=htmlspecialchars($main_image)?>"
                        alt="<?=htmlspecialchars($product['name'])?>" class="img-fluid rounded mb-3">
                        <?php if (count($imageArray) > 0): ?>
    <div class="row g-2">
        <?php foreach ($imageArray as $index => $img): ?>
            <div class="col-3">
                <img src="uploads/products/<?=htmlspecialchars($img)?>"
                     class="img-thumbnail thumbnail <?=$index === 0 ? 'active' : ''?>"
                     onclick="changeMainImage(this.src)" alt="Thumbnail">
            </div>
        <?php endforeach; ?>
    </div>
        <?php endif; ?>
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-lg-6">
                <h1 class="mb-3"><?=htmlspecialchars($product['name'])?></h1>

                <div class="d-flex align-items-center mb-3">
                    <span class="text-muted me-3">Mã SP: #<?=$product['id']?></span>
                    <span class="text-muted">Lượt xem: <?=number_format($product['view'] + 1)?></span>
                </div>

                <div class="price mb-4">
                    <?=number_format($product['price'], 0, ',', '.')?> ₫
                </div>

                <?php if (!empty($product['description'])): ?>
                    <div class="alert alert-light mb-4">
                        <strong>Mô tả ngắn:</strong><br>
                        <?=nl2br(htmlspecialchars($product['description']))?>
                    </div>
                <?php endif; ?>

                <!-- Form thêm vào giỏ hàng (AJAX) -->
                <form id="addToCartForm">
                    <input type="hidden" name="product_id" value="<?=$product['id']?>">

                    <?php if (!empty($variants)): ?>
                        <div class="variant-group">
                            <!-- Kích thước -->
                            <div>
                                <div class="variant-title">Kích thước: <span class="text-danger">*</span></div>
                                <div class="variant-options">
                                    <?php foreach ($unique_sizes as $size_id): ?>
                                        <label class="variant-label">
                                            <input type="radio" name="size_id" value="<?=$size_id?>" required style="display:none;">
                                            <?=getSizeName($size_id)?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Màu sắc -->
                            <div>
                                <div class="variant-title">Màu sắc: <span class="text-danger">*</span></div>
                                <div class="variant-options">
                                    <?php foreach ($unique_colors as $color_id): ?>
                                        <label class="variant-label">
                                            <input type="radio" name="color_id" value="<?=$color_id?>" required style="display:none;">
                                            <?=getColorName($color_id)?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 mt-4">
                            <strong>Số lượng:</strong>
                            <input type="number" name="quantity" value="1" min="1" max="99" class="form-control w-25 d-inline-block" required>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="quantity" value="1">
                        <p class="text-success mb-4 fw-bold">Sản phẩm chỉ có một phiên bản duy nhất.</p>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary btn-lg px-5" id="addToCartBtn">
                        <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ hàng
                    </button>
                    <div class="mt-3" id="addToCartMessage"></div>
                </form>

                <hr class="my-5">
            </div>

            <?php if (!empty($product['content'])): ?>
                    <div>
                        <h4>Chi tiết sản phẩm</h4>
                        <?=nl2br(htmlspecialchars($product['content']))?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($relatedProducts)): ?>
                <div>
                    <h4>Sản phẩm cùng danh mục</h4>
                    <div class="row g-3 mt-3">
                        <?php foreach ($relatedProducts as $rp): ?>
                            <div class="col-6 col-md-3">
                                <div class="card h-100">
                                    <?php 
                                    $rpImages = isset($rp['image_array']) ? array_map('trim', explode('","', trim($rp['image_array'], '"'))) : [];
                                    $rpMainImage = $rpImages[0] ?? $rp['image'] ?? '';
                                    ?>
                                    <img src="uploads/products/<?=htmlspecialchars($rpMainImage)?>" 
                                        class="card-img-top" alt="<?=htmlspecialchars($rp['name'])?>">
                                    <div class="card-body p-2">
                                        <h6 class="card-title mb-1"><?=htmlspecialchars($rp['name'])?></h6>
                                        <p class="mb-0 text-danger fw-bold"><?=number_format($rp['price'],0,',','.')?> ₫</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>


        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function changeMainImage(src) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            event.target.closest('.thumbnail').classList.add('active');
        }

        // Highlight khi chọn variant
        document.querySelectorAll('.variant-label input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const group = this.closest('.variant-options');
                group.querySelectorAll('.variant-label').forEach(label => label.classList.remove('selected'));
                this.closest('.variant-label').classList.add('selected');
            });
        });

        // AJAX thêm vào giỏ hàng
        document.getElementById('addToCartForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('addToCartBtn');
            const msg = document.getElementById('addToCartMessage');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang thêm...';
            msg.innerHTML = '';

            const formData = new FormData(this);

            fetch('<?php BASE_URL ?>/postCart', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const msg = document.getElementById('addToCartMessage');
    if (data.success) {
        msg.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        // Cập nhật badge giỏ hàng
        const badge = document.querySelector('.cart-badge, #cart-count, .badge-cart');
        if (badge) badge.textContent = data.total_items;
    } else {
        msg.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
    }
                })
                .catch(err => {
                    console.error(err);
                    msg.innerHTML = '<div class="alert alert-danger">Có lỗi xảy ra. Vui lòng thử lại!</div>';
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        });
    </script>

    <?php include BASE_PATH . '/app/views/user/layout/footer.php'; ?>