<?php
session_start(); // Oturumu başlatın

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
include "../../../../assets/db/db_connect.php";
include "../../../../php/push-notification.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $comment = $_POST['comment'] ?? '';
    $user = $_SESSION['userID'] ?? null; // Kullanıcı ID'yi oturumdan al

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

    // Fotoğrafları yükleyin ve küçük versiyonlarını oluşturun
    if (isset($_FILES['photos'])) {
        foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['photos']['name'][$key];
            $file_tmp = $_FILES['photos']['tmp_name'][$key];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $unique_id = uniqid();
            $file_path = "../../../../uploads/projects/comments/" . $unique_id . "." . $file_ext;
            $thumbnail_path = "../../../../uploads/projects/comments/thumbnails/" . $unique_id . "_thumb." . $file_ext;

            if (move_uploaded_file($file_tmp, $file_path)) {
                create_thumbnail($file_path, $thumbnail_path, 272, 128);
                $photoUrls[] = "uploads/projects/comments/" . basename($file_path);
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
        $stmt->bind_param("sissss", $user, $id, $comment, $photosJson, $documentsJson, $audiosJson);
        if ($stmt->execute()) {
            $commentId = $stmt->insert_id;

            // Bildirim oluşturma
            createNotification($commentId, $id, $user, $comment);

            echo json_encode([
                'status' => 'success',
                'message' => 'Yorum başarıyla eklendi',
                'commentId' => $commentId,
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

function createNotification($comment_id, $related_project, $comment_user_id, $comment_text) {
    global $conn;

    $users_to_notify = getUsersToNotify($related_project, $comment_user_id);

    // Etiketlenen kullanıcıları bul ve listeye ekle
    preg_match_all('/@(\d+)/', $comment_text, $matches);
    if (!empty($matches[1])) {
        foreach ($matches[1] as $user_id) {
            if ($user_id != $comment_user_id) {
                $users_to_notify[] = $user_id;
            }
        }
    }

    // Yöneticileri ekle
    $query = "SELECT id FROM ac_users WHERE userType = 1 AND status = 1 AND nt_notifyAll = 1";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $users_to_notify[] = $row['id'];
    }

    // Tekrar edenleri kaldır
    $users_to_notify = array_unique($users_to_notify);
    foreach ($users_to_notify as $user_id) {
        $query = "INSERT INTO nt_comments (author, username, comment_id) VALUES ('$comment_user_id', '$user_id', '$comment_id')";
        mysqli_query($conn, $query);
    }
    
    if (!empty($users_to_notify)) {
        $title = "Yeni Yorum!";
        $body = "Bir projeye yeni yorum yapıldı: " . substr($comment_text, 0, 50) . "...";
        $icon = "https://work.kanatmedya.com/uploads/logo/kanatmedyafavicon.ico";
        $click_action = "https://work.kanatmedya.com/project?id=$related_project";

        sendPushNotify($users_to_notify, $title, $body, $icon, $click_action);
    }
}

function getUsersToNotify($project_id, $comment_user_id) {
    global $conn;
    $users = [];

    $query = "SELECT u.id FROM projects_assignees pa
              JOIN ac_users u ON pa.user_id = u.id
              WHERE pa.project_id = ? AND u.id != ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $project_id, $comment_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $users[] = $row['id'];
    }

    return $users;
}

function create_thumbnail($source_path, $destination_path, $desired_width, $desired_height) {
    // Dosya uzantısını kontrol et
    $file_ext = strtolower(pathinfo($source_path, PATHINFO_EXTENSION));

    // Uygun fonksiyonu seç
    switch ($file_ext) {
        case 'jpeg':
        case 'jpg':
            $source_image = imagecreatefromjpeg($source_path);
            break;
        case 'png':
            $source_image = imagecreatefrompng($source_path);
            break;
        case 'gif':
            $source_image = imagecreatefromgif($source_path);
            break;
        default:
            echo "Desteklenmeyen dosya formatı: $file_ext";
            return;
    }

    $source_width = imagesx($source_image);
    $source_height = imagesy($source_image);

    // Oranı koruyarak yeni boyutu hesapla
    $source_aspect_ratio = $source_width / $source_height;
    $thumbnail_aspect_ratio = $desired_width / $desired_height;

    if ($source_aspect_ratio > $thumbnail_aspect_ratio) {
        $temp_height = $desired_height;
        $temp_width = (int) ($desired_height * $source_aspect_ratio);
    } else {
        $temp_width = $desired_width;
        $temp_height = (int) ($desired_width / $source_aspect_ratio);
    }

    // Yeniden boyutlandırılmış resmi oluştur
    $temp_image = imagecreatetruecolor($temp_width, $temp_height);
    imagecopyresampled($temp_image, $source_image, 0, 0, 0, 0, $temp_width, $temp_height, $source_width, $source_height);

    // Kırpma işlemi
    $x0 = ($temp_width - $desired_width) / 2;
    $y0 = ($temp_height - $desired_height) / 2;
    $thumbnail_image = imagecreatetruecolor($desired_width, $desired_height);
    imagecopy($thumbnail_image, $temp_image, 0, 0, $x0, $y0, $desired_width, $desired_height);

    // Kırpılmış ve yeniden boyutlandırılmış resmi kaydet
    switch ($file_ext) {
        case 'jpeg':
        case 'jpg':
            imagejpeg($thumbnail_image, $destination_path);
            break;
        case 'png':
            imagepng($thumbnail_image, $destination_path);
            break;
        case 'gif':
            imagegif($thumbnail_image, $destination_path);
            break;
    }

    // Hafızayı temizle
    imagedestroy($source_image);
    imagedestroy($temp_image);
    imagedestroy($thumbnail_image);
}

function getProjectById($project_id) {
    global $conn;
    $query = "SELECT * FROM projects WHERE id = '$project_id'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}
?>
