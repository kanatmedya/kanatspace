<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['userID'];

$response = [
    "status" => "error",  // Başlangıçta varsayılan olarak 'error' durumu belirlenir
    "message" => "Proje kaydedilirken bir hata oluştu."
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // POST verilerini al
    $title = $_POST['title'] ?? '';
    $projectType = $_POST['projectType'] ?? '';
    $deadlineDate = $_POST['deadlineDate'] ?? '';
    $deadlineTime = $_POST['deadlineTime'] ?? '';
    $client = $_POST['client'] ?? '';
    $visibility = $_POST['visibility'] ?? '';
    $description = $_POST['description'] ?? '';
    
    $sqlClient = "SELECT id FROM users_client WHERE username = ?";
    $stmtClient = $conn->prepare($sqlClient);
    
    if ($stmtClient === false) {
        $response["message"] = "Müşteri sorgusu hazırlanırken bir hata oluştu: " . $conn->error;
    } else {
        $stmtClient->bind_param("s", $client);
    
        if ($stmtClient->execute()) {
            $resultClient = $stmtClient->get_result();
    
            if ($resultClient->num_rows > 0) {
                $rowClient = $resultClient->fetch_assoc();
                $client_id = $rowClient['id']; // İlgili id'yi alıyoruz
            } else {
                $response["message"] = "Belirtilen müşteri bulunamadı.";
            }
        } else {
            $response["message"] = "Müşteri sorgusu çalıştırılırken bir hata oluştu: " . $stmtClient->error;
        }
    
        $stmtClient->close();
    }
    
    // Eğer $client_id bulunamadıysa işlemi sonlandır
    if (is_null($client_id)) {
        $client_id = 0;
    }

    // Visibility değeri boşsa varsayılan 'hidden' olarak ayarlanır
    if (empty($visibility)) {
        $visibility = 'hidden';
    }

    // Deadline tarih ve zamanını birleştirme
    $deadline = $deadlineDate . ' ' . $deadlineTime . ':00';

    // Proje ekleme sorgusu
    $sql = "INSERT INTO projects (title, projectType, dateDeadline, client, client_id, visibility, description, status, active, creator)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Proje', 1, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $response["message"] = "Sorgu hazırlanırken bir hata oluştu: " . $conn->error;
    } else {
        $stmt->bind_param("sissssss", $title, $projectType, $deadline, $client, $client_id, $visibility, $description, $user_id);

        if ($stmt->execute()) {
            $projectId = $stmt->insert_id; // Yeni proje ID'sini al
            $response["status"] = "success";
            $response["message"] = "Proje başarıyla kaydedildi.";

            // Görevli ekleme işlemi
            if (isset($_POST['employeeId']) && !empty($_POST['employeeId'])) {
                $employeeIds = explode(',', $_POST['employeeId']); // Çoklu görevli ekleme için IDs'yi ayır

                foreach ($employeeIds as $employeeId) {
                    $sqlAssign = "INSERT INTO projects_assignees (project_id, user_id) VALUES (?, ?)";
                    $stmtAssign = $conn->prepare($sqlAssign);

                    if ($stmtAssign === false) {
                        $response["status"] = "error";
                        $response["message"] = "Görevli eklenirken bir hata oluştu: " . $conn->error;
                        break;
                    }

                    $stmtAssign->bind_param("ii", $projectId, $employeeId);

                    if (!$stmtAssign->execute()) {
                        $response["status"] = "error";
                        $response["message"] = "Görevli eklenirken bir hata oluştu: " . $stmtAssign->error;
                        break;
                    }

                    $stmtAssign->close();
                }
            }
        } else {
            $response["message"] = "Proje kaydedilirken bir hata oluştu: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();

    // Yanıtı JSON olarak döndür
    echo json_encode($response);
    exit;
}
