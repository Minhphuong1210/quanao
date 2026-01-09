<?php
include BASE_PATH . '/app/views/user/layout/header.php';

$imageString = $product['image_array'] ?? '';

$imageString = trim($imageString, '"');
$imageArray = array_map('trim', explode('","', $imageString));

?>

<style>
    /* ====== CHUNG ====== */
.variant-group {
    margin-bottom: 25px;
}

.variant-block {
    margin-bottom: 20px;
}

.variant-title {
    font-weight: 600;
    margin-bottom: 10px;
}

/* ====== OPTIONS ====== */
.variant-options {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

/* ====== SIZE ====== */
.size-label {
    cursor: pointer;
}

.size-label span {
    display: inline-block;
    padding: 8px 18px;
    border: 2px solid #ddd;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.25s ease;
    background: #fff;
}

.size-label:hover span {
    border-color: #0d6efd;
    color: #0d6efd;
}

.size-label input:checked + span {
    background: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}

/* ====== COLOR ====== */
.color-label {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s ease;
}

.color-label span {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: block;
}

.color-label:hover {
    transform: scale(1.1);
    border-color: #000;
}

.color-label input:checked + span {
    box-shadow: 0 0 0 3px #0d6efd;
}
/* =========================
   BIẾN THỂ SẢN PHẨM
========================= */

.variant-group {
    background: #fafafa;
    border-radius: 12px;
    padding: 18px;
    border: 1px solid #eee;
    margin-bottom: 25px;
}

.variant-block {
    margin-bottom: 22px;
}

.variant-title {
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 10px;
    color: #333;
}

/* =========================
   OPTIONS CHUNG
========================= */
.variant-options {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

/* =========================
   SIZE
========================= */
.size-label {
    cursor: pointer;
}

.size-label span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 46px;
    height: 40px;
    padding: 0 14px;
    border-radius: 8px;
    border: 1.8px solid #ddd;
    background: #fff;
    font-weight: 500;
    font-size: 14px;
    color: #333;
    transition: all 0.25s ease;
}

.size-label:hover span {
    border-color: #0d6efd;
    color: #0d6efd;
    box-shadow: 0 4px 12px rgba(13,110,253,.15);
}

/* Size được chọn */
.size-label input:checked + span {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 6px 16px rgba(13,110,253,.35);
}

/* =========================
   COLOR
========================= */
.color-options {
    gap: 14px;
}

.color-label {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 2px solid #ddd;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s ease;
}

.color-label span {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: block;
    box-shadow: inset 0 0 0 1px rgba(0,0,0,.1);
}

.color-label:hover {
    transform: translateY(-3px) scale(1.08);
    border-color: #0d6efd;
}

/* Color được chọn */
.color-label input:checked + span {
    box-shadow:
        0 0 0 3px #fff,
        0 0 0 6px #0d6efd;
}

/* =========================
   SỐ LƯỢNG
========================= */
input[name="quantity"] {
    border-radius: 8px;
    padding: 8px 12px;
    font-weight: 500;
}

/* =========================
   BUTTON GIỎ HÀNG
========================= */
#addToCartForm button[type="submit"] {
    border-radius: 12px;
    padding: 14px 32px;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.25s ease;
}

#addToCartForm button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(13,110,253,.35);
}

/* =========================
   THUMBNAIL ẢNH
========================= */
.thumbnail {
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.25s ease;
    border-radius: 10px;
}

.thumbnail:hover {
    transform: scale(1.05);
    border-color: #0d6efd;
}

.thumbnail.active {
    border-color: #0d6efd;
    box-shadow: 0 4px 12px rgba(13,110,253,.35);
}

/* =========================
   GIÁ SẢN PHẨM
========================= */
.price {
    font-size: 26px;
    font-weight: 700;
    color: #dc3545;
}

/* =========================
   MÔ TẢ NGẮN
========================= */
.alert-light {
    border-radius: 12px;
    background: #f8f9fa;
    border: 1px solid #eee;
}

</style>

    <div class="container my-5">
        <div class="row">
            <!-- Gallery ảnh -->
            <div class="col-lg-6 mb-4">
                <div class="sticky-top" style="top: 20px;">
                    <img id="mainImage" src="<?=  BASE_URL . $product['image'] ?>"
                   
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



                <!-- Form thêm vào giỏ hàng (AJAX) -->
                <form id="addToCartForm">
    <input type="hidden" name="product_id" value="<?=$product['id']?>">

    <?php if (!empty($variants)): ?>
        <div class="variant-group">

            <!-- KÍCH THƯỚC -->
            <div class="variant-block">
                <div class="variant-title">
                    Kích thước: <span class="text-danger">*</span>
                </div>

                <div class="variant-options size-options">
                    <?php foreach ($sizes as $size): ?>
                        <label class="size-label">
                            <input type="radio" name="size_id" value="<?=$size['id']?>" required hidden>
                            <span><?=$size['name']?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- MÀU SẮC -->
            <div class="variant-block">
                <div class="variant-title">
                    Màu sắc: <span class="text-danger">*</span>
                </div>

                <div class="variant-options color-options">
                    <?php foreach ($colors as $color): ?>
                        <label class="color-label" title="<?=$color['name']?>">
                            <input type="radio" name="color_id" value="<?=$color['id']?>" required hidden>
                            <span style="background-color: <?=$color['ma_mau']?>;"></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <!-- SỐ LƯỢNG -->
        <div class="mb-4 mt-4">
            <strong>Số lượng:</strong>
            <input type="number"
                   name="quantity"
                   value="1"
                   min="1"
                   max="99"
                   class="form-control w-25 d-inline-block"
                   required>
        </div>

    <?php else: ?>
        <input type="hidden" name="quantity" value="1">
        <p class="text-success mb-4 fw-bold">
            Sản phẩm chỉ có một phiên bản duy nhất.
        </p>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary btn-lg px-5" id="addToCartBtn">
        <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ hàng
    </button>

    <div class="mt-3" id="addToCartMessage"></div>
</form>

<?php if (!empty($product['description'])): ?>
                    <div class="alert alert-light mb-4">
                        <strong>Mô tả ngắn:</strong><br>
                        <?=nl2br(htmlspecialchars($product['description']))?>
                    </div>
                <?php endif; ?>

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
                                        <p class="mb-0 text-danger fw-bold"><?=number_format($rp['price'], 0, ',', '.')?> ₫</p>
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
        document.addEventListener('DOMContentLoaded', function () {

const form = document.getElementById('addToCartForm');
if (!form) return;

const btn = document.getElementById('addToCartBtn');
const msg = document.getElementById('addToCartMessage');

form.addEventListener('submit', function (e) {
    e.preventDefault();

    msg.innerHTML = '';

    // =========================
    // VALIDATE VARIANT
    // =========================
    const sizeRequired  = form.querySelector('input[name="size_id"]');
    const colorRequired = form.querySelector('input[name="color_id"]');

    if (sizeRequired && !form.querySelector('input[name="size_id"]:checked')) {
        msg.innerHTML = '<div class="alert alert-warning">Vui lòng chọn kích thước</div>';
        return;
    }

    if (colorRequired && !form.querySelector('input[name="color_id"]:checked')) {
        msg.innerHTML = '<div class="alert alert-warning">Vui lòng chọn màu sắc</div>';
        return;
    }

    // =========================
    // UI LOADING
    // =========================
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang thêm...';

    const formData = new FormData(form);

    // =========================
    // FETCH ADD TO CART
    // =========================
    fetch('<?=BASE_URL?>/postCart', {
        method: 'POST',
        body: formData
    })
    .then(res => {
        if (!res.ok) throw new Error('HTTP Error');
        return res.json();
    })
    .then(data => {
        if (data.success) {
            msg.innerHTML = `<div class="alert alert-success">${data.message}</div>`;

            // Update badge giỏ hàng
            const badge = document.querySelector(
                '.cart-badge, #cart-count, .badge-cart'
            );
            if (badge && data.total_items !== undefined) {
                badge.textContent = data.total_items;
            }
        } else {
            msg.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
    })
    .catch(error => {
        console.error(error);
        msg.innerHTML = '<div class="alert alert-danger">Có lỗi xảy ra. Vui lòng thử lại!</div>';
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});

});
    </script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Hiệu ứng chọn size
    document.querySelectorAll('.size-label').forEach(label => {
        label.addEventListener('click', function () {
            document.querySelectorAll('.size-label span')
                .forEach(s => s.classList.remove('active'));
        });
    });

    // Hiệu ứng chọn color
    document.querySelectorAll('.color-label').forEach(label => {
        label.addEventListener('click', function () {
            document.querySelectorAll('.color-label')
                .forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });

});
</script>


    <?php include BASE_PATH . '/app/views/user/layout/footer.php'; ?>