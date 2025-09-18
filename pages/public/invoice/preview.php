<!DOCTYPE html>
    <html lang="tr" dir="ltr">
        
        <?php
        
            $invoiceType1 = $rowInv['type'];
            if($invoiceType1=='sale'){
                $invoiceType1='Satış';
            }else if($invoiceType1=='purchase'){
                $invoiceType1='Alış';
            }
            
            $invoiceType2 = $rowInv['status'];
            if($invoiceType2=='onOffer'){
                $invoiceType2='Teklifi';
            }else if($invoiceType2=='onOrder'){
                $invoiceType2='Siparisi';
            }else if($invoiceType2=='completed'){
                $invoiceType2='Faturası';
            }else if($invoiceType2=='rejected'){
                $invoiceType2='Faturası (İptal Edildi)';
            }
            
            
            $invoiceType = $invoiceType1 . ' ' . $invoiceType2;
        
        ?>

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title><?=$rowCli['username'] . ' - ' . $invTitle . ' | ' . $companyName ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <!-- Sayfa Başlığı -->
        <meta property="og:title" content="<?=$rowCli['username'] . ' - ' . $invTitle . ' | ' . $company ?>" />
        
        <!-- Sayfa Açıklaması -->
        <meta property="og:description" content="<?=$company . ' Tarafından adınıza yazılmış faturanızı görüntüleyin. Bu fatura GökERP kullanılarak hazırlanmıştır.'?>" />
        
        <!-- İçerik Türü -->
        <meta property="og:type" content="website" />
        
        <!-- Site Adı -->
        <meta property="og:site_name" content="<?=$company . ' | KanatSpace Kurumsal Yönetim Sistemleri' ?>" />
        
        <link rel="icon" type="image/x-icon" href="../uploads/logo/kanatmedyafavicon.ico" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" media="screen" href="assets/css/perfect-scrollbar.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="assets/css/style.css" />
        <link defer rel="stylesheet" type="text/css" media="screen" href="assets/css/animate.css" />
        <script src="assets/js/perfect-scrollbar.min.js"></script>
        <script defer src="assets/js/popper.min.js"></script>
        <script defer src="assets/js/tippy-bundle.umd.min.js"></script>
        <script defer src="assets/js/sweetalert.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    </head>

    <body x-data="main" class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased"
        :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ?  'dark' : '', $store.app.menu, $store.app.layout,$store.app.rtlClass]">

        <div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
            <div x-data="invoicePreview">
                <div class="panel">
                        <div class="flex flex-wrap flex-col justify-between px-0">
                            
                            <div class="flex" style="flex-direction: row;flex-wrap: nowrap;justify-content: space-between;">
                                <img src="uploads/logo/logo-wide.svg" alt="image" class="ltr:mr-auto rtl:ml-auto" style="width:200px" />
                                <div class="text-2xl font-semibold uppercase"><strong><?=$invoiceType ?></strong></div>
                            </div>
                                    
                            <div class="shrink-0">
                                <div class="ltr:text-left rtl:text-right">
                                    <div class="mt-6 space-y-1 text-white-dark">
                                        <div><?=$companyName ?></div>
                                        <div><?=$companyAddress ?></div>
                                        <div><?=$companyWeb ?> - <?=$companyMail ?></div>
                                        <div><?=$companyPhone ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <hr class="my-6 border-[#e0e6ed] dark:border-[#1b2e4b]" />
                    <div class="flex flex-col flex-wrap justify-between gap-6 lg:flex-row">
                        <div class="flex-1">
                            <div class="space-y-1 text-white-dark">
                                <div>Sayın <span
                                        class="font-semibold text-black dark:text-white"><?=$rowCli['username']?></span>
                                </div>
                                <div><?=$rowCli['clientTitle']?></div>
                                <div><?=$rowCli['taxOffice'] . '  -  ' . $rowCli['taxNumber']?></div>
                                <div><?=$rowCli['address']?></div>
                                <div>
                                    <?=$rowCli['district'] . '/' . $rowCli['city'] . '/' . $rowCli['country']?>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col justify-between gap-6 sm:flex-row lg:w-2/3">
                            <div class="xl:1/3 sm:w-1/2 lg:w-2/5">
                                <div class="mb-2 flex w-full items-center justify-between">
                                    <div class="text-white-dark">Telefon :</div>
                                    <div><?=$rowCli['phoneCompany']?></div>
                                </div>
                                <div class="mb-2 flex w-full items-center justify-between">
                                    <div class="text-white-dark">E-Mail :</div>
                                    <div><?=$rowCli['email']?></div>
                                </div>
                                <div class="mb-2 flex w-full items-center justify-between">
                                    <div class="text-white-dark">Yetkili :</div>
                                    <div><?=$rowCli['name']?></div>
                                </div>
                                <div class="flex w-full items-center justify-between">
                                    <div class="text-white-dark">Telefon :</div>
                                    <div><?=$rowCli['phone']?></div>
                                </div>
                            </div>
                            <div class="xl:1/3 sm:w-1/2 lg:w-2/5">
                                <div class="mb-2 flex w-full items-center justify-between">
                                    <div class="text-white-dark">Belge No :</div>
                                    <div><?=$rowInv['id']?></div>
                                </div>
                                <div class="mb-2 flex w-full items-center justify-between">
                                    <div class="text-white-dark">Fatura No :</div>
                                    <div><?=$rowInv['docNumber']?></div>
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
        
        // Iskonto hesaplaması: (main_price - price) * quantity
        $rowDiscount = ($rowPriceMain - $rowPrice) * $rowQuantity;

        $rowTax = $rowInvProducts['tax'];
        
        // Eğer main_price < price ise, discount'ı 0 yap
        if ($rowPriceMain < $rowPrice) {
            $rowPriceMain = $rowPrice;
            $rowDiscount = 0;
        }
        
        // Alt toplam ve vergi hesaplaması
        $rowSubTotal = $rowPrice * $rowQuantity;
        $rowTaxAmount = $rowPrice / 100 * $rowTax * $rowQuantity;
        $rowTotal = $rowTaxAmount + $rowSubTotal;

        // Toplamları biriktir
        $cartTotalSub += $rowSubTotal;
        $cartTotalDiscount += $rowDiscount;
        $cartTotalTax += $rowTaxAmount;
        $cartTotalMain += $rowTotal;

        // Değişkenleri Türk Lirası formatında biçimlendirin ve aynı değişkene atayın
        $rowPriceMain = number_format($rowPriceMain, 2, ',', '.') . '₺';
        $rowDiscount  = number_format($rowDiscount, 2, ',', '.') . '₺';
        $rowSubTotal  = number_format($rowSubTotal, 2, ',', '.') . '₺';
        $rowTaxAmount = number_format($rowTaxAmount, 2, ',', '.') . '₺';
        $rowTotal     = number_format($rowTotal, 2, ',', '.') . '₺';
        
        echo '
        <tr>
            <td>' . $sNo . '</td>
            <td><strong>' . $rowProductName . '</strong><br> '.$rowProductDescription.'</td>
            <td>' . $rowQuantity . '</td>
            <td class="ltr:text-right rtl:text-left">' . $rowPriceMain . '</td>
            <td class="ltr:text-right rtl:text-left">' . $rowDiscount . '</td>
            <td class="ltr:text-right rtl:text-left">' . $rowSubTotal . '</td>
            <td class="ltr:text-right rtl:text-left">' . $rowTaxAmount . '</td>
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
                    <div class="w-[37%]"><?=number_format($cartTotalSub+$cartTotalDiscount, 2, ',', '.') . '₺' ?></div>
                </div>
                <div class="flex items-center">
                    <div class="flex-1">İskonto</div>
                    <div class="w-[37%]"><?=number_format($cartTotalDiscount, 2, ',', '.') . '₺' ?></div>
                </div>
                <div class="flex items-center">
                    <div class="flex-1">Toplam</div>
                    <div class="w-[37%]"><?=number_format($cartTotalSub, 2, ',', '.') . '₺' ?></div>
                </div>
                <div class="flex items-center">
                    <div class="flex-1">Vergi</div>
                    <div class="w-[37%]"><?=number_format($cartTotalTax, 2, ',', '.') . '₺' ?></div>
                </div>
                <div class="flex items-center text-lg font-semibold">
                    <div class="flex-1">Genel Toplam</div>
                    <div class="w-[37%]"><?=number_format($cartTotalMain, 2, ',', '.') . '₺' ?></div>
                </div>
            </div>
        </div>
            </div>
        </div>
        <!-- end main content section -->
    </body>

    </html>