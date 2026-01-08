<?php
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/pages/layout/header.php';

$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if ($id <= 0 && empty($slug)) {
    die("Không tìm thấy sản phẩm.");
}

$pdo = Database::getInstance();

try {
    if ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND active = 1");
        $stmt->execute([$id]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE slug = ? AND active = 1");
        $stmt->execute([$slug]);
    }

    $product = $stmt->fetch();

    if (!$product) {
        die("Sản phẩm không tồn tại hoặc đã bị ẩn.");
    }

    // Tăng lượt xem
    $pdo->prepare("UPDATE products SET view = view + 1 WHERE id = ?")
        ->execute([$product['id']]);

    // Lấy variant
    $stmt_variant = $pdo->prepare("SELECT size_id, color_id FROM product_detail WHERE product_id = ?");
    $stmt_variant->execute([$product['id']]);
    $variants = $stmt_variant->fetchAll(PDO::FETCH_ASSOC);

    // Hàm tên size/màu
    function getSizeName($size_id)
    {
        $sizes = [
            1 => 'S',
            2 => 'M',
            3 => 'L',
            4 => 'XL',
            5 => 'XXL',
            6 => 'XXXL',
            28 => '28',
            29 => '29',
            30 => '30',
            31 => '31',
            32 => '32',
            33 => '33',
            34 => '34',
            35 => '35'
        ];
        return $sizes[$size_id] ?? 'N/A';
    }

    function getColorName($color_id)
    {
        $colors = [
            1 => 'Đen',
            2 => 'Trắng',
            3 => 'Xám',
            4 => 'Đỏ',
            5 => 'Xanh Navy',
            6 => 'Hồng',
            7 => 'Vàng',
            8 => 'Xanh Lá',
            9 => 'Be',
            10 => 'Nâu'
        ];
        return $colors[$color_id] ?? 'N/A';
    }

    // Danh sách size và color duy nhất
    $unique_sizes  = array_unique(array_filter(array_column($variants, 'size_id')));
    $unique_colors = array_unique(array_filter(array_column($variants, 'color_id')));

    // Xử lý hình ảnh
    $main_image = $product['image'] ?? 'default.jpg';
    $image_array = [];

    if (!empty($product['image_array'])) {
        $decoded = json_decode($product['image_array'], true);
        $image_array = is_array($decoded) ? $decoded : array_filter(explode(',', $product['image_array']));
    }

    $all_images = array_unique(array_merge([$main_image], $image_array));
    $all_images = array_filter($all_images);
} catch (Exception $e) {
    die("Lỗi: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Shop Quần Áo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .thumbnail {
            cursor: pointer;
            opacity: 0.7;
            transition: all 0.3s;
        }

        .thumbnail:hover,
        .thumbnail.active {
            opacity: 1;
            border: 2px solid #0d6efd;
        }

        .price {
            font-size: 1.8rem;
            font-weight: bold;
            color: #e74c3c;
        }

        /* Style variant giống giỏ hàng */
        .variant-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .variant-title {
            font-weight: bold;
            font-size: 0.95rem;
            color: #333;
        }

        .variant-options {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .variant-label {
            display: inline-block;
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            min-width: 50px;
            text-align: center;
            transition: all 0.2s;
        }

        .variant-label:hover {
            border-color: #0d6efd;
            background-color: #f8fbff;
        }

        .variant-label.selected {
            border-color: #0d6efd !important;
            background-color: #e7f0ff !important;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="row">
            <!-- Gallery ảnh -->
            <div class="col-lg-6 mb-4">
                <div class="sticky-top" style="top: 20px;">
                    <img id="mainImage" src="uploads/products/<?= htmlspecialchars($main_image) ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid rounded mb-3">
                    <?php if (count($all_images) > 1): ?>
                        <div class="row g-2">
                            <?php foreach ($all_images as $index => $img): ?>
                                <div class="col-3">
                                    <img src="uploads/products/<?= htmlspecialchars($img) ?>"
                                        class="img-thumbnail thumbnail <?= $index === 0 ? 'active' : '' ?>"
                                        onclick="changeMainImage(this.src)" alt="Thumbnail">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-lg-6">
                <h1 class="mb-3"><?= htmlspecialchars($product['name']) ?></h1>

                <div class="d-flex align-items-center mb-3">
                    <span class="text-muted me-3">Mã SP: #<?= $product['id'] ?></span>
                    <span class="text-muted">Lượt xem: <?= number_format($product['view'] + 1) ?></span>
                </div>

                <div class="price mb-4">
                    <?= number_format($product['price'], 0, ',', '.') ?> ₫
                </div>

                <?php if (!empty($product['description'])): ?>
                    <div class="alert alert-light mb-4">
                        <strong>Mô tả ngắn:</strong><br>
                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                    </div>
                <?php endif; ?>

                <!-- Form thêm vào giỏ hàng (AJAX) -->
                <form id="addToCartForm">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                    <?php if (!empty($variants)): ?>
                        <div class="variant-group">
                            <!-- Kích thước -->
                            <div>
                                <div class="variant-title">Kích thước: <span class="text-danger">*</span></div>
                                <div class="variant-options">
                                    <?php foreach ($unique_sizes as $size_id): ?>
                                        <label class="variant-label">
                                            <input type="radio" name="size_id" value="<?= $size_id ?>" required style="display:none;">
                                            <?= getSizeName($size_id) ?>
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
                                            <input type="radio" name="color_id" value="<?= $color_id ?>" required style="display:none;">
                                            <?= getColorName($color_id) ?>
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

                <?php if (!empty($product['content'])): ?>
                    <div>
                        <h4>Chi tiết sản phẩm</h4>
                        <?= nl2br(htmlspecialchars($product['content'])) ?>
                    </div>
                <?php endif; ?>
            </div>
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

            fetch('/quanao/php-thuan/views/user/cart/addtocart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        msg.innerHTML = `<div class="alert alert-success">${data.message}</div>`;

                        // Cập nhật badge giỏ hàng ở header (nếu có)
                        const badge = document.querySelector('.cart-badge, #cart-count, .badge-cart');
                        if (badge) badge.textContent = data.total_items;

                        setTimeout(() => msg.innerHTML = '', 4000);
                    } else {
                        msg.innerHTML = `<div class="alert alert-danger">Lỗi: ${data.message}</div>`;
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

    <?php require_once __DIR__ . '/pages/layout/footer.php'; ?>