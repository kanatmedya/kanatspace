<?php
$projectID = $row['id']; // Assuming you have the project ID available
$deadline = strtotime($row["dateDeadline"]);

if ($deadline < time()) {
    $timeColorBG = "bg-danger/20";
    $timeColorText = "text-danger";
} else {
    $timeColorBG = "bg-success/20";
    $timeColorText = "text-success";
}

// Fetch assignees from the projects_assignees table
$sqlAssignees = "SELECT user_id FROM projects_assignees WHERE project_id = ?";
$stmtAssignees = $conn->prepare($sqlAssignees);
$stmtAssignees->bind_param('i', $projectID);
$stmtAssignees->execute();
$resultAssignees = $stmtAssignees->get_result();

$assignees = [];
while ($rowAssignee = $resultAssignees->fetch_assoc()) {
    $assignees[] = $rowAssignee['user_id'];
}


if($row['status']=="Tamamlandı" OR $row['status']=="Reddedildi"){
    $taskDate = $row['dateComplete'];
    $taskDateTime = strtotime($taskDate);
    $timeColorBG = "bg-success/20";
    $timeColorText = "text-success";
}else{
    $taskDate = $row['dateDeadline'];
    $taskDateTime = strtotime($taskDate);
}
?>

<div class="group flex items-end justify-between" style="align-items: center;">
    <div style="display: flex;gap: .3rem;">
        <?php
            $status = $row["status_invoice"];
            $title = $status == 0 ? "Fatura Yok" : "Fatura Hazır";
            $bgClass = $status == 0 ? "bg-danger/20 text-danger" : "bg-success/20 text-success";
        ?>
        
        <a title="<?php echo $title; ?>" class="updateInvoice flex items-center rounded-full px-2 py-1 text-xs font-semibold <?php echo $bgClass; ?>"
           style="text-wrap: nowrap;">
            <i class="fa-solid fa-file-invoice"></i>
        </a>
        <a title="<?= $taskDate ?>" class="projectDate flex items-center rounded-full <?php echo $timeColorBG; ?> px-2 py-1 text-xs font-semibold <?php echo $timeColorText; ?>" style="text-wrap: nowrap;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ltr:mr-1 rtl:ml-1">
                <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"></circle>
                <path d="M12 8V12L14.5 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <?php
            echo(timeElapsedTR($taskDateTime));
            ?>
        </a>
    </div>
    <div
            class="flex items-end group-hover:-space-x-2 rtl:space-x-reverse rtl:group-hover:space-x-reverse" style="justify-content: right;">
        <?php
        $assigneeCount = count($assignees);
        if ($assigneeCount > 3) {
            $assigneesHiddenNum = $assigneeCount - 3;

            $assigneesHidden = [];
            $hiddenAssigneesString = '';
            for ($i = 3; $i < $assigneeCount; $i++) {
                $userID = $assignees[$i];

                // Fetch the name of the hidden assignees from ac_users
                $sqlUserName = "SELECT CONCAT(name, ' ', surname) AS fullName FROM ac_users WHERE id = ?";
                $stmtUserName = $conn->prepare($sqlUserName);
                $stmtUserName->bind_param('i', $userID);
                $stmtUserName->execute();
                $resultUserName = $stmtUserName->get_result();

                if ($resultUserName->num_rows > 0) {
                    $rowUserName = $resultUserName->fetch_assoc();
                    $hiddenAssigneesString .= htmlspecialchars($rowUserName['fullName']) . "\n";
                }
            }

            echo '<!-- Number -->
            <a title="' . trim($hiddenAssigneesString) . '">
            <svg class="flex h-9 w-9 items-center justify-center rounded-full bg-[#bfc9d4] font-semibold text-white opacity-0 transition-all duration-300 group-hover:opacity-100 dark:bg-dark" xmlns="http://www.w3.org/2000/svg">
                <circle cx="18" cy="18" r="18" fill="#bfc9d4" />
                <text x="16" y="22" font-size="14" text-anchor="middle" fill="white" >+' . $assigneesHiddenNum . '</text>
            </svg>
            </a>';
        }
        ?>

        <!-- Pictures -->
        <?php
        $displayCount = min(3, $assigneeCount);
        for ($i = 0; $i < $displayCount; $i++) {
            $userID = $assignees[$i];

            // Fetch profile picture from ac_users
            $sqlUser = "SELECT profilePicture FROM ac_users WHERE id = ?";
            $stmtUser = $conn->prepare($sqlUser);
            $stmtUser->bind_param('i', $userID);
            $stmtUser->execute();
            $resultUser = $stmtUser->get_result();

            if ($resultUser->num_rows > 0) {
                $rowUser = $resultUser->fetch_assoc();
                if (!empty($rowUser['profilePicture'])) {
                    echo '<img class="h-9 w-9 rounded-full border-2 border-white object-cover transition-all duration-300 dark:border-dark" src="' . htmlspecialchars($rowUser['profilePicture']) . '" alt="Profile Picture">';
                }
            }
        }
        ?>
    </div>
</div>
