<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!defined('ROOT_PATH')) {
        define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
    }

    if (!isset($_SESSION)) {
        session_start();
    }

    include ROOT_PATH . "assets/db/db_connect.php";
    global $conn;

    // Formdan gelen veriler
    $amount = $_POST['amount'];
    $cc = $_POST['cc'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $categorySub = $_POST['categorySub'];
    $sender = $_POST['sender'];
    $reciever = $_POST['reciever'];
    $description = $_POST['description'];
    $creator_id = $_SESSION['userID'];
    $date = $_POST['date'];
    $recurrence_period =  $_POST['recurrence_period'];

    // Taksit ve tekrar bilgileri
    $repeat_count = -1;

    if (!empty($_POST['installment_count'])) {
        $repeat_count = $_POST['installment_count'];
    } elseif (!empty($_POST['repeat_count'])) {
        $repeat_count = $_POST['repeat_count'];
    }

    $installment_amount = !empty($_POST['installment_amount']) ? $_POST['installment_amount'] : $_POST['amount'];

    // Payment plan description
    $payment_plan_description = $_POST['paymentPlanDescription'];

    // Veritabanına kaydetme
    $sql = "INSERT INTO accounting_scheduled_payments (cc, creator_id, total_amount, installment_amount, next_due_date, recurrence_period, repeat_count, type, category, categorySub, sender, reciever, description, payment_plan_description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Sorgu hazırlama hatasını kontrol et
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssssssssss", $cc, $creator_id, $amount, $installment_amount, $date, $recurrence_period, $repeat_count, $type, $category, $categorySub, $sender, $reciever, $description, $payment_plan_description);

        if ($stmt->execute()) {
            // Başarıyla eklenen satırın ID'sini JSON formatında geri döndür
            echo json_encode(['inserted_id' => $conn->insert_id]);
        } else {
            // Sorgu yürütme hatası
            echo json_encode(['error' => 'Kayıt sırasında bir hata oluştu: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        // SQL hazırlama hatası
        echo json_encode(['error' => 'SQL hazırlama hatası: ' . $conn->error]);
    }
}
?>
