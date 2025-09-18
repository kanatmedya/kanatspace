<?php

date_default_timezone_set('Europe/Istanbul');

function go($url ,$time=0){
    //$url = 'dijisirket'.$url;
    if ($time != 0){
        header("Refresh:$time;url=$url");

    }else{
        header("Location:$url");
    }
}


function comeBack($time=0){
    $url= $_SERVER["HTTP_REFERER"];
    if ($time != 0){
        header("Refresh:$time;url=$url");

    }else{
        header("Location:$url");
    }
}

function saatgoster(){
    date_default_timezone_set('Europe/Istanbul');

    $gunler = array(
        'Pazartesi',
        'Salı',
        'Çarşamba',
        'Perşembe',
        'Cuma',
        'Cumartesi',
        'Pazar'
    );

    $aylar = array(
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

    $ay = $aylar[date('m') - 1];
    $gun = $gunler[date('N') - 1];

    echo date('j ') . $ay . date(' Y ') . $gun . date(' H:i:s');

}

function aygoster($day,$month){

    $aylar = array(
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

    $ay = $aylar[$month-1];

    echo $day. ' '.$ay;

}
?>