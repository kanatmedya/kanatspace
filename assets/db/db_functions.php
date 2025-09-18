<?php

function db_num_rows($sqlRequest)
{
    global $conn;
    $result = $conn->query($sqlRequest);
    return $result->num_rows;
}

function db_sum_format($sqlRequest)
{
    global $conn;
    $result = $conn->query($sqlRequest);
    $row = $result->fetch_array();
    if($row["amount"] != null){
        return number_format($row['amount'], 2, ',', '.');
    }else{
        return 0;
    }
}

function db_sum($sqlRequest)
{
    global $conn;
    $result = $conn->query($sqlRequest);
    $row = $result->fetch_array();
    if($row["amount"] != null){
        return $row['amount'];
    }else{
        return 0;
    }
}



?>