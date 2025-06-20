<?php
session_start();
require_once '../db/db_connect.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email']; // <- добавили email
    header("Location: profile.php");     // <- перенаправляем на PHP, не HTML
    exit();
} else {
    echo "Неверный логин или пароль";
}
?>
