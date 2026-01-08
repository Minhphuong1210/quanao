<?php
// app/models/CategoryModel.php
// Model xử lý CRUD cho Category (Danh mục sản phẩm)

class CategoryModel
{
    private $pdo;

    public function __construct()
    {
        // Kết nối DB (giả sử config trong config/database.php hoặc hardcode cho đơn giản)
        $host = 'localhost';
        $dbname = 'quanao_db';  // Tên DB của bạn
        $username = 'root';     // User DB
        $password = '';         // Pass DB (mặc định XAMPP)

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Lỗi kết nối DB: " . $e->getMessage());
        }
    }

    // Lấy tất cả categories
    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy số lượng categories
    public function getCount()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM categories");
        return $stmt->fetchColumn();
    }

    // Tìm category theo ID
    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Tạo category mới
    public function create($name, $description = '')
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
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
            $stmt = $this->pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
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
            $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa category: " . $e->getMessage());
            return false;
        }
    }
}