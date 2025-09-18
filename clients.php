<?php

session_start();
require "assets/php/routing.php";

$status = $_SESSION['status'];

if ($status == 2){

    go("construction",1);

}elseif ($status == 1){
    
    include "pages/manager/.staticHead.php";
    
    include "pages/manager/.staticSideMenu.php";
    
    include "pages/manager/.staticHeader.php";
    
    include "pages/manager/accounting/clients.php";
    
    include "pages/manager/.staticFooter.php";

}elseif ($status == 3){
    
    go("construction",1);

}else{
    
    go("login",1);

}