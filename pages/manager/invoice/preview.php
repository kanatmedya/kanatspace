<?php

//Post Edilen ID'ye göre invoice bilgilerini alıyor
if (isset($_GET['id'])) {
    $invID = $_GET['id'];
    $sqlInv = "SELECT * FROM invoices WHERE id = '$invID'";
} elseif (isset($_GET['key'])) {
    $invKey = $_GET['key'];
    $sqlInv = "SELECT * FROM invoices WHERE secretKey = '$invKey'";
}

$resInv = $conn->query($sqlInv);
$rowInv = $resInv->fetch_array();

$clientID = $rowInv['clientID'];
$invID = $rowInv['id'];

$sqlCli = "SELECT * FROM users_client WHERE id = '$clientID'";
$resCli = $conn->query($sqlCli);
$rowCli = $resCli->fetch_array();

//$totalNum = $resTab->num_rows;
//$sumRemain = number_format($rowTotalSum['amount'] - $rowSumPayment["amount"], 2, ',', '.');
?>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div x-data="invoicePreview">
        <div class="mb-6 flex flex-wrap items-center justify-center gap-4 lg:justify-end">
            
            <style>
                .tooltip {
                    position: relative;
                    display: inline-block;
                }
                
                .tooltip .tooltiptext {
                    visibility: hidden;
                    background-color: #555;
                    width: 140px;
                    color: #fff;
                    text-align: center;
                    border-radius: 6px;
                    padding: 5px;
                    position: absolute;
                    z-index: 1;
                    bottom: -100%;
                    left: 50%;
                    margin-left: -75px;
                    opacity: 0;
                    transition: opacity 0.3s;
                }
                
                .tooltip:hover .tooltiptext {
                    visibility: visible;
                    opacity: 1;
                }
            </style>
            
            <input type="text"
                value="https://<?php echo $_SERVER['SERVER_NAME'] . '/invoice?key=' . $rowInv['secretKey'] ?>"
                id="linkShare" style="display:none">
            <div class="tooltip">
                <button onclick="copyLink()" type="button" class="btn btn-info gap-2">
                    <span class="tooltiptext" id="myTooltip">Kopyala</span>
                    <img width="24" src="./assets/media/system/linkShare.svg">
                    Linki Kopyala
                </button>
            </div>
            
            <script>
                function copyLink() {
                    var copyText = document.getElementById("linkShare");
                    copyText.select();
                    copyText.setSelectionRange(0, 99999);
                    navigator.clipboard.writeText(copyText.value);

                    var tooltip = document.getElementById("myTooltip");
                    tooltip.innerHTML = "Link Kopyalandı";
                }

                function outFunc() {
                    var tooltip = document.getElementById("myTooltip");
                    tooltip.innerHTML = "Kopyala";
                }
                
            </script>
            
            <a href="invoiceAdd" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Yeni Oluştur
            </a>
            
            <a href="apps-invoice-edit.html" class="btn btn-warning gap-2">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5">
                    <path opacity="0.5"
                        d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path
                        d="M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z"
                        stroke="currentColor" stroke-width="1.5"></path>
                    <path opacity="0.5"
                        d="M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797M10.1002 15.5876L8.4126 13.9"
                        stroke="currentColor" stroke-width="1.5"></path>
                </svg>
                Düzenle
            </a>
        </div>
        
        <div class="panel">
            <div class="flex flex-wrap justify-between gap-4 px-4">
                <div class="shrink-0">
                    <img src="uploads/logo/logo-wide.svg" alt="image" class="ltr:mr-auto rtl:ml-auto"
                        style="width:230px" />
                    <div class="ltr:text-left rtl:text-right">
                        <div class="mt-6 space-y-1 text-white-dark">
                            <div>Kuruköprü Mah. Sefa Özler Cad. No:39 Kat:6 Daire:65</div>
                            <div>www.kanatmedya.com - info@kanatmedya.com</div>
                            <div>+90 533 566 61 01</div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-semibold uppercase" style="">Fatura</div>
                </div>
            </div>
            
            <hr class="my-6 border-[#e0e6ed] dark:border-[#1b2e4b]" />
            <div class="flex flex-col flex-wrap justify-between gap-6 lg:flex-row">
                <div class="flex-1">
                    <div class="space-y-1 text-white-dark">
                        <div>Sayın <span
                                class="font-semibold text-black dark:text-white"><?php echo $rowCli['username']; ?></span>
                        </div>
                        <div><?php echo $rowCli['clientTitle']; ?></div>
                        <div><?php echo $rowCli['taxOffice'] . '  -  ' . $rowCli['taxNumber']; ?></div>
                        <div><?php echo $rowCli['address']; ?></div>
                        <div>
                            <?php echo $rowCli['district'] . '/' . $rowCli['city'] . '/' . $rowCli['country']; ?>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-between gap-6 sm:flex-row lg:w-2/3">
                    <div class="xl:1/3 sm:w-1/2 lg:w-2/5">
                        <div class="mb-2 flex w-full items-center justify-between">
                            <div class="text-white-dark">Telefon :</div>
                            <div><?php echo $rowCli['phoneCompany']; ?></div>
                        </div>
                        <div class="mb-2 flex w-full items-center justify-between">
                            <div class="text-white-dark">E-Mail :</div>
                            <div><?php echo $rowCli['email']; ?></div>
                        </div>
                        <div class="mb-2 flex w-full items-center justify-between">
                            <div class="text-white-dark">Yetkili :</div>
                            <div><?php echo $rowCli['name']; ?></div>
                        </div>
                        <div class="flex w-full items-center justify-between">
                            <div class="text-white-dark">Telefon :</div>
                            <div><?php echo $rowCli['phone']; ?></div>
                        </div>
                    </div>
                    <div class="xl:1/3 sm:w-1/2 lg:w-2/5">
                        <div class="mb-2 flex w-full items-center justify-between">
                            <div class="text-white-dark">Belge No :</div>
                            <div><?php echo $rowInv['id']; ?></div>
                        </div>
                        <div class="mb-2 flex w-full items-center justify-between">
                            <div class="text-white-dark">Fatura No :</div>
                            <div><?php echo $rowInv['docNumber']; ?></div>
                        </div>
                        <div class="mb-2 flex w-full items-center justify-between">
                            <div class="text-white-dark">Sipariş Tarihi :</div>
                            <div><?php
                            $dateCreate = strtotime($rowInv['dateCreate']);
                            echo date('d.m.Y H:i', $dateCreate)
                                ?>
                            </div>
                        </div>
                        <div class="flex w-full items-center justify-between">
                            <div class="text-white-dark">Ödeme Tarihi :</div>
                            <div><?php
                            $dateCreate = strtotime($rowInv['datePayment']);
                            echo date('d.m.Y H:i', $dateCreate)
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-6">
                <table class="table-striped">
                    <thead>
                        <tr>
                            <th class="">S.No</th>
                            <th class="">Ürün</th>
                            <th class="">Adet</th>
                            <th class="ltr:text-right rtl:text-left">Fiyat</th>
                            <th class="ltr:text-right rtl:text-left">İskonto</th>
                            <th class="ltr:text-right rtl:text-left">Ara Toplam</th>
                            <th class="ltr:text-right rtl:text-left">Vergi</th>
                            <th class="ltr:text-right rtl:text-left">Toplam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlInvProducts = "SELECT * FROM invoices_products WHERE invoiceID = '$invID'";
                        $resInvProducts = $conn->query($sqlInvProducts);

                        $sNo = 1;
                        $cartTotalSub = 0;
                        $cartTotalTax = 0;
                        $cartTotalDiscount = 0;
                        $cartTotalMain = 0;

                        while ($rowInvProducts = $resInvProducts->fetch_array()) {
                            
                            $rowProductName = $rowInvProducts['name'];
                            $rowProductDescription = $rowInvProducts['description'];
                            $rowQuantity = $rowInvProducts['quantity'];
                            $rowPrice = $rowInvProducts['price'];
                            $rowPriceMain = $rowInvProducts['price_main'];
                            $rowDiscount = $rowPriceMain - $rowPrice;
                            $rowTax = $rowInvProducts['tax'];
                            
                            if($rowPriceMain < $rowPrice){
                                $rowPriceMain = $rowPrice;
                                $rowDiscount = 0;
                            }
                            
                            $rowSubTotal = $rowPrice * $rowQuantity;
                            $rowTax = $rowPrice/100 * $rowTax * $rowQuantity;
                            $rowTotal = $rowTax + $rowSubTotal;
                            
                            $cartTotalSub += $rowSubTotal;
                            $cartTotalDiscount += $rowDiscount;
                            $cartTotalTax += $rowTax;
                            $cartTotalMain += $rowTotal;
                            
                            echo '
                        <tr>
                            <td>' . $sNo . '</td>
                            <td><strong>' . $rowProductName . '</strong><br> '.$rowProductDescription.'</td>
                            <td>' . $rowQuantity . '</td>
                            <td class="ltr:text-right rtl:text-left">' . $rowPriceMain . '</td>
                            <td class="ltr:text-right rtl:text-left">' . $rowDiscount . '</td>
                            <td class="ltr:text-right rtl:text-left">' . $rowInvProducts['price'] * $rowInvProducts['quantity'] . '</td>
                            <td class="ltr:text-right rtl:text-left">' . $rowTax . '</td>
                            <td class="ltr:text-right rtl:text-left">' . $rowTotal . '</td>
                        </tr>';
                            $sNo += 1;
                        }



                        ?>
                    </tbody>
                </table>
            </div>
            <table class="table-striped">
                <thead>
                    <tr>
                        <template x-for="item in columns" :key="item.key">
                            <th :class="[item.class]" x-text="item.label"></th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="item in items" :key="item.id">
                        <tr>
                            <td x-text="item.id"></td>
                            <td x-text="item.title"></td>
                            <td x-text="item.quantity"></td>
                            <td class="ltr:text-right rtl:text-left" x-text="`$${item.price}`"></td>
                            <td class="ltr:text-right rtl:text-left" x-text="`$${item.amount}`"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="mt-6 grid grid-cols-1 px-4 sm:grid-cols-2">
            <div></div>
            <div class="space-y-2 ltr:text-right rtl:text-left">
                <div class="flex items-center">
                    <div class="flex-1">Ara Toplam</div>
                    <div class="w-[37%]"><?php echo number_format($cartTotalSub+$cartTotalDiscount, 2, ',', '.') ?></div>
                </div>
                <div class="flex items-center">
                    <div class="flex-1">İskonto</div>
                    <div class="w-[37%]"><?php echo number_format($cartTotalDiscount, 2, ',', '.') ?></div>
                </div>
                <div class="flex items-center">
                    <div class="flex-1">Toplam</div>
                    <div class="w-[37%]"><?php echo number_format($cartTotalSub, 2, ',', '.') ?></div>
                </div>
                <div class="flex items-center">
                    <div class="flex-1">Vergi</div>
                    <div class="w-[37%]"><?php echo number_format($cartTotalTax, 2, ',', '.') ?></div>
                </div>
                <div class="flex items-center text-lg font-semibold">
                    <div class="flex-1">Genel Toplam</div>
                    <div class="w-[37%]"><?php echo number_format($cartTotalMain, 2, ',', '.') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end main content section -->