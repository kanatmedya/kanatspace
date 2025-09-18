<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Projeye ait id alınıyor
$projectId = $row['id']; // Bu değer mevcut satırdan (row) alınıyor

// SQL sorgusu hazırlanıyor
$query = "SELECT photos FROM projects_comments WHERE related = ? AND photos IS NOT NULL ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $projectId); // projectId'yi bağla
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $comment = $result->fetch_assoc();

    if (!empty($comment['photos'])) {
        $photoPathsJson = $comment['photos'];
        $photoPaths = json_decode($photoPathsJson);

        if (!empty($photoPaths)) {
            $originalPath = $photoPaths[0]; // İlk fotoğrafın orijinal yolunu alıyoruz
            $thumbnailPath = str_replace('projects/comments/', 'projects/comments/thumbnails/', $originalPath);

            // Thumb dosyasının varlığını kontrol et
            $fileExtension = strtolower(pathinfo($originalPath, PATHINFO_EXTENSION));
            $thumbnailPath = preg_replace("/\.(jpg|jpeg|png)$/i", "_thumb.jpg", $thumbnailPath);

            if (!file_exists($originalPath)) {
                error_log("Kaynak dosya bulunamadı: $originalPath");
                echo '<img src="path_to_placeholder_image.jpg" alt="No Image" class="lazyload h-32 w-full rounded-md object-cover" />';
                return;
            }

            if (!file_exists($thumbnailPath)) {
                // Thumbnail yoksa oluştur
                createThumbnail($originalPath, $thumbnailPath, 272, 128);
            }

            echo '<a href="./' . $originalPath . '" target="_blank">';
            echo '<img src="./' . $thumbnailPath . '" alt="images" class="lazyload h-32 w-full rounded-md object-cover" />';
            echo '</a>';
        } else {
            
        }
    }
} else {
    // Fotoğraf yoksa bir şey döndürmeyebilir veya bir uyarı mesajı gösterebilirsiniz.
}