<?php

class Size
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /* =========================================================
     * GET ALL SIZE (dùng filter / dropdown)
     * ========================================================= */
    public function getAll()
    {
        $sql = "
            SELECT id, name, slug
            FROM sizes
            WHERE active = 1
            ORDER BY id ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================================
     * GET SIZE + TOTAL PRODUCT
     * ========================================================= */
    public function getAllWithProductCount()
    {
        $sql = "
            SELECT 
                s.id,
                s.name,
                s.slug,
                COUNT(DISTINCT pd.product_id) AS total
            FROM sizes s
            LEFT JOIN product_detail pd ON pd.size_id = s.id
            WHERE s.active = 1
            GROUP BY s.id
            ORDER BY s.id ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================================
     * COUNT ALL SIZE
     * ========================================================= */
    public function countAll()
    {
        return $this->conn
            ->query("SELECT COUNT(*) FROM sizes WHERE active = 1")
            ->fetchColumn();
    }

    /* =========================================================
     * FIND SIZE BY SLUG
     * ========================================================= */
    public function findBySlug(string $slug)
    {
        $sql = "
            SELECT id, name, slug
            FROM sizes
            WHERE slug = :slug
              AND active = 1
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':slug' => $slug
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =========================================================
     * FIND SIZE BY IDS (array)
     * ========================================================= */
    public function getByIds(array $ids)
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "
            SELECT id, name, slug
            FROM sizes
            WHERE id IN ($placeholders)
              AND active = 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($ids);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================================
     * FIND ONE SIZE BY ID
     * ========================================================= */
    public function find(int $id)
    {
        $sql = "
            SELECT id, name, slug
            FROM sizes
            WHERE id = :id
              AND active = 1
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =========================================================
     * CREATE SIZE
     * ========================================================= */
    public function create(array $data)
    {
        $sql = "
            INSERT INTO sizes (name, slug, active)
            VALUES (:name, :slug, :active)
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':name'   => $data['name'],
            ':slug'   => $data['slug'],
            ':active' => $data['active'] ?? 1,
        ]);
    }

    /* =========================================================
     * UPDATE SIZE
     * ========================================================= */
    public function update(int $id, array $data)
    {
        $sql = "
            UPDATE sizes
            SET name = :name,
                slug = :slug
            WHERE id = :id
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':id'   => $id,
        ]);
    }

    /* =========================================================
     * DELETE SIZE (SOFT DELETE)
     * ========================================================= */
    public function delete(int $id)
    {
        $sql = "UPDATE sizes SET active = 0 WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}
