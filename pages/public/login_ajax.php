<?php
session_start();

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

// Hata raporlamasını kapatalım
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Hataları loglayalım
ini_set('log_errors', 1);
ini_set('error_log', '/path_to_your_log/php_errors.log'); // Bu yolu güncelleyin

$response = ["success" => false, "message" => ""];

// JSON Yanıtı olarak döndürülecek verileri toplama süreci
header('Content-Type: application/json');

// IP adresi al
$ip_address = $_SERVER['REMOTE_ADDR'];

// Protokol versiyonu
$protocol_version = $_SERVER['SERVER_PROTOCOL'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $pass = htmlspecialchars($_POST['password']);
    $rememberMe = isset($_POST['remember_me']);

    // Tarayıcı bilgilerini elde etmek için User-Agent bilgisini kullan
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser_name = get_browser_name($user_agent);
    $browser_version = get_browser_version($user_agent);
    $operating_system = get_os($user_agent);
    $platform = get_platform($user_agent);
    $device_type = get_device_type($user_agent);
    $bit_version = get_bit_version($user_agent);

    if (!empty($email) && !empty($pass)) {
        $user = loginCheck("SELECT * FROM ac_users WHERE mail = ?", $email, $pass, $rememberMe);
        if ($user) {
            $response['success'] = true;
            $response['message'] = 'Login successful';
        } else {
            $response['message'] = 'E-posta veya şifre yanlış';
        }
    } else {
        $response['message'] = 'Lütfen Email Ve Şifreyi Giriniz!';
    }
} else {
    $response['message'] = 'Geçersiz istek yöntemi';
}

// Yanıtı JSON olarak döndürelim
echo json_encode($response);

// Aşağıdaki fonksiyonlar tarayıcı, işletim sistemi vb. bilgileri çekmek için kullanılıyor
function get_browser_name($user_agent) {
    if (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident')) return 'Internet Explorer';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR')) return 'Opera';
    return 'Unknown';
}

function get_browser_version($user_agent) {
    preg_match('/(Chrome|Firefox|MSIE|Trident|Opera|Safari)[\/ ]([0-9\.]+)/', $user_agent, $matches);
    return isset($matches[2]) ? $matches[2] : 'Unknown';
}

function get_os($user_agent) {
    if (strpos($user_agent, 'Windows NT 10.0')) return 'Windows 10';
    elseif (strpos($user_agent, 'Windows NT 6.1')) return 'Windows 7';
    elseif (strpos($user_agent, 'Mac OS X')) return 'Mac OS X';
    elseif (strpos($user_agent, 'Linux')) return 'Linux';
    elseif (strpos($user_agent, 'Android')) return 'Android';
    elseif (strpos($user_agent, 'iPhone')) return 'iPhone';
    return 'Unknown';
}

function get_platform($user_agent) {
    if (strpos($user_agent, 'Mac')) return 'Macintosh';
    elseif (strpos($user_agent, 'Windows')) return 'Windows';
    elseif (strpos($user_agent, 'Linux')) return 'Linux';
    return 'Unknown';
}

function get_device_type($user_agent) {
    if (strpos($user_agent, 'Mobile')) return 'Mobile';
    elseif (strpos($user_agent, 'Tablet')) return 'Tablet';
    return 'Desktop';
}

function get_bit_version($user_agent) {
    if (strpos($user_agent, 'WOW64') || strpos($user_agent, 'Win64')) return '64-bit';
    return '32-bit';
}

function loginCheck($sqlRequest, $email, $password, $rememberMe)
{
    global $conn;
    $stmt = $conn->prepare($sqlRequest);
    if (!$stmt) {
        global $response;
        $response['message'] = 'SQL Hazırlama hatası: ' . $conn->error;
        echo json_encode($response);
        exit();
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            loginTrue($user, $rememberMe);
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function loginTrue($user, $rememberMe, $firebaseToken = null)
{
    global $conn, $response;
    session_regenerate_id(true);

    $_SESSION['name'] = $user['name'] . " " . $user['surname'];
    $_SESSION['userID'] = $user['id'];
    $_SESSION['email'] = $user['mail'];
    $_SESSION['status'] = $user['userType'];
    $_SESSION['pic'] = $user['profilePicture'];
    $_SESSION['department'] = $user['department'];
    $_SESSION['position'] = $user['position'];

    if ($rememberMe) {
        $token = bin2hex(random_bytes(32));
        $userId = $user['id'];

        $query = "INSERT INTO user_tokens (user_id, user_type, token, firebase_token, expiry, ip_address, protocol_version, browser_name, browser_version, operating_system, device_type, platform, bit_version) 
                  VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY), ?, ?, ?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE firebase_token = VALUES(firebase_token)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param('iissssssssss', $userId, $user['userType'], $token, $firebaseToken, $_SERVER['REMOTE_ADDR'], $_SERVER['SERVER_PROTOCOL'], get_browser_name($_SERVER['HTTP_USER_AGENT']), get_browser_version($_SERVER['HTTP_USER_AGENT']), get_os($_SERVER['HTTP_USER_AGENT']), get_device_type($_SERVER['HTTP_USER_AGENT']), get_platform($_SERVER['HTTP_USER_AGENT']), get_bit_version($_SERVER['HTTP_USER_AGENT']));
            if ($stmt->execute()) {
                setcookie('remember_me', $token, time() + (86400 * 30), '/', '', true, true); // Secure and HttpOnly
            } else {
                $response['message'] = 'SQL Hatası: ' . $stmt->error;
                echo json_encode($response);
                exit();
            }
            $stmt->close();
        } else {
            $response['message'] = 'SQL Hazırlama hatası: ' . $conn->error;
            echo json_encode($response);
            exit();
        }
    }

    // Kullanıcı hareketlerini logla
    $userName = $_SESSION['name'];
    $activity = 'LOGIN';
    $logLogin = "INSERT INTO log_entry (user, action) VALUES (?, ?)";
    $stmt = $conn->prepare($logLogin);
    $stmt->bind_param('ss', $userName, $activity);
    $stmt->execute();
    $stmt->close();
}

