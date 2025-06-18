<?php
require 'config.php';
checkAdminAuth();

// Обработка действий
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status'], $_POST['order_id'])) {
        $order = query("SELECT * FROM orders WHERE id = ?", [$_POST['order_id']])->fetch();
        
        query("UPDATE orders SET status = ? WHERE id = ?", [$_POST['status'], $_POST['order_id']]);
        
        if ($_POST['status'] === 'confirmed') {
            query("UPDATE equipment SET available_quantity = available_quantity - 1 WHERE id = ?", [$order['equipment_id']]);
        } elseif ($_POST['status'] === 'cancelled' && $order['status'] === 'confirmed') {
            query("UPDATE equipment SET available_quantity = available_quantity + 1 WHERE id = ?", [$order['equipment_id']]);
        }
    }
}

// Получение данных
$orders = query("
    SELECT o.*, u.name, e.name as equipment 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN equipment e ON o.equipment_id = e.id
    ORDER BY o.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Админ-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">Админ-панель</span>
            <a href="logout.php" class="btn btn-sm btn-outline-light">Выйти</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4">Заказы</h2>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Клиент</th>
                        <th>Инвентарь</th>
                        <th>Даты</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['name']) ?></td>
                        <td><?= htmlspecialchars($order['equipment']) ?></td>
                        <td><?= date('d.m H:i', strtotime($order['start_time'])) ?> - <?= date('d.m H:i', strtotime($order['end_time'])) ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $order['status'] === 'completed' ? 'success' : 
                                ($order['status'] === 'cancelled' ? 'danger' : 'warning') ?>">
                                <?= $order['status'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($order['status'] === 'new'): ?>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <button name="status" value="confirmed" class="btn btn-sm btn-success">✓</button>
                                </form>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <button name="status" value="cancelled" class="btn btn-sm btn-danger">✗</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>