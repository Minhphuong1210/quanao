<?php
require_once __DIR__ . '/../../config/database.php';

class NhaCungCap
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    public function getAll()
    {
        $sql = "SELECT id, name 
                FROM nha_cung_cap
                ORDER BY vi_tri ASC, name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
