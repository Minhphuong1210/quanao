<?php
require_once __DIR__ . '/../../config/database.php';
class Product
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }


    // Lấy tất cả sản phẩm (cho admin, bao gồm inactive)
    public function getAll($activeOnly = false)
    {
        $sql = "SELECT * FROM products";
        if ($activeOnly) {
            $sql .= " WHERE active = 1";
        }
        $sql .= " ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm active (frontend)
    //     public function getActive()
    //     {
    //         return $this->getAll(true);  // Tái sử dụng getAll
    //     }

    // Sản phẩm trang chủ (frontend)
    public function getHomeProducts()
    {
        $sql = "SELECT * FROM products 
                WHERE hien_trang_chu = 1 AND active = 1 
                ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Sản phẩm banner (frontend, fix: dùng query đơn giản)
    public function getActiveBanner()
    {
        $sql = "SELECT * FROM products 
                WHERE active = 1 AND san_pham_hien_nhu_baner = 1 
                LIMIT 1";  // Giả sử chỉ 1 banner
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Sản phẩm nổi bật (frontend)
    public function getFeaturedProducts()
    {
        $sql = "SELECT * FROM products 
                WHERE san_pham_noi_bat = 1 AND active = 1 
                ORDER BY id DESC LIMIT 8";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy theo category (frontend/admin)
    //     public function getByCategory($categoryId)
    //     {
    //         $sql = "SELECT * FROM products 
    //                 WHERE category_id = :categoryId AND active = 1 
    //                 ORDER BY id DESC";

    // //     public function getAll()
    // //     {
    // //         $sql = "SELECT * FROM products WHERE active = 1";
    // //         return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    //     }

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


    // // Sản phẩm hiển thị trang chủ
    // public function getHomeProducts()
    // {
    //     $sql = "SELECT * FROM products 
    //             WHERE hien_trang_chu = 1 AND active = 1 LIMIT 8";

    //     return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    // }

    // Sản phẩm hiển thị banner
    //     public function getActiveBanner()
    //     {
    //         $sql = "SELECT * FROM products 
    //                 WHERE active = 1 
    //                 AND san_pham_hien_nhu_baner = 1 LIMIT 8";
    //  $stmt = $this->conn->prepare($sql);
    //  $stmt->execute();
    // return $stmt->fetch(PDO::FETCH_ASSOC);
    //     }

    // public function getFeaturedProducts()
    // {
    //     $sql = "SELECT * FROM products 
    //             WHERE san_pham_noi_bat = 1 AND active = 1 LIMIT 8";

    //     return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function getByCategorySlug($slug, $page = 1, $limit = 8)
    {
        // 1. Lấy category ID từ slug



        $stmt = $this->conn->prepare("SELECT id FROM category WHERE slug = :slug AND active = 1 LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!$category) {
            return [
                'products' => [],
                'total' => 0,
                'page' => $page,
                'limit' => $limit,
                'pages' => 0
            ];
        }

        $categoryId = $category['id'];

        // 2. Lấy tổng số sản phẩm
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM products WHERE category_id = :categoryId AND active = 1");
        $stmt->execute(['categoryId' => $categoryId]);
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 3. Lấy sản phẩm theo phân trang
        $offset = ($page - 1) * $limit;
        $stmt = $this->conn->prepare("SELECT * FROM products 
                                    WHERE category_id = :categoryId AND active = 1 
                                    ORDER BY id DESC 
                                    LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil($total / $limit)
        ];
    }

public function getByNhaCungCap($nhaCungCapId, $page = 1, $limit = 9)
{
    $page   = max(1, (int)$page);
    $limit  = max(1, (int)$limit);
    $offset = ($page - 1) * $limit;

    // Lấy sản phẩm
    $stmt = $this->conn->prepare("
        SELECT *
        FROM products
        WHERE nha_cung_cap_id = :id
          AND active = 1
        ORDER BY id DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':id', $nhaCungCapId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Đếm tổng
    $countStmt = $this->conn->prepare("
        SELECT COUNT(*)
        FROM products
        WHERE nha_cung_cap_id = :id
          AND active = 1
    ");
    $countStmt->bindValue(':id', $nhaCungCapId, PDO::PARAM_INT);
    $countStmt->execute();

    $total = (int)$countStmt->fetchColumn();

    return [
        'products' => $products,
        'total'    => $total,
        'page'     => $page,
        'limit'    => $limit,
        'pages'    => (int)ceil($total / $limit)
    ];
}




public function getByColorSlug($slug, $page = 1, $limit = 9)
{
    $page   = max(1, (int)$page);
    $limit  = max(1, (int)$limit);
    $offset = ($page - 1) * $limit;

    // Đếm tổng
    $countSql = "
        SELECT COUNT(DISTINCT p.id)
        FROM products p
        JOIN product_detail pd ON pd.product_id = p.id
        JOIN colors c ON c.id = pd.color_id
        WHERE c.slug = :slug
          AND c.active = 1
          AND p.active = 1
    ";
    $stmt = $this->conn->prepare($countSql);
    $stmt->execute(['slug' => $slug]);
    $total = (int)$stmt->fetchColumn();

    if ($total === 0) {
        return [
            'products' => [],
            'total'    => 0,
            'page'     => $page,
            'limit'    => $limit,
            'pages'    => 0
        ];
    }

    // Lấy danh sách
    $sql = "
        SELECT DISTINCT p.*
        FROM products p
        JOIN product_detail pd ON pd.product_id = p.id
        JOIN colors c ON c.id = pd.color_id
        WHERE c.slug = :slug
          AND c.active = 1
          AND p.active = 1
        ORDER BY p.id DESC
        LIMIT :limit OFFSET :offset
    ";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return [
        'products' => $stmt->fetchAll(PDO::FETCH_ASSOC),
        'total'    => $total,
        'page'     => $page,
        'limit'    => $limit,
        'pages'    => (int)ceil($total / $limit)
    ];
}

    public function getBySlug($slug)
    {
        $sql = "SELECT * FROM products 
                WHERE slug = :slug AND active = 1 LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ===== PHẦN CRUD CHO ADMIN =====

    // Lấy 1 sản phẩm theo ID (admin)
    public function find($id)
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới sản phẩm (admin)
    public function create($data)
    {
        $sql = "INSERT INTO products (name, slug, price, description, image, category_id, stock, active, hien_trang_chu, san_pham_hien_nhu_baner, san_pham_noi_bat) 
                VALUES (:name, :slug, :price, :description, :image, :category_id, :stock, :active, :hien_trang_chu, :san_pham_hien_nhu_baner, :san_pham_noi_bat)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'name' => $data['name'],
            'slug' => $this->generateSlug($data['name']),  // Tự tạo slug
            'price' => $data['price'],
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? '',
            'category_id' => $data['category_id'],
            'stock' => $data['stock'] ?? 0,
            'active' => $data['active'] ?? 1,
            'hien_trang_chu' => $data['hien_trang_chu'] ?? 0,
            'san_pham_hien_nhu_baner' => $data['san_pham_hien_nhu_baner'] ?? 0,
            'san_pham_noi_bat' => $data['san_pham_noi_bat'] ?? 0
        ]);
    }

    // Cập nhật sản phẩm (admin)
    public function update($id, $data)
    {
        $sql = "UPDATE products SET 
                name = :name, slug = :slug, price = :price, description = :description, 
                image = :image, category_id = :category_id, stock = :stock, 
                active = :active, hien_trang_chu = :hien_trang_chu, 
                san_pham_hien_nhu_baner = :san_pham_hien_nhu_baner, san_pham_noi_bat = :san_pham_noi_bat 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'slug' => $this->generateSlug($data['name']),
            'price' => $data['price'],
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? '',
            'category_id' => $data['category_id'],
            'stock' => $data['stock'] ?? 0,
            'active' => $data['active'] ?? 1,
            'hien_trang_chu' => $data['hien_trang_chu'] ?? 0,
            'san_pham_hien_nhu_baner' => $data['san_pham_hien_nhu_baner'] ?? 0,
            'san_pham_noi_bat' => $data['san_pham_noi_bat'] ?? 0
        ]);
    }

    // Xóa sản phẩm (admin)
    public function delete($id)
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Đếm tổng sản phẩm (cho dashboard)
    public function getCount()
    {
        $sql = "SELECT COUNT(*) FROM products";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Helper: Tạo slug từ name (ví dụ: "Áo Thun" -> "ao-thun")
    private function generateSlug($name)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        return $slug;
    }

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
