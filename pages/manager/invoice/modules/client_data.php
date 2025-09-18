<?php

include("../../../../assets/db/db_connect.php");

$client = $_POST['client'];
$sql = "SELECT * FROM users_client WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $client);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);

$stmt->close();
$conn->close();
?>
