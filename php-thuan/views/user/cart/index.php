<?php
require_once '../../../includes/init.php';
require_once '../../../pages/layout/header.php';
require_once __DIR__ . '/../../../includes/database.php';
require_once __DIR__ . '/../../../models/Cart.php';

$cart = new Cart();

function getSizeName($size_id)
{
    $sizes = [1 => 'S', 2 => 'M', 3 => 'L', 4 => 'XL', 5 => 'XXL', 6 => 'XXXL', 28 => '28', 29 => '29', 30 => '30', 31 => '31', 32 => '32', 33 => '33', 34 => '34', 35 => '35'];
    return $sizes[$size_id] ?? 'N/A';
}

function getColorName($color_id)
{
    $colors = [1 => 'Đen', 2 => 'Trắng', 3 => 'Xám', 4 => 'Đỏ', 5 => 'Xanh Navy', 6 => 'Hồng', 7 => 'Vàng', 8 => 'Xanh Lá', 9 => 'Be', 10 => 'Nâu'];
    return $colors[$color_id] ?? 'N/A';
}

$pdo = Database::getInstance();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/quanao/php-thuan/assets/css/style.css">
    <style>
        body {
            padding-top: 80px;
            background: #f8f9fa;
        }

        .cart-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .quantity-btn {
            cursor: pointer;
        }

        .quantity-input {
            width: 70px;
            text-align: center;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

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

        .delete-selected-btn {
            margin-left: 10px;
        }

        .selected-total {
            font-size: 1.1rem;
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <?php if ($cart->isEmpty()): ?>
            <div class="text-center py-5">
                <img src="https://static.vecteezy.com/system/resources/previews/016/462/240/non_2x/empty-shopping-cart-illustration-concept-on-white-background-vector.jpg"
                    alt="Giỏ trống" style="max-width:300px;" class="mb-4">
                <h4>Giỏ hàng đang trống</h4>
                <a href="/quanao/php-thuan/index.php" class="btn btn-primary btn-lg">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <h3 class="mb-4">Giỏ hàng của bạn</h3>

            <div class="d-flex align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="select-all">
                    <label class="form-check-label fw-bold" for="select-all">Chọn tất cả</label>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm delete-selected-btn" id="delete-selected" disabled>
                    <i class="bi bi-trash"></i> Xóa các mục đã chọn
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle" id="cart-table">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px;"></th>
                            <th>Sản phẩm</th>
                            <th>Kích thước & Màu sắc</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart->getItems() as $key => $item):
                            $parts = explode('-', $key);
                            $product_id = $parts[0] ?? 0;
                            $current_size_id = $parts[1] ?? 0;
                            $current_color_id = $parts[2] ?? 0;

                            $stmt = $pdo->prepare("SELECT DISTINCT size_id, color_id FROM product_detail WHERE product_id = ?");
                            $stmt->execute([$product_id]);
                            $variants = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $available_sizes = array_unique(array_column($variants, 'size_id'));
                            $available_colors = array_unique(array_column($variants, 'color_id'));
                        ?>
                            <tr data-key="<?= htmlspecialchars($key) ?>" data-price="<?= $item['price'] ?>" data-quantity="<?= $item['quantity'] ?>">
                                <td class="align-middle text-center">
                                    <input class="form-check-input item-checkbox" type="checkbox" value="<?= htmlspecialchars($key) ?>">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" class="cart-img me-3" alt="<?= htmlspecialchars($item['name']) ?>">
                                        <div class="fw-bold"><?= htmlspecialchars($item['name']) ?></div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="variant-group">
                                        <div>
                                            <div class="variant-title">Kích thước:</div>
                                            <div class="variant-options">
                                                <?php foreach ($available_sizes as $size_id):
                                                    $new_key = $product_id . '-' . $size_id . '-' . $current_color_id;
                                                ?>
                                                    <label class="variant-label <?= ($size_id == $current_size_id) ? 'selected' : '' ?>"
                                                        data-new-key="<?= $new_key ?>">
                                                        <input type="radio" name="size_<?= $key ?>" value="<?= $new_key ?>" style="display:none;">
                                                        <?= getSizeName($size_id) ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="variant-title">Màu sắc:</div>
                                            <div class="variant-options">
                                                <?php foreach ($available_colors as $color_id):
                                                    $new_key = $product_id . '-' . $current_size_id . '-' . $color_id;
                                                ?>
                                                    <label class="variant-label <?= ($color_id == $current_color_id) ? 'selected' : '' ?>"
                                                        data-new-key="<?= $new_key ?>">
                                                        <input type="radio" name="color_<?= $key ?>" value="<?= $new_key ?>" style="display:none;">
                                                        <?= getColorName($color_id) ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="item-price align-middle"><?= number_format($item['price']) ?> ₫</td>
                                <td class="align-middle">
                                    <div class="input-group" style="width: 140px;">
                                        <button class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                        <input type="number" class="form-control text-center quantity-input" value="<?= $item['quantity'] ?>" min="1">
                                        <button class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                    </div>
                                </td>
                                <td class="item-total fw-bold align-middle"><?= number_format($item['price'] * $item['quantity']) ?> ₫</td>
                                <td class="align-middle">
                                    <button class="btn btn-danger btn-sm remove-item">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="d-flex justify-content-between align-items-center mb-4">
                                <span>Tổng thanh toán (các sản phẩm đã chọn):</span>
                                <span class="text-danger fs-4 fw-bold selected-total" id="selected-total">0 ₫</span>
                            </h5>
                            <a href="checkout.php" class="btn btn-primary btn-lg w-100 mt-3">Thanh toán</a>
                            <a href="/quanao/php-thuan/index.php" class="btn btn-outline-secondary w-100 mt-2">Tiếp tục mua sắm</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tbody = document.querySelector('#cart-table tbody');
            const selectAll = document.getElementById('select-all');
            const deleteSelectedBtn = document.getElementById('delete-selected');
            const selectedTotalDisplay = document.getElementById('selected-total');

            // Tính tổng tiền các mục đã chọn
            function calculateSelectedTotal() {
                let total = 0;
                tbody.querySelectorAll('.item-checkbox:checked').forEach(cb => {
                    const row = cb.closest('tr');
                    const price = parseInt(row.dataset.price);
                    const qty = parseInt(row.querySelector('.quantity-input').value) || 1;
                    total += price * qty;
                });
                selectedTotalDisplay.textContent = total.toLocaleString('vi-VN') + ' ₫';
                deleteSelectedBtn.disabled = tbody.querySelectorAll('.item-checkbox:checked').length === 0;
            }

            // Cập nhật cột "Thành tiền" của dòng
            function updateRowSubtotal(row, quantity) {
                const price = parseInt(row.dataset.price);
                const newSubTotal = price * quantity;
                row.querySelector('.item-total').textContent = newSubTotal.toLocaleString('vi-VN') + ' ₫';
                row.dataset.quantity = quantity;
                row.querySelector('.quantity-input').value = quantity;
            }

            function updateCart(action, key, quantity = null, newKey = null) {
                const formData = new FormData();
                formData.append('action', action);
                formData.append('id', key);
                if (quantity !== null) formData.append('quantity', quantity);
                if (newKey !== null) formData.append('new_key', newKey);

                fetch('update_cart.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (action === 'change_variant') {
                                location.reload();
                            }
                            if (action === 'remove') {
                                const row = tbody.querySelector(`tr[data-key="${key}"]`);
                                if (row) row.remove();
                                calculateSelectedTotal();
                                if (tbody.querySelectorAll('tr').length === 0) location.reload();
                            }
                            if (action === 'update' && quantity !== null) {
                                const row = tbody.querySelector(`tr[data-key="${key}"]`);
                                if (row) {
                                    updateRowSubtotal(row, quantity);
                                    calculateSelectedTotal();
                                }
                            }
                        } else {
                            alert('Lỗi: ' + data.message);
                        }
                    })
                    .catch(err => console.error(err));
            }

            // Chọn tất cả
            selectAll.addEventListener('change', function() {
                tbody.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
                calculateSelectedTotal();
            });

            // Thay đổi checkbox hoặc số lượng
            tbody.addEventListener('change', function(e) {
                if (e.target.classList.contains('item-checkbox')) {
                    calculateSelectedTotal();
                    const checkedCount = tbody.querySelectorAll('.item-checkbox:checked').length;
                    const totalCount = tbody.querySelectorAll('.item-checkbox').length;
                    selectAll.checked = checkedCount === totalCount && totalCount > 0;
                    selectAll.indeterminate = checkedCount > 0 && checkedCount < totalCount;
                }

                if (e.target.matches('.quantity-input')) {
                    const row = e.target.closest('tr');
                    const key = row.dataset.key;
                    let qty = parseInt(e.target.value);
                    if (isNaN(qty) || qty < 1) {
                        qty = 1;
                        e.target.value = 1;
                    }
                    updateRowSubtotal(row, qty);
                    calculateSelectedTotal();
                    updateCart('update', key, qty);
                }
            });

            // Nút +/-
            tbody.addEventListener('click', function(e) {
                if (e.target.matches('.quantity-btn')) {
                    const row = e.target.closest('tr');
                    const key = row.dataset.key;
                    let qty = parseInt(row.querySelector('.quantity-input').value) || 1;
                    if (e.target.dataset.action === 'increase') qty++;
                    else if (e.target.dataset.action === 'decrease' && qty > 1) qty--;
                    updateRowSubtotal(row, qty);
                    calculateSelectedTotal();
                    updateCart('update', key, qty);
                }

                if (e.target.closest('.variant-label')) {
                    const label = e.target.closest('.variant-label');
                    const oldKey = label.closest('tr').dataset.key;
                    const newKey = label.dataset.newKey;
                    if (newKey !== oldKey) {
                        label.parentElement.querySelectorAll('.variant-label').forEach(l => l.classList.remove('selected'));
                        label.classList.add('selected');
                        if (confirm('Đổi sang variant này?')) {
                            updateCart('change_variant', oldKey, null, newKey);
                        }
                    }
                }

                if (e.target.closest('.remove-item')) {
                    if (confirm('Xóa sản phẩm này khỏi giỏ hàng?')) {
                        const key = e.target.closest('tr').dataset.key;
                        updateCart('remove', key);
                    }
                }
            });

            // Xóa nhiều
            deleteSelectedBtn.addEventListener('click', function() {
                const checked = tbody.querySelectorAll('.item-checkbox:checked');
                if (checked.length === 0) return;
                if (confirm(`Xóa ${checked.length} sản phẩm đã chọn?`)) {
                    checked.forEach(cb => updateCart('remove', cb.value));
                }
            });

            calculateSelectedTotal();
        });
    </script>

    <?php require_once '../../../pages/layout/footer.php'; ?>