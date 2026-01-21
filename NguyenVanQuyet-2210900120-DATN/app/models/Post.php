<?php

require_once __DIR__ . '/../../config/database.php';

class Post
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /* ==================== ADMIN ==================== */

    public function getAll()
    {
        $sql = "SELECT p.*, c.name AS category_name
        FROM posts p
        LEFT JOIN category_post c ON c.id = p.category_post_id";

   
            $sql .= " WHERE p.active = 1";
        

        $sql .= " ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM posts WHERE id = :id and active =1 LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $slug = $data['slug'] ?: $this->generateSlug($data['name']);

        $sql = "INSERT INTO posts
                (name, slug, image, description, content, view, category_post_id, active)
                VALUES
                (:name, :slug, :image, :description, :content, 0, :category_post_id, :active)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':slug' => $slug,
            ':image' => $data['image'],
            ':description' => $data['description'],
            ':content' => $data['content'],
            ':category_post_id' => $data['category_post_id'],
            ':active' => (int) $data['active'],
        ]);
    }

    public function update($id, $data)
    {
        $slug = $data['slug'] ?: $this->generateSlug($data['name']);

        $sql = "UPDATE posts SET
                    name = :name,
                    slug = :slug,
                    image = :image,
                    description = :description,
                    content = :content,
                    category_post_id = :category_post_id,
                    active = :active
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':slug' => $slug,
            ':image' => $data['image'],
            ':description' => $data['description'],
            ':content' => $data['content'],
            ':category_post_id' => $data['category_post_id'],
            ':active' => (int) $data['active'],
            ':id' => $id,
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("UPDATE posts SET active = 0 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    private function generateSlug($str)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9\s-]+/', '', $str)));
        $slug = preg_replace('/\s+/', '-', $slug);
        return $slug ?: 'posts-' . time();
    }

    public function paginate($limit, $offset)
    {
        $sql = "SELECT p.*, c.name AS category_name
                FROM posts p
                LEFT JOIN category_post c ON c.id = p.category_post_id
                 WHERE p.active = 1
                ORDER BY p.id DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số bài viết
    public function countAll()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM posts");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

}
