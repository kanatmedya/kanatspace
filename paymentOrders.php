<?php

    session_start();
    require "assets/php/routing.php";

    $status = $_SESSION['status'];

    if ($status == 1) {

        include "pages/manager/.staticHead.php";

        include "pages/manager/.staticSideMenu.php";

        include "pages/manager/.staticHeader.php";

        include "pages/manager/payments/paymentOrders.php";

        include "pages/manager/.staticFooter.php";

    } elseif ($status == 2) {
        
        go("./");


    } elseif ($status == 3) {
        
        go("./");

    } else {

        go("login", 1);

    }

?>