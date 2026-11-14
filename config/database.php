<?php
// Database connection (PDO)
$host = 'localhost';
$db   = 'staycore_db';
$user = 'root';
$pass = ''; // Change as per your XAMPP/WAMP setup
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    die("Database connection failed: " . $e->getMessage());
}
?>