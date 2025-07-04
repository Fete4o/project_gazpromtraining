<?php
// Включение вывода ошибок (для разработки)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка работы сессий
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Проверка окружения
function checkEnvironment() {
    // Проверка PHP расширений
    $required_extensions = ['pdo_mysql', 'session'];
    foreach ($required_extensions as $ext) {
        if (!extension_loaded($ext)) {
            die("Требуется расширение PHP: $ext");
        }
    }
    
    // Проверка доступности сессий
    if (!is_writable(session_save_path())) {
        die("Сессии не доступны: нет прав на запись в " . session_save_path());
    }
}
checkEnvironment();

// Подключение к БД (пример для MySQL)
function connectDB() {
    $host = getenv('DB_HOST') ?: 'localhost';
    $dbname = getenv('DB_NAME') ?: 'gazprom_training';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASSWORD') ?: '';
    
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Ошибка подключения к БД: " . $e->getMessage());
        return null;
    }
}

// Проверка авторизации
$isLoggedIn = false;
$username = '';
$db = connectDB();

if ($db && isset($_SESSION['user_id'])) {
    $stmt = $db->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if ($user) {
        $isLoggedIn = true;
        $username = $user['username'];
    } else {
        session_destroy(); // Удаляем невалидную сессию
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная | Корпоративный университет</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                        <li><a href="index.php" class="active">Главная</a></li>
                        <li><a href="/project_gazpromtraining/courses/course.php">Курсы</a></li>
                        <li><a href="instructors/instructors.html">Преподаватели</a></li>
                        <li><a href="contact/index.html">Контакты</a></li>
                    </ul>
                </nav>
                <div class="auth-buttons">
                    <?php if ($isLoggedIn): ?>
                        <a href="user/profile.php" class="btn-login">Профиль</a>
                        <a href="user/logout.php" class="btn-register active">Выйти</a>
                    <?php else: ?>
                        <a href="user/login.html" class="btn-login">Войти</a>
                        <a href="user/register.html" class="btn-register active">Регистрация</a>
                    <?php endif; ?>
                </div>
                </button>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Повышение квалификации для сотрудников</h1>
                    <p>Обучайтесь у лучших экспертов в отрасли</p>
                    <div class="hero-buttons">
                        <a href="/project_gazpromtraining/courses/course.php" class="btn-primary">Начать обучение</a>
                        <a href="#features" class="btn-secondary">Подробнее</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="features" id="features">
            <div class="container">
                <h2>Наши преимущества</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-graduation-cap"></i>
                        <h3>Квалифицированные преподаватели</h3>
                        <p>Опытные специалисты с практическим опытом</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-laptop-code"></i>
                        <h3>Современные программы</h3>
                        <p>Актуальные курсы по последним стандартам</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas  fa-certificate"></i>
                        <h3>Сертификаты</h3>
                        <p>Официальные документы о повышении квалификации</p>
                    </div>
                </div>
            </div>
        </section>
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

    <script src="assets/js/main.js"></script>
</body>
</html>
