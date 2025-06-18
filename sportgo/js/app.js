// Инициализация календаря
document.addEventListener('DOMContentLoaded', function() {
    // Выбор даты аренды
    flatpickr("#start_time", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today"
    });
    
    flatpickr("#end_time", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: new Date().fp_incr(1)
    });
});

// Мобильное меню
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.querySelector('.mobile-menu-toggle');
    const menu = document.querySelector('.mobile-menu');
    
    if (toggle && menu) {
        toggle.addEventListener('click', function() {
            menu.classList.toggle('active');
        });
    }
});
// Показываем ссылку только если в куках есть метка админа
if (document.cookie.includes('user_role=admin')) {
    document.getElementById('admin-login-link').style.display = 'block';
}