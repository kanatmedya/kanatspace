<?php

include "./assets/db/db_connect.php";

// Kullanıcı ID'sini URL'den alın
$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo "Kullanıcı ID'si belirtilmedi.";
    exit;
}

// Formdan gelen verilerle güncelleme
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $department = $_POST['department'] ?? '';
    $position = $_POST['position'] ?? '';
    $phone_num = $_POST['phone_num'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $dateStart = $_POST['startTime'] ?? '';
    $salary = $_POST['salary'] ?? '';
    $salaryFood = $_POST['salaryFood'] ?? 'false';
    $salaryRoad = $_POST['mealFee'] ?? 'false';
    $annualLeave = $_POST['annualLeave'] ?? '';
    $workDays = $_POST['workDays'] ?? '';
    $workHourStart = $_POST['workHourStart'] ?? '';
    $workHourEnd = $_POST['workHourEnd'] ?? '';

    // SQL sorgusu
    $sql = "UPDATE users_employee 
            SET name='$name', department='$department', position='$position', phone_num='$phone_num', email='$email', password='$password', startTime='$dateStart', salary='$salary', mealFee='$salaryFood', roadFee='$salaryRoad', annualLeave='$annualLeave', workDays='$workDays', workHourStart='$workHourStart', workHourEnd='$workHourEnd'
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Kayıt başarıyla güncellendi";
    } else {
        echo "Hata: " . $conn->error;
    }
}

// Kullanıcı bilgilerini veritabanından çekme
$sql = "SELECT * FROM users_employee WHERE id='$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Kullanıcı bulunamadı.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Update</title>
    <link rel="stylesheet" href="path_to_your_css_file.css">
</head>
<body>
<div class="animate__animated p-6" :class="[$store.app.animation]">

    <!-- start main content section -->
    <div x-data="invoiceAdd">
        <div class="flex flex-col gap-2.5 xl:flex-row">
            <div class="panel flex-1 px-0 py-6 ltr:lg:mr-6 rtl:lg:ml-6">
                <div class="px-4">
                    <form method="POST" id="employee-form" action="employeeUpdate.php?id=<?php echo $id; ?>">
                        <div class="flex flex-col justify-between lg:flex-row" id="client-info">
                            <div class="mb-6 w-full lg:w-1/2 ltr:lg:mr-6 rtl:lg:ml-6 maxTablet-m0">

                                <div class="text-lg font-semibold">Personel Düzenle</div>

                                <div class="mt-4 flex items-center">
                                    <label for="name" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Adı Soyadı    
                                    </label>
                                    <input type="text" id="name" name="name" class="form-input flex-1"
                                        placeholder="Adı Soyadı" value="<?php echo $row['name']; ?>">
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="department" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Departman
                                    </label>
                                    <input id="department" type="text" name="department" class="form-input flex-1"
                                        placeholder="Departman" value="<?php echo $row['department']; ?>">
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="position" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Pozisyon
                                    </label>
                                    <input id="position" type="text" name="position" class="form-input flex-1"
                                        placeholder="Pozisyon" value="<?php echo $row['position']; ?>">
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="phone_num" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Telefon
                                    </label>
                                    <input id="phone_num" type="text" name="phone_num" class="form-input flex-1"
                                        placeholder="Telefon Girin" value="<?php echo $row['phone_num']; ?>">
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="email" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Kurumsal Mail
                                    </label>
                                    <input id="email" type="text" name="email"
                                        class="form-input flex-1" placeholder="Kurumsal Mail" value="<?php echo $row['email']; ?>">
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="personal_email" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Kişisel Mail
                                    </label>
                                    <input id="personal_email" type="text" name="personal_email"
                                        class="form-input flex-1" placeholder="Kişisel Mail" value="<?php echo $row['email']; ?>">
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="password" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Space Şifresi
                                    </label>
                                    <input id="password" type="text" name="password"
                                        class="form-input flex-1" placeholder="Space Şifresi" value="<?php echo $row['password']; ?>">
                                </div>

                            </div>
                            <div class="w-full lg:w-1/2">

                                <div class="text-lg font-semibold maxTablet-hidden"><br></div>

                                <div class="mt-4 flex items-center">
                                    <label for="startTime" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        İşe Başlama
                                    </label>
                                    <input type="date" name="startTime" class="form-input flex-1" 
                                           value="<?php echo $row['startTime']; ?>">
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="salary" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Maaş
                                    </label>
                                    <input id="salary" type="text" name="salary" class="form-input flex-1"
                                        placeholder="Maaş" value="<?php echo $row['salary']; ?>">
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="mealFee" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Yemek Ücreti
                                    </label>
                                    <select name="mealFee" required class="form-input flex-1">
                                        <option value="false" <?php if ($row['mealFee'] == 'false') echo 'selected'; ?>>Verilmiyor</option>
                                        <option value="true" <?php if ($row['mealFee'] == 'true') echo 'selected'; ?>>Veriliyor</option>
                                    </select>
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="roadFee" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Yol Ücreti
                                    </label>
                                    <select name="roadFee" required class="form-input flex-1">
                                        <option value="false" <?php if ($row['roadFee'] == 'false') echo 'selected'; ?>>Verilmiyor</option>
                                        <option value="true" <?php if ($row['roadFee'] == 'true') echo 'selected'; ?>>Veriliyor</option>
                                    </select>
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="annualLeave" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Yıllık İzin    
                                    </label>
                                    <input id="annualLeave" type="text" name="annualLeave" class="form-input flex-1"
                                        placeholder="Yıllık İzin" value="<?php echo $row['annualLeave']; ?>">
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="workDays" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Çalışma Günleri
                                    </label>
                                    <input id="workDays" type="text" name="workDays" class="form-input flex-1"
                                        placeholder="Çalışma Günleri" value="<?php echo $row['workDays']; ?>">
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="workHourStart" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Mesai Saatleri
                                    </label>
                                    <input type="time" name="workHourStart" class="form-input flex-1" 
                                           value="<?php echo $row['workHourStart']; ?>">
                                    <input type="time" name="workHourEnd" class="form-input flex-1" 
                                           value="<?php echo $row['workHourEnd']; ?>">
                                </div>

                            </div>
                        </div>
                        <div class="mt-8 px-4" id="save-button-container">
                            <button type="submit" class="btn btn-success w-full gap-2" id="saveNewClientButton">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                                    <path d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 11.6585 22 11.4878 21.9848 11.3142C21.9142 10.5049 21.586 9.71257 21.0637 9.09034C20.9516 8.95687 20.828 8.83317 20.5806 8.58578L15.4142 3.41944C15.1668 3.17206 15.0431 3.04835 14.9097 2.93631C14.2874 2.414 13.4951 2.08581 12.6858 2.01515C12.5122 2 12.3415 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M17 22V21C17 19.1144 17 18.1716 16.4142 17.5858C15.8284 17 14.8856 17 13 17H11C9.11438 17 8.17157 17 7.58579 17.5858C7 18.1716 7 19.1144 7 21V22" stroke="currentColor" stroke-width="1.5"/>
                                    <path opacity="0.5" d="M7 8H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
