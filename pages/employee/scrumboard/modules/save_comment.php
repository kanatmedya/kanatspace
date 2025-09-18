<?php
session_start(); // Oturumu başlatın

header('Content-Type: application/json');
include "../../../../assets/db/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $comment = $_POST['comment'] ?? '';
    $user = $_SESSION['name'] ?? null; // Kullanıcı adını oturumdan al

    error_log("POST id: " . print_r($id, true));
    error_log("POST comment: " . print_r($comment, true));
    error_log("SESSION user: " . print_r($user, true));

    $photoUrls = [];
    $documentUrls = [];
    $audioUrls = [];

    if (!$id || !$user) {
        echo json_encode(['status' => 'error', 'message' => 'Gerekli bilgiler eksik']);
        exit();
    }

    // Fotoğrafları yükleyin
    if (isset($_FILES['photos'])) {
        foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['photos']['name'][$key];
            $file_tmp = $_FILES['photos']['tmp_name'][$key];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $file_path = "../../../../uploads/projects/comments/" . uniqid() . "." . $file_ext;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $photoUrls[] = "uploads/projects/comments/" . basename($file_path); // relative path for frontend
            }
        }
    }

    // Belgeleri yükleyin
    if (isset($_FILES['documents'])) {
        foreach ($_FILES['documents']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['documents']['name'][$key];
            $file_tmp = $_FILES['documents']['tmp_name'][$key];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $file_path = "../../../../uploads/projects/comments/" . uniqid() . "." . $file_ext;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $documentUrls[] = ['url' => "uploads/projects/comments/" . basename($file_path), 'name' => $file_name];
            }
        }
    }

    // Ses dosyalarını yükleyin
    if (isset($_FILES['audios'])) {
        foreach ($_FILES['audios']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['audios']['name'][$key];
            $file_tmp = $_FILES['audios']['tmp_name'][$key];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $file_path = "../../../../uploads/projects/comments/" . uniqid() . "." . $file_ext;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $audioUrls[] = "uploads/projects/comments/" . basename($file_path);
            }
        }
    }

    // Veritabanına kaydedilecek değerleri belirleyin
    $photosJson = !empty($photoUrls) ? json_encode($photoUrls) : null;
    $documentsJson = !empty($documentUrls) ? json_encode($documentUrls) : null;
    $audiosJson = !empty($audioUrls) ? json_encode($audioUrls) : null;

    // Yorumları veritabanına kaydedin
    $sql = "INSERT INTO projects_comments (type, user, related, value, photos, documents, audios) VALUES ('projectComment', ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parametrelerini yazdırarak kontrol edelim
        error_log("Bind Params: user=$user, id=$id, comment=$comment, photosJson=$photosJson, documentsJson=$documentsJson, audiosJson=$audiosJson");

        $stmt->bind_param("sissss", $user, $id, $comment, $photosJson, $documentsJson, $audiosJson);
        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Yorum başarıyla eklendi',
                'commentId' => $stmt->insert_id,
                'photos' => $photoUrls,
                'documents' => $documentUrls,
                'audios' => $audioUrls
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Sorgu hatası: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Hazırlık hatası: ' . $conn->error]);
    }
}
?>
