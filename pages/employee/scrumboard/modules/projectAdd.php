<?php

include ("../../../../assets/db/db_connect.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $projectType = $_POST['projectType'];
    $deadlineDate = $_POST['deadlineDate'];
    $deadlineTime = $_POST['deadlineTime'];
    $client = $_POST['client'];
    $visibility = $_POST['visibility'];
    $description = $_POST['description'];

    if ($visibility == '') {
        $visibility = 'hidden';
    }

    if (isset($_POST['employee'])) {
        $employee = $_POST['employee'];
    } else {
        $employee = $_SESSION['name'];
    }


    // Deadline tarih ve zamanını birleştirme
    $deadline = $deadlineDate . ' ' . $deadlineTime . ':00';

    // Veritabanına ekleme
    $sql = "INSERT INTO projects (title, projectType, deadline, client, employee, visibility, description, status, active)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Proje', 1)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssss", $title, $projectType, $deadline, $client, $employee, $visibility, $description);

    if ($stmt->execute()) {
        echo "Proje başarıyla kaydedildi.";
    } else {
        echo "Hata: " . $stmt->error;
    }

    exit;
}
?>