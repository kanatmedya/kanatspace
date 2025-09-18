<?php echo '<!--  ' . $table . ' Panel -->' ?>
<div class="panel w-80 flex-none">
    <div class="mb-5 flex justify-between">
        <h4 class="text-base font-semibold"><?php echo $table ?></h4>
    </div>

    <!-- task list -->
    <div class="sortable-list min-h-[150px]" data-status="<?php echo $table ?>">
        <?php
        $resProject = $conn->query($sqlProject);

        while ($row = $resProject->fetch_array()) {
            include "task.php";
        }

        ?>
    </div>
</div>