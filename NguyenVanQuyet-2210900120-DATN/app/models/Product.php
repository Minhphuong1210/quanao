<?php
require_once __DIR__ . '/../../config/database.php';

class Product
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM products WHERE active = 1";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActive()
    {
        $sql = "SELECT * FROM products WHERE active = 1";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Sản phẩm hiển thị trang chủ
    public function getHomeProducts()
    {
        $sql = "SELECT * FROM products 
                WHERE hien_trang_chu = 1 AND active = 1";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Sản phẩm hiển thị banner
    public function getActiveBanner()
    {
        $sql = "SELECT * FROM products 
                WHERE active = 1 
                AND san_pham_hien_nhu_baner = 1";
 $stmt = $this->conn->prepare($sql);
 $stmt->execute();
return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFeaturedProducts()
    {
        $sql = "SELECT * FROM products 
                WHERE san_pham_noi_bat = 1 AND active = 1";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategory($categoryId)
    {
        $sql = "SELECT * FROM products 
                WHERE category_id = :categoryId AND active = 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['categoryId' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBySlug($slug)
    {
        $sql = "SELECT * FROM products 
                WHERE slug = :slug AND active = 1 LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
