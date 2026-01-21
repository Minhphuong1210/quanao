<?php
require_once __DIR__ . '/../../config/database.php';
require_once BASE_PATH . '/app/enums/OrderStatus.php';
require_once BASE_PATH . '/app/enums/PaymentStatus.php';
class Order
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function createOrder(
        $name,
        $ma_don_hang,
        $address,
        $user_id,
        $status,
        $payment,
        $total,
        $voucher_id = null,
        $email = null,
        $phone = null,
        $note = null
    ) {
        $sql = "
            INSERT INTO orders
            (
                name,
                ma_don_hang,
                address,
                user_id,
                status,
                payment,
                voucher_id,
                email,
                phone,
                note,
                tong_tien,
                ngay_tao
            )
            VALUES
            (
                :name,
                :ma_don_hang,
                :address,
                :user_id,
                :status,
                :payment,
                :voucher_id,
                :email,
                :phone,
                :note,
                :total,
                NOW()
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':name' => $name,
            ':ma_don_hang' => $ma_don_hang,
            ':address' => $address,
            ':user_id' => $user_id,
            ':status' => $status,
            ':payment' => $payment,
            ':voucher_id' => $voucher_id,
            ':email' => $email,
            ':phone' => $phone,
            ':note' => $note,
            ':total' => $total,
        ]);

        return $this->pdo->lastInsertId();

    }

    public function renMaDonHang($ma_don_hang = 'DH')
    {

        $date = date('Ymd');
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $random = '';
        for ($i = 0; $i < 4; $i++) {
            $random .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $ma_don_hang . $date . $random;
    }

    public function findById($orderId)
    {
        $sql = "SELECT * FROM orders WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrdersByUserPaginate($userId, $limit, $offset)
    {
        $sql = "
            SELECT
                id,
                ma_don_hang,
                status,
                payment,
                tong_tien,
                ngay_tao
            FROM orders
            WHERE user_id = :user_id
            ORDER BY ngay_tao DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countOrdersByUser($userId)
    {
        $sql = "SELECT COUNT(*) FROM orders WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function getByIdAndUser($orderId, $userId)
    {
        $sql = "SELECT * FROM orders
            WHERE id = :id AND user_id = :user_id
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $orderId,
            ':user_id' => $userId,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cancelOrder($orderId, $userId)
    {
        $sql = "
        UPDATE orders
        SET status = :status
        WHERE id = :id
          AND user_id = :user_id
          AND status = :pending
    ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':status' => OrderStatus::CANCELLED,
            ':id' => $orderId,
            ':user_id' => $userId,
            ':pending' => OrderStatus::PENDING,
        ]);
    }

// tạo đơn hàng của admin xem đươc hết

    public function getOrder()
    {
        $sql = "
        SELECT
            id,
            ma_don_hang,
            status,
            payment,
            tong_tien,
            ngay_tao
        FROM orders
        ORDER BY ngay_tao DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePaymentStatus($orderId, $payment)
    {
        $sql = "
            UPDATE orders
            SET payment = :payment
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':payment' => $payment,
            ':id' => $orderId,
        ]);
    }
// tổng đơn 
    public function getSummary()
{
    $sql = "
        SELECT 
            COUNT(*) AS total_orders,
            COALESCE(SUM(tong_tien), 0) AS total_revenue
        FROM orders
    ";
    return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
}
// thống kê theo trạng thái biểu đồ tròn 
public function getOrderByStatus()
{
    $sql = "
        SELECT status, COUNT(*) AS total
        FROM orders
        GROUP BY status
    ";
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// doanh thu theo ngày 
public function getRevenueByDate()
{
    $sql = "
        SELECT 
            DATE(ngay_tao) AS order_date,
            SUM(tong_tien) AS revenue
        FROM orders
        GROUP BY DATE(ngay_tao)
        ORDER BY order_date ASC
    ";
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}


}
