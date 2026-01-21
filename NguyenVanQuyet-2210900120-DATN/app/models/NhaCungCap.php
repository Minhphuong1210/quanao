<?php
require_once __DIR__ . '/../../config/database.php';

class NhaCungCap
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /* ===== READ ===== */
    public function getAll($limit = 20, $offset = 0)
    {
        $sql = "SELECT * 
                FROM nha_cung_cap
                WHERE active =1
                ORDER BY vi_tri ASC, id DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        return $this->conn->query("SELECT COUNT(*) FROM nha_cung_cap where active =1 ")->fetchColumn();
    }

    public function find($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM nha_cung_cap WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* ===== CREATE ===== */
    public function create($data)
    {
        $sql = "INSERT INTO nha_cung_cap (name, vi_tri)
                VALUES (:name, :vi_tri)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name'   => $data['name'],
            ':vi_tri' => $data['vi_tri'] ?? 0,
        ]);
    }

    /* ===== UPDATE ===== */
    public function update($id, $data)
    {
        $sql = "UPDATE nha_cung_cap
                SET name = :name,
                    vi_tri = :vi_tri
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name'   => $data['name'],
            ':vi_tri' => $data['vi_tri'],
            ':id'     => $id,
        ]);
    }

    /* ===== DELETE (soft) ===== */
    public function delete($id)
    {
        $stmt = $this->conn->prepare(
            "UPDATE nha_cung_cap SET active = 0 WHERE id = :id"
        );
        return $stmt->execute(['id' => $id]);
    }
}
