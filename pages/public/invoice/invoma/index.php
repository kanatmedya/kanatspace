<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/svg+xml" href="">
        <link rel="icon" type="image/x-icon" href="../uploads/logo/kanatmedyafavicon.ico">
        <title><?=$rowCli['username'] . ' - ' . $invTitle . ' | ' . $companyName ?></title>
        <script type="module" crossorigin="" src="/pages/public/invoice/invoma/assets/index-ChoZqX4R.js"></script>
        <link rel="stylesheet" crossorigin="" href="/pages/public/invoice/invoma/assets/index-CnXrSKdW.css">
        <meta property="og:description" content="<?=$companyName . ' Tarafından adınıza yazılmış faturanızı görüntüleyin. Bu fatura GökERP kullanılarak hazırlanmıştır.'?>" />
        <style>
            @font-face {
                font-family: 'Open Sans Regular';
                font-style: normal;
                font-weight: 400;
                src: url('chrome-extension://gkkdmjjodidppndkbkhhknakbeflbomf/fonts/open_sans/open-sans-v18-latin-regular.woff');
            }
            
            @font-face {
                font-family: 'Open Sans Bold';
                font-style: normal;
                font-weight: 800;
                src: url('chrome-extension://gkkdmjjodidppndkbkhhknakbeflbomf/fonts/open_sans/OpenSans-Bold.woff');
            }
            
            @font-face {
                font-family: 'Open Sans ExtraBold';
                font-style: normal;
                font-weight: 800;
                src: url('chrome-extension://gkkdmjjodidppndkbkhhknakbeflbomf/fonts/open_sans/open-sans-v18-latin-800.woff');
            }
        </style>
        
        <style>
            @media screen and (max-width: 768px) {
              tbody {
                font-size: 10px;
              }
            }
            
            table {
                font-size: 10px;
              }
        </style>
    </head>

    <body>
       <div id="root">
          <div class="tm_container">
             <div class="tm_invoice_wrap">
                <div class="tm_invoice tm_style3" id="tm_download_section">
                   <div class="tm_invoice_in">
                      <div class="tm_invoice_head tm_align_center tm_accent_bg">
                         <div class="tm_invoice_left" style="max-width: 30%;">
                            <?php if($isBranded==1){?>
                            <div class="tm_logo"><img src="<?= $companyLogoWhite ?>" alt="Logo" style="padding: 2rem;"></div>
                            <?php } ?>
                         </div>
                         <div class="tm_invoice_right">
                            <?php if($isBranded==1){?>
                            <div class="tm_head_address tm_white_color">
                                <span><?=$companyName?></span><br>
                                <span><?=$companyAddress?></span><br>
                                <span><?=$companyWeb . ' - ' . $companyMail?></span><br>
                                <span><?=$companyPhone?></span>
                            </div>
                            <?php } ?>
                         </div>
                         <div class="tm_primary_color tm_text_uppercase tm_watermark_title tm_white_color"><?=$invoiceType3?></div>
                      </div>
                      <div class="tm_invoice_info">
                         <div class="tm_invoice_info_left tm_gray_bg">
                            <p class="tm_mb2"><b class="tm_primary_color">Sayın</b></p>
                            <p class="tm_mb0">
                                <?php 
                                    if (!empty($rowCli['username'])) echo $rowCli['username'] . "<br>";
                                    if (!empty($rowCli['clientTitle'])) echo $rowCli['clientTitle'] . "<br>";
                                    if (!empty($rowCli['taxOffice']) || !empty($rowCli['taxNumber'])) 
                                        echo trim($rowCli['taxOffice'] . ' - ' . $rowCli['taxNumber'], ' - ') . "<br>";
                                    if (!empty($rowCli['address'])) echo $rowCli['address'] . "<br>";
                                    if (!empty($rowCli['district']) || !empty($rowCli['city']) || !empty($rowCli['country'])) 
                                        echo trim($rowCli['district'] . '/' . $rowCli['city'] . '/' . $rowCli['country'], '/') . "<br>";
                                    if (!empty($rowCli['phone'])) echo $rowCli['phone'];
                                ?>
                            </p>
                         </div>
                         <div class="tm_invoice_info_right tm_text_right">
                            <p class="tm_invoice_number tm_m0"><?=$invoiceType3Lower?> No: <b class="tm_primary_color">#<?=$invKey?>-<?=$invID?></b></p>
                            <p class="tm_invoice_date tm_m0">Tarih: <b class="tm_primary_color">
                            <?php
                                    $dateCreate = strtotime($rowInv['dateCreate']);
                                    echo date('d.m.Y H:i', $dateCreate)
                                        ?>
                                        </b></p>
                         </div>
                      </div>
                      <div style="padding: 0 50px 20px 50px;">
                          <p class="tm_mb2"><b class="tm_primary_color"><?= $rowInv['title'] ?></b></p>
                      </div>
                      <div class="tm_invoice_details">
                         <div class="tm_table tm_style1 tm_mb30">
                            <div class="tm_border">
                               <div class="tm_table_responsive">
                                  <table class="tm_gray_bg">
                                     <thead>
                                        <tr>
                                           <th class="tm_width_1 tm_semi_bold tm_white_color tm_accent_bg">#</th>
                                           <th class="tm_width_5 tm_semi_bold tm_white_color tm_accent_bg">Ürün/Hizmet</th>
                                           <th class="tm_width_1 tm_semi_bold tm_white_color tm_accent_bg tm_border_left">Adet</th>
                                           <th class="tm_width_2 tm_semi_bold tm_white_color tm_accent_bg tm_border_left">Fiyat</th>
                                           <th class="tm_width_2 tm_semi_bold tm_white_color tm_accent_bg tm_border_left">İskonto</th>
                                           <th class="tm_width_2 tm_semi_bold tm_white_color tm_accent_bg tm_border_left">Ara Toplam</th>
                                           <th class="tm_width_2 tm_semi_bold tm_white_color tm_accent_bg tm_border_left">Vergi</th>
                                           <th class="tm_width_2 tm_semi_bold tm_white_color tm_accent_bg tm_border_left tm_text_right">Toplam</th>
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
        
        // Birim iskontoyu hesapla: (main_price - price)
        $unitDiscount = $rowPriceMain - $rowPrice;

        // Toplam iskontoyu hesapla: birim iskonto * quantity
        $rowDiscount = $unitDiscount * $rowQuantity;

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
        $unitDiscount  = number_format($unitDiscount, 2, ',', '.') . '₺';  // Birim iskonto formatı
        $rowDiscount   = number_format($rowDiscount, 2, ',', '.') . '₺';
        $rowSubTotal   = number_format($rowSubTotal, 2, ',', '.') . '₺';
        $rowTaxAmount  = number_format($rowTaxAmount, 2, ',', '.') . '₺';
        $rowTotal      = number_format($rowTotal, 2, ',', '.') . '₺';
        
        echo '
        <tr>
            <td>' . $sNo . '</td>
            <td><strong>' . $rowProductName . '</strong><br> '.$rowProductDescription.'</td>
            <td>' . $rowQuantity . '</td>
            <td class="ltr:text-right rtl:text-left">' . $rowPriceMain . '</td>
            <td class="ltr:text-right rtl:text-left">' . $unitDiscount . '</td>  <!-- Birim iskonto burada -->
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
                            </div>
                            <div class="tm_invoice_footer">
                               <div class="tm_left_footer">
                                   <?php if (!empty($rowInv['description'])) {?>
                                    <p class="tm_mb2"><b class="tm_primary_color">Not:</b></p>
                                    <p class="tm_mb0"><?=$rowInv['description']?></p>
                                   <?php } ?>
                               </div>
                               <div class="tm_right_footer">
                                  <table class="tm_gray_bg">
                                     <tbody>
                                        <tr>
                                           <td class="tm_width_3 tm_primary_color tm_bold">Ara Toplam</td>
                                           <td class="tm_width_3 tm_primary_color tm_text_right tm_bold"><?=number_format($cartTotalSub+$cartTotalDiscount, 2, ',', '.') . '₺' ?></td>
                                        </tr>
                                        <tr>
                                           <td class="tm_width_3 tm_primary_color tm_bold">İskonto</td>
                                           <td class="tm_width_3 tm_primary_color tm_bold tm_text_right"><?=number_format($cartTotalDiscount, 2, ',', '.') . '₺' ?></td>
                                        </tr>
                                        <tr>
                                           <td class="tm_width_3 tm_primary_color tm_bold">Toplam</td>
                                           <td class="tm_width_3 tm_primary_color tm_bold tm_text_right"><?=number_format($cartTotalSub, 2, ',', '.') . '₺' ?></td>
                                        </tr>
                                        <tr>
                                           <td class="tm_width_3 tm_primary_color tm_bold">Vergi</td>
                                           <td class="tm_width_3 tm_primary_color tm_bold tm_text_right"><?=number_format($cartTotalTax, 2, ',', '.') . '₺' ?></td>
                                        </tr>
                                        <tr class="tm_border_top tm_border_bottom tm_accent_bg">
                                           <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_white_color">Genel Toplam</td>
                                           <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_white_color tm_text_right"><?=number_format($cartTotalMain, 2, ',', '.') . '₺' ?></td>
                                        </tr>
                                     </tbody>
                                  </table>
                               </div>
                            </div>
                         </div>
                         <?php
                         if($rowInv['note'] != '' and $rowInv['note'] != null){
                         ?>
                         <div class="tm_padd_15_20 tm_gray_bg">
                            <p class="tm_mb5"><?= $rowInv['note'] ?></p>
                         </div>
                         <? } ?>
                      </div>
                   </div>
                </div>
                <div class="tm_invoice_btns tm_hide_print">
                   <a href="javascript:window.print()" class="tm_invoice_btn tm_color1">
                      <span class="tm_btn_icon">
                         <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"></path>
                            <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"></rect>
                            <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"></path>
                            <circle cx="392" cy="184" r="24" fill="currentColor"></circle>
                         </svg>
                      </span>
                      <span class="tm_btn_text">Yazdır</span>
                   </a>
                   <a href="javascript:window.print()" class="tm_invoice_btn tm_color2">
                   <button id="tm_download_btn" class="tm_invoice_btn">
                      <span class="tm_btn_icon">
                         <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <path d="M320 336h76c55 0 100-21.21 100-75.6s-53-73.47-96-75.6C391.11 99.74 329 48 256 48c-69 0-113.44 45.79-128 91.2-60 5.7-112 35.88-112 98.4S70 336 136 336h56M192 400.1l64 63.9 64-63.9M256 224v224.03" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"></path>
                         </svg>
                      </span>
                      <span class="tm_btn_text">İndir</span>
                   </button>
                   </a>
                </div>
             </div>
          </div>
       </div>
   </body>

</html>