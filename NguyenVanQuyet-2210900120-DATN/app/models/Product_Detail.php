<?php
class Product_Detail
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /**
     * Lấy tất cả màu sắc có sẵn cho sản phẩm
     * Kết quả: id, name, ma_mau, slug, và số lượng tồn kho
     */
    public function getAvailableColors($product_id)
    {
        $sql = "
        SELECT DISTINCT
            c.id,
            c.name,
            c.ma_mau,
            c.slug,
            MIN(CASE WHEN pd.stock > 0 THEN 1 ELSE 0 END) as in_stock
        FROM product_detail pd
        INNER JOIN colors c ON pd.color_id = c.id
        WHERE pd.product_id = :product_id
        AND c.active = 1
        GROUP BY c.id, c.name, c.ma_mau, c.slug
        ORDER BY c.name ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':product_id' => $product_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả kích cỡ có sẵn cho sản phẩm
     * Có thể lọc theo màu nếu cần
     */
    public function getAvailableSizes($product_id, $color_id = null)
    {
        $sql = "
        SELECT DISTINCT
            s.id,
            s.name,
            s.slug,
            MIN(CASE WHEN pd.stock > 0 THEN 1 ELSE 0 END) as in_stock
        FROM product_detail pd
        INNER JOIN sizes s ON pd.size_id = s.id
        WHERE pd.product_id = :product_id
        ";

        $params = [':product_id' => $product_id];

        if ($color_id !== null) {
            $sql .= " AND pd.color_id = :color_id";
            $params[':color_id'] = $color_id;
        }

        $sql .= " GROUP BY s.id, s.name, s.slug
                  ORDER BY
                    CASE
                        WHEN s.name REGEXP '^[0-9]+$' THEN CAST(s.name AS UNSIGNED)
                        ELSE 99999
                    END,
                    s.name ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin chi tiết tất cả biến thể của sản phẩm
     * Kết quả: màu sắc, kích cỡ, số lượng tồn kho và thông tin ảnh
     */
    public function getAllVariants($product_id)
    {
        $sql = "
        SELECT
            pd.id as variant_id,
            pd.product_id,
            pd.color_id,
            pd.size_id,
            pd.stock,
            c.name as color_name,
            c.ma_mau as color_code,
            c.slug as color_slug,
            s.name as size_name,
            s.slug as size_slug,
            p.image as product_image,
            p.image_array as product_image_array,
            -- Trạng thái tồn kho
            CASE
                WHEN pd.stock > 10 THEN 'in_stock'
                WHEN pd.stock > 0 AND pd.stock <= 10 THEN 'low_stock'
                ELSE 'out_of_stock'
            END as stock_status
        FROM product_detail pd
        LEFT JOIN colors c ON pd.color_id = c.id
        LEFT JOIN sizes s ON pd.size_id = s.id
        LEFT JOIN products p ON pd.product_id = p.id
        WHERE pd.product_id = :product_id
        AND (c.active = 1 OR c.active IS NULL)
        ORDER BY c.name ASC,
            CASE
                WHEN s.name REGEXP '^[0-9]+$' THEN CAST(s.name AS UNSIGNED)
                ELSE 99999
            END,
            s.name ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':product_id' => $product_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra xem biến thể có tồn tại và còn hàng không
     */
    public function checkVariantAvailability($product_id, $color_id, $size_id)
    {
        $sql = "
        SELECT
            pd.id,
            pd.stock,
            CASE
                WHEN pd.stock > 0 THEN 1
                ELSE 0
            END as is_available
        FROM product_detail pd
        WHERE pd.product_id = :product_id
        AND pd.color_id = :color_id
        AND pd.size_id = :size_id
        LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':product_id' => $product_id,
            ':color_id' => $color_id,
            ':size_id' => $size_id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin tồn kho theo nhóm (dùng cho hiển thị bảng tồn kho)
     * Kèm thông tin ảnh sản phẩm
     */
    public function getStockByColorSize($product_id)
    {
        $sql = "
        SELECT
            c.name as color_name,
            c.ma_mau as color_code,
            s.name as size_name,
            pd.stock,
            p.image as product_image,
            p.image_array as product_image_array
        FROM product_detail pd
        INNER JOIN colors c ON pd.color_id = c.id
        INNER JOIN sizes s ON pd.size_id = s.id
        INNER JOIN products p ON pd.product_id = p.id
        WHERE pd.product_id = :product_id
        AND c.active = 1
        ORDER BY c.name ASC,
            CASE
                WHEN s.name REGEXP '^[0-9]+$' THEN CAST(s.name AS UNSIGNED)
                ELSE 99999
            END,
            s.name ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':product_id' => $product_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy kích cỡ và tổng số lượng tồn kho theo từng size (có thể lọc theo màu)
     */
    public function getSizesWithStock($product_id, $color_id = null)
    {
        $sql = "
        SELECT 
            s.id,
            s.name,
            s.slug,
            SUM(pd.stock) as total_stock,
            -- Đếm số màu có sẵn cho size này
            COUNT(DISTINCT c.id) as available_colors
        FROM product_detail pd
        INNER JOIN sizes s ON pd.size_id = s.id
        INNER JOIN colors c ON pd.color_id = c.id
        WHERE pd.product_id = :product_id
        AND pd.stock > 0
        ";

        $params = [':product_id' => $product_id];

        if ($color_id !== null) {
            $sql .= " AND pd.color_id = :color_id";
            $params[':color_id'] = $color_id;
        }

        $sql .= " GROUP BY s.id
              ORDER BY 
                CASE 
                    WHEN s.name REGEXP '^[0-9]+$' THEN CAST(s.name AS UNSIGNED)
                    ELSE 99999
                END,
                s.name ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tổng quan tồn kho của sản phẩm (kèm thông tin ảnh)
     */
    public function getProductStockSummary($product_id)
    {
        $sql = "
        SELECT 
            -- Tổng số lượng tồn kho
            COALESCE(SUM(pd.stock), 0) as total_stock,
            -- Số màu có sẵn
            COUNT(DISTINCT CASE WHEN pd.stock > 0 THEN c.id END) as available_colors,
            -- Số size có sẵn
            COUNT(DISTINCT CASE WHEN pd.stock > 0 THEN s.id END) as available_sizes,
            -- Số variant có sẵn
            COUNT(CASE WHEN pd.stock > 0 THEN pd.id END) as available_variants,
            -- Số variant hết hàng
            COUNT(CASE WHEN pd.stock = 0 THEN pd.id END) as out_of_stock_variants,
            -- Tổng số variant
            COUNT(pd.id) as total_variants,
            -- Thông tin ảnh sản phẩm
            p.image as product_image,
            p.image_array as product_image_array
        FROM product_detail pd
        LEFT JOIN colors c ON pd.color_id = c.id AND c.active = 1
        LEFT JOIN sizes s ON pd.size_id = s.id
        LEFT JOIN products p ON pd.product_id = p.id
        WHERE pd.product_id = :product_id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':product_id' => $product_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy màu sắc và tổng số lượng tồn kho theo từng màu (kèm thông tin ảnh)
     */
    public function getColorsWithStock($product_id)
    {
        $sql = "
        SELECT 
            c.id,
            c.name,
            c.ma_mau,
            c.slug,
            COALESCE(SUM(pd.stock), 0) as total_stock,
            -- Đếm số size có sẵn cho màu này
            COUNT(DISTINCT CASE WHEN pd.stock > 0 THEN s.id END) as available_sizes,
            -- Thông tin ảnh sản phẩm
            p.image as product_image,
            p.image_array as product_image_array
        FROM product_detail pd
        INNER JOIN colors c ON pd.color_id = c.id
        LEFT JOIN sizes s ON pd.size_id = s.id
        LEFT JOIN products p ON pd.product_id = p.id
        WHERE pd.product_id = :product_id
        AND c.active = 1
        GROUP BY c.id, p.image, p.image_array
        ORDER BY c.name ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':product_id' => $product_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin sản phẩm chi tiết với variant (kèm đầy đủ thông tin ảnh)
     */
    public function getProductDetail($product_id, $color_id, $size_id)
    {
        $sql = "
        SELECT 
            pd.*,
            p.name as product_name,
            p.description as product_description,
            p.price as base_price,
            p.image as product_image,
            p.image_array as product_image_array,
            p.slug as product_slug,
            p.sale_price as product_sale_price,
            p.view as product_view,
            p.active as product_active,
            p.hien_trang_chu as show_homepage,
            p.san_pham_noi_bat as featured,
            p.san_pham_hien_nhu_baner as show_as_banner,
            p.nha_cung_cap_id as supplier_id,
            c.name as color_name,
            c.ma_mau as color_code,
            s.name as size_name
        FROM product_detail pd
        INNER JOIN products p ON pd.product_id = p.id
        LEFT JOIN colors c ON pd.color_id = c.id
        LEFT JOIN sizes s ON pd.size_id = s.id
        WHERE pd.product_id = :product_id
        AND pd.color_id = :color_id
        AND pd.size_id = :size_id
        LIMIT 1
        ";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':product_id' => $product_id,
                ':color_id' => $color_id,
                ':size_id' => $size_id
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Product_Detail::getProductDetail Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy tất cả thông tin chi tiết của sản phẩm với variant
     * Bao gồm tất cả thông tin từ bảng products
     */
    public function getFullProductDetail($product_id, $color_id = null, $size_id = null)
    {
        $sql = "
        SELECT 
            pd.*,
            -- Tất cả thông tin từ bảng products
            p.id as product_id,
            p.name as product_name,
            p.price as product_price,
            p.category_id,
            p.image as product_main_image,
            p.image_array as product_images,
            p.description as product_description,
            p.content as product_content,
            p.active as product_active,
            p.slug as product_slug,
            p.view as product_views,
            p.hien_trang_chu as show_on_homepage,
            p.san_pham_noi_bat as is_featured,
            p.san_pham_hien_nhu_baner as show_as_banner,
            p.nha_cung_cap_id as supplier_id,
            -- Thông tin màu sắc
            c.name as color_name,
            c.ma_mau as color_code,
            c.slug as color_slug,
            -- Thông tin kích thước
            s.name as size_name,
            s.slug as size_slug
        FROM product_detail pd
        INNER JOIN products p ON pd.product_id = p.id
        LEFT JOIN colors c ON pd.color_id = c.id
        LEFT JOIN sizes s ON pd.size_id = s.id
        WHERE pd.product_id = :product_id
        ";
        
        $params = [':product_id' => $product_id];
        
        if ($color_id !== null) {
            $sql .= " AND pd.color_id = :color_id";
            $params[':color_id'] = $color_id;
        }
        
        if ($size_id !== null) {
            $sql .= " AND pd.size_id = :size_id";
            $params[':size_id'] = $size_id;
        }
        
        $sql .= " LIMIT 1";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Product_Detail::getFullProductDetail Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy số lượng tồn kho của một màu cụ thể (tổng tất cả size)
     */
    public function getTotalStockByColor($product_id, $color_id)
    {
        $sql = "
        SELECT SUM(stock) as total_stock
        FROM product_detail
        WHERE product_id = :product_id
        AND color_id = :color_id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':product_id' => $product_id,
            ':color_id' => $color_id
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_stock'] ?? 0;
    }

    /**
     * Lấy ID của biến thể dựa trên product_id, color_id, size_id
     */
    public function getVariantId($product_id, $color_id, $size_id)
    {
        $sql = "
        SELECT id
        FROM product_detail
        WHERE product_id = :product_id
        AND color_id = :color_id
        AND size_id = :size_id
        LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':product_id' => $product_id,
            ':color_id' => $color_id,
            ':size_id' => $size_id
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'] ?? null;
    }

    /**
     * Lấy danh sách ảnh sản phẩm từ image_array
     * Trả về mảng các ảnh
     */
    public function getProductImages($product_id, $color_id = null, $size_id = null)
    {
        $sql = "
        SELECT 
            p.image as main_image,
            p.image_array as image_list
        FROM product_detail pd
        INNER JOIN products p ON pd.product_id = p.id
        WHERE pd.product_id = :product_id
        ";
        
        $params = [':product_id' => $product_id];
        
        if ($color_id !== null) {
            $sql .= " AND pd.color_id = :color_id";
            $params[':color_id'] = $color_id;
        }
        
        if ($size_id !== null) {
            $sql .= " AND pd.size_id = :size_id";
            $params[':size_id'] = $size_id;
        }
        
        $sql .= " LIMIT 1";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return ['main_image' => null, 'images' => []];
            }
            
            // Xử lý image_array để trả về mảng các ảnh
            $images = [];
            $main_image = $result['main_image'];
            
            if (!empty($result['image_list'])) {
                // Loại bỏ dấu ngoặc kép và cắt chuỗi
                $image_list = trim($result['image_list'], '"');
                $images = array_map('trim', explode('","', $image_list));
            }
            
            // Thêm ảnh chính vào đầu danh sách nếu có
            if (!empty($main_image)) {
                array_unshift($images, $main_image);
            }
            
            return [
                'main_image' => $main_image,
                'images' => array_unique($images) // Loại bỏ trùng lặp
            ];
            
        } catch (PDOException $e) {
            error_log("Product_Detail::getProductImages Error: " . $e->getMessage());
            return ['main_image' => null, 'images' => []];
        }
    }

    /**
     * Lấy thông tin variant với đầy đủ hình ảnh
     * Phương thức này trả về tất cả thông tin cần thiết cho trang chi tiết
     */
    public function getVariantWithImages($product_id, $color_id, $size_id)
    {
        $variant = $this->getProductDetail($product_id, $color_id, $size_id);
        
        if (!$variant) {
            return null;
        }
        
        // Xử lý image_array thành mảng
        $image_array = $variant['product_image_array'] ?? '';
        $image_list = [];
        
        if (!empty($image_array)) {
            $image_array = trim($image_array, '"');
            $image_list = array_map('trim', explode('","', $image_array));
        }
        
        // Thêm thông tin hình ảnh vào kết quả
        $variant['product_images'] = $image_list;
        $variant['all_images'] = array_unique(array_merge(
            [$variant['product_image']],
            $image_list
        ));
        
        return $variant;
    }
    public function getSubImages($product_id, $color_id = null, $size_id = null)
    {
        $sql = "
        SELECT 
            p.image_array as image_list
        FROM product_detail pd
        INNER JOIN products p ON pd.product_id = p.id
        WHERE pd.product_id = :product_id
        ";
        
        $params = [':product_id' => $product_id];
        
        if ($color_id !== null) {
            $sql .= " AND pd.color_id = :color_id";
            $params[':color_id'] = $color_id;
        }
        
        if ($size_id !== null) {
            $sql .= " AND pd.size_id = :size_id";
            $params[':size_id'] = $size_id;
        }
        
        $sql .= " LIMIT 1";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result || empty($result['image_list'])) {
                return [];
            }
            
            // Xử lý image_array thành mảng các ảnh phụ
            $image_string = trim($result['image_list'], '"');
            
            if (empty($image_string)) {
                return [];
            }
            
            $images = array_map('trim', explode('","', $image_string));
            
            // Lọc các ảnh trống
            return array_filter($images, function($img) {
                return !empty($img);
            });
            
        } catch (PDOException $e) {
            error_log("Product_Detail::getSubImages Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy tất cả ảnh của sản phẩm (ảnh chính + ảnh phụ)
     * @param int $product_id ID sản phẩm
     * @return array ['main_image' => string, 'sub_images' => array]
     */
    public function getAllProductImages($product_id)
    {
        $sql = "
        SELECT 
            p.image as main_image,
            p.image_array as image_list
        FROM products p
        WHERE p.id = :product_id
        LIMIT 1
        ";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':product_id' => $product_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return [
                    'main_image' => null,
                    'sub_images' => []
                ];
            }
            
            // Xử lý ảnh chính
            $main_image = $result['main_image'];
            
            // Xử lý ảnh phụ từ image_array
            $sub_images = [];
            if (!empty($result['image_list'])) {
                $image_string = trim($result['image_list'], '"');
                if (!empty($image_string)) {
                    $sub_images = array_map('trim', explode('","', $image_string));
                    $sub_images = array_filter($sub_images, function($img) {
                        return !empty($img);
                    });
                }
            }
            
            return [
                'main_image' => $main_image,
                'sub_images' => $sub_images
            ];
            
        } catch (PDOException $e) {
            error_log("Product_Detail::getAllProductImages Error: " . $e->getMessage());
            return [
                'main_image' => null,
                'sub_images' => []
            ];
        }
    }

    /**
     * Lấy ảnh mặc định của sản phẩm (ảnh chính)
     * @param int $product_id ID sản phẩm
     * @return string|null Đường dẫn ảnh chính
     */
    public function getMainImage($product_id)
    {
        $sql = "
        SELECT image as main_image
        FROM products
        WHERE id = :product_id
        LIMIT 1
        ";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':product_id' => $product_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['main_image'] ?? null;
            
        } catch (PDOException $e) {
            error_log("Product_Detail::getMainImage Error: " . $e->getMessage());
            return null;
        }
    }
}