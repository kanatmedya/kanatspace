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
    $categorySub = $rowCategorySub["categorySub"];
}
?>


<div class="rounded border border-[#ebedf2] dark:border-0 dark:bg-[#1b2e4b]">
    <div class="flex items-center justify-between p-4 py-2">
        <div
            class="grid h-9 w-9 place-content-center rounded-md bg-info-light text-info dark:bg-info dark:text-info-light">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 shrink-0">
                <path
                    d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                    stroke="currentColor" stroke-width="1.5"></path>
                <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2" transform="rotate(-45 8.60699 8.87891)"
                    stroke="currentColor" stroke-width="1.5">
                </circle>
                <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="currentColor" stroke-width="1.5"
                    stroke-linecap="round"></path>
            </svg>
        </div>
        <div class="flex flex-auto items-center justify-between font-semibold ltr:ml-4 rtl:mr-4">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="flex flex-auto items-center">
                    <span class="text-[#515365] dark:text-white-light">
                        <?php echo $rowIncome['amount'].$rowIncome['cc'];
                          $sayi=$sayi+$rowIncome['amount'];

                          ?>
                    </span>
                </div>
                <div>
                    <h6 class="text-[13px] text-white-dark dark:text-white-dark">
                    <?php echo $rowIncome['reciever'] . ' / ' . $category ?> <span

                    </h6>
                    
                </div>
            </div>
            <div>
                <p class="text-secondary ltr:ml-auto rtl:mr-auto" style="text-align:right">
                    18:31
                </p>
                <p class="text-secondary ltr:ml-auto rtl:mr-auto" style="text-align:right">
                    22.06.2024
                </p>
            </div>
        </div>
    </div>
</div>