<?php
require 'db.php';

function calculateTotalPrice($equipmentId, $startTime, $endTime) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT price_per_hour FROM equipment WHERE equipment_id = ?");
    $stmt->execute([$equipmentId]);
    $price = $stmt->fetchColumn();
    
    $hours = (strtotime($endTime) - strtotime($startTime)) / 3600;
    return round($price * $hours, 2);
}

function getEquipmentName($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT name FROM equipment WHERE equipment_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}
?>