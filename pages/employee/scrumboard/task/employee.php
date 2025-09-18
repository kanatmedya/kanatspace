<?php
$empExp = $row['employee'];
$empExp = explode(",", $empExp);

$deadline = strtotime($row["dateDeadline"]);

if ($deadline < time()) {
    $timeColorBG = "bg-danger/20";
    $timeColorText = "text-danger";
} else {
    $timeColorBG = "bg-success/20";
    $timeColorText = "text-success";
}

?>
<div class="group flex items-end justify-between" style="align-items: center;">
    <div
        class="flex items-center rounded-full <?php echo $timeColorBG; ?> px-2 py-1 text-xs font-semibold <?php echo $timeColorText; ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
            class="h-3 w-3 ltr:mr-1 rtl:ml-1">
            <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"></circle>
            <path d="M12 8V12L14.5 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round"></path>
        </svg>
        <?php
        echo (timeElapsedTR($deadline));
        ?>
    </div>
    <div
        class="flex items-end justify-center group-hover:-space-x-2 rtl:space-x-reverse rtl:group-hover:space-x-reverse">
        <?php
        if (count($empExp) > 3) {
            $empExpHiddenNum = count($empExp) - 3;

            $empExpHidden = "";

            $empLimitHidden = 0;
            foreach ($empExp as $emp) {
                if ($empLimitHidden >= 3) {
                    $empExpHidden = $empExpHidden . "\n" . $emp;
                }
                $empLimitHidden += 1;
            }


            echo '<!-- Number -->
            <a title="' . $empExpHidden . '">
            <svg class="flex h-9 w-9 items-center justify-center rounded-full bg-[#bfc9d4] font-semibold text-white opacity-0 transition-all duration-300 group-hover:opacity-100 dark:bg-dark" xmlns="http://www.w3.org/2000/svg">
                <circle cx="18" cy="18" r="18" fill="#bfc9d4" />
                <text x="16" y="22" font-size="14" text-anchor="middle" fill="white" >+' . $empExpHiddenNum . '</text>
            </svg>
            </a>';
        }

        ?>

        <!-- Pictures -->
        <?php
        $empLimit = 0;
        foreach ($empExp as $emp) {
            if ($empLimit == 3)
                break;

            $empLimit += 1;

            $sqlEmp = "SELECT ppLetter,ppColorBG,ppColorText,profilePicture FROM users_employee WHERE name = '$emp'";
            $resEmp = $conn->query($sqlEmp);
            if ($resEmp->num_rows > 0) {
                while ($rowEmp = $resEmp->fetch_array()) {
                    if ($rowEmp['profilePicture'] != '') {
                        echo '<img class="h-9 w-9 rounded-full border-2 border-white object-cover transition-all duration-300 dark:border-dark" src="' . $rowEmp['profilePicture'] . '" alt="' . $emp . '">';
                    } else {
                        echo '<svg class="h-9 w-9 rounded-full border-2 border-white object-cover transition-all duration-300 dark:border-dark" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="18" cy="18" r="18" fill="' . $rowEmp['ppColorBG'] . '" />
                            <text x="16" y="20" font-size="14" text-anchor="middle" fill="' . $rowEmp['ppColorText'] . '">' . $rowEmp['ppLetter'] . '</text>
                        </svg>';
                    }
                }
            }

            $sqlMan = "SELECT ppLetter,ppColorBG,ppColorText,profilePicture FROM users_manager WHERE name = '$emp'";
            $resMan = $conn->query($sqlMan);
            if ($resMan->num_rows > 0) {
                while ($rowMan = $resMan->fetch_array()) {
                    if ($rowMan['profilePicture'] != '') {
                        echo '<img class="h-9 w-9 rounded-full border-2 border-white object-cover transition-all duration-300 dark:border-dark" src="' . $rowMan['profilePicture'] . '" alt="' . $emp . '">';
                    } else {
                        echo '<svg class="h-9 w-9 rounded-full border-2 border-white object-cover transition-all duration-300 dark:border-dark" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="18" cy="18" r="18" fill="' . $rowMan['ppColorBG'] . '" />
                            <text x="16" y="20" font-size="14" text-anchor="middle" fill="' . $rowMan['ppColorText'] . '">' . $rowMan['ppLetter'] . '</text>
                        </svg>';
                    }
                }
            }

        }
        ?>
    </div>
</div>