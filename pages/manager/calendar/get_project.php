<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

include ROOT_PATH . "assets/db/db_connect.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($id === null) {
    echo json_encode(['error' => 'ID eksik']);
    exit;
}

// Proje bilgilerini al
$sql_project = "SELECT p.id, p.title, p.projectType, p.dateDeadline, p.client, p.status, p.description 
                FROM projects p 
                WHERE p.id = ?";
$stmt_project = $conn->prepare($sql_project);

if (!$stmt_project) {
    echo json_encode(['error' => 'SQL Hatası: ' . $conn->error]);
    exit;
}

$stmt_project->bind_param("i", $id);
$stmt_project->execute();
$result_project = $stmt_project->get_result();

if ($result_project->num_rows > 0) {
    $project = $result_project->fetch_assoc();

    // Projeye atanmış kullanıcılar
    $sql_assignees = "SELECT u.id, u.name, u.profilePicture 
                      FROM projects_assignees pa 
                      JOIN ac_users u ON pa.user_id = u.id 
                      WHERE pa.project_id = ?";
    $stmt_assignees = $conn->prepare($sql_assignees);

    if (!$stmt_assignees) {
        echo json_encode(['error' => 'SQL Hatası (Assignees): ' . $conn->error]);
        exit;
    }

    $stmt_assignees->bind_param("i", $id);
    $stmt_assignees->execute();
    $result_assignees = $stmt_assignees->get_result();

    $assignees = [];
    while ($row = $result_assignees->fetch_assoc()) {
        $assignees[] = $row;
    }

    $project['assignees'] = $assignees;

    echo json_encode($project);
} else {
    echo json_encode(['error' => 'Proje bulunamadı']);
}
?>
