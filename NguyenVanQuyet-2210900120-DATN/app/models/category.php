<?php
require_once __DIR__ . '/../../config/database.php';

class category
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    // Lấy tất cả category
    public function getAll()
    {
        $sql = "SELECT * FROM category where active =1";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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
