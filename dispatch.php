<?php

session_start();
require "assets/php/routing.php";

if (isset($_SESSION['status'])){
    $status = $_SESSION['status'];
}else{
    $status = "";
}


if ($status == 2){

    include "pages/public/invPreview.php";

}elseif ($status == 1){
    
    include "pages/manager/.staticHead.php";
    
    include "pages/manager/.staticSideMenu.php";
    
    include "pages/manager/.staticHeader.php";
    
    include "pages/manager/invoice/previewDispatch.php";
    
    include "pages/manager/.staticFooter.php";

}elseif ($status == 3){
    
    include "pages/public/invPreview.php";

}else{
    
    include "pages/public/invPreview.php";

}

?>