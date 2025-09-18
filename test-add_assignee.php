<?php

include 'assets/db/db_connect.php';
global $conn;

$projectId = $_POST['projectId']; // POST edilen proje ID'si
$userId = $_POST['assigneeId'];   // POST edilen kullanıcı ID'si

// Projeye yeni bir görevli eklemek için SQL sorgusu
$sql = "INSERT INTO projects_assignees (project_id, user_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $projectId, $userId);

if ($stmt->execute()) {
    // Yeni eklenen kullanıcıyı çekmek için sorgu
    $userSql = "SELECT id, name, surname, profilePicture FROM ac_users WHERE id = ?";
    $userStmt = $conn->prepare($userSql);
    $userStmt->bind_param("i", $userId);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    $newUser = $userResult->fetch_assoc();

    echo json_encode(["status" => "success", "user" => $newUser]);
} else {
    echo json_encode(["status" => "error"]);
}

$stmt->close();
$conn->close();
?>
