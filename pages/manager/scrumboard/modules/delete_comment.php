<?php
session_start(); // Oturumu başlatın

header('Content-Type: application/json');
include "../../../../assets/db/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $commentId = $_POST['commentId'];

    // Yorum bilgilerini çekin
    $sql = "SELECT photos, documents, audios FROM projects_comments WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $comment = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Hazırlık hatası: ' . $conn->error]);
        exit();
    }

    // Yorum silme işlemi
    $sql = "DELETE FROM projects_comments WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $commentId);
        if ($stmt->execute()) {
            // Yorum silindikten sonra dosyaları silin
            $photos = json_decode($comment['photos'], true);
            $documents = json_decode($comment['documents'], true);
            $audios = json_decode($comment['audios'], true);

            // Fotoğrafları ve thumb'ları silin
            if (is_array($photos)) {
                foreach ($photos as $photo) {
                    $photoPath = "../../../../" . $photo; // Fotoğraf yolu
                    if (file_exists($photoPath)) {
                        unlink($photoPath);
                    }
                    // Thumbnail yolunu oluştur ve sil
                    $thumbnailPath = str_replace('projects/comments/', 'projects/comments/thumbnails/', $photo);
                    $thumbnailPath = str_replace('.jpg', '_thumb.jpg', $thumbnailPath); // Burada .jpg dosya uzantısını varsayıyoruz. Gerektiğinde bu kısmı uzantıya göre dinamik hale getirebilirsin.
                    if (file_exists($thumbnailPath)) {
                        unlink($thumbnailPath);
                    }
                }
            }

            // Belgeleri silin
            if (is_array($documents)) {
                foreach ($documents as $document) {
                    $documentPath = "../../../../" . $document['url']; // Belge yolu
                    if (file_exists($documentPath)) {
                        unlink($documentPath);
                    }
                }
            }

            // Ses dosyalarını silin
            if (is_array($audios)) {
                foreach ($audios as $audio) {
                    $audioPath = "../../../../" . $audio; // Ses dosyası yolu
                    if (file_exists($audioPath)) {
                        unlink($audioPath);
                    }
                }
            }
            
            // Bildirimleri silin
            $sqlNotification = "DELETE FROM nt_comments WHERE comment_id = ?";
            $stmtNotification = $conn->prepare($sqlNotification);
            $stmtNotification->bind_param("i", $commentId);
            $stmtNotification->execute();
            

            echo json_encode(['status' => 'success', 'message' => 'Yorum başarıyla silindi']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Sorgu hatası: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Hazırlık hatası: ' . $conn->error]);
    }
}
?>
