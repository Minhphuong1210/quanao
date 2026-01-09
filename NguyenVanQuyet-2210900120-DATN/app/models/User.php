<?php
require_once __DIR__ . '/../../config/database.php';

class User
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    // Lấy user theo tel
    public function checkMatKhau($tel, $plainPassword)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE tel = :tel LIMIT 1");
        $stmt->execute(['tel' => $tel]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user){
    return true;
}

        // if ($user && password_verify($plainPassword, $user['password'])) {
        //     return $user; // đúng → trả user
        // }
        return false; // sai → false
    }

    // Tạo hash mật khẩu mới
    public function hashPassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }
}
