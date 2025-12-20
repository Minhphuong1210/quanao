<?php

require_once __DIR__ . '/../includes/database.php';

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all()
    {
        $sql = "SELECT * FROM category WHERE active != -1 ORDER BY id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM category WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO category (name, slug, parent_id, active)
                VALUES (:name, :slug, :parent_id, :active)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name'      => $data['name'],
            'slug'      => $data['slug'],
            'parent_id' => $data['parent_id'],
            'active'    => $data['active']
        ]);
    }
    public function countAll()
{
    $sql = "SELECT COUNT(*) FROM category WHERE active != -1";
    return (int)$this->db->query($sql)->fetchColumn();
}

public function paginate($offset, $limit)
{
    $stmt = $this->db->prepare("SELECT * FROM category WHERE active != -1 ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->execute([$limit, $offset]);
    return $stmt->fetchAll();
}
    public function update($data)
    {
        $sql = "UPDATE category
                SET name=:name, slug=:slug, parent_id=:parent_id, active=:active
                WHERE id=:id";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':name'      => $data['name'],
            ':slug'      => $data['slug'],
            ':parent_id' => $data['parent_id'],
            ':active'    => $data['active'],
            ':id'        => $data['id']
        ]);
    }

    // Soft delete
    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "UPDATE category SET active = -1 WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }
}
