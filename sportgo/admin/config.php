<?php
session_start();
require 'db.php'; // Подключение к БД

// Проверка авторизации администратора
function checkAdminAuth() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Функция для выполнения SQL запросов
function query($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}
?>