<?php
require_once __DIR__ . '/../../../includes/auth.php';
// checkAdmin();

require_once __DIR__ . '/../../../models/category.php';

$model = new Category();
$categories = $model->all();
?>

<form id="category-form" method="post" action="../../../controllers/admin/category_controller.php?action=create">
    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">Tên danh mục</label>
        <input type="text" name="name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;">
    </div>

    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">Slug</label>
        <input type="text" name="slug" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;">
        <small style="color: #7f8c8d;">Dùng để tạo URL thân thiện (ví dụ: quan-ao-tre-em)</small>
    </div>

    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">Danh mục cha</label>
        <select name="parent_id" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;">
            <option value="">-- Không có (danh mục gốc) --</option>
            <?php
            function display_categories($cats, $parent_id = 0, $prefix = '', $exclude_id = null) {
                foreach ($cats as $c) {
                    if ($c['parent_id'] == $parent_id && $c['id'] != $exclude_id) {
                        echo '<option value="'.$c['id'].'">'.$prefix.htmlspecialchars($c['name']).'</option>';
                        display_categories($cats, $c['id'], $prefix.'— ', $exclude_id);
                    }
                }
            }
            display_categories($categories);
            ?>
        </select>
    </div>

    <div style="margin-bottom: 25px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">Trạng thái</label>
        <select name="active" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;">
            <option value="1" selected>Hiện</option>
            <option value="0">Ẩn</option>
        </select>
    </div>

    <div style="text-align: center;">
        <button type="submit" style="padding: 14px 30px; background: #3498db; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;">
            Thêm danh mục
        </button>
    </div>
</form>