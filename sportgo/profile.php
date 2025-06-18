<?php
require 'includes/auth.php';
require 'includes/db.php';

// Получаем данные пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Заголовок страницы | SportGo</title>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="css/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Личный кабинет</h1>
        
        <div class="profile-info">
            <h2>Ваши данные</h2>
            <p><strong>ФИО:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
            <p><strong>Телефон:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        </div>
        
        <div class="actions">
            <a href="catalog.php" class="btn">Арендовать инвентарь</a>
            <a href="my_orders.php" class="btn">Мои заказы</a>
        </div>
    </div>
</body>
</html>