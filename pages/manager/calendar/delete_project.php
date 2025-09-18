<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

$projectId = $_POST['id'];
    echo $projectId;

// Assignees'leri sil
$sql_delete_assignees = "DELETE FROM projects_assignees WHERE project_id = ?";
$stmt_delete_assignees = $conn->prepare($sql_delete_assignees);
$stmt_delete_assignees->bind_param("i", $projectId);
$stmt_delete_assignees->execute();

// Projeyi sil
$sql_delete_project = "DELETE FROM projects WHERE id = ?";
$stmt_delete_project = $conn->prepare($sql_delete_project);
$stmt_delete_project->bind_param("i", $projectId);

if ($stmt_delete_project->execute()) {
    echo "success";
} else {
    echo "error";
}
?>
