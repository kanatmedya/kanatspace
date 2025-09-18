<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = intval($_POST['id'] ?? 0);

    if ($projectId <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz proje ID']);
        exit;
    }

    // Fatura bilgilerini kontrol et ve güncelle
    $query = "SELECT invoice_id FROM projects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
        $oldInvoiceId = $project['invoice_id'];

        if ($oldInvoiceId === null) {
            echo json_encode(['status' => 'error', 'message' => 'Bu proje için zaten fatura bulunmamaktadır.']);
            exit;
        }

        // Projeyi güncelle: invoice_id = NULL, status_invoice = 0
        $updateQuery = "UPDATE projects SET invoice_id = NULL, status_invoice = 0 WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("i", $projectId);
        if ($updateStmt->execute()) {
            // Log tablosuna "remove" kaydı ekle
            $userId = 1; // Giriş yapan kullanıcının ID'si (dinamik yapılabilir)
            $logQuery = "INSERT INTO log_projects_invoices (user_id, project_id, request_type, invoice_id) VALUES (?, ?, 'remove', ?)";
            $logStmt = $conn->prepare($logQuery);
            $logStmt->bind_param("iii", $userId, $projectId, $oldInvoiceId);
            $logStmt->execute();
            $logStmt->close();

            echo json_encode(['status' => 'success', 'message' => 'Fatura başarıyla iptal edildi.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Fatura iptali sırasında bir hata oluştu.']);
        }
        $updateStmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Proje bulunamadı.']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz istek.']);
}