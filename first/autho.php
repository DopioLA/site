<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = $_POST["login"] ?? '';
    $password = $_POST["password"] ?? '';

    try {
        $connection = new PDO("mysql:host=MySQL-8.0;dbname=demka_db", "root", "");
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM users WHERE login = :login";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':login', $login);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_login'] = $user['login'];
            header("Location: statements.php");
            exit();
        } else {
            $_SESSION['error'] = "Неверный логин или пароль";
            header("Location: autho.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Ошибка базы данных: " . $e->getMessage();
        header("Location: autho.php");
        exit();
    }
} else {
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-card">

        <form method="POST">
            <h2 class="text-center mb-2">Авторизация</h2>
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="login" required>
            </div>

            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Войти</button>
            <p class="text-center mt-2">
                Уже есть аккаунт? <a href="index.php" class="link">Зарегистрироваться</a>
            </p>
        </form>
    </div>
</body>
</html>
<?php
}
?>