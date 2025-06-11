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

    <link rel="stylesheet" href="style.css">
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