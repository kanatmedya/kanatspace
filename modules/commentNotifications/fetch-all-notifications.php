<?php
session_start();
header('Content-Type: application/json');

include '../../assets/db/db_connect.php';

$userName = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;

if ($userName === null) {
    echo json_encode(['success' => false, 'error' => 'Kullanıcı oturumu bulunamadı']);
    exit();
}

$sql = "SELECT * FROM nt_comments WHERE username='$userName' AND author!='$userName' ORDER BY readed ASC, dateCreate DESC";
$result = $conn->query($sql);
$notifications = [];

while ($row = $result->fetch_assoc()) {
    $commentId = $row['comment_id'];
    $author_id = $row['author'];
    
    $commentSql = "SELECT value, related FROM projects_comments WHERE id='$commentId'";
    $commentResult = $conn->query($commentSql);
    $commentData = $commentResult->fetch_assoc();
    
    $profilePicture = '';
    $userSql = "SELECT profilePicture, name, surname FROM ac_users WHERE id='$author_id'";
    $userResult = $conn->query($userSql);
    if ($userResult->num_rows > 0) {
        $userData = $userResult->fetch_assoc();
        $profilePicture = $userData['profilePicture'];
        $author = $userData['name'] . $userData['surname'];
    }
    
    $notifications[] = [
        'id' => $row['id'],
        'author' => $author,
        'profilePicture' => $profilePicture,
        'commentText' => $commentData['value'] ?: 'Dosya Eklendi',
        'projectID' => $commentData['related'],
        'dateCreate' => $row['dateCreate'],
        'readed' => $row['readed']
    ];
}

echo json_encode(['success' => true, 'notifications' => $notifications]);
$conn->close();
?>
