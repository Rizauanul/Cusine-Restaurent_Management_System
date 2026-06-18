<?php
// db.php
$host    = 'localhost';
$db      = 'cuisine_db';
$user    = 'root';
$pass    = ''; // default XAMPP password is empty
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Set response code to Server Error
    http_response_code(500);
    echo "Database connection failed.";
    exit; // Properly halt script execution
}
?>