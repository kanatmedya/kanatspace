<?php

    include "db_connect.php";
    global $conn;
    
    $id = $_POST['id'];
    
    echo $id;
    
    // sql to delete a record
    $sql = "DELETE FROM gorev WHERE id='$id'";
    
    if (mysqli_query($conn, $sql)) {
      echo "Record deleted successfully";
    } else {
      echo "Error deleting record: " . mysqli_error($conn);
    }
    
    echo "sa";
?>