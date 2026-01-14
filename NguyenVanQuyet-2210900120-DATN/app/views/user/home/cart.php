<?php
include BASE_PATH . '/app/views/user/layout/header.php';
?>

<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">
        Giỏ hàng
    </h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
        <li class="breadcrumb-item active text-white">Giỏ hàng</li>
    </ol>
</div>

<div class="container-fluid py-5">
    <div class="container py-5">

        <?php if (empty($cart)): ?>
            <div class="alert alert-warning text-center py-4">
                <i class="fa fa-shopping-cart fa-3x mb-3 text-warning"></i>
                <h4 class="mb-3">Giỏ hàng của bạn đang trống</h4>
                <p class="mb-4">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
                <a href="<?= BASE_URL ?>/san-pham" class="btn btn-primary btn-lg px-5">
                    <i class="fa fa-shopping-bag me-2"></i> Mua sắm ngay
                </a>
            </div>
        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px">Ảnh</th>
                            <th>Sản phẩm</th>
                            <th style="width: 100px">Đơn giá</th>
                            <th style="width: 140px">Số lượng</th>
                            <th style="width: 120px">Thành tiền</th>
                            <th style="width: 120px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="cart-table-body">
                        <?php
                        $subTotal = 0;
                        foreach ($cart as $key => $item):
                            $itemTotal = $item['price'] * $item['quantity'];
                            $subTotal += $itemTotal;
                        ?>
                            <tr id="cart-item-<?= $key ?>">
                                <!-- Ảnh sản phẩm -->
                                <td>
                                    <img src="<?= BASE_URL ?>/<?= $item['image'] ?>"
                                        width="70"
                                        class="img-thumbnail rounded"
                                        alt="<?= htmlspecialchars($item['name']) ?>">
                                </td>

                                <!-- Thông tin sản phẩm -->
                                <td>
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="<?= BASE_URL ?>/san-pham/<?= $item['slug'] ?? '' ?>"
                                                class="text-decoration-none text-dark">
                                                <?= htmlspecialchars($item['name']) ?>
                                            </a>
                                        </h6>
                                        <div class="text-muted small">
                                            <div class="mb-1">
                                                <span class="me-3">
                                                    <i class="fa fa-tag me-1"></i> #<?= $item['product_id'] ?>
                                                </span>
                                                <?php if (isset($item['name_size'])): ?>
                                                    <span class="me-3">
                                                        <i class="fa fa-expand me-1"></i> Size: <?= $item['name_size'] ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (isset($item['name_color'])): ?>
                                                    <span>
                                                        <i class="fa fa-palette me-1"></i> Màu: <?= $item['name_color'] ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="stock-info" id="stock-info-<?= $key ?>">
                                                <?php if (isset($item['stock'])): ?>
                                                    <?php if ($item['quantity'] > $item['stock']): ?>
                                                        <div class="text-danger small">
                                                            <i class="fa fa-exclamation-triangle me-1"></i>
                                                            Chỉ còn <?= $item['stock'] ?> sản phẩm
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Đơn giá -->
                                <td>
                                    <div class="fw-bold text-primary item-price"
                                        data-price="<?= $item['price'] ?>"
                                        id="price-<?= $key ?>">
                                        <?= number_format($item['price'], 0, ',', '.') ?> đ
                                    </div>
                                </td>

                                <!-- Số lượng -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="input-group" style="width: 130px;">
                                            <button class="btn btn-outline-secondary btn-sm btn-quantity-minus"
                                                type="button"
                                                data-key="<?= $key ?>"
                                                <?= ($item['quantity'] <= 1) ? 'disabled' : '' ?>>
                                                <i class="fa fa-minus"></i>
                                            </button>

                                            <input type="number"
                                                class="form-control form-control-sm text-center quantity-input"
                                                id="quantity-<?= $key ?>"
                                                value="<?= $item['quantity'] ?>"
                                                min="1"
                                                max="<?= $item['stock'] ?? 99 ?>"
                                                data-key="<?= $key ?>"
                                                data-price="<?= $item['price'] ?>"
                                                style="height: 31px;">

                                            <button class="btn btn-outline-secondary btn-sm btn-quantity-plus"
                                                type="button"
                                                data-key="<?= $key ?>"
                                                <?= (isset($item['stock']) && $item['quantity'] >= $item['stock']) ? 'disabled' : '' ?>>
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <?php if (isset($item['stock'])): ?>
                                        <small class="text-muted d-block mt-1" id="stock-label-<?= $key ?>">
                                            Còn: <span class="stock-count"><?= $item['stock'] ?></span> sp
                                        </small>
                                    <?php endif; ?>
                                </td>

                                <!-- Thành tiền (TÍNH TOÁN ĐÚNG: đơn giá × số lượng) -->
                                <td>
                                    <div class="fw-bold text-danger item-total"
                                        id="item-total-<?= $key ?>"
                                        data-total="<?= $itemTotal ?>">
                                        <?= number_format($itemTotal, 0, ',', '.') ?> đ
                                    </div>
                                </td>

                                <!-- Thao tác -->
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- Nút sửa -->
                                        <button type="button"
                                            class="btn btn-outline-primary btn-sm btn-edit"
                                            data-key="<?= $key ?>"
                                            data-product-id="<?= $item['product_id'] ?>"
                                            data-size-id="<?= $item['size_id'] ?? '' ?>"
                                            data-color-id="<?= $item['color_id'] ?? '' ?>"
                                            title="Chọn lại màu sắc/kích cỡ">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <!-- Nút xóa -->
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm btn-remove"
                                            data-key="<?= $key ?>"
                                            title="Xóa sản phẩm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tổng giỏ hàng và nút hành động -->
            <div class="row mt-5">
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>/san-pham" class="btn btn-outline-primary">
                            <i class="fa fa-arrow-left me-2"></i> Tiếp tục mua sắm
                        </a>
                        <button class="btn btn-outline-danger" id="clear-cart">
                            <i class="fa fa-trash me-2"></i> Xóa toàn bộ giỏ hàng
                        </button>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-light rounded p-4 mt-4 mt-lg-0">
                        <h5 class="mb-4 border-bottom pb-3">Tổng giỏ hàng</h5>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tạm tính</span>
                            <strong id="sub-total"><?= number_format($subTotal, 0, ',', '.') ?> đ</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Phí vận chuyển</span>
                            <span class="text-success">Miễn phí</span>
                        </div>

                        <?php if (isset($discount) && $discount > 0): ?>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Giảm giá</span>
                                <span class="text-danger">-<?= number_format($discount, 0, ',', '.') ?> đ</span>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between border-top pt-3 mb-4">
                            <h5 class="mb-0">Tổng cộng</h5>
                            <h5 class="text-danger mb-0" id="grand-total">
                                <?= number_format($subTotal, 0, ',', '.') ?> đ
                            </h5>
                        </div>

                        <a href="<?= BASE_URL ?>/checkout" class="btn btn-primary btn-lg w-100 py-3">
                            <i class="fa fa-credit-card me-2"></i> Tiến hành thanh toán
                        </a>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<!-- Modal chọn lại màu/size -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn lại màu sắc & kích cỡ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="editKey" name="key">
                    <input type="hidden" id="editProductId" name="product_id">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Màu sắc</label>
                            <select class="form-select" id="editColor" name="color_id">
                                <option value="">Chọn màu</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kích cỡ</label>
                            <select class="form-select" id="editSize" name="size_id">
                                <option value="">Chọn size</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số lượng</label>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button" id="editMinus">
                                <i class="fa fa-minus"></i>
                            </button>
                            <input type="number"
                                class="form-control text-center"
                                id="editQuantity"
                                name="quantity"
                                value="1"
                                min="1">
                            <button class="btn btn-outline-secondary" type="button" id="editPlus">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <div class="form-text" id="stockInfo"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="saveEdit">Lưu thay đổi</button>
            </div>
        </div>
    </div>
</div>

<?php
include BASE_PATH . '/app/views/user/layout/footer.php';
?>

<!-- JavaScript SỬA LỖI TÍNH TOÁN -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Định dạng tiền
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
        }

        // Tính toán và cập nhật toàn bộ
        function updateAllCalculations() {
            let newSubTotal = 0;

            // Duyệt qua tất cả các sản phẩm trong giỏ hàng
            document.querySelectorAll('.quantity-input').forEach(input => {
                const key = input.dataset.key;
                const price = parseFloat(input.dataset.price);
                const quantity = parseInt(input.value) || 1;

                // Tính thành tiền cho từng sản phẩm
                const itemTotal = price * quantity;

                // Cập nhật thành tiền cho từng item
                const itemTotalElement = document.getElementById(`item-total-${key}`);
                if (itemTotalElement) {
                    itemTotalElement.textContent = formatCurrency(itemTotal);
                    itemTotalElement.dataset.total = itemTotal;
                }

                // Cộng dồn vào tổng
                newSubTotal += itemTotal;
            });

            // Cập nhật tổng tạm tính và tổng cộng
            document.getElementById('sub-total').textContent = formatCurrency(newSubTotal);
            document.getElementById('grand-total').textContent = formatCurrency(newSubTotal);

            return newSubTotal;
        }

        // Tăng số lượng
        document.querySelectorAll('.btn-quantity-plus').forEach(btn => {
            btn.addEventListener('click', function() {
                const key = this.dataset.key;
                const input = document.getElementById(`quantity-${key}`);
                const max = parseInt(input.max) || 99;
                const current = parseInt(input.value) || 1;

                if (current < max) {
                    input.value = current + 1;
                    updateCartItem(key, input.value);
                    updateAllCalculations();
                    toggleMinusButton(key, input.value);
                }

                // Disable nút plus nếu đạt max
                if (parseInt(input.value) >= max) {
                    this.disabled = true;
                }
            });
        });

        // Giảm số lượng
        document.querySelectorAll('.btn-quantity-minus').forEach(btn => {
            btn.addEventListener('click', function() {
                const key = this.dataset.key;
                const input = document.getElementById(`quantity-${key}`);
                const current = parseInt(input.value) || 1;

                if (current > 1) {
                    input.value = current - 1;
                    updateCartItem(key, input.value);
                    updateAllCalculations();
                    toggleMinusButton(key, input.value);
                }

                // Enable nút plus nếu đang disabled
                const plusBtn = document.querySelector(`.btn-quantity-plus[data-key="${key}"]`);
                if (plusBtn.disabled) {
                    plusBtn.disabled = false;
                }
            });
        });

        // Thay đổi số lượng trực tiếp trong input
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const key = this.dataset.key;
                const max = parseInt(this.max) || 99;
                let value = parseInt(this.value) || 1;

                // Validate giá trị
                if (value < 1) value = 1;
                if (value > max) value = max;

                this.value = value;
                updateCartItem(key, value);
                updateAllCalculations();
                toggleMinusButton(key, value);

                // Cập nhật trạng thái nút plus
                const plusBtn = document.querySelector(`.btn-quantity-plus[data-key="${key}"]`);
                if (plusBtn) {
                    plusBtn.disabled = value >= max;
                }
            });
        });

        // Cập nhật trạng thái nút minus
        function toggleMinusButton(key, value) {
            const minusBtn = document.querySelector(`.btn-quantity-minus[data-key="${key}"]`);
            if (minusBtn) {
                minusBtn.disabled = value <= 1;
            }
        }

        // Cập nhật giỏ hàng qua AJAX
        function updateCartItem(key, quantity) {
            // Gửi request AJAX để cập nhật session
            fetch('<?= BASE_URL ?>/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `key=${encodeURIComponent(key)}&quantity=${encodeURIComponent(quantity)}`
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        console.error('Update failed:', data.message);
                        // Nếu cần, có thể revert lại giá trị cũ
                    }
                })
                .catch(error => {
                    console.error('Error updating cart:', error);
                    // Hiển thị thông báo lỗi cho người dùng
                    alert('Có lỗi xảy ra khi cập nhật giỏ hàng. Vui lòng thử lại.');
                });
        }

        // Xóa sản phẩm
        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.addEventListener('click', function() {
                const key = this.dataset.key;
                const itemName = document.querySelector(`#cart-item-${key} h6`).textContent.trim();

                if (confirm(`Bạn có chắc muốn xóa "${itemName}" khỏi giỏ hàng?`)) {
                    fetch('<?= BASE_URL ?>/cart/remove', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: `key=${encodeURIComponent(key)}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Xóa dòng khỏi bảng
                                const row = document.getElementById(`cart-item-${key}`);
                                if (row) row.remove();

                                // Cập nhật lại tổng tiền
                                updateAllCalculations();

                                // Kiểm tra nếu giỏ hàng trống
                                if (document.querySelectorAll('#cart-table-body tr').length === 0) {
                                    location.reload();
                                }
                            } else {
                                alert('Có lỗi xảy ra: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Có lỗi xảy ra khi xóa sản phẩm');
                        });
                }
            });
        });

        // Xóa toàn bộ giỏ hàng
        document.getElementById('clear-cart')?.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
                fetch('<?= BASE_URL ?>/cart/clear', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi xóa giỏ hàng');
                    });
            }
        });

        // Xử lý modal chỉnh sửa
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));

        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const key = this.dataset.key;
                const productId = this.dataset.productId;
                const sizeId = this.dataset.sizeId;
                const colorId = this.dataset.colorId;
                const quantity = document.getElementById(`quantity-${key}`).value;

                // Lưu thông tin vào modal
                document.getElementById('editKey').value = key;
                document.getElementById('editProductId').value = productId;
                document.getElementById('editQuantity').value = quantity;

                // Load màu sắc và kích cỡ
                loadProductVariants(productId, colorId, sizeId);

                // Hiển thị modal
                editModal.show();
            });
        });

        // Khởi tạo trạng thái nút minus ban đầu
        document.querySelectorAll('.quantity-input').forEach(input => {
            toggleMinusButton(input.dataset.key, input.value);
        });

        // DEBUG: Kiểm tra tính toán
        console.log('Initial calculation check:');
        document.querySelectorAll('.quantity-input').forEach(input => {
            const key = input.dataset.key;
            const price = parseFloat(input.dataset.price);
            const quantity = parseInt(input.value) || 1;
            const calculatedTotal = price * quantity;
            const displayedTotal = document.getElementById(`item-total-${key}`)?.textContent;

            console.log(`Item ${key}: ${price} x ${quantity} = ${calculatedTotal}, Displayed: ${displayedTotal}`);
        });
    });
</script>

<style>
    .quantity-input::-webkit-inner-spin-button,
    .quantity-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .quantity-input {
        -moz-appearance: textfield;
    }

    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }

    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }

    .btn-edit:hover {
        background-color: #0d6efd;
        color: white;
    }

    .btn-remove:hover {
        background-color: #dc3545;
        color: white;
    }

    #clear-cart:hover {
        background-color: #dc3545;
        color: white;
        border-color: #dc3545;
    }

    .img-thumbnail {
        padding: 0;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }

    .input-group .btn {
        padding: 0.25rem 0.75rem;
    }

    /* Debug border for testing */
    .debug-border {
        border: 1px solid red !important;
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.9rem;
        }

        .table th,
        .table td {
            padding: 0.5rem;
        }

        .input-group {
            width: 110px !important;
        }
    }
</style>