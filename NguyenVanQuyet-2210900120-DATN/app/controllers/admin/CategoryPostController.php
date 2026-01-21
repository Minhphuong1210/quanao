<?php

require_once BASE_PATH . '/app/models/CategoryPost.php';
require_once BASE_PATH . '/app/helpers/admin_auth.php';

class CategoryPostController
{
    private CategoryPost $model;

    public function __construct()
    {
        checkAdminLogin();
        $this->model = new CategoryPost();
    }

    /**
     * Danh sách danh mục bài viết
     */
    public function index()
    {
        // lấy tất cả (kể cả inactive cho admin)
        $items = $this->model->getAll(true);

        require BASE_PATH . '/app/views/admin/category_post/index.php';
    }

    /**
     * Thêm danh mục bài viết
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');


            $str = mb_strtolower($name, 'UTF-8'); // Bỏ dấu
            $viet = [
                'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
                'd' => 'đ',
                'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
                'i' => 'í|ì|ỉ|ĩ|ị',
                'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
                'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
                'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            ];
            foreach ($viet as $ascii => $regex) {
                $str = preg_replace("/($regex)/u", $ascii, $str);
            }

            $slug = preg_replace('/[^a-z0-9]+/', '-', $str);

            $slug = $slug . '-' . time();

            if ($name === '') {
                $_SESSION['error'] = 'Tên danh mục không được để trống';
                header('Location: ' . BASE_URL . 'admin/category-post/create');
                exit;
            }

            $this->model->create($name, $slug);

            $_SESSION['success'] = 'Thêm danh mục bài viết thành công';
            header('Location: ' . BASE_URL . 'admin/category-post');
            exit;
        }

        require BASE_PATH . '/app/views/admin/category_post/add.php';
    }

    /**
     * Sửa danh mục bài viết
     */
    public function edit($id)
    {
        $item = $this->model->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Danh mục không tồn tại';
            header('Location: ' . BASE_URL . 'admin/category-post');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name   = trim($_POST['name'] ?? '');
            $slug   = trim($_POST['slug'] ?? '');
            $active = isset($_POST['active']) ? (int)$_POST['active'] : null;

            if ($name === '') {
                $_SESSION['error'] = 'Tên danh mục không được để trống';
                header('Location: ' . BASE_URL . 'admin/category-post/edit/' . $id);
                exit;
            }

            $this->model->update($id, $name, $slug, $active);

            $_SESSION['success'] = 'Cập nhật danh mục bài viết thành công';
            header('Location: ' . BASE_URL . 'admin/category-post');
            exit;
        }

        require BASE_PATH . '/app/views/admin/category_post/edit.php';
    }

    /**
     * Xóa mềm (ẩn danh mục)
     */
    public function delete($id)
    {
        $this->model->delete($id);

        $_SESSION['success'] = 'Ẩn danh mục bài viết thành công';
        header('Location: ' . BASE_URL . 'admin/category-post');
        exit;
    }

    /**
     * Khôi phục danh mục
     */
    public function restore($id)
    {
        $this->model->restore($id);

        $_SESSION['success'] = 'Khôi phục danh mục bài viết thành công';
        header('Location: ' . BASE_URL . 'admin/category-post');
        exit;
    }
}
