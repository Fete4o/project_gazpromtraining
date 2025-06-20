<?php
// db/db_connect.php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$dbname = getenv('DB_NAME') ?: 'gazprom_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Ошибка подключения к базе данных. Пожалуйста, попробуйте позже.");
}

$conn->set_charset("utf8mb4");
?>