<?php
// Zaman dilimini ayarlayalım
date_default_timezone_set('Europe/Istanbul');

// Veritabanı bilgileri
$dbname = "u490326670_workspace";
$servername = "localhost";

$server = 'server';

if ($server == "server") {
    $username = "u490326670_admin";
    $password = "t+2Kx!54t";
} else if ($server == 'local') {
    $username = "root";
    $password = "";
}

// Veritabanına bağlanalım
try {
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    mysqli_set_charset($conn, 'utf8');
    $conn->query("SET time_zone = '+03:00'");
} catch (PDOException $error) {
    echo $error->getMessage();
    echo "DB Error: Connection Not Established";
}

if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

// PaymentReminderRemainingDays değerini settings tablosundan alalım
$sql_settings = "SELECT value FROM settings WHERE type = 'PaymentReminderRemainingDays'";
$result_settings = $conn->query($sql_settings);
$reminder_days = 0; // Varsayılan değer

if ($result_settings->num_rows > 0) {
    $row = $result_settings->fetch_assoc();
    $reminder_days = intval($row['value']); // Kaç gün önce hatırlatma yapılacağını alalım
}

// Şu anki tarih ve reminder_days kadar sonrasını hesapla
$current_date = new DateTime(); // Şu anki tarih
$current_date_plus_reminder = clone $current_date; 
$current_date_plus_reminder->modify("+$reminder_days days"); // Şu anki tarihe reminder_days ekle

// next_due_date bugünün tarihi ve reminder_days toplamından küçük veya eşit olanları alalım
$sql = "SELECT * FROM accounting_scheduled_payments 
        WHERE next_due_date <= '" . $current_date_plus_reminder->format('Y-m-d H:i:s') . "'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $transaction_id = $row['id'];
        $creator_id = $row['creator_id'];
        $total_amount = $row['total_amount'];
        $installment_amount = $row['installment_amount'];
        $next_due_date = $row['next_due_date'];
        $created_count = $row['created_count'];
        $repeat_count = $row['repeat_count'];
        $recurrence_period = $row['recurrence_period'];

        // Eğer taksitli ödeme yoksa installment_amount alanına total_amount ekle
        if (is_null($installment_amount)) {
            $installment_amount = $total_amount;
        }

        // Sonsuz tekrarlama kontrolü (repeat_count = -1)
        $is_infinite = ($repeat_count == -1);

        // Döngü ile geçmiş ödemeleri kontrol et ve oluştur
        $due_date = new DateTime($next_due_date);  // Ödemenin planlanan tarihi

        // Döngüyle due_date bugünkü tarihe ve reminder_days eklenmiş tarihe ulaşana kadar işle
        while ($due_date <= $current_date_plus_reminder) {
            // Eğer tekrar sayısına ulaşıldıysa döngüden çık
            if (!$is_infinite && $created_count >= $repeat_count) {
                break; // Döngüyü durdur
            }

            // Ödeme işlemi `accounting_recurring_transactions` tablosuna kaydediliyor
            $sql_insert = "INSERT INTO accounting_recurring_transactions 
                           (transaction_id, creator_id, installment_amount, due_date, payment_status, installment_number)
                           VALUES ('$transaction_id', '$creator_id', '$installment_amount', '" . $due_date->format('Y-m-d H:i:s') . "', 0, $created_count+1)";
            if ($conn->query($sql_insert) === TRUE) {
                echo "Yeni ödeme eklendi (ID: $transaction_id): " . $due_date->format('Y-m-d H:i:s') . "<br>";

                // created_count'i artırıyoruz
                $created_count++;

                // Bir sonraki ödeme tarihini belirle
                if ($recurrence_period == 'monthly' || $recurrence_period == 'taksit') {
                    $due_date->modify('+1 month');
                } elseif ($recurrence_period == 'weekly') {
                    $due_date->modify('+1 week');
                } elseif ($recurrence_period == 'daily') {
                    $due_date->modify('+1 day');
                } elseif ($recurrence_period == 'yearly') {
                    $due_date->modify('+1 year');
                }
            } else {
                echo "Hata: " . $sql_insert . "<br>" . $conn->error;
                break;
            }
        }

        // Eğer işlem başarılı olduysa, ödeme planı güncelleniyor
        $sql_update = "UPDATE accounting_scheduled_payments 
                       SET next_due_date = '" . $due_date->format('Y-m-d H:i:s') . "', created_count = $created_count 
                       WHERE id = '$transaction_id'";
        if ($conn->query($sql_update) === TRUE) {
            echo "Ödeme planı güncellendi (ID: $transaction_id): " . $due_date->format('Y-m-d H:i:s') . "<br>";
        } else {
            echo "Hata: Ödeme planı güncellenemedi (ID: $transaction_id).<br>";
        }
    }
} else {
    echo "Bekleyen ödeme yok.<br>";
}

$conn->close();
?>
