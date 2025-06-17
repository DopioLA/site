<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        "login" => $_POST["login"],
        "password" => $_POST["password"],
        "email" => $_POST["email"],
        "phone" => $_POST["phone"],
        "FCS" => $_POST["FCS"],
    ];

    try {
        $connection = new PDO("mysql:host=MySQL-8.0;dbname=demka_db", "root", '');
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'INSERT INTO users(login, password, email, FCS, phone) 
                VALUES (:login, :password, :email, :FCS, :phone)';
        
        $statement = $connection->prepare($sql);
        $result = $statement->execute($data);
        
        if ($result) {
            header('Location: autho.php');
            exit();
        }
    } catch(PDOException $e) {
        echo '<div class="error">Ошибка регистрации: ' . $e->getMessage() . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
   <link rel="stylesheet" href="style.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');
            
            phoneInput.addEventListener('input', function(e) {
                let number = e.target.value.replace(/\D/g, '');
                
                
                if(number.startsWith('8') && number.length > 1) {
                    number = '7' + number.substring(1);
                }
                
               
                let formatted = '+7';
                if(number.length > 1) {
                    formatted += ' (' + number.substring(1, 4);
                }
                if(number.length >= 5) {
                    formatted += ') ' + number.substring(4, 7);
                }
                if(number.length >= 8) {
                    formatted += '-' + number.substring(7, 9);
                }
                if(number.length >= 10) {
                    formatted += '-' + number.substring(9, 11);
                }
                
                
                e.target.value = formatted;
            });

           
            phoneInput.addEventListener('change', function(e) {
                e.target.value = e.target.value.replace(/[^\d+()-\s]/g, '');
            });
        });
    </script>

</head>
<body>
    <div class="registration-card">
        <form method="POST" class="auth-form">
            <h2 class="text-center mb-2">Регистрация</h2>
            
            <?php if(isset($e)): ?>
            <div class="error-message"><?= $e->getMessage() ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label>ФИО</label>
                <input type="text" class="form-control" name="FCS" required 
                    pattern="^[А-Яа-яЁё\s\-]{2,}$"
                    title="Только русские буквы, пробелы и дефисы"
                    placeholder="Иванов Иван Иванович"
                    oninput="this.setCustomValidity('')"
                    oninvalid="this.setCustomValidity('Используйте только русские буквы, пробелы и дефисы')">
            </div>


            <div class="form-group">
                <label>Логин</label>
                <input type="text" class="form-control" name="login" required 
                       placeholder="Придумайте логин">
            </div>

            <div class="form-group">
                <label>Пароль</label>
                <input type="password" class="form-control" name="password" required 
                       placeholder="Не менее 8 символов">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" required 
                       placeholder="example@mail.ru">
            </div>

            <div class="form-group">
                <label>Телефон</label>
                <input type="tel" class="form-control" id="phone" name="phone" required 
                       placeholder="+7 (999) 999-99-99" maxlength="18" minlength="5">
            </div>

            <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
            
            <p class="text-center mt-2">
                Уже есть аккаунт? <a href="autho.php" class="link">Войти</a>
            </p>
        </form>
    </div>
</body>
</html>