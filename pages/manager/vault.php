<?php

$sqlIncomeCash = "SELECT SUM(amount) as incomeCash FROM accounting_transactions WHERE reciever='Şirket Nakit Hesabı'
";

$sqlIncomeCash = $conn->query($sqlIncomeCash);
while ($row = $sqlIncomeCash->fetch_assoc()) {
    $cashIncome = $row["incomeCash"];
}

$sqlIncomeCash = "SELECT sum(amount) as paymentCash FROM accounting_transactions WHERE sender='Şirket Nakit Hesabı'";
$sqlIncomeCash = $conn->query($sqlIncomeCash);
while ($row = $sqlIncomeCash->fetch_assoc()) {
    $cashPayment = $row["paymentCash"];
}

$cash = $cashIncome - $cashPayment;

$sqlIncomeCard = "SELECT SUM(amount) as incomeCard FROM accounting_transactions WHERE reciever='Şirket Kart Hesabı'";
$resultIncomeCard = $conn->query($sqlIncomeCard);

if ($resultIncomeCard && $resultIncomeCard->num_rows > 0) {
    $row = $resultIncomeCard->fetch_assoc();
    $cardIncome = $row["incomeCard"];
} else {
    $cardIncome = 0; // Sonuç bulunamadı
}

// Şirket Kart Hesabı için toplam gider hesaplama
$sqlPaymentCard = "SELECT SUM(amount) as paymentCard FROM accounting_transactions WHERE sender='Şirket Kart Hesabı'";
$resultPaymentCard = $conn->query($sqlPaymentCard);

if ($resultPaymentCard && $resultPaymentCard->num_rows > 0) {
    $row = $resultPaymentCard->fetch_assoc();
    $cardPayment = $row["paymentCard"];
} else {
    $cardPayment = 0; // Sonuç bulunamadı
}

// Kart farkı hesaplama
$card = $cardIncome - $cardPayment;

// Bu haftaki kart gelir
$query_income_card = "
    SELECT SUM(amount) as totalIncomeCard 
    FROM accounting_transactions 
    WHERE reciever='Şirket Kart Hesabı' 
    AND YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)
";
$result_income_card = $conn->query($query_income_card);
$weekly_card_income = ($result_income_card && $result_income_card->num_rows > 0) ? $result_income_card->fetch_assoc()["totalIncomeCard"] : 0;

// Geçen haftaki kart gelir
$query_last_week_income_card = "
    SELECT SUM(amount) as lastWeekIncomeCard 
    FROM accounting_transactions 
    WHERE reciever='Şirket Kart Hesabı' 
    AND YEARWEEK(date, 1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK, 1)
";
$result_last_week_income_card = $conn->query($query_last_week_income_card);
$last_week_card_income = ($result_last_week_income_card && $result_last_week_income_card->num_rows > 0) ? $result_last_week_income_card->fetch_assoc()["lastWeekIncomeCard"] : 0;

// Haftalık kart artış/azalış oranı
if ($last_week_card_income > 0) {
    $percentage_change_card = (($weekly_card_income - $last_week_card_income) / $last_week_card_income) * 100;
} else {
    $percentage_change_card = 0;
}

$sqlIncomeCheck = "SELECT SUM(amount) as incomeCheck FROM accounting_transactions WHERE reciever='Şirket Çek/Senet Hesabı'";
$resultIncomeCheck = $conn->query($sqlIncomeCheck);

if ($resultIncomeCheck && $resultIncomeCheck->num_rows > 0) {
    $row = $resultIncomeCheck->fetch_assoc();
    $checkIncome = $row["incomeCheck"];
} else {
    $checkIncome = 0; // Sonuç bulunamadı
}

// Şirket Çek/Senet Hesabı için toplam gider hesaplama
$sqlPaymentCheck = "SELECT SUM(amount) as paymentCheck FROM accounting_transactions WHERE sender='Şirket Çek/Senet Hesabı'";
$resultPaymentCheck = $conn->query($sqlPaymentCheck);

if ($resultPaymentCheck && $resultPaymentCheck->num_rows > 0) {
    $row = $resultPaymentCheck->fetch_assoc();
    $checkPayment = $row["paymentCheck"];
} else {
    $checkPayment = 0; // Sonuç bulunamadı
}

// Çek/Senet farkı hesaplama
$check = $checkIncome - $checkPayment;

// Bu haftaki Çek/Senet gelir
$query_income_check = "
    SELECT SUM(amount) as totalIncomeCheck 
    FROM accounting_transactions 
    WHERE reciever='Şirket Çek/Senet Hesabı' 
    AND YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)
";
$result_income_check = $conn->query($query_income_check);
$weekly_check_income = ($result_income_check && $result_income_check->num_rows > 0) ? $result_income_check->fetch_assoc()["totalIncomeCheck"] : 0;

// Geçen haftaki Çek/Senet gelir
$query_last_week_income_check = "
    SELECT SUM(amount) as lastWeekIncomeCheck 
    FROM accounting_transactions 
    WHERE reciever='Şirket Çek/Senet Hesabı' 
    AND YEARWEEK(date, 1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK, 1)
";
$result_last_week_income_check = $conn->query($query_last_week_income_check);
$last_week_check_income = ($result_last_week_income_check && $result_last_week_income_check->num_rows > 0) ? $result_last_week_income_check->fetch_assoc()["lastWeekIncomeCheck"] : 0;

// Haftalık Çek/Senet artış/azalış oranı
if ($last_week_check_income > 0) {
    $percentage_change_check = (($weekly_check_income - $last_week_check_income) / $last_week_check_income) * 100;
} else {
    $percentage_change_check = 0;
}


$sqlIncomePartners = "SELECT SUM(amount) as incomePartners FROM accounting_transactions WHERE reciever='Şirket Ortaklar Hesabı'";
$resultIncomePartners = $conn->query($sqlIncomePartners);

if ($resultIncomePartners && $resultIncomePartners->num_rows > 0) {
    $row = $resultIncomePartners->fetch_assoc();
    $partnersIncome = $row["incomePartners"];
} else {
    $partnersIncome = 0; // Sonuç bulunamadı
}

// Şirket Ortaklar Hesabı için toplam gider hesaplama
$sqlPaymentPartners = "SELECT SUM(amount) as paymentPartners FROM accounting_transactions WHERE sender='Şirket Ortaklar Hesabı'";
$resultPaymentPartners = $conn->query($sqlPaymentPartners);

if ($resultPaymentPartners && $resultPaymentPartners->num_rows > 0) {
    $row = $resultPaymentPartners->fetch_assoc();
    $partnersPayment = $row["paymentPartners"];
} else {
    $partnersPayment = 0; // Sonuç bulunamadı
}

// Ortaklar hesabı farkı hesaplama
$partners = $partnersIncome - $partnersPayment;

// Bu haftaki Ortaklar hesabı gelir
$query_income_partners = "
    SELECT SUM(amount) as totalIncomePartners 
    FROM accounting_transactions 
    WHERE reciever='Şirket Ortaklar Hesabı' 
    AND YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)
";
$result_income_partners = $conn->query($query_income_partners);
$weekly_partners_income = ($result_income_partners && $result_income_partners->num_rows > 0) ? $result_income_partners->fetch_assoc()["totalIncomePartners"] : 0;

// Geçen haftaki Ortaklar hesabı gelir
$query_last_week_income_partners = "
    SELECT SUM(amount) as lastWeekIncomePartners 
    FROM accounting_transactions 
    WHERE reciever='Şirket Ortaklar Hesabı' 
    AND YEARWEEK(date, 1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK, 1)
";
$result_last_week_income_partners = $conn->query($query_last_week_income_partners);
$last_week_partners_income = ($result_last_week_income_partners && $result_last_week_income_partners->num_rows > 0) ? $result_last_week_income_partners->fetch_assoc()["lastWeekIncomePartners"] : 0;

// Haftalık Ortaklar hesabı artış/azalış oranı
if ($last_week_partners_income > 0) {
    $percentage_change_partners = (($weekly_partners_income - $last_week_partners_income) / $last_week_partners_income) * 100;
} else {
    $percentage_change_partners = 0;
}


    
?>



<div class="animate__animated p-6" :class="[$store.app.animation]">

    <div class="mb-6 grid grid-cols-1 gap-6 text-white sm:grid-cols-2 xl:grid-cols-4">
        <!-- Users Visit -->
        <div class="panel bg-gradient-to-r from-cyan-500 to-[#1e9afe]">
            <div class="flex justify-between">
                <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Nakit Hesapları</div>
                <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                    <a href="javascript:;" @click="toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 opacity-70 hover:opacity-80">
                            <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                            <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5">
                            </circle>
                            <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                        </svg>
                    </a>
                    <ul x-show="open" x-transition="" x-transition.duration.300ms=""
                        class="text-black ltr:right-0 rtl:left-0 dark:text-white-dark" style="display: none;">
                        <li><a href="javascript:;" @click="toggle">Raporu Gör</a></li>
                        <li><a href="javascript:;" @click="toggle">Hesapları Gör</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-5 flex items-center">
                <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3"><?php  echo $cash ?>₺</div>
                <div class="badge bg-white/30"> <?php 
                $query_income_cash = "
    SELECT SUM(amount) as totalIncomeCash 
    FROM accounting_transactions 
    WHERE reciever='Şirket Nakit Hesabı' 
    AND YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)
";
$result_income_cash = $conn->query($query_income_cash);
$weekly_cash_income = ($result_income_cash && $result_income_cash->num_rows > 0) ? $result_income_cash->fetch_assoc()["totalIncomeCash"] : 0;

// Geçen haftaki gelir
$query_last_week_income_cash = "
    SELECT SUM(amount) as lastWeekIncomeCash 
    FROM accounting_transactions 
    WHERE reciever='Şirket Nakit Hesabı' 
    AND YEARWEEK(date, 1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK, 1)
";
$result_last_week_income_cash = $conn->query($query_last_week_income_cash);
$last_week_cash_income = ($result_last_week_income_cash && $result_last_week_income_cash->num_rows > 0) ? $result_last_week_income_cash->fetch_assoc()["lastWeekIncomeCash"] : 0;

// Haftalık artış/azalış oranı
if ($last_week_cash_income > 0) {
    $percentage_change = (($weekly_cash_income - $last_week_cash_income) / $last_week_cash_income) * 100;
} else {
    $percentage_change = 0;
}
 echo ($percentage_change >= 0 ? '+' : '') . number_format($percentage_change, 2);

                 ?>%</div>
            </div>
            <div class="mt-5 flex items-center font-semibold">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                    <path opacity="0.5"
                        d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                    <path
                        d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                </svg>
                Bu Hafta +<?php 
                $query_income_cash = "
    SELECT SUM(amount) as totalIncomeCash 
    FROM accounting_transactions 
    WHERE reciever='Şirket Nakit Hesabı' 
    AND YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)
";
$result_income_cash = $conn->query($query_income_cash);

if ($result_income_cash && $result_income_cash->num_rows > 0) {
    $row = $result_income_cash->fetch_assoc();
    $weekly_cash_income = $row["totalIncomeCash"] ?? 0;
} else {
    $weekly_cash_income = 0;
} echo $row["totalIncomeCash"] ;
 ?>₺
            </div>
</div>

        <div class="panel bg-gradient-to-r from-blue-500 to-blue-400">
            <div class="flex justify-between">
                <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Banka Hesapları</div>
                <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                    <a href="javascript:;" @click="toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 opacity-70 hover:opacity-80">
                            <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                            <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5">
                            </circle>
                            <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                        </svg>
                    </a>
                    <ul x-show="open" x-transition="" x-transition.duration.300ms=""
                        class="text-black ltr:right-0 rtl:left-0 dark:text-white-dark" style="display: none;">
                        <li><a href="javascript:;" @click="toggle">Raporu Gör</a></li>
                        <li><a href="javascript:;" @click="toggle">Hesapları Gör</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-5 flex items-center">
                <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3"><?php echo $card; ?>₺</div>
               <div class="badge bg-white/30"> <?php echo ($percentage_change_card >= 0 ? '+' : '') . number_format($percentage_change_card, 2); ?>%</div>
            </div>
            <div class="mt-5 flex items-center font-semibold">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                    <path opacity="0.5"
                        d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                    <path
                        d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                </svg>
                Bu Hafta +<?php echo $weekly_card_income; ?>₺
            </div>
        </div>

        <!-- Sessions -->
        <div class="panel bg-gradient-to-r from-blue-500 to-[#1e9afe]">
            <div class="flex justify-between">
                <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Çek/Senet Hesapları</div>
                <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                    <a href="javascript:;" @click="toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 opacity-70 hover:opacity-80">
                            <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                            <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5">
                            </circle>
                            <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                        </svg>
                    </a>
                    <ul x-show="open" x-transition="" x-transition.duration.300ms=""
                        class="text-black ltr:right-0 rtl:left-0 dark:text-white-dark" style="display: none;">
                        <li><a href="javascript:;" @click="toggle">Raporu Gör</a></li>
                        <li><a href="javascript:;" @click="toggle">Hesapları Gör</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-5 flex items-center">
                <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3"><?php echo $check; ?>₺</div>
                <div class="badge bg-white/30"> <?php echo ($percentage_change_check >= 0 ? '+' : '') . number_format($percentage_change_check, 2); ?>%</div>
            </div>
            <div class="mt-5 flex items-center font-semibold">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                    <path opacity="0.5"
                        d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                    <path
                        d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                </svg>
                Bu Hafta +<?php echo $weekly_check_income; ?>₺
            </div>
        </div>

        <!-- Bounce Rate -->
        <div class="panel bg-gradient-to-r from-cyan-500 to-cyan-400">
            <div class="flex justify-between">
                <div class="text-md font-semibold ltr:mr-1 rtl:ml-1">Ortak Hesapları</div>
                <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                    <a href="javascript:;" @click="toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 opacity-70 hover:opacity-80">
                            <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                            <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5">
                            </circle>
                            <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                        </svg>
                    </a>
                    <ul x-show="open" x-transition="" x-transition.duration.300ms=""
                        class="text-black ltr:right-0 rtl:left-0 dark:text-white-dark" style="display: none;">
                        <li><a href="javascript:;" @click="toggle">Raporu Gör</a></li>
                        <li><a href="javascript:;" @click="toggle">Hesapları Gör</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-5 flex items-center">
                <div class="text-3xl font-bold ltr:mr-3 rtl:ml-3"><?php echo $partners; ?>₺</div>
                 <div class="badge bg-white/30"> <?php echo ($percentage_change_partners >= 0 ? '+' : '') . number_format($percentage_change_partners, 2); ?>%</div>
            </div>
            <div class="mt-5 flex items-center font-semibold">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                    <path opacity="0.5"
                        d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                    <path
                        d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                </svg>
                 Bu Hafta +<?php echo $weekly_partners_income; ?>₺
            </div>
        </div>

        <!-- Time On-Site -->

    </div>
    <div class="grid grid-cols-1 mt-10 gap-5 md:grid-cols-2">
        <div class="panel overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-lg font-bold">Personel Hesapları</div>
                    <div class="text-success">Personel Hesapları</div>
                </div>
                <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                    <a href="javascript:;" @click="toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 opacity-70 hover:opacity-80">
                            <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                            <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5">
                            </circle>
                            <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                        </svg>
                    </a>
                    <ul x-show="open" x-transition="" x-transition.duration.300ms="" class="ltr:right-0 rtl:left-0"
                        style="display: none;">
                        <li><a href="javascript:;" @click="toggle">Raporu Gör</a></li>
                        <li><a href="javascript:;" @click="toggle">Hesapları Gör</a></li>
                    </ul>
                </div>
            </div>
            <div class="relative mt-10">
                <div class="absolute -bottom-12 h-24 w-24 ltr:-right-12 rtl:-left-12">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="h-full w-full text-success opacity-20">
                        <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5">
                        </circle>
                        <path d="M8.5 12.5L10.5 14.5L15.5 9.5" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <div class="grid grid-cols-2 gap-6 md:grid-cols-3">
                    <div>
                        <div class="text-primary">Ödenen</div>
                        <div class="mt-2 text-2xl font-semibold">0.000,00₺</div>
                    </div>
                    <div>
                        <div class="text-primary">Alınan</div>
                        <div class="mt-2 text-2xl font-semibold">0.000,00₺</div>
                    </div>
                    <div>
                        <div class="text-primary">Kalan</div>
                        <div class="mt-2 text-2xl font-semibold">0.000,00₺</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-lg font-bold">Bireyler Hesaplar</div>
                    <div class="text-success">Bireysel Hesaplar</div>
                </div>
                <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                    <a href="javascript:;" @click="toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 opacity-70 hover:opacity-80">
                            <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                            <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5">
                            </circle>
                            <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                        </svg>
                    </a>
                    <ul x-show="open" x-transition="" x-transition.duration.300ms="" class="ltr:right-0 rtl:left-0"
                        style="display: none;">
                        <li><a href="javascript:;" @click="toggle">Raporu Gör</a></li>
                        <li><a href="javascript:;" @click="toggle">Hesapları Gör</a></li>
                    </ul>
                </div>
            </div>
            <div class="relative mt-10">
                <div class="absolute -bottom-12 h-24 w-24 ltr:-right-12 rtl:-left-12">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="h-full w-full text-success opacity-20">
                        <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5">
                        </circle>
                        <path d="M8.5 12.5L10.5 14.5L15.5 9.5" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <div class="grid grid-cols-2 gap-6 md:grid-cols-3">
                    <div>
                        <div class="text-primary">Ödenen</div>
                        <div class="mt-2 text-2xl font-semibold">0.000,00₺</div>
                    </div>
                    <div>
                        <div class="text-primary">Alınan</div>
                        <div class="mt-2 text-2xl font-semibold">0.000,00₺</div>
                    </div>
                    <div>
                        <div class="text-primary">Kalan</div>
                        <div class="mt-2 text-2xl font-semibold">0.000,00₺</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 mt-10 gap-5 md:grid-cols-1">
        <div class="panel">
            <h5 class="text-lg font-semibold dark:text-white-light">Son Hareketler</h5>
            <p>Şirket Hesaplarından (Nakit,Kart,Çek) Yapılan Son 10 Giriş ve Çıkış. Yandaki Semboller Kategori
                Sembollerine Göre Ayarlanacak.</p>
        </div>
    </div>
    <div class="grid grid-cols-1 mt-5 gap-5 md:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <h5 class="text-lg font-semibold dark:text-white-light">Gelirler</h5>
            </div>

            <div class="space-y-4">
                <?php
                $sayi=0;
                $sqlIncome = "SELECT * FROM accounting_transactions WHERE reciever='Şirket Nakit Hesabı' OR reciever='Şirket Kart Hesabı' OR reciever='Şirket Çek/Senet Hesabı' OR reciever='Şirket Ortaklar Hesabı' ORDER BY id DESC";
                $sqlIncome = $conn->query($sqlIncome);
                while ($rowIncome = $sqlIncome->fetch_assoc()) {
                    include ("accounting/vaultIncome.php");
                }
                echo "TOPLAM GELİRLER: ".$sayi;
                $sayi=0;
                ?>
            </div>
        </div>
        <div class="panel">

            <div class="mb-5">
                <h5 class="text-lg font-semibold dark:text-white-light">Giderler</h5>
            </div>
            <div class="space-y-4">
                <?php
                $sqlIncome = "SELECT * FROM accounting_transactions WHERE sender='Şirket Nakit Hesabı' OR sender='Şirket Kart Hesabı' OR sender='Şirket Çek/Senet Hesabı'OR sender='Şirket Ortaklar Hesabı' ORDER BY id DESC";
                $sqlIncome = $conn->query($sqlIncome);
                while ($rowIncome = $sqlIncome->fetch_assoc()) {
                    include ("accounting/vaultIncome.php");
                }
                echo "TOPLAM GİDERLER: ".$sayi;
                ?>
            </div>
        </div>
    </div>
</div>