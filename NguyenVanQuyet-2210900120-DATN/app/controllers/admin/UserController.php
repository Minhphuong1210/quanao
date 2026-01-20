<?php
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/helpers/admin_auth.php';

class UserController
{
    private User $userModel;

    public function __construct()
    {
        // Chỉ admin mới vào được
        checkAdminLogin();

        $this->userModel = new User();
    }


    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
    
        $pagination = $this->userModel->paginate($page, $perPage);
    
        $users       = $pagination['items'];
        $currentPage = $pagination['current'];
        $lastPage    = $pagination['last_page'];
    
        require BASE_PATH . '/app/views/admin/users/index.php';
    }
    public function create()
    {



        require BASE_PATH . '/app/views/admin/users/add.php';
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }
    
     
        if (
            empty($_POST['name']) ||
            empty($_POST['tel']) ||
            empty($_POST['password'])
        ) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin bắt buộc';
            header('Location: /admin/users/create');
            exit;
        }
    
        $data = [
            'name'     => trim($_POST['name']),
            'tel'      => trim($_POST['tel']),
            'email'    => trim($_POST['email'] ?? ''),
            'address'  => trim($_POST['address'] ?? ''),
            'password' => $this->userModel->hashPassword($_POST['password']),
            'is_admin' => isset($_POST['is_admin']) ? (int)$_POST['is_admin'] : 0,
            'active'   => isset($_POST['active']) ? (int)$_POST['active'] : 1,
        ];
    
        $this->userModel->create($data);
    
        $_SESSION['success'] = 'Thêm user thành công';
        header('Location: /admin/user');
        exit;
    }
    
    public function edit($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            die('User không tồn tại');
        }

        require BASE_PATH . '/app/views/admin/users/edit.php';
    }
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/user');
            exit;
        }

        $data = [
            'name'     => trim($_POST['name'] ?? ''),
            'tel'      => trim($_POST['tel'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'address'  => trim($_POST['address'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'is_admin' => isset($_POST['is_admin']) ? (int)$_POST['is_admin'] : 0,
            'active'   => isset($_POST['active']) ? (int)$_POST['active'] : 1,
        ];

        $this->userModel->updateProfile($id, $data);

        $_SESSION['success'] = 'Cập nhật user thành công';
        header('Location: /admin/user');
        exit;
    }
    public function delete($id)
    {
        if ($id == $_SESSION['admin_id']) {
            die('Không thể xóa chính mình');
        }

        $this->userModel->delete($id);

        $_SESSION['success'] = 'Đã xóa user';
        header('Location: /admin/users');
        exit;
    }
}
