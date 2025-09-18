<?php
function formatDateTimeRelative($date)
{
    $now = new DateTime();
    $today = new DateTime($now->format('Y-m-d'));
    $yesterday = (clone $today)->modify('-1 day');
    $tomorrow = (clone $today)->modify('+1 day');
    $startOfWeek = (clone $today)->modify('monday this week');
    $endOfWeek = (clone $startOfWeek)->modify('+6 days');
    $startOfNextWeek = (clone $endOfWeek)->modify('+1 day');
    $startOfLastWeek = (clone $startOfWeek)->modify('-7 days');

    $interval = $now->diff($date);

    // Eğer bugün ise
    if ($date >= $today && $date < $tomorrow) {
        if ($interval->h > 0) {
            return $interval->invert ? $interval->h . ' Saat ' . $interval->i . ' Dakika Önce' : $interval->h . ' Saat ' . $interval->i . ' Dakika Sonra';
        } elseif ($interval->i > 0) {
            return $interval->invert ? $interval->i . ' Dakika Önce' : $interval->i . ' Dakika Sonra';
        } else {
            return $interval->invert ? $interval->s . ' Saniye Önce' : $interval->s . ' Saniye Sonra';
        }
    }

    // Eğer dün ise
    if ($date >= $yesterday && $date < $today) {
        return 'Dün, ' . $date->format('H:i');
    }

    // Eğer yarın ise
    if ($date >= $tomorrow && $date < $startOfNextWeek) {
        return 'Yarın, ' . $date->format('H:i');
    }

    // Eğer bu hafta içinde ise
    if ($date >= $startOfWeek && $date <= $endOfWeek) {
        return $date->format('l');
    }

    // Eğer geçen hafta ise
    if ($date >= $startOfLastWeek && $date < $startOfWeek) {
        return 'Geçen ' . $date->format('l, H:i');
    }

    // Eğer haftaya ise
    if ($date >= $startOfNextWeek && $date < (clone $startOfNextWeek)->modify('+7 days')) {
        return 'Haftaya ' . $date->format('l, H:i');
    }

    // Eğer bu yıl içinde ise
    if ($date->format('Y') == $today->format('Y')) {
        return $date->format('j F');
    }

    // Eğer bu yıl içinde değil ise
    return $date->format('j F Y');
}
?>