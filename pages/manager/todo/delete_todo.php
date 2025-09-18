<?php
include "../../../assets/db/db_connect.php";
global $conn;

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM todos WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
