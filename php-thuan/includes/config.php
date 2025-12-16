
<?php


// ---- SESSION ----
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---- CONFIG ----
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', 'http://localhost/quanao/php-thuan');

// ---- DATABASE ----
try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;dbname=webbanquanao;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('DB connection failed: ' . $e->getMessage());
}
