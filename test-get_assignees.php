<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'assets/db/db_connect.php';
global $conn;

$projectId = $_GET['projectId'];

$sql = "SELECT u.id, u.name, u.surname, u.profilePicture 
        FROM ac_users u 
        INNER JOIN projects_assignees pa ON u.id = pa.user_id 
        WHERE pa.project_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $projectId);
$stmt->execute();
$result = $stmt->get_result();

$assignees = array();
while ($row = $result->fetch_assoc()) {
    $assignees[] = $row;
}

echo json_encode(["status" => "success", "assignees" => $assignees]);

$stmt->close();
$conn->close();
?>
