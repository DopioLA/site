<?php
session_start();
require 'includes/db.php';

// Проверка авторизации
$is_auth = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заголовок страницы | SportGo</title>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="css/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Аренда спортивного инвентаря</h1>
            <nav>
                <?php if ($is_auth): ?>
                    <a href="profile.php">Личный кабинет</a>
                    <a href="logout.php">Выйти</a>
                <?php else: ?>
                    <a href="login.php">Войти</a>
                    <a href="register.php">Регистрация</a>
                <?php endif; ?>
            </nav>
        </header>

        <main>
            <section class="hero">
                <h2>Арендуйте качественный спортивный инвентарь</h2>
                <a href="catalog.php" class="btn">Посмотреть каталог</a>
            </section>
        </main>
    </div>
    <div class="admin-access">
        <a href="/admin/dashboard.php" class="btn btn-admin">
            <i class="fas fa-lock"></i> Перейти в админ-панель
        </a>
    <a href="/admin/login.php" style="display:none;" id="admin-login-link"></a>
</div>
</body>
</html>