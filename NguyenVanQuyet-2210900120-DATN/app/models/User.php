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
        $sql = "INSERT INTO users 
                (name, tel, address, email, password, is_admin, active)
                VALUES 
                (:name, :tel, :address, :email, :password, :is_admin, :active)";
    
        $stmt = $this->pdo->prepare($sql);
    
        return $stmt->execute([
            ':name'     => $data['name'],
            ':tel'      => $data['tel'],
            ':address'  => $data['address'],
            ':email'    => $data['email'],
            ':password' => $data['password'],
            ':is_admin' => $data['is_admin'] ?? 0,
            ':active'   => $data['active'] ?? 1,
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


        if (!empty($data['is_admin'])) {
            $fields[] = "is_admin = :is_admin";
            $params[':is_admin'] = $data['is_admin'];
        }

        if (!empty($data['active'])) {
            $fields[] = "active = :active";
            $params[':active'] = $data['active'];
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
            "SELECT id, name, tel, email, address, is_admin, active FROM users WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function paginate($page = 1, $perPage = 10)
{
    $page = max(1, (int)$page);
    $perPage = max(1, (int)$perPage);
    $offset = ($page - 1) * $perPage;

    // Lấy danh sách user
    $stmt = $this->pdo->prepare(
        "SELECT id, name, tel, email, address
         FROM users
         ORDER BY id DESC
         LIMIT :limit OFFSET :offset"
    );

    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

   
    $total = $this->pdo
        ->query("SELECT COUNT(*) FROM users")
        ->fetchColumn();

    return [
        'items'      => $items,
        'total'      => (int)$total,
        'per_page'   => $perPage,
        'current'    => $page,
        'last_page'  => (int)ceil($total / $perPage),
    ];
}


}
