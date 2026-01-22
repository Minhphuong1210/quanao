<?php
require_once BASE_PATH . '/app/models/NhaCungCap.php';
require_once BASE_PATH . '/app/helpers/admin_auth.php';

class NhaCungCapController
{
    private NhaCungCap $model;

    public function __construct()
    {
        checkAdminLogin();
        $this->model = new NhaCungCap();
    }

    /* ===== LIST ===== */
    public function index()
    {
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $items = $this->model->getAll($limit, $offset);




        $total = $this->model->countAll();
        $pages = ceil($total / $limit);

        require BASE_PATH . '/app/views/admin/nha_cung_cap/index.php';
    }

    /* ===== CREATE ===== */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->create([
                'name'   => trim($_POST['name']),
                'vi_tri' => $_POST['vi_tri'],
                'giam_gia' => $_POST['giam_gia'],
            ]);

            $_SESSION['success'] = 'Thêm nhà cung cấp thành công';
            header('Location: ' . BASE_URL . 'admin/nha_cung_cap');
            exit;
        }

        require BASE_PATH . '/app/views/admin/nha_cung_cap/add.php';
    }

    /* ===== EDIT ===== */
    public function edit($id)
    {
        $item = $this->model->find($id);
        if (!$item) {
            header('Location: ' . BASE_URL . 'admin/nha_cung_cap');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->update($id, [
                'name'   => trim($_POST['name']),
                'vi_tri' => $_POST['vi_tri'],
                'giam_gia' => $_POST['giam_gia'],
            ]);

            $_SESSION['success'] = 'Cập nhật thành công';
            header('Location: ' . BASE_URL . 'admin/nha_cung_cap');
            exit;
        }

        require BASE_PATH . '/app/views/admin/nha_cung_cap/edit.php';
    }

    /* ===== DELETE ===== */
    public function delete($id)
    {
        $this->model->delete($id);
        $_SESSION['success'] = 'Đã xóa';
        header('Location: ' . BASE_URL . 'admin/nha_cung_cap');
        exit;
    }
}
