<?php
include "../../../assets/db/db_connect.php";
global $conn;

if (isset($_POST['task'])) {
    $task = $_POST['task'];
    $sql = "INSERT INTO todos (task) VALUES ('$task')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
