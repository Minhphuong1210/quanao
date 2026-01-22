<?php

require_once BASE_PATH . '/app/models/Color.php';
require_once BASE_PATH . '/app/helpers/admin_auth.php';

class ColorController
{
    private Color $model;

    public function __construct()
    {
        checkAdminLogin();
        $this->model = new Color();
    }

    public function index()
    {
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $items = $this->model->getAll($limit, $offset);
        $total = $this->model->countAll();

        $lastPage   = (int)ceil($total / $limit);
        $currentPage = $page;

        require BASE_PATH . '/app/views/admin/colors/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->create([
                'name'   => $_POST['name'],
                'ma_mau' => $_POST['ma_mau'],
                'slug'   => $_POST['slug'],
                'active' => (int)($_POST['active'] ?? 1),
            ]);

            $_SESSION['success'] = 'Thêm màu thành công';
            header('Location: ' . BASE_URL . 'admin/colors');
            exit;
        }

        require BASE_PATH . '/app/views/admin/colors/add.php';
    }

    public function edit($id)
    {
        $item = $this->model->find($id);

        if (!$item) {
            header('Location: ' . BASE_URL . 'admin/colors');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->update($id, [
                'name'   => $_POST['name'],
                'ma_mau' => $_POST['ma_mau'],
                'slug'   => $_POST['slug'],
                'active' => (int)($_POST['active'] ?? 1),
            ]);

            $_SESSION['success'] = 'Cập nhật màu thành công';
            header('Location: ' . BASE_URL . 'admin/colors');
            exit;
        }

        require BASE_PATH . '/app/views/admin/colors/edit.php';
    }

    public function delete($id)
    {
        $this->model->delete($id);

        $_SESSION['success'] = 'Xóa màu thành công';
        header('Location: ' . BASE_URL . 'admin/colors');
        exit;
    }
}
