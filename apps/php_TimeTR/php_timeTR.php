<?php

date_default_timezone_set('Europe/Istanbul');
setlocale(LC_TIME, 'turkish');

function timeElapsedTR($unix)
{
    $now = time();
    $diff = $unix - $now;

    $dayOfWeekTR = array(
        'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'
    );

    $monthsTR = array(
        'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran',
        'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'
    );

    $todayStart = strtotime("today");
    $tomorrowStart = strtotime("tomorrow");
    $yesterdayStart = strtotime("yesterday");
    $weekStart = strtotime("last Monday", $todayStart);
    $weekEnd = strtotime("next Sunday", $todayStart);
    $nextWeekStart = strtotime("+1 week", $weekStart);
    $nextWeekEnd = strtotime("+1 week", $weekEnd);
    $lastWeekStart = strtotime("-1 week", $weekStart);
    $lastWeekEnd = strtotime("-1 week", $weekEnd);

    // Eğer tarih bugüne eşitse
    if (date('Ymd', $unix) == date('Ymd', $now)) {
        $remaining = abs($diff);
        $hours = floor($remaining / 3600);
        $minutes = floor(($remaining % 3600) / 60);

        $timeString = '';

        if ($hours > 0) {
            $timeString .= $hours . ' Sa ';
        }

        if ($minutes > 0) {
            $timeString .= $minutes . ' Dk ';
        }

        if ($diff > 0) {
            return trim($timeString);
        } else {
            return trim($timeString);
        }
    }

    // Eğer tarih yarına eşitse
    if (date('Ymd', $unix) == date('Ymd', $tomorrowStart)) {
        return "Yarın";
    }

    // Eğer tarih düne eşitse
    if (date('Ymd', $unix) == date('Ymd', $yesterdayStart)) {
        return "Dün";
    }

    // Eğer bu hafta içindeyse
    if ($unix >= $weekStart && $unix <= $weekEnd) {
        return $dayOfWeekTR[date('N', $unix) - 1];
    }

    // Eğer geçen hafta içindeyse
    if ($unix >= $lastWeekStart && $unix <= $lastWeekEnd) {
        return "Geçen " . $dayOfWeekTR[date('N', $unix) - 1];
    }

    // Eğer gelecek hafta içindeyse
    if ($unix >= $nextWeekStart && $unix <= $nextWeekEnd) {
        return "Gelecek " . $dayOfWeekTR[date('N', $unix) - 1];
    }

    // Eğer bu yıl içindeyse
    if (date('Y', $unix) == date('Y', $now)) {
        return date('d', $unix) . ' ' . $monthsTR[date('n', $unix) - 1];
    }

    // Eğer bu yıl değilse
    return date('d.m.Y', $unix);
}


function dateElapsedTR($unix)
{
   return strftime('%e %B %Y %A, %H:%I', $unix);
}

function dateTR($date)
{
   $date = new DateTime($date);

   $turkceAylar = array(
      'Ocak',
      'Şubat',
      'Mart',
      'Nisan',
      'Mayıs',
      'Haziran',
      'Temmuz',
      'Ağustos',
      'Eylül',
      'Ekim',
      'Kasım',
      'Aralık'
   );

   $turkceAy = $turkceAylar[$date->format('m') - 1]; // Dönen değer 4
   /*
   ## Buradaki mantık basit;
   ## Hangi ayda ve hangi günde olduğumuzu rakamsal olarak buluyoruz.
   ## Daha sonra 1 ile çıkartıyoruz çünkü dizideki elemanların keyleri 0 dan başlıyor.
   ## mesela 5. ayda olduğumuzu düşünelim $turkceAylar dizisinde 5 keye sahip eleman Haziran
   ancak -1 yaptığımız için bize mayısı dönecektir. ($turkceAylar[4] demiş olduk aslında.)
   ## Keyleri 1 den başlatırsanız elle yaparsanız -1 değerini yapmanıza gerek yok.
   */
   return $date->format('j ') . $turkceAy . $date->format(' Y ');
}

function timeTR($date)
{
   $date = new DateTime($date);

   return $date->format('H.i');
}

function dayTR($date)
{
   $date = new DateTime($date);

   $turkceGunler = array(
      'Pazartesi',
      'Salı',
      'Çarşamba',
      'Perşembe',
      'Cuma',
      'Cumartesi',
      'Pazar'
   );

   $turkceGun = $turkceGunler[$date->format('N') - 1]; // Dönen değer 5
   /*
   ## Buradaki mantık basit;
   ## Hangi ayda ve hangi günde olduğumuzu rakamsal olarak buluyoruz.
   ## Daha sonra 1 ile çıkartıyoruz çünkü dizideki elemanların keyleri 0 dan başlıyor.
   ## mesela 5. ayda olduğumuzu düşünelim $turkceAylar dizisinde 5 keye sahip eleman Haziran
   ancak -1 yaptığımız için bize mayısı dönecektir. ($turkceAylar[4] demiş olduk aslında.)
   ## Keyleri 1 den başlatırsanız elle yaparsanız -1 değerini yapmanıza gerek yok.
   */
   return $turkceGun;
}



?>