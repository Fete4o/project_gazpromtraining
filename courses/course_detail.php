<?php
session_start();
require_once '../db/db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Курс не найден.");
}

$course_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Курс не существует.");
}

$course = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($course['title']) ?></title>
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
        <h1><?= htmlspecialchars($course['title']) ?></h1>
        <img src="../assets/img/<?= htmlspecialchars($course['image']) ?>" alt="<?= htmlspecialchars($course['title']) ?>" style="max-width:400px;">
        <p><strong>Описание:</strong><br> <?= nl2br(htmlspecialchars($course['description'])) ?></p>
        <p><strong>Категория:</strong> <?= htmlspecialchars($course['category']) ?></p>
        <p><strong>Длительность:</strong> <?= htmlspecialchars($course['duration']) ?></p>
        <p><strong>Дата начала:</strong> <?= htmlspecialchars($course['start_date']) ?></p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="post" action="tests/start.php">
                <input type="hidden" name="course_id" value="<?= $course_id ?>">
                <button type="submit">Пройти тест</button>
            </form>
        <?php else: ?>
            <p><a href="../user/login.html">Войдите</a>, чтобы пройти тест.</p>
        <?php endif; ?>
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
</body>
</html>
