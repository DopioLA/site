document.addEventListener('DOMContentLoaded', function() {
    // Валидация формы регистрации
    if (document.getElementById('register-form')) {
        initRegisterValidation();
    }

    // Валидация формы входа
    if (document.getElementById('login-form')) {
        initLoginValidation();
    }

    // Валидация формы заказа
    if (document.getElementById('order-form')) {
        initOrderValidation();
    }
});

// Валидация регистрации
function initRegisterValidation() {
    const form = document.getElementById('register-form');
    const phoneInput = form.querySelector('[name="phone"]');

    // Маска телефона
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.startsWith('7') && value.length > 1) {
            value = '+7' + value.substring(1);
        }
        e.target.value = value;
    });

    form.addEventListener('submit', function(e) {
        let valid = true;
        const errors = [];

        // Проверка ФИО
        const nameInput = form.querySelector('[name="full_name"]');
        if (/\d/.test(nameInput.value)) {
            errors.push('ФИО не должно содержать цифр');
            valid = false;
        }

        // Проверка телефона
        if (!/^\+7\d{10}$/.test(phoneInput.value)) {
            errors.push('Телефон должен быть в формате +7XXXXXXXXXX');
            valid = false;
        }

        // Проверка пароля
        const password = form.querySelector('[name="password"]').value;
        if (password.length < 6) {
            errors.push('Пароль должен быть не менее 6 символов');
            valid = false;
        }

        // Проверка подтверждения пароля
        if (password !== form.querySelector('[name="confirm_password"]').value) {
            errors.push('Пароли не совпадают');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            showValidationErrors(errors);
        }
    });
}

// Валидация входа
function initLoginValidation() {
    const form = document.getElementById('login-form');

    form.addEventListener('submit', function(e) {
        const login = form.querySelector('[name="login"]').value;
        const password = form.querySelector('[name="password"]').value;
        
        if (!login || !password) {
            e.preventDefault();
            showValidationErrors(['Заполните все поля']);
        }
    });
}

// Валидация заказа
function initOrderValidation() {
    const form = document.getElementById('order-form');

    form.addEventListener('submit', function(e) {
        const startTime = new Date(form.querySelector('[name="start_time"]').value);
        const endTime = new Date(form.querySelector('[name="end_time"]').value);
        const errors = [];

        if (startTime >= endTime) {
            errors.push('Дата окончания должна быть позже даты начала');
        }

        if (startTime < new Date()) {
            errors.push('Нельзя выбрать прошедшую дату');
        }

        if (errors.length > 0) {
            e.preventDefault();
            showValidationErrors(errors);
        }
    });
}

// Показ ошибок
function showValidationErrors(errors) {
    // Удаляем старые ошибки
    const oldAlerts = document.querySelectorAll('.alert-error');
    oldAlerts.forEach(el => el.remove());

    // Создаем контейнер для ошибок
    const errorContainer = document.createElement('div');
    errorContainer.className = 'alert alert-error';
    
    errors.forEach(error => {
        const errorEl = document.createElement('p');
        errorEl.textContent = error;
        errorContainer.appendChild(errorEl);
    });

    // Вставляем перед формой
    const form = document.querySelector('form');
    form.parentNode.insertBefore(errorContainer, form);
}