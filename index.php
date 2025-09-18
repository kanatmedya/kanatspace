<?php

session_start();
require "assets/php/routing.php";

if (isset($_SESSION['status'])){
    $status = $_SESSION['status'];
}else{
    $status = "";
}

if ($status == 2){
    
    include "pages/employee/.staticHead.php";
    
    include "pages/employee/.staticSideMenu.php";
    
    include "pages/employee/.staticHeader.php";
    
    include "pages/employee/dashboard.php";
    
    include "pages/employee/.staticFooter.php";

}elseif ($status == 1){
    
    include "pages/manager/.staticHead.php";
    
    include "pages/manager/.staticSideMenu.php";
    
    include "pages/manager/.staticHeader.php";
    
    include "pages/manager/dashboard.php";
    
    include "pages/manager/.staticFooter.php";

}elseif ($status == 3){
    
    include "pages/client/.staticHead.php";
    
    include "pages/client/.staticSideMenu.php";
    
    include "pages/client/.staticHeader.php";
    
    include "pages/client/dashboard.php";
    
    include "pages/client/.staticFooter.php";

}else{
    
    go("login",1);

}

?>

<script>
    document.title = "Dashboard | <?php echo $companyName?>";
</script>