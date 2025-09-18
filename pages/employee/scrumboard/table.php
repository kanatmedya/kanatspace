<?php echo '<!--  ' . $type . ' Panel -->' ?>
<div class="panel w-80 flex-none">
    <div class="mb-5 flex justify-between">
        <h4 class="text-base font-semibold"><?php echo $type ?></h4>
    </div>

    <!-- task list -->
    <div class="sortable-list min-h-[150px]" :data-id="project.id" data-status="<?php echo $type ?>">
        <?php
        $sqlProject = "SELECT * FROM projects WHERE employee LIKE '%$activeUser%' AND status='$type' AND active='1' ORDER BY displayOrder";
        $resProject = $conn->query($sqlProject);

        while ($row = $resProject->fetch_array()) {
            include "task.php";
        }

        ?>
    </div>
</div>