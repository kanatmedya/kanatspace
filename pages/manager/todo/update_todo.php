<?php
include "../../../assets/db/db_connect.php";
global $conn;

if (isset($_POST['id']) && isset($_POST['completed'])) {
    $id = $_POST['id'];
    $completed = $_POST['completed'];
    $sql = "UPDATE todos SET completed=$completed WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
