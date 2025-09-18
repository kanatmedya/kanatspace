<?php
session_start();
include "assets/db/db_connect.php";
require "assets/php/routing.php";
global $conn;

if (isset($_SESSION['name'])) {
    $userName = $_SESSION['name'];
    $activity = 'LOGOUT';

    $logLogout = "INSERT INTO log_entry (user, action) VALUES (?, ?)";
    $stmt = $conn->prepare($logLogout);
    $stmt->bind_param('ss', $userName, $activity);
    $stmt->execute();
    $stmt->close();
}

// Çerez silme işlemi
if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $deleteToken = "DELETE FROM user_tokens WHERE token = ?";
    $stmt = $conn->prepare($deleteToken);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->close();

    // Çerezi tarayıcıdan silme
    setcookie('remember_me', '', time() - 3600, '/');
}

session_unset();
session_destroy();

header("Location: ./");
exit("Çıkış Yapıldı. <br> Giriş Sayfasına Yönlendiriliyorsunuz.");
?>