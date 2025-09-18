<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    $cc = $_POST['cc'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $categorySub = $_POST['categorySub'];
    $sender = $_POST['sender'];
    $reciever = $_POST['reciever'];
    $description = $_POST['description'];
    $user = $_SESSION['name'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // İşlem tarih ve zamanını birleştirme
    $dateProcess = $date . ' ' . $time . ':00';

    // Veritabanına ekleme
    $sql = "INSERT INTO accounting_transactions (amount, cc, type, category, categorySub, sender, reciever, description, user, date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $amount, $cc, $type, $category, $categorySub, $sender, $reciever, $description, $user, $dateProcess);

    if ($stmt->execute()) {
        echo "Proje başarıyla kaydedildi.";
    } else {
        echo "Hata: " . $stmt->error;
    }
}

?>

<script>
    $(document).ready(function() {
        // İşlem türü seçildiğinde kategori ve başlığı göster
        $('select[name="type"]').change(function() {
            var type = $(this).val();
            if (type != "-1") {
                $("#category-header").removeClass("hidden");
                $("#category-section").show();
                updateCategories();
            } else {
                $("#category-header").addClass("hidden");
                $("#category-section").hide();
            }
        });

        // Kategori seçildiğinde alt kategorileri göster
        $('select[name="category"]').change(function() {
            var categoryId = $(this).val();
            if (categoryId != "-1") {
                $("#subcategory-section").show();
                updateSubCategories();
            } else {
                $("#subcategory-section").hide();
            }
        });

        // Alt kategori seçildiğinde gönderici ve alıcıları göster
        $('select[name="categorySub"]').change(function() {
            var categorySubId = $(this).val();
            if (categorySubId != "-1") {
                $("#sender-section").show();
                $("#receiver-section").show();
            } else {
                $("#sender-section").hide();
                $("#receiver-section").hide();
            }
        });

        // Gönderici veya alıcı seçildiğinde açıklama ve kaydet butonunu göster
        $('select[name="sender"], select[name="reciever"]').change(function() {
            var sender = $('select[name="sender"]').val();
            var reciever = $('select[name="reciever"]').val();
            if (sender != "-1" || reciever != "-1") {  // Sadece biri değişirse
                $("#description-section").removeClass("hidden");
                $("#saveOrderButton").removeClass("hidden");
            } else {
                $("#description-section").addClass("hidden");
                $("#saveOrderButton").addClass("hidden");
            }
        });

        function updateCategories() {
            var type = $('select[name="type"]').val();

            $.ajax({
                url: 'pages/manager/accounting/transferAdd/get_categories.php',
                type: 'POST',
                data: {type: type},
                success: function(response) {
                    console.log(response);
                    $('select[name="category"]').html(response);
                    updateSubCategories();
                }
            });
        }

        function updateSubCategories() {
            var categoryId = $('select[name="category"]').val();

            $.ajax({
                url: 'pages/manager/accounting/transferAdd/get_sub_categories.php',
                type: 'POST',
                data: {categoryId: categoryId},
                success: function(response) {
                    console.log(response);
                    $('select[name="categorySub"]').html(response);
                }
            });
        }
    });
</script>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <form method="post">
        <div class="panel grid grid-rows gap-4 px-6 pt-6">
            <p>Ödeme Bilgileri</p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4">
                <!-- Tutar -->
                <div class="flex">
                    <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                        Tutar
                    </div>
                    <input name="amount" type="text" placeholder="" class="form-input rounded-none rounded-tr-md rounded-br-md" />
                </div>

                <!-- Döviz -->
                <div class="flex">
                    <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                        Döviz
                    </div>
                    <select name="cc" class="form-select rounded-none rounded-tr-md rounded-br-md">
                        <option selected value="₺">₺</option>
                        <option value="$">$</option>
                        <option value="€">€</option>
                    </select>
                </div>

                <!-- İşlem Türü -->
                <div class="flex">
                    <select name="type" class="form-select text-white-dark">
                        <option value="-1">İşlem Türü Seçiniz</option>
                        <option value="0">Gider</option>
                        <option value="1">Gelir</option>
                    </select>
                </div>

                <!-- İşlem Tarihi -->
                <div class="flex">
                    <input type="date" name="date" class="form-input rounded-none rounded-tl-md rounded-bl-md" value="<?php echo date('Y-m-d'); ?>" />
                    <input type="time" name="time" class="form-input rounded-none rounded-tr-md rounded-br-md" value="<?php echo date('H:m'); ?>" />
                </div>
            </div>

            <p class="mt-5 hidden" id="category-header">Kategori ve Hesap Bilgileri</p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4" id="category-section" style="display:none;">
                <!-- Kategori -->
                <div>
                    <select name="category" class="form-select text-white-dark">
                    </select>
                </div>

                <!-- Alt Kategori -->
                <div id="subcategory-section" style="display:none;">
                    <select name="categorySub" class="form-select text-white-dark">
                    </select>
                </div>



                <!-- Gönderici -->
                <div id="sender-section" style="display:none;">
                    <select name="sender" class="form-select text-white-dark">
                        <option value="-1">Gönderici</option>
                        <option disabled style="font-weight: bold;">Şirket Hesapları</option>
                        <option value="Şirket Nakit Hesabı">Nakit Hesabı</option>
                        <option value="Şirket Kart Hesabı">Kart Hesabı</option>
                        <option value="Şirket Çek/Senet Hesabı">Çek/Senet Hesabı</option>
                        <option value="Şirket Ortaklar Hesabı">Ortaklar Hesabı</option>
                        <?php
                            // client sorgusu
                            $sqlClient = "SELECT *
                                            FROM users_client WHERE accountType='client' ORDER BY username";
                            $resultClient = $conn->query($sqlClient);
                            
                            // dealer sorgusu
                            $sqlDealer = "SELECT *
                                            FROM users_client WHERE accountType='dealer' ORDER BY username";
                            $resultDealer = $conn->query($sqlDealer);
                            
                            // supplier sorgusu
                            $sqlSupplier = "SELECT *
                                            FROM users_client WHERE accountType='supplier' ORDER BY username";
                            $resultSupplier = $conn->query($sqlSupplier);
                            
                            // individual sorgusu
                            $sqlIndividual = "SELECT *
                                            FROM users_client WHERE accountType='individual' ORDER BY username";
                            $resultIndividual = $conn->query($sqlIndividual);
                            
                            // employee sorgusu
                            $sqlEmployee = "SELECT CONCAT(ac_users.name, ' ', ac_users.surname) AS username
                                            FROM ac_users WHERE accountType='2' OR accountType='3' OR accountType='4' OR accountType='5' ORDER BY username";
                            $resultEmployee = $conn->query($sqlEmployee);
                            
                            // employee sorgusu
                            $sqlManager = "SELECT CONCAT(ac_users.name, ' ', ac_users.surname) AS username
                                            FROM ac_users WHERE accountType='1' ORDER BY username";
                            $resultManager = $conn->query($sqlManager);
                            
                        ?>
                        
                        <optgroup label="Müşteriler" style="font-weight: bold;">
                            <?php
                                while ($rowClient = $resultClient->fetch_assoc()) {
                                    echo '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
                                }
                            ?>
                        </optgroup>
                        
                        <optgroup label="Bayiler" style="font-weight: bold;">
                            <?php
                                while ($rowDealer = $resultDealer->fetch_assoc()) {
                                    echo '<option value="' . $rowDealer['username'] . '">' . $rowDealer['username'] . '</option>';
                                }
                            ?>
                        </optgroup>
                        
                        <optgroup label="Tedarikçiler" style="font-weight: bold;">
                            <?php
                                while ($rowSupplier = $resultSupplier->fetch_assoc()) {
                                    echo '<option value="' . $rowSupplier['username'] . '">' . $rowSupplier['username'] . '</option>';
                                }
                            ?>
                        </optgroup>
                        
                        <optgroup label="Bireysel Hesap" style="font-weight: bold;">
                            <?php
                                while ($rowIndividual = $resultIndividual->fetch_assoc()) {
                                    echo '<option value="' . $rowIndividual['username'] . '">' . $rowIndividual['username'] . '</option>';
                                }
                            ?>
                        </optgroup>
                        
                        <optgroup label="Personel" style="font-weight: bold;">
                            <?php
                                while ($rowEmployee = $resultEmployee->fetch_assoc()) {
                                    echo '<option value="' . $rowEmployee['username'] . '">' . $rowEmployee['username'] . '</option>';
                                }
                            ?>
                        </optgroup>
                        
                        <optgroup label="Ortaklar" style="font-weight: bold;">
                            <?php
                                while ($rowManager = $resultManager->fetch_assoc()) {
                                    echo '<option value="' . $rowManager['username'] . '">' . $rowManager['username'] . '</option>';
                                }
                            ?>
                        </optgroup>
                    </select>
                </div>

                <!-- Alıcı -->
                <div id="receiver-section" style="display:none;">
                    <select name="reciever" class="form-select text-white-dark">
                        <option value="-1">Alıcı</option>
                        <option disabled style="font-weight: bold;">Şirket Hesapları</option>
                        <option value="Şirket Nakit Hesabı">Nakit Hesabı</option>
                        <option value="Şirket Kart Hesabı">Kart Hesabı</option>
                        <option value="Şirket Çek/Senet Hesabı">Çek/Senet Hesabı</option>
                        <option value="Şirket Ortaklar Hesabı">Ortaklar Hesabı</option>
                        <?php
                            // client sorgusu
                            $sqlClient = "SELECT *
                                            FROM users_client WHERE accountType='client' ORDER BY username";
                            $resultClient = $conn->query($sqlClient);
                            
                            // dealer sorgusu
                            $sqlDealer = "SELECT *
                                            FROM users_client WHERE accountType='dealer' ORDER BY username";
                            $resultDealer = $conn->query($sqlDealer);
                            
                            // supplier sorgusu
                            $sqlSupplier = "SELECT *
                                            FROM users_client WHERE accountType='supplier' ORDER BY username";
                            $resultSupplier = $conn->query($sqlSupplier);
                            
                            // individual sorgusu
                            $sqlIndividual = "SELECT *
                                            FROM users_client WHERE accountType='individual' ORDER BY username";
                            $resultIndividual = $conn->query($sqlIndividual);
                            
                            // employee sorgusu
                            $sqlEmployee = "SELECT CONCAT(ac_users.name, ' ', ac_users.surname) AS username
                                            FROM ac_users WHERE accountType='2' OR accountType='3' OR accountType='4' OR accountType='5' ORDER BY username";
                            $resultEmployee = $conn->query($sqlEmployee);
                            
                            // employee sorgusu
                            $sqlManager = "SELECT CONCAT(ac_users.name, ' ', ac_users.surname) AS username
                                            FROM ac_users WHERE accountType='1' ORDER BY username";
                            $resultManager = $conn->query($sqlManager);
                            
                        ?>
                        
                        <optgroup label="Müşteriler" style="font-weight: bold;">
                            <?php
                                while ($rowClient = $resultClient->fetch_assoc()) {
                                    echo '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
                                }
                            ?>
                        </optgroup>
                        
                        <optgroup label="Bayiler" style="font-weight: bold;">
                            <?php
                                while ($rowDealer = $resultDealer->fetch_assoc()) {
                                    echo '<option value="' . $rowDealer['username'] . '">' . $rowDealer['username'] . '</option>';
                                }
                            ?>
                        </optgroup>
                        
                        <optgroup label="Tedarikçiler" style="font-weight: bold;">
                            <?php
                                while ($rowSupplier = $resultSupplier->fetch_assoc()) {
                                    echo '<option value="' . $rowSupplier['username'] . '">' . $rowSupplier['username'] . '</option>';
                                }
                            ?>
                        </optgroup>
                        
                        <optgroup label="Bireysel Hesap" style="font-weight: bold;">
                            <?php
                                while ($rowIndividual = $resultIndividual->fetch_assoc()) {
                                    echo '<option value="' . $rowIndividual['username'] . '">' . $rowIndividual['username'] . '</option>';
                                }
                            ?>
                        </optgroup>
                        
                        <optgroup label="Personel" style="font-weight: bold;">
                            <?php
                                while ($rowEmployee = $resultEmployee->fetch_assoc()) {
                                    echo '<option value="' . $rowEmployee['username'] . '">' . $rowEmployee['username'] . '</option>';
                                }
                            ?>
                        </optgroup>
                        
                        <optgroup label="Ortaklar" style="font-weight: bold;">
                            <?php
                                while ($rowManager = $resultManager->fetch_assoc()) {
                                    echo '<option value="' . $rowManager['username'] . '">' . $rowManager['username'] . '</option>';
                                }
                            ?>
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="flex mt-5 hidden" id="description-section">
                <input name="description" type="text" placeholder="" class="form-input rounded-none rounded-tr-md rounded-br-md" />
            </div>

            <button type="submit" class="btn btn-success w-full gap-2 mt-5 hidden" id="saveOrderButton">
                Kaydet
            </button>
        </div>
    </form>
</div>
