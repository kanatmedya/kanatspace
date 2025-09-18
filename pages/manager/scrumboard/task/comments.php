<!-- Comments -->
<div style="display: flex;gap: 5px;">
    
    <?php
    
    $userId = $rowComment['user'];

    // Veritabanından name ve surname çek
    $sqlComUser = "SELECT name, surname FROM ac_users WHERE id = ?";
    $stmtComUser = $conn->prepare($sqlComUser);
    $stmtComUser->bind_param("i", $userId);
    $stmtComUser->execute();
    $resultUser = $stmtComUser->get_result();
    
    if ($resultUser->num_rows > 0) {
        $userRow = $resultUser->fetch_assoc();
        $comUser = $userRow['name'] . ' ' . mb_substr($userRow['surname'], 0, 1, "UTF-8") . ".";
    } else {
        $comUser = "Kullanıcı bulunamadı"; // Eğer kullanıcı yoksa
    }
    
    $stmtComUser->close();
    
    
    ?>
            
<a title="<?php echo $comUser ."\n". $rowComment['value'] ?>" class="add-comment-icon" style="display:block">
    <svg class="h-6 w-6 transition-all duration-300" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="12" fill="#dff0d8"></circle>
        <text x="12" y="16" font-size="14" text-anchor="middle" fill="#3c763d"><?php echo $resCom->num_rows ?></text>
    </svg>
</a>

</div>