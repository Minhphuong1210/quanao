<?php

require_once __DIR__ . '/../../config/database.php';

class CategoryPost
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /* ==================== LẤY DỮ LIỆU CHO ADMIN & FRONTEND ==================== */

    // Lấy tất cả danh mục bài viết (admin: bao gồm cả inactive)
    public function getAll($includeInactive = true)
    {
        $sql = "SELECT * FROM category_post";
        
        if (!$includeInactive) {
            $sql .= " WHERE active = 1";
        }
        
        $sql .= " ORDER BY id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Chỉ lấy danh mục đang hoạt động (dùng cho frontend)
    public function getActive()
    {
        return $this->getAll(false);
    }

    // Lấy danh mục theo ID
    public function find($id)
    {
        $sql = "SELECT * FROM category_post WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh mục theo slug (dùng cho routing frontend)
    public function getBySlug($slug)
    {
        $sql = "SELECT * FROM category_post WHERE slug = :slug AND active = 1 LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* ==================== CRUD CHO ADMIN ==================== */

    // Tạo danh mục bài viết mới
    public function create($name, $slug = '')
    {
        $slug = $slug ?: $this->generateSlug($name);

        // Tránh trùng slug
        if ($this->slugExists($slug)) {
            $slug = $slug . '-' . time();
        }

        $sql = "INSERT INTO category_post (name, slug, active) 
                VALUES (:name, :slug, 1)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => trim($name),
            ':slug' => $slug
        ]);
    }

    // Cập nhật danh mục bài viết
    public function update($id, $name, $slug = '', $active = null)
    {
        $slug = $slug ?: $this->generateSlug($name);

        // Tránh trùng slug với danh mục khác
        $current = $this->find($id);
        if ($current && $current['slug'] !== $slug && $this->slugExists($slug)) {
            $slug = $slug . '-' . $id;
        }

        $sql = "UPDATE category_post SET name = :name, slug = :slug";
        
        if ($active !== null) {
            $sql .= ", active = :active";
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        
        $params = [
            ':name' => trim($name),
            ':slug' => $slug,
            ':id'   => $id
        ];

        if ($active !== null) {
            $params[':active'] = (int)$active;
        }

        return $stmt->execute($params);
    }

    // Xóa mềm (ẩn danh mục)
    public function delete($id)
    {
        $sql = "UPDATE category_post SET active = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Khôi phục danh mục (nếu cần)
    public function restore($id)
    {
        $sql = "UPDATE category_post SET active = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Đếm tổng số danh mục bài viết
    public function getCount($includeInactive = true)
    {
        $sql = "SELECT COUNT(*) FROM category_post";
        
        if (!$includeInactive) {
            $sql .= " WHERE active = 1";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /* ==================== HELPER ==================== */

    // Tạo slug từ tên danh mục
    private function generateSlug($name)
    {
        // Chuyển về chữ thường, thay khoảng trắng bằng gạch ngang, xóa ký tự đặc biệt
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9\s-]+/', '', $name)));
        $slug = preg_replace('/\s+/', '-', $slug);
        $slug = trim($slug, '-');

        if (empty($slug)) {
            $slug = 'danh-muc-bai-viet-' . time();
        }

        return $slug;
    }

    // Kiểm tra slug đã tồn tại chưa (tránh trùng)
    private function slugExists($slug, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM category_post WHERE slug = :slug";
        if ($excludeId !== null) {
            $sql .= " AND id != :excludeId";
        }

        $stmt = $this->conn->prepare($sql);
        $params = [':slug' => $slug];
        if ($excludeId !== null) {
            $params[':excludeId'] = $excludeId;
        }

        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}