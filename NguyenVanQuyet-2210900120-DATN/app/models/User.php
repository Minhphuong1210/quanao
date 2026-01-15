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

    public function updateProfile($userId, $data)
    {
        $fields = [];
        $params = [':id' => $userId];
    
        // name
        if (!empty($data['name'])) {
            $fields[] = "name = :name";
            $params[':name'] = $data['name'];
        }
    
        // tel
        if (!empty($data['tel'])) {
            $fields[] = "tel = :tel";
            $params[':tel'] = $data['tel'];
        }
    
        // email
        if (!empty($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = $data['email'];
        }
    
        // address
        if (!empty($data['address'])) {
            $fields[] = "address = :address";
            $params[':address'] = $data['address'];
        }
    
        // password (nếu có nhập)
        if (!empty($data['password'])) {
            $fields[] = "password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
    
        // Không có gì để update
        if (empty($fields)) {
            return false;
        }
    
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
    
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function findById($id)
{
    $stmt = $this->pdo->prepare(
        "SELECT id, name, tel, email, address FROM users WHERE id = :id LIMIT 1"
    );
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


}
