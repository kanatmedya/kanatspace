<?php
include "assets/db/db_connect.php";
include "assets/db/db_projects.php";
include "./apps/php_TimeTR/php_timeTR.php";
global $conn;

date_default_timezone_set('Europe/Istanbul');

$activeUser = $_SESSION['name'];
$cID = $_SESSION['userID'];

?>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div x-data="scrumboard">
        <h1 style="font-size:24px;margin-right:20px">Projeler</h1>

        <!-- project list -->
        <div class="relative pt-5">
            <div class="perfect-scrollbar -mx-2 h-full">
                <div class="flex flex-nowrap items-start gap-5 overflow-x-auto px-2 pb-2">

                    <!-- Title -->
                    <?php

                    $tableNames = array("Proje", "Beklemede", "Devam Eden", "Onayda", "Onaylandı", "Tamamlandı");
                    $numTitle = count($tableNames);
                    $vIndex = 0;

                    foreach ($tableNames as $type) {
                        include "scrumboard/table.php";
                        $vIndex += 1;
                    }


                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- end main content section -->


    <?php include "scrumboard/modules/commentModal.php"; ?>

</div>