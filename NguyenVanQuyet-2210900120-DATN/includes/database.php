<?php
class Database
{
    private static $instance = null;
    private $conn;

    // Ngăn clone và unserialize
    private function __clone() {}
    public function __wakeup() {}

    private function __construct()
    {
        try {
            $this->conn = new PDO(
                'mysql:host=127.0.0.1;dbname=webbanquanao;charset=utf8mb4',
                'root',
                '',
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            die("Lỗi kết nối CSDL: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->conn;
    }
}
?>