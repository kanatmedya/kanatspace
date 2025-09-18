<?php
session_start();
header('Content-Type: application/json');

include '../../assets/db/db_connect.php';

date_default_timezone_set('Europe/Istanbul'); // veya geçerli olan diğer bir zaman dilimi

$activeUser = $_SESSION['userID'];

try {
    $sql = "SELECT * FROM nt_comments WHERE username='$activeUser' AND author!='$activeUser' ORDER BY readed ASC, dateCreate DESC LIMIT 5";
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception("Veritabanı sorgusu başarısız: " . $conn->error);
    }

    $notifications = [];
    $playSound = false; // Yeni bildirim olup olmadığını kontrol etmek için
    while ($row = $result->fetch_assoc()) {
        $comID = $row['comment_id'];

        // Yorum metnini alın
        $sqlCom = "SELECT * FROM projects_comments WHERE id=?";
        $stmtCom = $conn->prepare($sqlCom);

        if ($stmtCom === false) {
            throw new Exception("Yorum sorgusu hazırlama hatası: " . $conn->error);
        }

        $stmtCom->bind_param("i", $comID);
        $stmtCom->execute();
        $resCom = $stmtCom->get_result();

        if ($resCom === false) {
            throw new Exception("Yorum sorgusu çalıştırma hatası: " . $stmtCom->error);
        }

        $comText = '';
        if ($resCom->num_rows > 0) {
            $rowCom = $resCom->fetch_assoc();
            $comText = $rowCom['value'];
            if ($comText == '') {
                $comText = "<i>Dosya Ekledi</i>";
            }
        }

        // Profil fotoğrafını alın
        $comAuthor = $row['author'];
        $tables = ['ac_users'];
        $profilePicture = null;

        $sqlProfile = "SELECT * FROM ac_users WHERE id = ?";
        $stmtProfile = $conn->prepare($sqlProfile);
        if ($stmtProfile === false) {
            throw new Exception("Profil fotoğrafı sorgusu hazırlama hatası: " . $conn->error);
        }
        $stmtProfile->bind_param("s", $comAuthor);
        $stmtProfile->execute();
        $resultProfile = $stmtProfile->get_result();
        if ($resultProfile->num_rows > 0) {
            $rowProfile = $resultProfile->fetch_assoc();
            $profilePicture = $rowProfile['profilePicture'];
            $authorName = $rowProfile['name'];
            $authorSurName = $rowProfile['surname'];

            $authorFullName = $authorName . ' ' . $authorSurName;
        }

        $profilePicture = $profilePicture ? $profilePicture : 'uploads/users/profile/default.jpg';

        // soundNotify kontrolü
        if ($row['soundNotify'] == 0) {
            $playSound = true;

            // soundNotify ve soundNotifyDate güncellenmesi
            $updateSql = "UPDATE nt_comments SET soundNotify = 1, soundNotifyDate = NOW() WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            if ($updateStmt === false) {
                throw new Exception("Güncelleme sorgusu hazırlama hatası: " . $conn->error);
            }
            $updateStmt->bind_param("i", $row['id']);
            $updateStmt->execute();
            if ($updateStmt->error) {
                throw new Exception("Güncelleme sorgusu çalıştırma hatası: " . $updateStmt->error);
            }
        }

        $notifications[] = [
            'id' => $row['id'],
            'author' => $authorFullName,
            'comText' => $comText,
            'comTime' => $row['dateCreate'],
            'projectID' => $rowCom['related'],
            'comReaded' => $row['readed'],
            'profilePicture' => $profilePicture,
            'soundNotify' => $row['soundNotify']
        ];
    }

    // Yeni bildirimlerin sayısını alın
    $sqlNew = "SELECT COUNT(*) as count FROM nt_comments WHERE author != '$activeUser' AND username = '$activeUser' AND readed = 0";
    $resultNew = $conn->query($sqlNew);
    if ($resultNew === false) {
        throw new Exception("Yeni bildirim sayısı sorgusu başarısız: " . $conn->error);
    }
    $numNewCom = $resultNew->fetch_assoc()['count'];

    echo json_encode([
        'success' => true,
        'notifications' => $notifications,
        'numNewCom' => $numNewCom,
        'playSound' => $playSound // Yeni bildirim olup olmadığını belirtiyoruz
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
