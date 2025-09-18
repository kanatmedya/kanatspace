<?php
include 'assets/db/db_connect.php';

$term = $_GET['term']; // Arama terimi

$sql = "SELECT id, name, surname, profilePicture FROM ac_users WHERE userType IN (1, 2) AND (name LIKE ? OR surname LIKE ?) AND status <> 0";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $term . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$users = array();
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);

$stmt->close();
$conn->close();

?>