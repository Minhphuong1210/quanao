<?php
require_once BASE_PATH . '/app/models/Size.php';
require_once BASE_PATH . '/app/helpers/admin_auth.php';

class SizeController
{
    private Size $model;

    public function __construct()
    {
        checkAdminLogin();
        $this->model = new Size();
    }

    public function index()
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $items = $this->model->getAll($limit, $offset);
        $total = $this->model->countAll();
        $pages = ceil($total / $limit);

        require BASE_PATH . '/app/views/admin/sizes/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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


            $this->model->create([
                'name'   => $_POST['name'],
                'slug'   => $slug,
                'active' => 1,
            ]);

            header('Location: ' . BASE_URL . 'admin/sizes');
            exit;
        }

        require BASE_PATH . '/app/views/admin/sizes/add.php';
    }

    public function edit($id)
    {
        $item = $this->model->find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->update($id, [
                'name'   => $_POST['name'],
                // 'slug'   => $_POST['slug'],
                // 'active' => (int)$_POST['active'],
            ]);

            header('Location: ' . BASE_URL . 'admin/sizes');
            exit;
        }

        require BASE_PATH . '/app/views/admin/sizes/edit.php';
    }

    public function delete($id)
    {
        $this->model->delete($id);
        header('Location: ' . BASE_URL . 'admin/sizes');
        exit;
    }
}
