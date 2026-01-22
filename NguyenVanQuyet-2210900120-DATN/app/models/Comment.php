<?php

require_once __DIR__ . '/../../config/database.php';

class Comment
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /* ==================== LẤY BÌNH LUẬN THEO SẢN PHẨM ==================== */

    // Lấy tất cả bình luận của sản phẩm (bao gồm reply, có phân cấp)
    public function getByProductId($productId, $approvedOnly = true)
    {
        $sql = "SELECT c.*, u.name AS user_name 
                FROM comment c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.product_id = :productId";

        if ($approvedOnly) {
            // Giả sử bạn có cột 'approved' hoặc dùng 'active', nếu không có thì bỏ điều kiện này
            // $sql .= " AND c.approved = 1";
        }

        $sql .= " ORDER BY c.parent_id ASC, c.id ASC"; // Bình luận gốc trước, reply theo thứ tự

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':productId' => $productId]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Xây dựng cấu trúc phân cấp (tree)
        return $this->buildCommentTree($comments);
    }

    // Lấy bình luận theo bài viết (nếu dùng cho blog)
    public function getByPostId($postId, $approvedOnly = true)
    {
        $sql = "SELECT c.*, u.name AS user_name 
                FROM comment c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.post_id = :postId";

        $sql .= " ORDER BY c.parent_id ASC, c.id ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':postId' => $postId]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->buildCommentTree($comments);
    }

    /* ==================== CRUD CHO ADMIN & USER ==================== */

    // Tạo bình luận mới (user hoặc admin)
    public function create(array $data): bool
    {
        // ===== VALIDATE =====
        if (empty($data['user_id'])) {
            return false;
        }
    
        if (empty($data['comment'])) {
            return false;
        }
    
        // Nếu là review thì bắt buộc có sao
        if (($data['type'] ?? 'comment') === 'review') {
            if (
                empty($data['star']) ||
                !is_numeric($data['star']) ||
                $data['star'] < 1 ||
                $data['star'] > 5
            ) {
                return false;
            }
        }
    
        $sql = "INSERT INTO comment
                (order_id, product_id, post_id, comment, type, user_id, parent_id, star, image, liked)
                VALUES
                (:order_id, :product_id, :post_id, :comment, :type, :user_id, :parent_id, :star, :image, 0)";
    
        $stmt = $this->conn->prepare($sql);
    
        return $stmt->execute([
            ':order_id'  => $data['order_id']  ?? null,
            ':product_id'=> $data['product_id'] ?? null,
            ':post_id'   => $data['post_id'] ?? null,
            ':comment'   => trim($data['comment']),
            ':type'      => $data['type'] ?? 'comment', // review | comment | reply
            ':user_id'   => $data['user_id'],
            ':parent_id' => $data['parent_id'] ?? null,
            ':star'      => $data['star'] ?? null,
            ':image'     => $data['image'] ?? null
        ]);
    }
    

    // Cập nhật bình luận (chủ yếu admin duyệt hoặc user sửa)
    public function update($id, $data)
    {
        $allowedFields = ['comment', 'star', 'image', 'liked', 'type'];
        $setParts = [];
        $params = [':id' => $id];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $setParts[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }

        if (empty($setParts)) {
            return false;
        }

        $sql = "UPDATE comment SET " . implode(', ', $setParts) . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Xóa bình luận
    public function delete($id)
    {
        // Có thể xóa cả reply hoặc chỉ xóa bình luận gốc
        $sql = "DELETE FROM comment WHERE id = :id OR parent_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Tăng lượt like
    public function like($id)
    {
        $sql = "UPDATE comment SET liked = liked + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Lấy bình luận theo ID
    public function find($id)
    {
        $sql = "SELECT c.*, u.name AS user_name 
                FROM comment c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Đếm số bình luận của sản phẩm
    public function countByProduct($productId)
    {
        $sql = "SELECT COUNT(*) FROM comment WHERE product_id = :productId AND parent_id IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':productId' => $productId]);
        return (int)$stmt->fetchColumn();
    }

    // Tính trung bình sao của sản phẩm
    public function getAverageRating($productId)
    {
        $sql = "SELECT AVG(star) AS avg_rating 
                FROM comment 
                WHERE product_id = :productId AND star IS NOT NULL AND star > 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':productId' => $productId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return round((float)$result['avg_rating'], 1);
    }

    /* ==================== HELPER ==================== */

    // Xây dựng cây bình luận (gốc + reply)
    private function buildCommentTree($comments)
    {
        $tree = [];
        $lookup = [];

        foreach ($comments as $comment) {
            $comment['replies'] = [];
            $lookup[$comment['id']] = $comment;
        }

        foreach ($comments as $comment) {
            if ($comment['parent_id'] === null) {
                $tree[] = &$lookup[$comment['id']];
            } else {
                if (isset($lookup[$comment['parent_id']])) {
                    $lookup[$comment['parent_id']]['replies'][] = &$lookup[$comment['id']];
                }
            }
        }

        return $tree;
    }

    public function isReviewed($productId, $userId)
    {
        $sql = "SELECT id FROM comment
                WHERE product_id = :productId
                  AND user_id = :userId
                  AND parent_id IS NULL
                  AND star IS NOT NULL
                LIMIT 1";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':productId' => $productId,
            ':userId'    => $userId
        ]);
    
        return (bool) $stmt->fetch();
    }
    
    public function getByProduct($productId)
    {
        $sql = "SELECT c.*, u.name AS user_name
                FROM comment c
                JOIN users u ON u.id = c.user_id
                WHERE c.product_id = ?
                ORDER BY c.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$productId]);

        return $stmt->fetchAll();
    }

    public function isUserCommented($productId, $userId)
    {
        $sql = "SELECT id FROM comment
                WHERE product_id = ? 
                AND user_id = ?
                AND star IS NOT NULL
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$productId, $userId]);

        return $stmt->fetch() !== false;
    }

    public function getProductsReviewedByUser($userId)
    {
        $sql = "
            SELECT 
                p.id,
                p.name,
                p.slug,
                MAX(c.id) AS last_comment_id
            FROM comment c
            JOIN products p ON p.id = c.product_id
            WHERE c.user_id = :user_id
            GROUP BY p.id, p.name, p.slug
            ORDER BY last_comment_id DESC
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}