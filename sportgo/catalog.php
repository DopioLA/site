<?php
require 'includes/db.php';
require 'includes/auth.php'; // Добавляем подключение auth.php

$equipment = $pdo->query("
    SELECT * FROM equipment 
    WHERE available_quantity > 0
    ORDER BY type, name
")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Каталог | SportGo</title>
    <link rel="stylesheet" href="css/main.css">
    <?php include 'includes/header.php'; ?>
</head>
<body>
    <div class="container">
        <h1>Каталог спортивного инвентаря</h1>
        
        <div class="equipment-grid">
            <?php foreach ($equipment as $item): ?>
            <div class="equipment-card">
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p><strong>Тип:</strong> <?= $item['type'] ?></p>
                <p><strong>Цена:</strong> <?= $item['price_per_hour'] ?> ₽/час</p>
                <p><strong>Доступно:</strong> <?= $item['available_quantity'] ?> шт.</p>
                <?php if (isLoggedIn()): ?>
                    <a href="create_order.php?equipment_id=<?= $item['equipment_id'] ?>" class="btn">Арендовать</a>
                <?php else: ?>
                    <p><a href="login.php">Войдите</a>, чтобы арендовать</p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>