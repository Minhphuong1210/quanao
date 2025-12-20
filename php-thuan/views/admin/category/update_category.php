<?php
require_once __DIR__ . '/../../../includes/auth.php';
// checkAdmin();

require_once __DIR__ . '/../../../models/category.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit('Thiếu hoặc sai ID');
}

$model = new Category();
$cat = $model->find((int)$_GET['id']);

if (!$cat) {
    exit('Danh mục không tồn tại');
}

$categories = $model->all();
?>

<form id="category-form" method="post" action="../../../controllers/admin/category_controller.php?action=edit">
    <input type="hidden" name="id" value="<?= $cat['id'] ?>">

    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">Tên danh mục</label>
        <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;">
    </div>

    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">Slug</label>
        <input type="text" name="slug" value="<?= htmlspecialchars($cat['slug']) ?>" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;">
        <small style="color: #7f8c8d;">Dùng để tạo URL thân thiện</small>
    </div>

    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">Danh mục cha</label>
        <select name="parent_id" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;">
            <option value="">-- Không có (danh mục gốc) --</option>
            <?php
            function display_categories($cats, $parent_id = 0, $prefix = '', $current_parent = null, $exclude_id = null) {
                foreach ($cats as $c) {
                    if ($c['parent_id'] == $parent_id && $c['id'] != $exclude_id) {
                        $selected = ($c['id'] == $current_parent) ? 'selected' : '';
                        echo '<option value="'.$c['id'].'" '.$selected.'>'.$prefix.htmlspecialchars($c['name']).'</option>';
                        display_categories($cats, $c['id'], $prefix.'— ', $current_parent, $exclude_id);
                    }
                }
            }
            display_categories($categories, 0, '', $cat['parent_id'], $cat['id']);
            ?>
        </select>
    </div>

    <div style="margin-bottom: 25px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">Trạng thái</label>
        <select name="active" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;">
            <option value="1" <?= $cat['active'] == 1 ? 'selected' : '' ?>>Hiện</option>
            <option value="0" <?= $cat['active'] == 0 ? 'selected' : '' ?>>Ẩn</option>
        </select>
    </div>

    <div style="text-align: center;">
        <button type="submit" style="padding: 14px 30px; background: #3498db; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;">
            Cập nhật danh mục
        </button>
    </div>
</form>