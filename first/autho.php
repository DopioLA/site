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
    <style>
    :root {
        --primary: #3b82f6;
        --primary-dark: #2563eb;
        --background: #f8f9fa;
        --text: #111827;
        --text-light: #6b7280;
        --border: #e5e7eb;
        --error: #ef4444;
        --success: #10b981;
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    
    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--background);
        padding: 1rem;
        line-height: 1.6;
    }
    
    .auth-container {
        width: 100%;
        max-width: 420px;
    }
    
    .auth-logo {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .auth-logo img {
        height: 48px;
    }
    
    .auth-card {
        background: white;
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        width: 100%;
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }
    
    .auth-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(to bottom, var(--primary), var(--primary-dark));
    }
    
    h1.auth-title {
        color: var(--text);
        font-size: 1.75rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
    }
    
    h1.auth-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(to right, var(--primary), var(--primary-dark));
        border-radius: 2px;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    label {
        display: block;
        color: var(--text);
        font-size: 0.95rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #f9fafb;
    }
    
    input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background-color: white;
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 38px;
        cursor: pointer;
        color: var(--text-light);
    }
    
    .auth-btn {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(to right, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0.5rem;
    }
    
    .auth-btn:hover {
        background: linear-gradient(to right, var(--primary-dark), #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.2);
    }
    
    .auth-btn:active {
        transform: translateY(0);
    }
    
    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
        color: var(--text-light);
        font-size: 0.95rem;
    }
    
    .auth-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .auth-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }
    
    .error-message {
        color: var(--error);
        font-size: 0.9rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .alert-error {
        background-color: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: var(--error);
    }
    
    .alert-success {
        background-color: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: var(--success);
    }
    
    .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
        color: var(--text-light);
        font-size: 0.9rem;
    }
    
    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid var(--border);
    }
    
    .divider::before {
        margin-right: 1rem;
    }
    
    .divider::after {
        margin-left: 1rem;
    }
    
    @media (max-width: 480px) {
        .auth-card {
            padding: 2rem 1.5rem;
        }
        
        h1.auth-title {
            font-size: 1.5rem;
        }
    }
</style>
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