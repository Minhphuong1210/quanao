<?php

require_once __DIR__ . '/../../config/database.php';

class OrderDetail
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /* ==================== LẤY CHI TIẾT ĐƠN HÀNG ==================== */

    // Lấy tất cả chi tiết của một đơn hàng
    public function getByOrderId($orderId)
    {
        $sql = "SELECT od.*, 
                       c.name AS color_name,
                       s.name AS size_name
                FROM order_detail od
                LEFT JOIN colors c ON od.color_id = c.id
                LEFT JOIN sizes s ON od.size_id = s.id
                WHERE od.order_id = :order_id
                ORDER BY od.id ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết theo ID (admin xem chi tiết một dòng)
    public function find($id)
    {
        $sql = "SELECT od.*, 
                       o.order_code,
                       p.name AS current_product_name
                FROM order_detail od
                LEFT JOIN orders o ON od.order_id = o.id
                LEFT JOIN products p ON od.product_id = p.id
                WHERE od.id = :id LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* ==================== CRUD (CHỦ YẾU CHO ĐẶT HÀNG & ADMIN) ==================== */

    // Thêm chi tiết đơn hàng (khi khách đặt hàng)
    public function create($data)
    {
        $sql = "INSERT INTO order_detail 
                (order_id, product_id, color_id, size_id, image_product, name_product, price_product) 
                VALUES 
                (:order_id, :product_id, :color_id, :size_id, :image_product, :name_product, :price_product)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':order_id'       => $data['order_id'],
            ':product_id'     => $data['product_id'],
            ':color_id'       => $data['color_id'] ?? null,
            ':size_id'        => $data['size_id'] ?? null,
            ':image_product'  => $data['image_product'] ?? '',
            ':name_product'   => $data['name_product'],
            ':price_product'  => $data['price_product']
        ]);
    }

    // Thêm nhiều chi tiết cùng lúc (khi tạo đơn hàng từ giỏ hàng)
    public function createMultiple($orderId, $items)
    {
        // $items là mảng các sản phẩm: [
        //     ['product_id' => 1, 'color_id' => 2, 'size_id' => 3, 'name_product' => '...', 'price_product' => 500000, 'image_product' => '...'],
        //     ...
        // ]

        $sql = "INSERT INTO order_detail 
                (order_id, product_id, color_id, size_id, image_product, name_product, price_product) 
                VALUES ";

        $values = [];
        $params = [':order_id_base' => $orderId];

        foreach ($items as $index => $item) {
            $placeholders = "(:order_id_{$index}, :product_id_{$index}, :color_id_{$index}, :size_id_{$index}, :image_product_{$index}, :name_product_{$index}, :price_product_{$index})";
            $values[] = $placeholders;

            $params[":order_id_{$index}"]      = $orderId;
            $params[":product_id_{$index}"]    = $item['product_id'];
            $params[":color_id_{$index}"]      = $item['color_id'] ?? null;
            $params[":size_id_{$index}"]       = $item['size_id'] ?? null;
            $params[":image_product_{$index}"] = $item['image_product'] ?? '';
            $params[":name_product_{$index}"]  = $item['name_product'];
            $params[":price_product_{$index}"] = $item['price_product'];
        }

        $sql .= implode(', ', $values);

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Xóa chi tiết đơn hàng (admin chỉnh sửa đơn - hiếm dùng)
    public function deleteByOrderId($orderId)
    {
        $sql = "DELETE FROM order_detail WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':order_id' => $orderId]);
    }

    // Xóa một dòng chi tiết
    public function delete($id)
    {
        $sql = "DELETE FROM order_detail WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /* ==================== THỐNG KÊ & BÁO CÁO ==================== */

    // Đếm số lượng sản phẩm đã bán theo product_id
    public function getSoldQuantity($productId)
    {
        $sql = "SELECT SUM(1) FROM order_detail od
                JOIN orders o ON od.order_id = o.id
                WHERE od.product_id = :product_id 
                  AND o.status IN ('completed', 'delivered')"; // chỉ tính đơn hoàn thành

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
        return (int)$stmt->fetchColumn();
    }

    // Top sản phẩm bán chạy
    public function getBestSellers($limit = 10)
    {
        $sql = "SELECT od.product_id, od.name_product, od.image_product, od.price_product, COUNT(*) as sold_count
                FROM order_detail od
                JOIN orders o ON od.order_id = o.id
                WHERE o.status IN ('completed', 'delivered')
                GROUP BY od.product_id
                ORDER BY sold_count DESC
                LIMIT :limit";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}