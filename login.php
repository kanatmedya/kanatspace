<?php
session_start();
include "assets/db/db_connect.php";

// Çerez kontrolü
if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $stmt = $conn->prepare("SELECT user_id, user_type FROM user_tokens WHERE token = ? AND expiry > NOW()");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($userId, $userType);
        $stmt->fetch();

        // Kullanıcıyı ac_users tablosundan al
        $userQuery = "SELECT * FROM ac_users WHERE id = ? AND userType = ?";
        $userStmt = $conn->prepare($userQuery);
        $userStmt->bind_param('ii', $userId, $userType);
        $userStmt->execute();
        $result = $userStmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            $_SESSION['name'] = $user['name'] . " " . $user['surname'];
            $_SESSION['userID'] = $user['id'];
            $_SESSION['email'] = $user['mail'];
            $_SESSION['status'] = $user['status'];
            $_SESSION['pic'] = $user['profilePicture'];
            $_SESSION['department'] = $user['department'];
            $_SESSION['position'] = $user['position'];
            $_SESSION['theme'] = $user['theme'];

            header("Location: ./");
            exit();
        }
    }
}

if (isset($_SESSION['status'])) {
    $status = $_SESSION['status'];
    if (in_array($status, [1, 2, 3])) {
        header("Location: ./");
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="tr" dir="ltr">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="icon" href="uploads/logo/kanatmedyafavicon.ico" type="image/x-icon">
        <title>Portal | Kanat Medya</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!--Google font-->
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300&display=swap"
    rel="stylesheet">
  <!-- Bootstrap css -->
  <link rel="stylesheet" type="text/css" href="assets/error/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="assets/error/css/fontawesome.css">
  <!-- Theme css -->
  <link rel="stylesheet" type="text/css" href="assets/error/css/login.css">
</head>
    
    <body>
    <!-- 01 Preloader -->
    <div class="loader-wrapper" id="loader-wrapper">
    <div class="loader"></div>
    </div>
    <!-- Preloader end -->
    <!-- 02 Main page -->
    <section class="page-section login-page">
    <div class="full-width-screen">
      <div class="container-fluid">
        <div class="content-detail">
          <!-- Login form -->
          <form class="login-form" id="loginForm" method="POST">
            <div class="imgcontainer">
                                    <img class="ml-[5px] flex-none" style="width:30px" src="uploads/logo/kanatmedyafavicon.ico" alt="image">
                                    <span class="align-middle text-2xl font-semibold ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light lg:inline"><strong>Kanat Space</strong></span>
            </div>
            <div class="input-control">
              <input id="Email" type="email" name="email" placeholder="Mail" required>
              <span class="password-field-show">
                <input placeholder="Şifre" id="Password" type="password" name="password" class="password-field" value=""
                  required>
                <span data-toggle=".password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
              </span>
              <label class="label-container">Beni Hatırla
                <input type="checkbox" name="remember_me" checked>
                <span class="checkmark"></span>
              </label>
              <span class="psw"><a href="#" class="forgot-btn">Şifremi Unuttum</a></span>
              <div class="login-btns">
                <button type="submit">Giriş</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    </section>
    <!-- latest jquery-->
    <script src="assets/error/js/jquery-3.5.1.min.js"></script>
    <!-- Theme js-->
    <script src="assets/error/js/script.js"></script>
   
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('pages/public/login_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text()) // Yanıtı JSON olarak değil, metin olarak al
        .then(data => {

            let jsonData;
            try {
                jsonData = JSON.parse(data); // Yanıtı JSON'a dönüştür
            } catch (e) {
                throw new Error('Geçersiz JSON yanıtı: ' + e.message); // JSON dönüştürme hatası
            }

            if (jsonData.success) {
                window.location.href = './';
            } else {
                console.error('Hata:', jsonData.message);
                alert(jsonData.message);
            }
        })
        .catch((error) => {
            console.error('Hata:', error);
            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
        });
    });
});

    </script>
    </body>
    </html>
    <?php
}
?>
