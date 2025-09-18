<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

include ROOT_PATH . "assets/db/db_connect.php";
include ROOT_PATH . "assets/php/convertRichText.php";
include ROOT_PATH . "assets/php/formatDateTimeRelative.php";

$projectID = $_GET['id'] ?? null;
$clientCommentCount = $_GET['commentCount'] ?? 0; // İstemciden gelen yorum sayısı

session_start();
$activeUser = $_SESSION['name'];

if (!$projectID) {
    echo json_encode(['error' => 'Proje ID eksik']);
    exit();
}

// Sunucudaki mevcut yorum sayısını hesaplayalım
$sqlCommentCount = "SELECT COUNT(*) as totalComments FROM projects_comments WHERE related='$projectID'";
$resCommentCount = $conn->query($sqlCommentCount);
$serverCommentCount = $resCommentCount->fetch_assoc()['totalComments'];

// İstemciden gelen yorum sayısı ile sunucudaki aynıysa hiçbir işlem yapmadan döneriz
if ($clientCommentCount == $serverCommentCount) {
    echo json_encode(['commentCount' => $serverCommentCount, 'comments' => '']);
    exit();
}

// Eğer yorum sayısı değişmişse tüm yorumları getirip işleyelim
$comments = '';
$sqlProjectCom = "SELECT * FROM projects_comments WHERE related='$projectID' ORDER BY dateCreate DESC";
$resProjectCom = $conn->query($sqlProjectCom);

while ($rowCom = $resProjectCom->fetch_array()) {
    ob_start();
    include "../project/comments.php"; // Yorumları renderla
    $comments .= ob_get_clean();
}

// JSON formatında sonuçları döndür
echo json_encode([
    'commentCount' => $serverCommentCount, // Yorum sayısını döndür
    'comments' => $comments // Yorumları döndür
]);
?>
