<div class="task-item" data-id="<?php echo $row['id']; ?>" data-order="<?php echo $row['displayOrder']; ?>">
    <div class="mb-5 cursor-move space-y-2 rounded-md bg-[#f4f4f4] p-3 shadow dark:bg-[#262e40]">
        <!-- Picture -->
        <?php require "task/photo.php" ?>

        <!-- Title -->
        <div class="projectTitle flex items-end justify-between" style="align-items: flex-start;">
            <div class="text-base font-medium">
                <a title="Projeyi Görüntüle" href="project?id=<?php echo $row['id'] ?>">
                    <strong><?php echo $row['title'] ?></strong><br>
                </a>
                <a title="Cari Görüntüle" href="client?id=<?php echo $row['client_id'] ?>">
                    <?php
                    $clientID = $row['client_id'];
                    $sqlClient = "SELECT * FROM users_client WHERE id='$clientID'";
                    $resClient = $conn->query($sqlClient);
        
                    if ($resClient->num_rows > 0) {
                        while ($rowClient = $resClient->fetch_array()) {
                            $clientName = $rowClient['username'];
                        }
                    }else{
                        $clientName = $clientID;
                    }
                    ?>
                    <p style="font-size:12px;line-height: initial;"><?php echo $clientName ?></p>
                </a>
            </div>

            <?php
            $projectID = $row['id'];
            $sqlCom = "SELECT * FROM projects_comments WHERE related = '$projectID' AND type='projectComment' ORDER BY id DESC";
            $resCom = $conn->query($sqlCom);
            $comCount = 0;
            


            if ($resCom->num_rows > 0) {
                while ($rowComment = $resCom->fetch_array()) {
                    if ($comCount < 1) {
                        include "task/comments.php";
                        $comCount += 1;
                    }
                }
            }else{
                echo'
                <!-- Comments -->
                <div style="display: flex;gap: 5px;">
                            
                <a title="Yorumlar" class="add-comment-icon">
                    <svg class="h-6 w-6 transition-all duration-300" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="12" fill="#dff0d8"></circle>
                        <text x="12" y="16" font-size="14" text-anchor="middle" fill="#3c763d">+</text>
                    </svg>
                </a>
                
                </div>
                ';
                    }
            ?>
        </div>

        <!-- Label -->
        <?php
        if ($row['projectType'] != "") {
            //require "task/tag.php";
        } else {

        }
        ?>

        <!-- Employee -->
        <?php
        $projectID = $row['id']; // Assuming $row['id'] holds the current project's ID

        // Check if there are any assignees for this project
        $sqlCheckAssignees = "SELECT COUNT(*) AS assigneeCount FROM projects_assignees WHERE project_id = ?";
        $stmtCheckAssignees = $conn->prepare($sqlCheckAssignees);
        $stmtCheckAssignees->bind_param('i', $projectID);
        $stmtCheckAssignees->execute();
        $resultCheckAssignees = $stmtCheckAssignees->get_result();
        $assigneeData = $resultCheckAssignees->fetch_assoc();

        if ($assigneeData['assigneeCount'] > 0) {
            require "task/employee.php";
        } else {

        }
        ?>
    </div>
</div>