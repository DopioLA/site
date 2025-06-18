<?php
$current_page = basename($_SERVER['PHP_SELF']);
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'SportGo - Аренда спортивного инвентаря' ?></title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <a href="/index.php" class="logo">
                    <i class="fas fa-basketball-ball"></i> SportGo
                </a>
                
                <nav class="main-nav">
                    <ul>
                        <li>
                            <a href="/catalog.php" class="<?= $current_page == 'catalog.php' ? 'active' : '' ?>">
                                <i class="fas fa-list"></i> Каталог
                            </a>
                        </li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li>
                                <a href="/profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>">
                                    <i class="fas fa-user"></i> Профиль
                                </a>
                            </li>
                            <li>
                                <a href="/my_orders.php" class="<?= $current_page == 'my_orders.php' ? 'active' : '' ?>">
                                    <i class="fas fa-history"></i> Мои заказы
                                </a>
                            </li>
                            <?php if ($is_admin): ?>
                                <li>
                                    <a href="/admin/dashboard.php" class="admin-link">
                                        <i class="fas fa-cog"></i> Админка
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a href="/logout.php" class="logout-link">
                                    <i class="fas fa-sign-out-alt"></i> Выход
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="/login.php" class="<?= $current_page == 'login.php' ? 'active' : '' ?>">
                                    <i class="fas fa-sign-in-alt"></i> Вход
                                </a>
                            </li>
                            <li>
                                <a href="/register.php" class="<?= $current_page == 'register.php' ? 'active' : '' ?>">
                                    <i class="fas fa-user-plus"></i> Регистрация
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                
                <div class="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </header>

    <div class="mobile-menu">
        <nav>
            <ul>
                <!-- Повторяем пункты меню для мобильной версии -->
                <li><a href="/catalog.php"><i class="fas fa-list"></i> Каталог</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/profile.php"><i class="fas fa-user"></i> Профиль</a></li>
                    <li><a href="/my_orders.php"><i class="fas fa-history"></i> Мои заказы</a></li>
                    <?php if ($is_admin): ?>
                        <li><a href="/admin/dashboard.php"><i class="fas fa-cog"></i> Админка</a></li>
                    <?php endif; ?>
                    <li><a href="/logout.php"><i class="fas fa-sign-out-alt"></i> Выход</a></li>
                <?php else: ?>
                    <li><a href="/login.php"><i class="fas fa-sign-in-alt"></i> Вход</a></li>
                    <li><a href="/register.php"><i class="fas fa-user-plus"></i> Регистрация</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <main class="main-content">
        <!-- Контент страницы будет здесь -->