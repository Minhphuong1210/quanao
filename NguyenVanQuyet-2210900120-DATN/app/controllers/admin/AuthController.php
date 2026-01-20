<?php
// require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../models/User.php';


class AuthController {

    public function login(){
        include BASE_PATH . '/app/views/admin/auth/login.php';
    }

    public function postLogin()
    {
        $userModel = new User();
    
        $tel = $_POST['tel'] ?? '';
        $password = $_POST['password'] ?? '';
    
        // Nếu dữ liệu trống
        if (empty($tel) || empty($password)) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }
    
        // Check mật khẩu
        $user = $userModel->checkMatKhau($tel, $password);
    
        if (!$user) {
            $_SESSION['error'] = 'Tel hoặc mật khẩu không đúng!';
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }
    
        
        if ((int)$user['active'] !== 1) {
            $_SESSION['error'] = 'Tài khoản đã bị khóa!';
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }
    
       
        if ((int)$user['is_admin'] !== 1) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang quản trị!';
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }


        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['name'];
        $_SESSION['is_admin'] = 1;
    
        header('Location: ' . BASE_URL . 'admin');
        exit;
    }
    

}