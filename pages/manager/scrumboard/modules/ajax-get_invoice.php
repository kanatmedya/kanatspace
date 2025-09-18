<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = intval($_POST['id'] ?? 0);

    if ($projectId > 0) {
        $query = "SELECT invoice_id, status_invoice FROM projects WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Proje bulunamadı.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz proje ID.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz istek.']);
}
?>
