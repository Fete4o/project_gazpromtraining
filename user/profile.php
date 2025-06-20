<?php
session_start();
require_once '../db/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT c.id, c.title, c.description, uc.progress
        FROM courses c
        JOIN user_courses uc ON c.id = uc.course_id
        WHERE uc.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$courses = $stmt->get_result();

$sql_results = "SELECT t.title AS test_title, tr.score, tr.passed_at
                FROM test_results tr
                JOIN tests t ON t.id = tr.test_id
                WHERE tr.user_id = ?";
$stmt2 = $conn->prepare($sql_results);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$results = $stmt2->get_result();

$user_stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
    $username = $user_data['username'];
    $email = $user_data['email'];
} else {
    $username = "Неизвестно";
    $email = "Неизвестно";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль | Газпром Обучение</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-info {
            margin-bottom: 30px;
        }
        .profile-info p {
            margin-bottom: 10px;
        }
        .profile-courses {
            margin-top: 30px;
        }
        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <span>Газпром</span>
                    <span>Обучение</span>
                </div>
                <nav class="navigation">
                    <ul class="nav-links">
                        <li><a href="/project_gazpromtraining/index.php" class="active">Главная</a></li>
                        <li><a href="http://localhost/project_gazpromtraining/courses/course.php">Курсы</a></li>
                        <li><a href="/project_gazpromtraining/instructors/instructors.html">Преподаватели</a></li>
                        <li><a href="/project_gazpromtraining/contact/index.html">Контакты</a></li>
                    </ul>
                </nav>
                <div class="auth-buttons">
                    <a href="profile.php" class="btn-login active">Профиль</a>
                    <a href="logout.php" class="btn-register">Выйти</a>
                </div>
                <button class="menu-toggle" aria-label="Меню">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="profile-container">
                <div class="profile-header">
                    <h1>Личный кабинет</h1>
                </div>
                <div class="profile-info">
                    <h2>Данные пользователя</h2>
                    <p><strong>Имя:</strong> <?= htmlspecialchars($username) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
                </div>
                <div class="profile-courses">
                    <h2>Мои курсы</h2>
                    <ul>
                        <?php if ($courses->num_rows > 0): ?>
                            <?php while ($row = $courses->fetch_assoc()): ?>
                                <li><?= htmlspecialchars($row['title']) ?> — <?= $row['progress'] ?>%</li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li>Вы пока не записаны на курсы.</li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="profile-tests">
                    <h2>Результаты тестов</h2>
                    <ul>
                        <?php if ($results->num_rows > 0): ?>
                            <?php while ($row = $results->fetch_assoc()): ?>
                                <li>
                                    <?= htmlspecialchars($row['test_title']) ?> —
                                    <?= $row['score'] ?> баллов
                                    (<?= date('d.m.Y H:i', strtotime($row['passed_at'])) ?>)
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li>Вы пока не проходили тесты.</li>
                        <?php endif; ?>
                    </ul>
                </div>
                <a href="logout.php" class="logout-btn">Выйти из аккаунта</a>
            </div>
        </div>
    </main>

    <script src="../assets/js/main.js"></script>
</body>
</html>
