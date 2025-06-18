<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    // Сначала проверяем администратора
    if ($login === 'admin') {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE login = ? LIMIT 1");
        $stmt->execute([$login]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['is_admin'] = true;
            header("Location: /admin/dashboard.php");
            exit;
        }
    }

    // Проверка обычного пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ? LIMIT 1");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: /profile.php");
        exit;
    }
    
    $error = "Неверный логин или пароль";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Вход | SportGo</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="container">
        <h1>Вход в систему</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Логин:</label>
                <input type="text" name="login" required>
            </div>
            
            <div class="form-group">
                <label>Пароль:</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Войти</button>
        </form>
        
        <p>Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>
    </div>
</body>
</html>