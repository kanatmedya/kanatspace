<?php
include "assets/db/db_connect.php";
global $conn;

$err = "";

date_default_timezone_set('Europe/Istanbul');

function loginCheck($sqlRequest, $password, $rememberMe, $userType)
{
    global $conn;
    $result = $conn->query($sqlRequest);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if ($user['password'] == $password) {
            echo "<script>console.log('Şifre doğru.');</script>";
            loginTrue($user, $rememberMe, $userType);
        } else {
            echo "<script>console.log('Şifre yanlış!');</script>";
            loginFalsePass();
        }
    } else {
        echo "<script>console.log('E-posta bulunamadı!');</script>";
        loginFalseMail();
    }
}

function loginTrue($user, $rememberMe, $userType)
{
    global $conn;
    session_regenerate_id(true);
    
    $_SESSION['name'] = $user['name'] ? $user['name'] : $user['username'];
    $_SESSION['userID'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['status'] = $user['userType'];
    $_SESSION['pic'] = $user['profilePicture'];
    $_SESSION['departman'] = isset($user['department']) ? $user['department'] : '';
    $_SESSION['theme'] = isset($user['theme']) ? $user['theme'] : '';

    if ($rememberMe) {
        $token = bin2hex(random_bytes(32));
        $userId = $user['id'];

        $query = "INSERT INTO user_tokens (user_id, user_type, token, expiry) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY))";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param('iis', $userId, $userType, $token);
            $stmt->execute();
            if ($stmt->affected_rows === 1) {
                if (setcookie('remember_me', $token, time() + (86400 * 30), '/')) {
                    error_log("Çerez başarıyla oluşturuldu.");
                } else {
                    error_log("Çerez oluşturulamadı.");
                }
            } else {
                error_log("Token veritabanına eklenemedi: " . $stmt->error);
            }
            $stmt->close();
        } else {
            error_log("Prepared statement oluşturulamadı: " . $conn->error);
        }
    }

    $userName = $_SESSION['name'];
    $activity = 'LOGIN';

    $logLogin = "INSERT INTO log_entry (user, action) VALUES ('$userName', '$activity')";
    $conn->query($logLogin);
}


function loginFalse()
{
    echo "Bu sayfayı görüntüleme hakkınız yok";
}

function loginFalsePass()
{
    echo '<div class="alert alert-danger" role="alert">Şifre Yanlış!</div>';
}

function loginFalseMail()
{
    echo '<div class="alert alert-danger" role="alert">Email Bulunamadı!</div>';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $rememberMe = isset($_POST['remember_me']);

        if (!empty($email) && !empty($pass)) {
            echo "<script>console.log('Giriş işlemi başlatılıyor...');</script>";
            loginCheck("SELECT * FROM users_manager WHERE email = '$email'", $pass, $rememberMe, 1);
            loginCheck("SELECT * FROM users_employee WHERE email = '$email'", $pass, $rememberMe, 2);
            loginCheck("SELECT * FROM users_client WHERE email = '$email'", $pass, $rememberMe, 3);
        } else {
            echo "<script>console.log('Lütfen Email Ve Şifreyi Giriniz!');</script>";
            echo "<div class='alert alert-danger'>Lütfen Email Ve Şifreyi Giriniz!</div>";
        }
    }
}
?>
