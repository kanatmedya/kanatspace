<?php
$categoryID = $rowIncome['category'];
$sqlCategory = "SELECT * FROM accounting_category WHERE id='$categoryID'";
$resultCategory = $conn->query($sqlCategory);
$category = '';
if ($resultCategory && $resultCategory->num_rows > 0) {
    $rowCategory = $resultCategory->fetch_assoc();
    $category = $rowCategory["category"];
}


$categorySubID = $rowIncome['categorySub'];
$sqlCategorySub = "SELECT * FROM accounting_category WHERE id='$categorySubID'";
$resultCategorySub = $conn->query($sqlCategorySub);
$categorySub = '';
if ($resultCategorySub && $resultCategorySub->num_rows > 0) {
    $rowCategorySub = $resultCategorySub->fetch_assoc();
    $categorySub = $rowCategorySub["category"];
}


if($categorySub!=''){
    $categorySubText = ' - ' . $categorySub;
}else{$categorySubText = '';}

if($rowIncome['reciever']=='-1'){
    $reciever = 'Diğer';
}else{$reciever = $rowIncome['reciever'];}


// Gelen tarih ve saat
$datetime = "2024-07-11 01:33:04";

// DateTime nesnesi oluştur
$date = new DateTime($rowIncome['date']);

// İstenen formatlar
$date_formatted = $date->format('d F Y'); // 14 Haziran 2024
$day_time_formatted = $date->format('l H:i'); // Perşembe 18:31

// Türkçe tarih ve gün isimlerini almak için ayarlamalar
setlocale(LC_TIME, 'tr_TR.UTF-8');
$date_formatted_tr = strftime('%d %B %Y', $date->getTimestamp());
$day_time_formatted_tr = strftime('%A %H:%M', $date->getTimestamp());

?>


<div class="rounded border border-[#ebedf2] dark:border-0 dark:bg-[#1b2e4b]">
    <div class="flex items-center justify-between p-4 py-2">
        <div
            class="grid h-9 w-9 place-content-center rounded-md bg-secondary-light text-secondary dark:bg-secondary dark:text-secondary-light">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 shrink-0">
                <path
                    d="M3.79424 12.0291C4.33141 9.34329 4.59999 8.00036 5.48746 7.13543C5.65149 6.97557 5.82894 6.8301 6.01786 6.70061C7.04004 6 8.40956 6 11.1486 6H12.8515C15.5906 6 16.9601 6 17.9823 6.70061C18.1712 6.8301 18.3486 6.97557 18.5127 7.13543C19.4001 8.00036 19.6687 9.34329 20.2059 12.0291C20.9771 15.8851 21.3627 17.8131 20.475 19.1793C20.3143 19.4267 20.1267 19.6555 19.9157 19.8616C18.7501 21 16.7839 21 12.8515 21H11.1486C7.21622 21 5.25004 21 4.08447 19.8616C3.87342 19.6555 3.68582 19.4267 3.5251 19.1793C2.63744 17.8131 3.02304 15.8851 3.79424 12.0291Z"
                    stroke="currentColor" stroke-width="1.5"></path>
                <path opacity="0.5" d="M9 6V5C9 3.34315 10.3431 2 12 2C13.6569 2 15 3.34315 15 5V6"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                <path opacity="0.5"
                    d="M9.1709 15C9.58273 16.1652 10.694 17 12.0002 17C13.3064 17 14.4177 16.1652 14.8295 15"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            </svg>
        </div>
        <div class="flex flex-auto items-start justify-between ltr:ml-4 rtl:mr-4">
            <h6 class="text-[13px] text-white-dark dark:text-white-dark"><?php echo $rowIncome['sender'] . ' -> ' . $reciever ?>
            
            <span class="block text-base text-[#515365] dark:text-white-light" style="font-size:x-small">
                <?php echo $category . $categorySubText ?>
            </span>
            
            <span class="block text-base text-[#515365] dark:text-white-light">
                <?php echo $rowIncome['amount'] . $rowIncome['cc']; $sayi=$sayi+$rowIncome['amount']; ?>
            </span>
            
            
            </h6>
            <div>
                <p class="text-secondary ltr:ml-auto rtl:mr-auto" style="text-align:right">
                    <?php echo $date_formatted_tr; ?>
                </p>
                <p class="text-secondary ltr:ml-auto rtl:mr-auto" style="text-align:right">
                    <?php echo $day_time_formatted_tr; ?>
                </p>
            </div>

        </div>
    </div>
</div>