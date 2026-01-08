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

    public function getActive($page = null, $limit = null)
    {
        
        if ($page !== null && $limit !== null) {
            $offset = ($page - 1) * $limit;
    
            $sql = "SELECT * FROM products WHERE active = 1 LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
         
            $total = $this->conn->query("SELECT COUNT(*) as total FROM products WHERE active = 1")
                               ->fetch(PDO::FETCH_ASSOC)['total'];
    
            return [
                'products' => $products,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'pages' => ceil($total / $limit)
            ];
        }
    
     
        $sql = "SELECT * FROM products WHERE active = 1";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Sản phẩm hiển thị trang chủ
    public function getHomeProducts()
    {
        $sql = "SELECT * FROM products 
                WHERE hien_trang_chu = 1 AND active = 1 LIMIT 8";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Sản phẩm hiển thị banner
    public function getActiveBanner()
    {
        $sql = "SELECT * FROM products 
                WHERE active = 1 
                AND san_pham_hien_nhu_baner = 1 LIMIT 8";
 $stmt = $this->conn->prepare($sql);
 $stmt->execute();
return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFeaturedProducts()
    {
        $sql = "SELECT * FROM products 
                WHERE san_pham_noi_bat = 1 AND active = 1 LIMIT 8";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategory($categoryId)
    {
        $sql = "SELECT * FROM products 
                WHERE category_id = :categoryId AND active = 1 LIMIT 8";

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

// đếm các số lượng của danh mục này 
public function countByCategory($categoryId)
{
    $sql = "SELECT COUNT(*) AS total 
            FROM products 
            WHERE category_id = :categoryId AND active = 1";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['categoryId' => $categoryId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

}
