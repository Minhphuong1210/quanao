<?php
require_once __DIR__ . '/../../config/database.php';

class Product
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    // ==================== LẤY DANH SÁCH SẢN PHẨM ====================

    // Lấy tất cả sản phẩm (cho admin, bao gồm inactive)
    public function getAll($activeOnly = false)
    {
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       ncc.name as nha_cung_cap_name 
                FROM products p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id";

        if ($activeOnly) {
            $sql .= " WHERE p.active = 1";
        }
        $sql .= " ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Sản phẩm trang chủ (frontend)
    public function getHomeProducts()
    {
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       ncc.name as nha_cung_cap_name 
                FROM products p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
                WHERE p.hien_trang_chu = 1 AND p.active = 1 
                ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Sản phẩm banner
    public function getActiveBanner()
    {
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       ncc.name as nha_cung_cap_name 
                FROM products p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
                WHERE p.active = 1 AND p.san_pham_hien_nhu_baner = 1 
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Sản phẩm nổi bật
    public function getFeaturedProducts()
    {
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       ncc.name as nha_cung_cap_name 
                FROM products p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
                WHERE p.san_pham_noi_bat = 1 AND p.active = 1 
                ORDER BY p.id DESC LIMIT 8";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm active với phân trang
    public function getActive($page = null, $limit = null)
    {
        if ($page !== null && $limit !== null) {
            $offset = ($page - 1) * $limit;

            $sql = "SELECT p.*, 
                           c.name as category_name, 
                           ncc.name as nha_cung_cap_name 
                    FROM products p
                    LEFT JOIN category c ON p.category_id = c.id
                    LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
                    WHERE p.active = 1 
                    LIMIT :limit OFFSET :offset";

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

        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       ncc.name as nha_cung_cap_name 
                FROM products p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
                WHERE p.active = 1";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm theo category slug
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
        $stmt = $this->conn->prepare("SELECT p.*, 
                                               c.name as category_name, 
                                               ncc.name as nha_cung_cap_name 
                                        FROM products p
                                        LEFT JOIN category c ON p.category_id = c.id
                                        LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
                                        WHERE p.category_id = :categoryId AND p.active = 1 
                                        ORDER BY p.id DESC 
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

    // Lấy sản phẩm theo nhà cung cấp
    public function getByNhaCungCap($nhaCungCapId, $page = 1, $limit = 9)
    {
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;

        // Lấy sản phẩm
        $stmt = $this->conn->prepare("
            SELECT p.*, 
                   c.name as category_name, 
                   ncc.name as nha_cung_cap_name 
            FROM products p
            LEFT JOIN category c ON p.category_id = c.id
            LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
            WHERE p.nha_cung_cap_id = :id AND p.active = 1
            ORDER BY p.id DESC
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
            WHERE nha_cung_cap_id = :id AND active = 1
        ");

        $countStmt->bindValue(':id', $nhaCungCapId, PDO::PARAM_INT);
        $countStmt->execute();
        $total = (int)$countStmt->fetchColumn();

        return [
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => (int)ceil($total / $limit)
        ];
    }

    // Lấy sản phẩm theo màu sắc slug
    public function getByColorSlug($slug, $page = 1, $limit = 9)
    {
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
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
                'total' => 0,
                'page' => $page,
                'limit' => $limit,
                'pages' => 0
            ];
        }

        // Lấy danh sách
        $sql = "
            SELECT DISTINCT p.*, 
                   cat.name as category_name, 
                   ncc.name as nha_cung_cap_name
            FROM products p
            LEFT JOIN category cat ON p.category_id = cat.id
            LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
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
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => (int)ceil($total / $limit)
        ];
    }

    // Lấy sản phẩm theo slug
    public function getBySlug($slug)
    {
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       ncc.name as nha_cung_cap_name 
                FROM products p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
                WHERE p.slug = :slug AND p.active = 1 LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==================== CRUD SẢN PHẨM ====================

    // Lấy 1 sản phẩm theo ID
    public function find($id)
    {
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       ncc.name as nha_cung_cap_name 
                FROM products p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
                WHERE p.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findBySlug($slug)
    {
        // Debug để biết chính xác slug nhận được
        error_log("findBySlug called | slug received: '" . $slug . "' | length: " . strlen($slug) . " | hex: " . bin2hex($slug));

        $sql = "
        SELECT p.*, 
               c.name as category_name, 
               ncc.name as nha_cung_cap_name 
        FROM products p
        LEFT JOIN category c ON p.category_id = c.id
        LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
        WHERE p.slug = :slug
    ";

        // KHÔNG thêm LIMIT 1 nếu bạn muốn an toàn hơn khi slug trùng (dù giờ chỉ còn 1)
        // Nhưng nếu giữ LIMIT 1 thì ổn vì giờ không trùng nữa

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['slug' => $slug]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                error_log("findBySlug SUCCESS | id: " . $product['id'] . " | name: " . $product['name']);
            } else {
                error_log("findBySlug FAILED | no row found for slug: '" . $slug . "'");
            }

            return $product ?: false;
        } catch (PDOException $e) {
            error_log("findBySlug ERROR: " . $e->getMessage());
            return false;
        }
    }

    // Tạo mới sản phẩm - ĐÃ SỬA: XÓA CỘT stock từ products
    public function create($data)
    {
        try {
            $slug = $this->generateSlug($data['name']);

            $sql = "INSERT INTO products (
                name, slug, price, description, content, 
                image, image_array, category_id, nha_cung_cap_id, 
                active, hien_trang_chu, san_pham_noi_bat, view
            ) VALUES (
                :name, :slug, :price, :description, :content, 
                :image, :image_array, :category_id, :nha_cung_cap_id, 
                :active, :hien_trang_chu, :san_pham_noi_bat, :view
            )";

            $stmt = $this->conn->prepare($sql);

            $params = [
                'name' => $data['name'],
                'slug' => $slug,
                'price' => $data['price'] ?? 0,
                'description' => $data['description'] ?? '',
                'content' => $data['content'] ?? '',
                'image' => $data['image'] ?? '',
                'image_array' => $data['image_array'] ?? '',
                'category_id' => $data['category_id'] ?? 0,
                'nha_cung_cap_id' => $data['nha_cung_cap_id'] ?? 0,
                'active' => $data['active'] ?? 1,
                'hien_trang_chu' => $data['hien_trang_chu'] ?? 0,
                'san_pham_noi_bat' => $data['san_pham_noi_bat'] ?? 0,
                'view' => 0
            ];

            $stmt->execute($params);

            // Trả về ID sản phẩm vừa tạo
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Product create error: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật sản phẩm - ĐÃ SỬA: XÓA CỘT stock từ products
    public function update($id, $data)
    {
        try {
            $slug = $this->generateSlug($data['name']);

            $sql = "UPDATE products SET 
                name = :name, 
                slug = :slug, 
                price = :price, 
                description = :description, 
                content = :content, 
                image = :image, 
                image_array = :image_array,
                category_id = :category_id, 
                nha_cung_cap_id = :nha_cung_cap_id,
                active = :active, 
                hien_trang_chu = :hien_trang_chu, 
                san_pham_noi_bat = :san_pham_noi_bat
                WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            $params = [
                'id' => $id,
                'name' => $data['name'],
                'slug' => $slug,
                'price' => $data['price'] ?? 0,
                'description' => $data['description'] ?? '',
                'content' => $data['content'] ?? '',
                'image' => $data['image'] ?? '',
                'image_array' => $data['image_array'] ?? '',
                'category_id' => $data['category_id'] ?? 0,
                'nha_cung_cap_id' => $data['nha_cung_cap_id'] ?? 0,
                'active' => $data['active'] ?? 1,
                'hien_trang_chu' => $data['hien_trang_chu'] ?? 0,
                'san_pham_noi_bat' => $data['san_pham_noi_bat'] ?? 0
            ];

            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Product update error: " . $e->getMessage());
            return false;
        }
    }

    // Xóa sản phẩm
    public function delete($id)
    {
        try {
            // Xóa tất cả biến thể trước
            $this->deleteAllVariants($id);

            // Xóa sản phẩm
            $sql = "DELETE FROM products WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Product delete error: " . $e->getMessage());
            return false;
        }
    }

    // ==================== CHỈNH SỬA TOÀN DIỆN (EDIT) ====================

    /**
     * Chỉnh sửa sản phẩm với xử lý transaction và biến thể
     * @param int $id ID sản phẩm
     * @param array $data Dữ liệu cần cập nhật
     * @param array $variants Danh sách biến thể
     * @return bool Thành công hay không
     */
    public function edit($id, $data, $variants = [])
    {
        try {
            $this->conn->beginTransaction();

            // 1. Cập nhật thông tin sản phẩm
            $result = $this->update($id, $data);
            if (!$result) {
                throw new Exception("Cập nhật thông tin sản phẩm thất bại");
            }

            // 2. Xử lý biến thể nếu có
            if (!empty($variants)) {
                // Xóa biến thể cũ
                $this->deleteAllVariants($id);

                // Thêm biến thể mới
                foreach ($variants as $variant) {
                    $sizeId = (int)($variant['size_id'] ?? 0);
                    $colorId = (int)($variant['color_id'] ?? 0);
                    $stock = (int)($variant['stock'] ?? 0);

                    if ($sizeId > 0 && $colorId > 0) {
                        $this->addVariant($id, $sizeId, $colorId, $stock);
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Product edit error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy dữ liệu đầy đủ cho form chỉnh sửa
     * @param int $id ID sản phẩm
     * @return array Dữ liệu đầy đủ
     */
    public function getEditData($id)
    {
        try {
            // Lấy thông tin sản phẩm
            $product = $this->find($id);
            if (!$product) {
                return null;
            }

            // Lấy các biến thể
            $variants = $this->getVariants($id);

            // Lấy danh sách category active
            $categories = $this->getAllCategories();

            // Lấy danh sách nhà cung cấp
            $suppliers = $this->getAllSuppliers();

            // Lấy danh sách sizes
            $sizes = $this->getAllSizes();

            // Lấy danh sách colors
            $colors = $this->getAllColors();

            return [
                'product' => $product,
                'variants' => $variants,
                'categories' => $categories,
                'suppliers' => $suppliers,
                'sizes' => $sizes,
                'colors' => $colors
            ];
        } catch (Exception $e) {
            error_log("Get edit data error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Cập nhật ảnh sản phẩm
     * @param int $id ID sản phẩm
     * @param string $imageField Trường ảnh ('image' hoặc 'image_array')
     * @param string $value Giá trị mới
     * @return bool Thành công hay không
     */
    public function updateImage($id, $imageField, $value)
    {
        try {
            $sql = "UPDATE products SET $imageField = :value WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['id' => $id, 'value' => $value]);
        } catch (PDOException $e) {
            error_log("Update image error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật trạng thái sản phẩm
     * @param int $id ID sản phẩm
     * @param int $active Trạng thái (0/1)
     * @return bool Thành công hay không
     */
    public function updateStatus($id, $active)
    {
        try {
            $sql = "UPDATE products SET active = :active WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['id' => $id, 'active' => $active]);
        } catch (PDOException $e) {
            error_log("Update status error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật hiển thị trang chủ
     * @param int $id ID sản phẩm
     * @param int $hienTrangChu Trạng thái (0/1)
     * @return bool Thành công hay không
     */
    public function updateHomeDisplay($id, $hienTrangChu)
    {
        try {
            $sql = "UPDATE products SET hien_trang_chu = :hien_trang_chu WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['id' => $id, 'hien_trang_chu' => $hienTrangChu]);
        } catch (PDOException $e) {
            error_log("Update home display error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật sản phẩm nổi bật
     * @param int $id ID sản phẩm
     * @param int $sanPhamNoiBat Trạng thái (0/1)
     * @return bool Thành công hay không
     */
    public function updateFeatured($id, $sanPhamNoiBat)
    {
        try {
            $sql = "UPDATE products SET san_pham_noi_bat = :san_pham_noi_bat WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['id' => $id, 'san_pham_noi_bat' => $sanPhamNoiBat]);
        } catch (PDOException $e) {
            error_log("Update featured error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra slug có tồn tại không (trừ sản phẩm hiện tại)
     * @param string $slug Slug cần kiểm tra
     * @param int $excludeId ID sản phẩm cần loại trừ
     * @return bool Slug đã tồn tại hay chưa
     */
    public function slugExists($slug, $excludeId = 0)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM products WHERE slug = :slug AND id != :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['slug' => $slug, 'id' => $excludeId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Check slug exists error: " . $e->getMessage());
            return false;
        }
    }

    // ==================== XỬ LÝ BIẾN THỂ (PRODUCT_DETAIL) ====================

    // Thêm biến thể vào product_detail
    public function addVariant($productId, $sizeId, $colorId, $stock = 0)
    {
        try {
            $sql = "INSERT INTO product_detail (product_id, size_id, color_id, stock) 
                    VALUES (:product_id, :size_id, :color_id, :stock)";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                'product_id' => $productId,
                'size_id' => $sizeId,
                'color_id' => $colorId,
                'stock' => $stock
            ]);
        } catch (PDOException $e) {
            error_log("Add variant error: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật stock của biến thể
    public function updateVariantStock($productId, $sizeId, $colorId, $stock)
    {
        try {
            $sql = "UPDATE product_detail SET stock = :stock 
                    WHERE product_id = :product_id 
                    AND size_id = :size_id 
                    AND color_id = :color_id";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                'product_id' => $productId,
                'size_id' => $sizeId,
                'color_id' => $colorId,
                'stock' => $stock
            ]);
        } catch (PDOException $e) {
            error_log("Update variant stock error: " . $e->getMessage());
            return false;
        }
    }

    // Thêm nhiều biến thể cùng lúc
    public function addVariants($productId, $variants)
    {
        try {
            foreach ($variants as $variant) {
                $sizeId = $variant['size_id'] ?? 0;
                $colorId = $variant['color_id'] ?? 0;
                $stock = $variant['stock'] ?? 0;

                if ($sizeId > 0 && $colorId > 0) {
                    // Kiểm tra xem biến thể đã tồn tại chưa
                    $checkSql = "SELECT id FROM product_detail 
                                WHERE product_id = :product_id 
                                AND size_id = :size_id 
                                AND color_id = :color_id";

                    $checkStmt = $this->conn->prepare($checkSql);
                    $checkStmt->execute([
                        'product_id' => $productId,
                        'size_id' => $sizeId,
                        'color_id' => $colorId
                    ]);

                    if ($checkStmt->fetch()) {
                        // Nếu đã tồn tại, cập nhật stock
                        $this->updateVariantStock($productId, $sizeId, $colorId, $stock);
                    } else {
                        // Nếu chưa tồn tại, thêm mới
                        $this->addVariant($productId, $sizeId, $colorId, $stock);
                    }
                }
            }

            return true;
        } catch (PDOException $e) {
            error_log("Add variants error: " . $e->getMessage());
            return false;
        }
    }

    // Lấy tất cả biến thể của sản phẩm
    public function getVariants($productId)
    {
        try {
            $sql = "SELECT pd.*, 
                           s.name as size_name, 
                           c.name as color_name, 
                           c.ma_mau 
                    FROM product_detail pd
                    LEFT JOIN sizes s ON pd.size_id = s.id
                    LEFT JOIN colors c ON pd.color_id = c.id
                    WHERE pd.product_id = :product_id
                    ORDER BY s.name, c.name";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['product_id' => $productId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get variants error: " . $e->getMessage());
            return [];
        }
    }

    // Xóa biến thể
    public function deleteVariant($variantId)
    {
        try {
            $sql = "DELETE FROM product_detail WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['id' => $variantId]);
        } catch (PDOException $e) {
            error_log("Delete variant error: " . $e->getMessage());
            return false;
        }
    }

    // Xóa tất cả biến thể của sản phẩm
    public function deleteAllVariants($productId)
    {
        try {
            $sql = "DELETE FROM product_detail WHERE product_id = :product_id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['product_id' => $productId]);
        } catch (PDOException $e) {
            error_log("Delete all variants error: " . $e->getMessage());
            return false;
        }
    }

    // Tính tổng stock từ tất cả biến thể
    public function getTotalStock($productId)
    {
        try {
            $sql = "SELECT SUM(stock) as total_stock 
                    FROM product_detail 
                    WHERE product_id = :product_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['product_id' => $productId]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_stock'] ?? 0;
        } catch (PDOException $e) {
            error_log("Get total stock error: " . $e->getMessage());
            return 0;
        }
    }

    // Kiểm tra biến thể có tồn tại không
    public function variantExists($productId, $sizeId, $colorId)
    {
        try {
            $sql = "SELECT id FROM product_detail 
                    WHERE product_id = :product_id 
                    AND size_id = :size_id 
                    AND color_id = :color_id 
                    LIMIT 1";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'product_id' => $productId,
                'size_id' => $sizeId,
                'color_id' => $colorId
            ]);

            return (bool) $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Check variant exists error: " . $e->getMessage());
            return false;
        }
    }

    // ==================== CÁC PHƯƠNG THỨC HỖ TRỢ ====================

    // Helper: Tạo slug từ name
    public function generateSlug($name)
    {
        $slug = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);
        $slug = strtolower(trim($slug));
        $slug = preg_replace('/\s+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);

        return $slug;
    }

    /**
     * Lấy danh sách category active
     * @return array Danh sách category
     */
    private function getAllCategories()
    {
        try {
            $sql = "SELECT id, name FROM category WHERE active = 1 ORDER BY name";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get categories error: " . $e->getMessage());
            return [];
        }
    }

    // Đếm tổng sản phẩm
    public function getCount()
    {
        $sql = "SELECT COUNT(*) as total FROM products";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Đếm sản phẩm theo danh mục
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

    // Lưu lượt xem
    public function saveView($id, $currentView)
    {
        $sql = "UPDATE products SET view = :view WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'view' => $currentView
        ]);
    }

    // Lấy sản phẩm liên quan (cùng category)
    public function getRelatedProducts($productId, $categoryId, $limit = 4)
    {
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       ncc.name as nha_cung_cap_name 
                FROM products p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
                WHERE p.category_id = :categoryId 
                AND p.id != :productId 
                AND p.active = 1
                ORDER BY p.id DESC 
                LIMIT :limit";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSizes()
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, name, slug FROM sizes WHERE active = 1 ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get sizes error: " . $e->getMessage());
            return [];
        }
    }

    public function getAllColors()
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, name, ma_mau, slug FROM colors WHERE active = 1 ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get colors error: " . $e->getMessage());
            return [];
        }
    }

    public function getAllSuppliers()
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, name, vi_tri FROM nha_cung_cap ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get suppliers error: " . $e->getMessage());
            return [];
        }
    }

    // Tìm kiếm sản phẩm
    public function search($keyword, $page = 1, $limit = 9)
    {
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;

        $keyword = "%" . $keyword . "%";

        // Đếm tổng
        $countSql = "SELECT COUNT(*) as total 
                     FROM products p
                     LEFT JOIN category c ON p.category_id = c.id
                     LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
                     WHERE (p.name LIKE :keyword 
                            OR p.description LIKE :keyword 
                            OR c.name LIKE :keyword 
                            OR ncc.name LIKE :keyword)
                     AND p.active = 1";

        $stmt = $this->conn->prepare($countSql);
        $stmt->execute(['keyword' => $keyword]);
        $total = (int)$stmt->fetchColumn();

        // Lấy sản phẩm
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       ncc.name as nha_cung_cap_name 
                FROM products p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN nha_cung_cap ncc ON p.nha_cung_cap_id = ncc.id
                WHERE (p.name LIKE :keyword 
                       OR p.description LIKE :keyword 
                       OR c.name LIKE :keyword 
                       OR ncc.name LIKE :keyword)
                AND p.active = 1
                ORDER BY p.id DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => (int)ceil($total / $limit)
        ];
    }
}
