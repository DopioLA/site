<?php
require 'includes/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Валидация
    if (empty($full_name) || preg_match('/\d/', $full_name)) {
        $errors[] = "ФИО не должно содержать цифр";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Некорректный email";
    }

    if (strlen($password) < 6) {
        $errors[] = "Пароль должен быть не менее 6 символов";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Пароли не совпадают";
    }

    // Проверка уникальности
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = ? OR email = ?");
    $stmt->execute([$login, $email]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = "Логин или email уже заняты";
    }

    // Регистрация
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (full_name, phone, email, login, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $phone, $email, $login, $hashed_password]);
        
        header("Location: login.php?registration=success");
        exit;
    }
}
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
        <h1>Регистрация</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="register-form">
            <div class="form-group">
                <label>ФИО:</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Телефон:</label>
                <input type="tel" name="phone" placeholder="+7XXXXXXXXXX" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Логин:</label>
                <input type="text" name="login" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Пароль:</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>Подтвердите пароль:</label>
                <input type="password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">Зарегистрироваться</button>
        </form>
        <script src="js/validate.js"></script>

        
        <p>Уже есть аккаунт? <a href="login.php">Войдите</a></p>
    </div>
</body>
</html>