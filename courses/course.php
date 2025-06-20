<?php
session_start();
require_once '../db/db_connect.php';

// Получаем все курсы
$result = $conn->query("SELECT * FROM courses ORDER BY start_date DESC");

if (!$result) {
    die("Ошибка запроса: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Курсы | Газпром Обучение</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <span>Газпром</span><span>Обучение</span>
            </div>
            <nav class="navigation">
                <ul class="nav-links">
                    <li><a href="/project_gazpromtraining/index.php">Главная</a></li>
                    <li><a href="/project_gazpromtraining/courses/course.php" class="active">Курсы</a></li>
                    <li><a href="/project_gazpromtraining/instructors/instructors.html">Преподаватели</a></li>
                    <li><a href="/project_gazpromtraining/contact/index.html">Контакты</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/project_gazpromtraining/user/profile.php" class="btn-login">Профиль</a>
                    <a href="/project_gazpromtraining/user/logout.php" class="btn-register">Выйти</a>
                <?php else: ?>
                    <a href="/project_gazpromtraining/user/login.html" class="btn-login">Вход</a>
                    <a href="/project_gazpromtraining/user/register.html" class="btn-register">Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<main>
    <div class="container">
        <h1>Доступные курсы</h1>
        <div class="courses-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="course-card">
                    <img src="../assets/img/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" style="width:200px; height:auto;">
                    <h2><?= htmlspecialchars($row['title']) ?></h2>
                    <p><?= mb_strimwidth(htmlspecialchars($row['description']), 0, 150, "...") ?></p>
                    <a href="course_detail.php?id=<?= $row['id'] ?>">Подробнее</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Контакты</h3>
                <p>Москва, ул. Наметкина, 16</p>
                <p>+7 (495) 123-45-67</p>
            </div>
            <div class="footer-section">
                <h3>Соцсети</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-vk"></i></a>
                    <a href="#"><i class="fab fa-telegram"></i></a>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2023 Корпоративный университет</p>
        </div>
    </div>
</footer>

<script src="../assets/js/main.js"></script>
</body>
</html>
