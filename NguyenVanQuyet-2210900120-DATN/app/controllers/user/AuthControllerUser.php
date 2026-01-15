<?php
require_once __DIR__ . '/../../models/User.php';

class AuthControllerUser
{

    public function loginUser()
    {
        // Nếu submit form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            require_once BASE_PATH . '/app/models/User.php';
            $userModel = new User();

            $tel = trim($_POST['tel'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validate
            if ($tel === '' || $password === '') {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin';
                include BASE_PATH . '/app/views/user/auth/login.php';
                return;
            }

            // Check mật khẩu + active
            $user = $userModel->checkMatKhau($tel, $password);

            if ($user) {
                // Login thành công
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_tel'] = $user['tel'];
                $_SESSION['user_address'] = $user['address'];
                $_SESSION['user_email'] = $user['email'];
                header('Location: ' . BASE_URL);
                exit;
            }

            // Sai thông tin
            $_SESSION['error'] = 'Số điện thoại hoặc mật khẩu không đúng';
            include BASE_PATH . '/app/views/user/auth/login.php';
            return;
        }

        include BASE_PATH . '/app/views/user/auth/login.php';
    }

    public function register()
    {
        // Nếu submit form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = trim($_POST['name'] ?? '');
            $tel = trim($_POST['tel'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validate
            if ($name === '' || $tel === '' || $address === '' || $email === '' || $password === '') {
                $error = 'Vui lòng nhập đầy đủ thông tin';
                include BASE_PATH . '/app/views/user/auth/register.php';
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email không hợp lệ';
                include BASE_PATH . '/app/views/user/auth/register.php';
                return;
            }

            $userModel = new User();

            $hashPassword = $userModel->hashPassword($password);

            $userModel->create([
                'name' => $name,
                'tel' => $tel,
                'address' => $address,
                'email' => $email,
                'password' => $hashPassword,
            ]);

            header('Location: /loginUser');
            exit;
        }

        include BASE_PATH . '/app/views/user/auth/register.php';
    }

    public function forgotPassword()
    {
        include BASE_PATH . '/app/views/user/auth/forgotpassword.php';
    }

    public function logout()
    {

        session_destroy();

        header('Location: ' . BASE_URL);
        exit;
    }

    public function profile()
    {
        if (!isset($_SESSION['user_logged_in'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

    
        $userModel = new User();

        $user = $userModel->findById($_SESSION['user_id']);

        include BASE_PATH . '/app/views/user/account/profile.php';
    }

    public function updateProfile()
    {
        if (!isset($_SESSION['user_logged_in'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $userModel = new User();

        $data = [
            'name' => $_POST['name'] ?? null,
            'tel' => $_POST['tel'] ?? null,
            'email' => $_POST['email'] ?? null,
            'address' => $_POST['address'] ?? null,
            'password' => $_POST['password'] ?? null,
        ];

        $userModel->updateProfile($_SESSION['user_id'], $data);
        $_SESSION['user_name'] = $data['name'] ?? $_SESSION['user_name'];
        $_SESSION['user_tel'] = $data['tel'] ?? $_SESSION['user_tel'];
        $_SESSION['user_email'] = $data['email'] ?? $_SESSION['user_email'];
        $_SESSION['user_address'] = $data['address'] ?? $_SESSION['user_address'];
        $_SESSION['success'] = 'Cập nhật thông tin thành công!';
        header('Location: ' . BASE_URL . 'account/profile');
        exit;
    }

}
