<?php
require 'includes/auth.php';
require 'includes/db.php';

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Проверка наличия equipment_id
if (!isset($_GET['equipment_id'])) {
    header("Location: catalog.php");
    exit;
}

// Получаем данные об оборудовании
$stmt = $pdo->prepare("SELECT * FROM equipment WHERE equipment_id = ?");
$stmt->execute([$_GET['equipment_id']]);
$equipment = $stmt->fetch();

if (!$equipment) {
    header("Location: catalog.php");
    exit;
}

// Получаем пункты выдачи
$points = $pdo->query("SELECT * FROM pickup_points")->fetchAll();

// Обработка формы
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Валидация данных
    $start_time = strtotime($_POST['start_time']);
    $end_time = strtotime($_POST['end_time']);
    
    if ($start_time >= $end_time) {
        $error = "Время окончания должно быть позже времени начала";
    } else {
        // Расчет стоимости
        $hours = ($end_time - $start_time) / 3600;
        $total_price = round($hours * $equipment['price_per_hour'], 2);
        
        try {
            // Создание заказа
            $stmt = $pdo->prepare("
                INSERT INTO orders 
                (user_id, equipment_id, point_id, start_time, end_time, total_price, payment_method, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'new')
            ");
            $stmt->execute([
                $_SESSION['user_id'],
                $equipment['equipment_id'],
                $_POST['point_id'],
                date('Y-m-d H:i:s', $start_time),
                date('Y-m-d H:i:s', $end_time),
                $total_price,
                $_POST['payment_method']
            ]);
            
            // Перенаправление после успешного создания
            header("Location: my_orders.php?success=1");
            exit;
        } catch (PDOException $e) {
            $error = "Ошибка при создании заказа. Пожалуйста, попробуйте позже.";
            error_log("Order creation error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Оформление заказа | SportGo</title>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .order-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .equipment-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn-submit {
            background: #28a745;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-submit:hover {
            background: #218838;
        }
        .error {
            color: #dc3545;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="order-container">
            <h1>Оформление заказа</h1>
            
            <div class="equipment-info">
                <h3><?= htmlspecialchars($equipment['name']) ?></h3>
                <p><strong>Тип:</strong> <?= htmlspecialchars($equipment['type']) ?></p>
                <p><strong>Цена за час:</strong> <?= $equipment['price_per_hour'] ?> ₽</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="start_time">Дата и время начала аренды:</label>
                    <input type="text" class="form-control flatpickr-input" id="start_time" name="start_time" required>
                </div>
                
                <div class="form-group">
                    <label for="end_time">Дата и время окончания аренды:</label>
                    <input type="text" class="form-control flatpickr-input" id="end_time" name="end_time" required>
                </div>
                
                <div class="form-group">
                    <label for="point_id">Пункт выдачи:</label>
                    <select class="form-control" id="point_id" name="point_id" required>
                        <?php foreach ($points as $point): ?>
                            <option value="<?= $point['point_id'] ?>">
                                <?= htmlspecialchars($point['address']) ?> 
                                (<?= htmlspecialchars($point['working_hours']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="payment_method">Способ оплаты:</label>
                    <select class="form-control" id="payment_method" name="payment_method" required>
                        <option value="cash">Наличные при получении</option>
                        <option value="card">Онлайн оплата картой</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">Подтвердить заказ</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация календаря
        const startPicker = flatpickr("#start_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            locale: "ru",
            time_24hr: true,
            onChange: function(selectedDates) {
                endPicker.set('minDate', selectedDates[0]);
            }
        });

        const endPicker = flatpickr("#end_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: new Date().fp_incr(1),
            locale: "ru",
            time_24hr: true
        });
    });
    </script>
</body>
</html>