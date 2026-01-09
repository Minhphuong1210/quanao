<?php

class Color
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /**
     * Lấy toàn bộ màu sắc (dùng cho bộ lọc)
     * bảng colors: id, name, ma_mau, active, slug
     */
    public function getAll()
    {
        $sql = "
            SELECT id, name, ma_mau, slug
            FROM colors
            WHERE active = 1
            ORDER BY id ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * (OPTIONAL) Lấy màu + số sản phẩm
     */
    public function getAllWithProductCount()
    {
        $sql = "
            SELECT 
                c.id,
                c.name,
                c.slug,
                c.ma_mau,
                COUNT(DISTINCT pd.product_id) AS total
            FROM colors c
            LEFT JOIN product_detail pd ON pd.color_id = c.id
            WHERE c.active = 1
            GROUP BY c.id
            ORDER BY c.id ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy color theo slug (dùng kiểm tra tồn tại)
     */
    public function findBySlug(string $slug)
    {
        $sql = "
            SELECT id, name, ma_mau, slug
            FROM colors
            WHERE slug = :slug AND active = 1
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slug]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
