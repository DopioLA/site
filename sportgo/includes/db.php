<?php
$host = 'MySQL-8.0';
$dbname = 'sportgo_bd';  // Ваше название БД
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к sportgo_bd: " . $e->getMessage());
}
?>