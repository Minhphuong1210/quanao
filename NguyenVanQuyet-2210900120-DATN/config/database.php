<?php

return [
    'host' => 'localhost',
    'dbname' => 'webquanao',  
    'user' => 'root',
    'pass' => '',  
    'charset' => 'utf8mb4'
];

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $config = include __DIR__ . '/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $this->pdo = new PDO($dsn, $config['user'], $config['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
?>