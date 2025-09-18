<?php

session_start();
require "assets/php/routing.php";

$status = $_SESSION['status'];

if ($status == 2){
    
    include "pages/employee/.staticHead.php";
    
    include "pages/employee/.staticSideMenu.php";
    
    include "pages/employee/.staticHeader.php";
    
    include "pages/employee/settingsUser.php";
    
    include "pages/employee/.staticFooter.php";

}elseif ($status == 1){
    
    include "pages/manager/.staticHead.php";
    
    include "pages/manager/.staticSideMenu.php";
    
    include "pages/manager/.staticHeader.php";
    
    include "pages/employee/settingsUser.php";
    
    include "pages/manager/.staticFooter.php";

}elseif ($status == 3){
    
    include "pages/client/.staticHead.php";
    
    include "pages/client/.staticSideMenu.php";
    
    include "pages/client/.staticHeader.php";
    
    include "pages/employee/settingsUser.php";
    
    include "pages/client/.staticFooter.php";

}else{
    
    go("login",1);

}