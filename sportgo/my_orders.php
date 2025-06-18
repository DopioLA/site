<?php
require 'includes/auth.php';
require 'includes/db.php';

// Получаем заказы пользователя
$orders = $pdo->prepare("
    SELECT o.*, e.name as equipment_name, p.address as pickup_address 
    FROM orders o
    JOIN equipment e ON o.equipment_id = e.equipment_id
    JOIN pickup_points p ON o.point_id = p.point_id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$orders->execute([$_SESSION['user_id']]);
$orders = $orders->fetchAll();
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
        <h1>Мои заказы</h1>
        
        <?php if (isset($_GET['order_created'])): ?>
            <div class="alert success">Заказ успешно создан!</div>
        <?php endif; ?>

        <?php if (empty($orders)): ?>
            <p>У вас пока нет заказов</p>
            <a href="catalog.php" class="btn">Перейти в каталог</a>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                <div class="order-card status-<?= $order['status'] ?>">
                    <h3><?= htmlspecialchars($order['equipment_name']) ?></h3>
                    <p><strong>Дата:</strong> 
                        <?= date('d.m.Y H:i', strtotime($order['start_time'])) ?> - 
                        <?= date('d.m.Y H:i', strtotime($order['end_time'])) ?>
                    </p>
                    <p><strong>Пункт выдачи:</strong> <?= htmlspecialchars($order['pickup_address']) ?></p>
                    <p><strong>Сумма:</strong> <?= $order['total_price'] ?> ₽</p>
                    <p><strong>Статус:</strong> 
                        <span class="status-badge"><?= $order['status'] ?></span>
                    </p>
                    <?php if ($order['status'] === 'cancelled' && !empty($order['cancellation_reason'])): ?>
                        <p><strong>Причина отмены:</strong> <?= htmlspecialchars($order['cancellation_reason']) ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>