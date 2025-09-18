<?php

session_start();

include ("../../../../assets/db/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $projectType = $_POST['projectType'];
    $deadlineDate = $_POST['deadlineDate'];
    $deadlineTime = $_POST['deadlineTime'];
    $client = $_POST['client'];
    $visibility = $_POST['visibility'];
    $description = $_POST['description'];
    $creator = $_SESSION['name'];

    if ($visibility == '') {
        $visibility = 'hidden';
    }

    if (isset($_POST['employee'])) {
        $employee = $_POST['employee'];
    } else {
        $employee = '';
    }

    // Deadline tarih ve zamanını birleştirme
    $deadline = $deadlineDate . ' ' . $deadlineTime . ':00';

    // Veritabanına ekleme
    $sql = "INSERT INTO projects (title, projectType, dateDeadline, client, employee, visibility, description, status, active, creator)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Proje', 1, ?)";

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die('Prepare Error: ' . $conn->error);
    }

    $stmt->bind_param("ssssssss", $title, $projectType, $deadline, $client, $employee, $visibility, $description, $creator);

    if ($stmt->execute()) {
        echo "Proje başarıyla kaydedildi. Teşekkürler " . $creator;
    } else {
        echo "Hata: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    exit;
}
?>
