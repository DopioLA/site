<?php
require 'config.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin = query("SELECT * FROM admins WHERE login = ? LIMIT 1", [$_POST['login']])->fetch();
    
    if ($admin && password_verify($_POST['password'], $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: admin.php");
        exit;
    }
    $error = "Неверные данные";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Вход</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width:400px">
        <form method="POST" class="bg-white p-4 rounded shadow">
            <h2 class="mb-4">Вход в админку</h2>
            <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <input type="text" name="login" class="form-control mb-3" placeholder="Логин" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Пароль" required>
            <button class="btn btn-primary w-100">Войти</button>
        </form>
    </div>
</body>
</html>