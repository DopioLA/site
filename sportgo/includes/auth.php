<?php
session_start();
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php' && basename($_SERVER['PHP_SELF']) != 'register.php') {
    header("Location: login.php");
    exit;
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
}
?>