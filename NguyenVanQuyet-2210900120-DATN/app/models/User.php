<?php
require_once __DIR__ . '/../../config/database.php';

class User
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function checkMatKhau($tel, $plainPassword)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM users WHERE tel = :tel LIMIT 1"
        );
        $stmt->execute(['tel' => $tel]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Nếu có user & mật khẩu đúng
        if ($user && password_verify($plainPassword, $user['password'])) {
            return $user; 
        }

        return false; 
    }


    public function hashPassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
      
    }

    public function create($data)
    {
        $sql = "INSERT INTO users (name, tel, address, email, password,active)
                VALUES (:name, :tel, :address, :email, :password,:active)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':name'     => $data['name'],
            ':tel'      => $data['tel'],
            ':address'  => $data['address'],
            ':email'    => $data['email'],
            ':password' => $data['password'],
            ':active' => 1,
        ]);
    }
}
