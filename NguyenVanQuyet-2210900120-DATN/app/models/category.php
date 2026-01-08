<?php
require_once __DIR__ . '/../../config/database.php';

class category
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }


    // Lấy tất cả category
    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM category where active =1 ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy số lượng category
    public function getCount()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM category");
        return $stmt->fetchColumn();
    }

    // Tìm category theo ID
    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM category WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Tạo category mới
    public function create($name, $description = '')
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO category (name, description) VALUES (?, ?)");
            return $stmt->execute([$name, $description]);
        } catch (PDOException $e) {
            error_log("Lỗi tạo category: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật category
    public function update($id, $name, $description = '')
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE category SET name = ?, description = ? WHERE id = ?");
            return $stmt->execute([$name, $description, $id]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật category: " . $e->getMessage());
            return false;
        }
    }

    // Xóa category
    public function delete($id)
    {
        try {
            // Kiểm tra foreign key (nếu có products liên kết, có thể cần cascade hoặc check trước)
            $stmt = $this->pdo->prepare("DELETE FROM category WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa category: " . $e->getMessage());
            return false;
        }
    }

 
    // Lấy category theo slug
    public function getBySlug($slug)
    {
        $sql = "SELECT * FROM category WHERE slug = :slug AND active = 1 LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
