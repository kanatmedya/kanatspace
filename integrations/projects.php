<?php
// projects.php - FivBot API

header('Content-Type: application/json');
include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/db/db_connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php/push-notification.php";

$api_key = $_POST['api_key'] ?? '';
$action = $_POST['action'] ?? '';

// API anahtarı kontrolü
if (!isValidApiKey($api_key)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid API key.']);
    exit;
}

switch ($action) {
    case 'create_project':
        handleCreateProject();
        break;

    case 'add_comment':
        handleAddComment();
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz işlem']);
        break;
}

function handleCreateProject() {
    global $conn, $api_key;

    $title = $_POST['title'] ?? '';
    $projectType = $_POST['projectType'] ?? 0;
    $assignedUsers = json_decode($_POST['assignedUsers'] ?? '[]', true);
    $firstComment = json_decode($_POST['firstComment'] ?? '{}', true);

    if (empty($title) || empty($assignedUsers)) {
        echo json_encode(['status' => 'error', 'message' => 'Başlık ve atanan kullanıcılar gerekli.']);
        return;
    }

    $description = $_POST['description'];
    $client_id = $_POST['client_id'] ?? 0; // dışarıdan gelen client_id

    try {
        // Proje oluştur
        $stmt = $conn->prepare("INSERT INTO projects (title, description, projectType, status, active, client_id) VALUES (?, ?, ?, 'Proje', 1, ?)");
        $stmt->bind_param("ssii", $title, $description, $projectType, $client_id);
        $stmt->execute();
        $projectId = $conn->insert_id;

        // Kullanıcıları ata
        foreach ($assignedUsers as $userId) {
            $stmt = $conn->prepare("INSERT INTO projects_assignees (project_id, user_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $projectId, $userId);
            $stmt->execute();
        }

        // İlk yorumu ekle
        if (!empty($firstComment['value']) && !empty($firstComment['user'])) {
            insertComment($projectId, $firstComment);
        }

        updateUsageData($api_key);
        echo json_encode(['status' => 'success', 'message' => 'Proje oluşturuldu.']);

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function handleAddComment() {
    $projectId = $_POST['project_id'] ?? 0;
    $comment = json_decode($_POST['comment'] ?? '{}', true);

    if (empty($projectId) || empty($comment['value']) || empty($comment['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Eksik yorum bilgisi']);
        return;
    }

    try {
        insertComment($projectId, $comment);
        echo json_encode(['status' => 'success', 'message' => 'Yorum eklendi.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function insertComment($projectId, $comment) {
    global $conn;

    $user = $comment['user'];
    $value = $comment['value'];
    $photoUrls = $comment['photos'] ?? [];
    $documentUrls = $comment['documents'] ?? [];
    $audioUrls = $comment['audios'] ?? [];

    $photosJson = !empty($photoUrls) ? json_encode($photoUrls) : null;
    $documentsJson = !empty($documentUrls) ? json_encode($documentUrls) : null;
    $audiosJson = !empty($audioUrls) ? json_encode($audioUrls) : null;

    $sql = "INSERT INTO projects_comments (type, user, related, value, photos, documents, audios) VALUES ('projectComment', ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissss", $user, $projectId, $value, $photosJson, $documentsJson, $audiosJson);
    $stmt->execute();

    $commentId = $stmt->insert_id;
    createNotification($commentId, $projectId, $user, $value);
}

function createNotification($comment_id, $related_project, $comment_user_id, $comment_text) {
    global $conn;
    $users_to_notify = getUsersToNotify($related_project, $comment_user_id);

    // Etiketlenmiş kullanıcıları bul
    preg_match_all('/@(\d+)/', $comment_text, $matches);
    if (!empty($matches[1])) {
        foreach ($matches[1] as $user_id) {
            if ($user_id != $comment_user_id) {
                $users_to_notify[] = $user_id;
            }
        }
    }

    // Yöneticileri dahil et
    $query = "SELECT id FROM ac_users WHERE userType = 1 AND status = 1 AND nt_notifyAll = 1";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $users_to_notify[] = $row['id'];
    }

    $users_to_notify = array_unique($users_to_notify);
    foreach ($users_to_notify as $user_id) {
        $query = "INSERT INTO nt_comments (author, username, comment_id) VALUES ('$comment_user_id', '$user_id', '$comment_id')";
        mysqli_query($conn, $query);
    }

    if (!empty($users_to_notify)) {
        $title = "Yeni Yorum!";
        $body = "Bir projeye yeni yorum yapıldı: " . substr($comment_text, 0, 50) . "...";
        $icon = "https://work.kanatmedya.com/assets/media/system/kanatmedyafavicon.ico";
        $click_action = "https://work.kanatmedya.com/project?id=$related_project";

        if (function_exists('sendPushNotify')) {
            sendPushNotify($users_to_notify, $title, $body, $icon, $click_action);
        }
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

function isValidApiKey($api_key) {
    // Buraya gerçek doğrulama yazılabilir
    return true;
}

function isUsageLimitExceeded($api_key) {
    return false;
}

function updateUsageData($api_key) {
    // Kullanım kaydı güncellenebilir
}