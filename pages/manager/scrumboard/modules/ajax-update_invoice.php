<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $invoiceId = isset($_POST['invoice_id']) ? intval($_POST['invoice_id']) : null;

    error_log('Gönderilen Proje ID: ' . $projectId); // Debugging için
    error_log('Gönderilen Fatura ID: ' . $invoiceId); // Debugging için

    if ($projectId <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz proje ID']);
        exit;
    }else if ($invoiceId === null) {
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz fatura ID']);
        exit;
    }

    if ($projectId > 0 && $invoiceId !== null) {
        $query = "SELECT invoice_id, status_invoice FROM projects WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $project = $result->fetch_assoc();
            $oldInvoiceId = $project['invoice_id'];
            $oldStatusInvoice = $project['status_invoice'];

            $newStatusInvoice = ($invoiceId !== null) ? 1 : 0;

            // Proje güncelle
            $updateQuery = "UPDATE projects SET invoice_id = ?, status_invoice = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("iii", $invoiceId, $newStatusInvoice, $projectId);
            $updateSuccess = $updateStmt->execute();
            $updateStmt->close();

            if ($updateSuccess) {
                // Log tablosuna ekleme
                $userId = 1; // Burada oturum açmış kullanıcının ID'sini kullanın.
                $requestType = '';

                if ($oldStatusInvoice == 0 && $newStatusInvoice == 1) {
                    $requestType = 'add';
                } elseif ($oldStatusInvoice == 1 && $newStatusInvoice == 0 && $invoiceId === null) {
                    $requestType = 'remove';
                } elseif ($oldStatusInvoice == 1 && $newStatusInvoice == 1 && $oldInvoiceId != $invoiceId) {
                    $requestType = 'update';
                }

                if ($requestType !== '') {
                    $logQuery = "INSERT INTO log_projects_invoices (user_id, project_id, request_type, invoice_id) VALUES (?, ?, ?, ?)";
                    $logStmt = $conn->prepare($logQuery);
                    $logStmt->bind_param("iisi", $userId, $projectId, $requestType, $invoiceId);
                    $logStmt->execute();
                    $logStmt->close();
                }

                echo json_encode(['status' => 'success', 'message' => 'Fatura bilgisi başarıyla güncellendi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Fatura bilgisi güncellenemedi.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Proje bulunamadı.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz veri.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz istek.']);
}
?>
