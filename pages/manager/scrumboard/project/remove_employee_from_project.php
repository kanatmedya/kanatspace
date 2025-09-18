<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

session_start(); // Oturumu başlat

include ROOT_PATH . "assets/db/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $projectId = $_POST['projectId'];
    $userId = $_POST['userId'];

    // Oturumda userID olup olmadığını kontrol edin
    if (!isset($_SESSION['userID'])) {
        echo json_encode(['status' => 'error', 'message' => 'Oturum açılmamış ya da kullanıcı bilgileri eksik.']);
        exit();
    }

    $activeUserId = $_SESSION['userID']; // Şu anki oturum açmış kullanıcı (görevli çıkaran)

    // Görevli silme sorgusu
    $sql = "DELETE FROM projects_assignees WHERE project_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $projectId, $userId);

    if ($stmt->execute()) {
        // Log işlemi
        $logSql = "INSERT INTO log_projects_assignees (project_id, user_id, related_user_id, request_type) VALUES (?, ?, ?, 'remove')";
        $stmtLog = $conn->prepare($logSql);
        $stmtLog->bind_param('iii', $projectId, $activeUserId, $userId);
        $stmtLog->execute();

        echo json_encode(['status' => 'success', 'message' => 'Görevli başarıyla silindi.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Görevli silinemedi: ' . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
