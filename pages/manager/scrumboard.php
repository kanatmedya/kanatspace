<?php
include "assets/db/db_connect.php";
include "./apps/php_TimeTR/php_timeTR.php";
global $conn;

date_default_timezone_set('Europe/Istanbul');

$activeUser = $_SESSION['name'];
$activeUserID = $_SESSION['userID'];
$activeUserStatus = $_SESSION['status'];

function createThumbnail($sourcePath, $destinationPath, $desiredWidth, $desiredHeight) {
    // Dosya uzantısını kontrol et
    $fileExtension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

    switch ($fileExtension) {
        case 'jpeg':
        case 'jpg':
            $sourceImage = @imagecreatefromjpeg($sourcePath);
            break;
        case 'png':
            $sourceImage = @imagecreatefrompng($sourcePath);
            break;
        default:
            error_log("Desteklenmeyen dosya formatı: $fileExtension");
            return; // Desteklenmeyen format
    }

    if (!$sourceImage) {
        error_log("Geçersiz görüntü dosyası: $sourcePath");
        return;
    }

    $sourceWidth = imagesx($sourceImage);
    $sourceHeight = imagesy($sourceImage);

    // Oranı koruyarak yeni boyutu hesapla
    $sourceAspectRatio = $sourceWidth / $sourceHeight;
    $thumbnailAspectRatio = $desiredWidth / $desiredHeight;

    if ($sourceAspectRatio > $thumbnailAspectRatio) {
        $tempHeight = $desiredHeight;
        $tempWidth = (int)($desiredHeight * $sourceAspectRatio);
    } else {
        $tempWidth = $desiredWidth;
        $tempHeight = (int)($desiredWidth / $sourceAspectRatio);
    }

    // Yeniden boyutlandırılmış resmi oluştur
    $tempImage = imagecreatetruecolor($tempWidth, $tempHeight);
    imagecopyresampled($tempImage, $sourceImage, 0, 0, 0, 0, $tempWidth, $tempHeight, $sourceWidth, $sourceHeight);

    // Kırpma işlemi
    $x0 = ($tempWidth - $desiredWidth) / 2;
    $y0 = ($tempHeight - $desiredHeight) / 2;
    $thumbnailImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
    imagecopy($thumbnailImage, $tempImage, 0, 0, $x0, $y0, $desiredWidth, $desiredHeight);

    // JPG formatında kaydet
    imagejpeg($thumbnailImage, $destinationPath);

    // Hafızayı temizle
    imagedestroy($sourceImage);
    imagedestroy($tempImage);
    imagedestroy($thumbnailImage);
}



?>

<script>
    document.title = "Projeler | Kanat Portal";

    function deleteTask(id) {
        $.ajax({
            url: "assets/db/db_projectDelete.php",
            type: "POST",
            data: {
                'id': id,
            },
            success: function (result) {
                console.log(result);
            }
        });
    }
</script>

<div class="animate__animated p-6 pb-0" :class="[$store.app.animation]">
    <div>
        <div style="display:flex;align-items: center;justify-content: space-between;flex-wrap: wrap;gap: 10px">
            <?php
                $tableNames = array("Proje", "Beklemede", "Devam Eden", "Onayda", "Onaylandı", "Tamamlandı");
    
                // Hem GET hem POST destekler
                $type = $_POST['type'] ?? $_GET['type'] ?? null;
                $client = isset($_POST['client']) ? intval($_POST['client']) : (isset($_GET['client']) ? intval($_GET['client']) : null);
                $completed = $_POST['completed'] ?? $_GET['completed'] ?? null;
                $employees = isset($_POST['employee']) ? array_map('intval', (array)$_POST['employee']) : (isset($_GET['employee']) ? array_map('intval', explode(',', $_GET['employee'])) : []);
    
                // Başlık
                if ($type === 'all') {
                    $pageTitle = "";
                } elseif ($type === 'team') {
                    $pageTitle = "";
                } elseif ($type === 'emp') {
                    $employeeNames = [];

                    if (!empty($employees)) {
                        // Tüm ID'ler için sorgu
                        $employeeIDs = implode(',', $employees);
                        $sql = "SELECT name, surname FROM ac_users WHERE id IN ($employeeIDs)";
                        $result = $conn->query($sql);
                    
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['name'];
                                if($surnameShow=='short'){
                                    $surnameInitial = mb_substr($row['surname'], 0, 1, "UTF-8") . "."; // Surname'in ilk harfi
                                }else{
                                    $surnameInitial = $row['surname'];
                                }
                                $employeeNames[] = $name . ' ' . $surnameInitial;
                            }
                        }
                    }
                    
                    // İsimleri virgülle birleştir
                    $employeeList = implode(' ', $employeeNames);
                    
                    $pageTitle = $employeeList . " Projeleri";
                    
                } else {
                    $pageTitle = "";
                }
            ?>
            <div style="display:flex;gap:15px">
                <h1 style="font-size:24px"><?php echo $pageTitle ?></h1>
            </div>
            
            <div style="display:flex;gap:15px">
                <select required id="selectedEmployee" name="selectedEmployee" class="form-select flex-1" onchange="postEmployee()">
                    <option value="">Personel Seçin</option>
                        <?php
                            $sqlEmployee = "SELECT * FROM ac_users WHERE status='1' AND (userType=1 OR userType=2)";
                            $resultEmployee = $conn->query($sqlEmployee);
            
                            while ($rowEmployee = $resultEmployee->fetch_assoc()) {
                                if(isset($_GET['employee'])){
                                    if($_GET['employee']==$rowEmployee['id']){
                                        $selectedEmployee= "selected";
                                    }else{
                                        $selectedEmployee= "";
                                    }
                                }else{
                                    $selectedEmployee= "";
                                }
                                
                                echo '<option value="' . $rowEmployee['id'] . '" '. $selectedEmployee .'>' . $rowEmployee['name'] . ' ' . $rowEmployee['surname'] . '</option>';
                            }
                        ?>
                </select>
                
                <select required id="selectedClient" name="selectedClient" class="form-select flex-1" onchange="postClient()">
                    <option value="">Müşteri Seçin</option>
                        <?php
                            $sqlClient = "SELECT * FROM users_client WHERE accountType='client' ORDER BY username";
                            $resultClient = $conn->query($sqlClient);
            
                            while ($rowClient = $resultClient->fetch_assoc()) {
                                if(isset($_GET['client'])){
                                    if($_GET['client']==$rowClient['id']){
                                        $selectedClient= "selected";
                                    }else{
                                        $selectedClient= "";
                                    }
                                }else{
                                    $selectedClient= "";
                                }
                                
                                echo '<option value="' . $rowClient['id'] . '" '.$selectedClient.'>' . $rowClient['username'] . '</option>';
                            }
                        ?>
                </select>
                    
                    <a href="./projects" class="btn btn-secondary gap-2">
                        Projelerin
                    </a>
                    <a href="./projects?type=team" class="btn btn-info gap-2">
                        Ekip
                    </a>
                    <a href="./projects?type=all" class="btn btn-primary gap-2">
                        Tüm Projeler
                    </a>
                    
            </div>
        </div>

        <style>
            .panel {
                position: relative;
                overflow-y: hidden; /* Dikey kaydırmayı varsayılan olarak gizle */
                overflow-x: hidden; /* Yatay kaydırmayı gizle */
                padding-right: 10px; /* Sağda boşluk bırakmak için padding ekle */
                box-sizing: border-box;
            }

            .sortable-list {
                max-height: calc(100vh - 275px); /* Yüksekliği sınırlayın */
                overflow-x: hidden; /* Yatay kaydırmayı tamamen gizleyin */
                padding-right: 10px; /* Sağda boşluk bırakmak için padding ekle */
                box-sizing: border-box;
            }

            .sortable-list:hover {
                overflow-y: auto;
            }

            .task-item {
                max-width: 100%; /* Genişliğin taşmasını engeller */
                box-sizing: border-box;
                overflow-y: visible; /* İçerik öğelerinde dikey kaydırmayı devre dışı bırakın */
            }
        </style>
        <!-- project list -->
        <div class="relative pt-5">
            <div class="h-full">
                <div class="flex flex-nowrap items-start gap-5 overflow-x-auto px-2 pb-2">
                    <?php

                    // Proje Türüne Göre Verileri Çek
                    foreach ($tableNames as $table) {
                        
                        if($table == "Tamamlandı" or $table == "Reddedildi"){
                            $orderContition = "ORDER BY dateComplete DESC";
                        }else{
                            $orderContition = "ORDER BY displayOrder";
                        }
                        
                        // Tarih Filtresi
                $dateCondition = '';
                if ($completed) {
                    switch ($completed) {
                        case 'thisMonth':
                            $dateCondition = "AND MONTH(dateComplete) = MONTH(CURRENT_DATE()) AND YEAR(dateComplete) = YEAR(CURRENT_DATE())";
                            break;
                        case 'lastMonth':
                            $dateCondition = "AND MONTH(dateComplete) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(dateComplete) = YEAR(CURRENT_DATE())";
                            break;
                        case '30days':
                            $dateCondition = "AND dateComplete >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)";
                            break;
                        case 'thisWeek':
                            $dateCondition = "AND WEEK(dateComplete, 1) = WEEK(CURRENT_DATE(), 1) AND YEAR(dateComplete) = YEAR(CURRENT_DATE())";
                            break;
                        case 'lastWeek':
                            $dateCondition = "AND WEEK(dateComplete, 1) = WEEK(CURRENT_DATE() - INTERVAL 1 WEEK, 1) AND YEAR(dateComplete) = YEAR(CURRENT_DATE())";
                            break;
                        case 'today':
                            $dateCondition = "AND DATE(dateComplete) = CURRENT_DATE()";
                            break;
                        case 'yesterday':
                            $dateCondition = "AND DATE(dateComplete) = DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY)";
                            break;
                    }
                }

                // Müşteri Filtresi
                $clientCondition = $client ? "AND client_id = '$client'" : '';

                // Çalışan Filtresi
                $employeeCondition = '';
                if ($type === 'emp' && $employees) {
                    $employeeIDs = implode(',', $employees);
                    $employeeCondition = "AND id IN (SELECT project_id FROM projects_assignees WHERE user_id IN ($employeeIDs))";
                    
                    $userId = $employeeIDs;

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
                    
                    $pageTitle = "Projeleri";
                }
                        $statusCondition = "status='$table'";
                        if ($type === 'all') {
                            $sqlProject = "SELECT * FROM projects WHERE $statusCondition AND active='1' $dateCondition $clientCondition $orderContition";
                        } elseif ($type === 'team') {
                            $sqlProject = "SELECT * FROM projects WHERE $statusCondition AND active='1' AND id NOT IN (SELECT project_id FROM projects_assignees WHERE user_id = '$activeUserID') $dateCondition $clientCondition $orderContition";
                        } elseif ($type === 'emp') {
                            $sqlProject = "SELECT * FROM projects WHERE $statusCondition AND active='1' $employeeCondition $dateCondition $clientCondition $orderContition";
                        } else {
                            $sqlProject = "SELECT * FROM projects WHERE $statusCondition AND active='1' AND id IN (SELECT project_id FROM projects_assignees WHERE user_id = '$activeUserID') $dateCondition $clientCondition $orderContition";
                        }

                        $result = $conn->query($sqlProject);
                        include "scrumboard/table.php";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function postEmployee() {
            var select = document.getElementById("selectedEmployee");
            var employee = select.value;
            if (employee) {
                window.location.href = "projects?type=emp&employee=" + employee;
            }
        }
        function postClient() {
            var select = document.getElementById("selectedClient");
            var client = select.value;
            if (client) {
                window.location.href = "projects?type=all&client=" + client;
            }
        }
    </script>
    <?php include "scrumboard/modules/dateTimePicker.php"; ?>
    <?php include "scrumboard/modules/updateProjectInvoiceModal.php"; ?>
    <?php include "scrumboard/modules/commentModal.php"; ?>

</div>