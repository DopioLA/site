<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user_id'])) {
        $data = [
            "carNumber" => $_POST["carNumber"],
            "description" => $_POST["description"],
            "status" => "new",
            "user_id" => $_SESSION['user_id']
        ];

        try {
            $connection = new PDO("mysql:host=MySQL-8.0;dbname=demka_db", "root", '');
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'INSERT INTO statements(carNumber, description, status, user_id) 
                    VALUES (:carNumber, :description, :status, :user_id)';
            
            $statement = $connection->prepare($sql);
            $result = $statement->execute($data);
            
            if ($result) {
                header("Location: application.php");
                exit();
            }
        } catch(PDOException $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = "Пользователь не авторизован";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Форма Заявления</title>

    <style>
        /* Основные стили (дополнение к существующим) */
:root {
    --primary-blue: #2563eb;
    --primary-dark-blue: #1e40af;
    --danger-red: #dc2626;
    --success-green: #16a34a;
    --warning-orange: #ea580c;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-700: #374151;
}

/* Улучшенные карточки нарушений */
.violation-card {
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 4px solid var(--primary-blue);
}

.violation-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-blue), var(--primary-dark-blue));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
}

.violation-card:hover::before {
    transform: scaleX(1);
}

/* Анимация статусов */
.status-badge {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.status-badge::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: all 0.6s ease;
}

.status-badge:hover::after {
    left: 100%;
}

/* Градиенты для статусов */
.status-new {
    background: linear-gradient(135deg, #60a5fa, #3b82f6);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.status-confirmed {
    background: linear-gradient(135deg, #34d399, #10b981);
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.status-rejected {
    background: linear-gradient(135deg, #f87171, #ef4444);
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

/* Улучшенный навбар */
.navbar {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    background: linear-gradient(135deg, #1e3a8a, #1e40af) !important;
}

.navbar-brand {
    font-weight: 600;
    letter-spacing: 0.5px;
}

.nav-link {
    position: relative;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: white;
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.nav-link:hover::after {
    width: 70%;
}

/* Анимация для контейнера */
.container {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Улучшенные формы */
.form-control {
    transition: all 0.3s ease;
    border: 1px solid var(--gray-200);
}

.form-control:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Кнопки с градиентом */
.btn-primary {
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-dark-blue));
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(37, 99, 235, 0.1);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(37, 99, 235, 0.15);
}

/* Адаптивные улучшения */
@media (max-width: 768px) {
    .violation-card {
        padding: 1.5rem;
    }
    
    .status-badge {
        padding: 0.4rem 1rem;
        font-size: 0.8rem;
    }
}

/* Индикатор загрузки (для AJAX операций) */
.loading-indicator {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-blue), var(--success-green), var(--danger-red));
    z-index: 9999;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Микро-интеракции */
[data-bs-toggle="tooltip"] {
    cursor: pointer;
    transition: all 0.2s ease;
}

/* Кастомный скроллбар */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: var(--gray-100);
}

::-webkit-scrollbar-thumb {
    background: var(--primary-blue);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark-blue);
}
    </style>

</head>
<body>
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="statements.php">
                <img src="img/car.jpg" alt="Logo" width="75" height="75" class="me-2 rounded-circle">
                <span class="fs-4">Нарушениям.Нет</span>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="application.php">Мои заявки</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <div class="header">
        <nav class="nav container">
            <div class="logo">Нарушениям.Нет</div>
                <div class="registration-card">
        <form method="POST" class="auth-form">
            <h2 class="text-center mb-2">Новое заявление</h2>
            
            <?php if(isset($error)): ?>
            <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label>Номер машины</label>
                <input type="text" class="form-control" name="carNumber" required 
                       placeholder="A123BC">
            </div>

            <div class="form-group">
                <label>Описание нарушения</label>
                <textarea class="form-control" name="description" rows="4" required 
                          placeholder="Опишите подробности нарушения"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Отправить</button>
        </form>
    </div>
        </nav>
    </div>
</body>
</html>