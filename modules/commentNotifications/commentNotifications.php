<?php

// Veritabanı bağlantınızı yapın
include './assets/db/db_connect.php'; 

$activeUser = $_SESSION['name'];

// Bildirimlerin alınması
$sql = "SELECT * FROM nt_comments WHERE author != '$activeUser' AND username = '$activeUser'";
$result = $conn->query($sql);
$numComs = $result->num_rows > 0 ? $result->num_rows : 0;

// Yeni bildirimin alınması
$sql = "SELECT * FROM nt_comments WHERE author != '$activeUser' AND username = '$activeUser' AND readed = 0";
$result = $conn->query($sql);
$numNewCom = $result->num_rows > 0 ? $result->num_rows : 0;

// Bildirim verilerini alın
$sql = "SELECT * FROM nt_comments WHERE username='$activeUser' AND author!='$activeUser' ORDER BY readed ASC, dateCreate DESC LIMIT 5";
$result = $conn->query($sql);
$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

include "commentSounds.php";
include "html.php";
?>

<style>
.notification-read {
    opacity: 0.3;
}
.modalAllNotifications {
    height: : -webkit-fill-available;
    flex-direction: column;
    flex-wrap: nowrap;
    justify-content: space-between;
    position: fixed;
    top: 20px;
    bottom: 20px;
    right: 20px;
    left: auto;
    width: 350px;
    max-width: 90%;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    overflow: hidden;
}
.modalAllNotifications.hidden {
    display: none;
}
</style>