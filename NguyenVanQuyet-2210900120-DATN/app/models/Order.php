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

}
