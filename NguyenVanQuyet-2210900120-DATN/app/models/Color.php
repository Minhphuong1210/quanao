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


    public function countAll()
    {
        return $this->conn->query("SELECT COUNT(*) FROM colors where active =1 ")->fetchColumn();
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

    public function getByIds($ids)
    {
        if (empty($ids)) return [];
    
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
    
        $sql = "
            SELECT id, name, ma_mau, slug
            FROM colors
            WHERE id IN ($placeholders)
            AND active = 1
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($ids);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function find($id)
    {
        $sql = "
            SELECT id, name, ma_mau,slug,active
            FROM colors
            WHERE id = :id AND active = 1
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO colors (name, ma_mau, slug, active)
                VALUES (:name, :ma_mau, :slug, :active)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name'   => $data['name'],
            ':ma_mau' => $data['ma_mau'],
            ':slug'   => $data['slug'],
            ':active' => $data['active'] ?? 1,
        ]);
    }

    /* ===== UPDATE ===== */
    public function update($id, $data)
    {
        $sql = "UPDATE colors
                SET name = :name,
                    ma_mau = :ma_mau,
                    slug = :slug,
                    active = :active
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name'   => $data['name'],
            ':ma_mau' => $data['ma_mau'],
            ':slug'   => $data['slug'],
            ':active' => $data['active'],
            ':id'     => $id,
        ]);
    }

    /* ===== DELETE (soft) ===== */
    public function delete($id)
    {
        $stmt = $this->conn->prepare(
            "UPDATE colors SET active = 0 WHERE id = :id"
        );
        return $stmt->execute(['id' => $id]);
    }

}
