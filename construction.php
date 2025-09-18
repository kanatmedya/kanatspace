<?php

session_start();
require "assets/php/routing.php";

$status = $_SESSION['status'];

if ($status == 2){
    
    include "pages/public/construction.php";

}elseif ($status == 1){
    
    include "pages/public/construction.php";

}elseif ($status == 3){
    
    include "pages/public/construction.php";

}else{
    
    include "pages/public/construction.php";

}
?>