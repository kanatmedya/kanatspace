<?php
include("../../../../assets/db/db_connect.php");
session_start();  // Eğer session başlatılmadıysa bu satırı ekleyin

// Log ekleme fonksiyonu
function logProjectChange($conn, $itemId, $userId, $previousStatus, $newStatus) {
    $stmtLog = $conn->prepare("INSERT INTO log_projects (`project_id`, `user`, `old`, `new`) VALUES (?, ?, ?, ?)");
    if (!$stmtLog) {
        throw new Exception("Log prepare failed: " . $conn->error);
    }
    $stmtLog->bind_param('iiss', $itemId, $userId, $previousStatus, $newStatus);
    $stmtLog->execute();
}

if (isset($_POST['orders'])) {
    $orders = json_decode($_POST['orders'], true);
    $itemId = $_POST['itemId'];
    $newStatus = $_POST['newStatus'];
    $previousStatus = $_POST['previousStatus'];
    $userId = $_SESSION['userID'];  // userId'ı $_SESSION'dan al

    $conn->begin_transaction();
    try {
        // Projelerin sıralamasını güncelle
        $stmt = $conn->prepare("UPDATE projects SET `displayOrder` = ?, `status` = ? WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        foreach ($orders as $order) {
            $stmt->bind_param('isi', $order['order'], $order['status'], $order['id']);
            $stmt->execute();
        }

        // Eğer yeni durum 'Tamamlandı' ise, sadece sıralamayı güncelle, dateComplete'i değiştirme
        if ($newStatus === 'Tamamlandı' && $previousStatus === 'Tamamlandı') {
            // Aynı sütun içinde sadece sıralama değişiyorsa, log oluşturma gerekmez
        } else if ($newStatus === 'Tamamlandı' && $previousStatus !== 'Tamamlandı') {
            // Eğer proje başka bir durumdan 'Tamamlandı' durumuna taşınırsa, dateComplete sütununu güncelle
            $stmtComplete = $conn->prepare("UPDATE projects SET `dateComplete` = NOW() WHERE id = ?");
            if (!$stmtComplete) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmtComplete->bind_param('i', $itemId);
            $stmtComplete->execute();

            // project_logs tablosuna log ekle
            logProjectChange($conn, $itemId, $userId, $previousStatus, $newStatus);
        } else if ($previousStatus === 'Tamamlandı' && $newStatus !== 'Tamamlandı') {
            // Eğer proje 'Tamamlandı'dan başka bir duruma taşındıysa dateComplete sütununu NULL yap
            $stmtResetComplete = $conn->prepare("UPDATE projects SET `dateComplete` = NULL WHERE id = ?");
            if (!$stmtResetComplete) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmtResetComplete->bind_param('i', $itemId);
            $stmtResetComplete->execute();

            // project_logs tablosuna log ekle
            logProjectChange($conn, $itemId, $userId, $previousStatus, $newStatus);
        } else {
            // Eğer durumlar farklıysa project_logs tablosuna log ekle
            logProjectChange($conn, $itemId, $userId, $previousStatus, $newStatus);
        }

        $conn->commit();
        echo "Order and logs updated successfully.";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error updating order or logs: " . $e->getMessage();
    }

    $stmt->close();
    $conn->close();
}
