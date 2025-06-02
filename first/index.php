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
<style>
    :root {
        /* Цветовая палитра */
        --primary: #2563eb;
        --primary-hover: #1d4ed8;
        --primary-light: #dbeafe;
        --background: #f8fafc;
        --surface: #ffffff;
        --text: #1e293b;
        --text-secondary: #64748b;
        --border: #cbd5e1;
        --border-hover: #94a3b8;
        --error: #dc2626;
        --success: #16a34a;
        
        /* Тени */
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        
        /* Радиусы */
        --radius-sm: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 1rem;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    }

    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background-color: var(--background);
        color: var(--text);
        line-height: 1.5;
    }

    .container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        width: 100%;
        border: 1px solid var(--border);
    }

    h1, h2 {
        color: var(--text);
        margin-bottom: 2rem;
        position: relative;
    }

    h1 {
        font-size: 2.5rem;
        text-align: center;
        font-weight: 700;
    }

    h1::after {
        content: '';
        position: absolute;
        bottom: -0.75rem;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(to right, var(--primary), var(--primary-hover));
        border-radius: 2px;
    }

    h2 {
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
    }

    /* Группы форм */
    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-label {
        display: block;
        color: var(--text);
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-label.required::after {
        content: ' *';
        color: var(--error);
    }

    /* Поля ввода */
    input, textarea, .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: 1rem;
        transition: all 0.2s ease;
        background-color: var(--background);
    }

    input:hover, textarea:hover, .form-control:hover {
        border-color: var(--border-hover);
    }

    input:focus, textarea:focus, .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
        background-color: var(--surface);
    }

    textarea {
        min-height: 120px;
        resize: vertical;
    }

    /* Кнопки */
    button, .registration-btn {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(to right, var(--primary), var(--primary-hover));
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0.5rem;
    }

    button:hover, .registration-btn:hover {
        background: linear-gradient(to right, var(--primary-hover), #1e40af);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    /* Ссылки */
    .auth-link, .registration-footer {
        text-align: center;
        margin-top: 1.5rem;
        color: var(--text-secondary);
    }

    .auth-link a, .registration-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .auth-link a:hover, .registration-link:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    /* Сообщения об ошибках */
    .error, .error-message, .alert-error {
        color: var(--error);
        background-color: rgba(220, 38, 38, 0.1);
        border: 1px solid rgba(220, 38, 38, 0.2);
        padding: 1rem;
        border-radius: var(--radius-md);
        margin: 1rem 0;
        font-size: 0.95rem;
    }

    .success, .alert-success {
        background-color: rgba(22, 163, 74, 0.1);
        border: 1px solid rgba(22, 163, 74, 0.2);
        color: var(--success);
        padding: 1rem;
        border-radius: var(--radius-md);
        margin: 1rem 0;
    }

    /* Адаптивность */
    @media (max-width: 768px) {
        .container {
            margin: 1rem;
            padding: 1.5rem;
            border-radius: var(--radius-md);
        }
        
        h1 {
            font-size: 2rem;
        }
        
        h2 {
            font-size: 1.5rem;
        }
    }

    /* Дополнительные элементы */
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 38px;
        cursor: pointer;
        color: var(--text-secondary);
        background: none;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
        color: var(--text-secondary);
        font-size: 0.875rem;
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

    .logo {
        color: var(--text);
        text-align: center;
    }
</style>
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