<?php
include "../../../assets/db/db_connect.php";
global $conn;

$sql = "SELECT * FROM todos ORDER BY created_at DESC";
$result = $conn->query($sql);

$todos = array();
while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}

echo json_encode($todos);
?>
