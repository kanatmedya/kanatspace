<?php
// Veritabanı bağlantısı

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

?>
<script>
    function loadClientData() {
        var client = document.getElementById("client").value;

        if (client === "new") {
            enableFormFields();
            clearFormFields();
            document.getElementById("save-button-container").style.display = "block";
            document.getElementById("save-clientName-container").style.display = "block";
            document.getElementById("save-accountType").style.display = "flex";
        } else {
            disableFormFields();
            document.getElementById("save-button-container").style.display = "none";
            document.getElementById("save-clientName-container").style.display = "none";
            document.getElementById("save-accountType").style.display = "none";
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "pages/manager/invoice/modules/client_data.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var clientData = JSON.parse(xhr.responseText);
                    updateClientInfo(clientData);
                }
            };
            xhr.send("client=" + client);
        }
    }

    function updateClientInfo(clientData) {
        document.getElementById("accountType").value = clientData.accountType || "";
        document.getElementById("username").value = clientData.username || "";
        document.getElementById("clientTitle").value = clientData.clientTitle || "";
        document.getElementById("taxOffice").value = clientData.taxOffice || "";
        document.getElementById("taxNumber").value = clientData.taxNumber || "";
        document.getElementById("address").value = clientData.address || "";
        document.getElementById("district").value = clientData.district || "";
        document.getElementById("city").value = clientData.city || "";
        document.getElementById("country").value = clientData.country || "";
        document.getElementById("phoneCompany").value = clientData.phoneCompany || "";
        document.getElementById("email").value = clientData.email || "";
        document.getElementById("web").value = clientData.web || "";
        document.getElementById("name").value = clientData.name || "";
        document.getElementById("phone").value = clientData.phone || "";
        
        document.getElementById("clientIDcontainer").value = clientData.id || "";
    }

    function disableFormFields() {
        var fields = document.querySelectorAll("#client-info input");
        fields.forEach(function (field) {
            field.disabled = true;
        });
    }

    function enableFormFields() {
        var fields = document.querySelectorAll("#client-info input");
        fields.forEach(function (field) {
            field.disabled = false;
        });
    }

    function clearFormFields() {
        var fields = document.querySelectorAll("#client-info input");
        fields.forEach(function (field) {
            field.value = "";
        });
    }

    function saveNewClient() {
        var fields = document.querySelectorAll("#client-info input");
        var allEmpty = Array.from(fields).every(function (field) {
            return field.value.trim() === "";
        });

        if (allEmpty) {
            alert("Lütfen tüm alanları doldurun.");
            return;
        }

        var formData = new FormData(document.getElementById("client-form"));
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "pages/manager/invoice/modules/save_client.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                alert(xhr.responseText);
                clearFormFields();
                document.getElementById("save-button-container").style.display = "none";
                location.reload();  // Sayfayı yeniden yükleyerek müşteri listesini günceller
            }
        };
        xhr.send(formData);
    }

    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Search product function
    function searchProduct(input) {
        var searchTerm = input.value;
        var suggestionsContainer = input.nextElementSibling;

        if (searchTerm.length < 2) {
            suggestionsContainer.innerHTML = "";
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "pages/manager/invoice/modules/search_product.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var products = JSON.parse(xhr.responseText);
                suggestionsContainer.innerHTML = "";
                products.forEach(function (product) {
                    var suggestion = document.createElement("div");
                    suggestion.classList.add("suggestion");
                    suggestion.innerHTML = product.name;
                    suggestion.onclick = function () {
                        input.value = product.name;
                        suggestionsContainer.innerHTML = "";
                        var row = input.closest("tr");
                        var priceInput = row.querySelector('input[name="product_price[]"]');
                        var taxInput = row.querySelector('input[name="product_tax[]"]');
                        priceInput.value = product.price;
                        taxInput.value = product.tax;
                        priceInput.setAttribute('data-initial-price', product.price);
                        updateCalculations(row);
                    };
                    suggestionsContainer.appendChild(suggestion);
                });
            }
        };
        xhr.send("searchTerm=" + searchTerm);
    }

    // Update calculations
    function updateCalculations(row) {
        var quantityInput = row.querySelector('input[name="product_quantity[]"]');
        var priceInput = row.querySelector('input[name="product_price[]"]');
        var taxInput = row.querySelector('input[name="product_tax[]"]');

        var quantity = parseFloat(quantityInput.value) || 0;
        var price = parseFloat(priceInput.value) || 0;
        var initialPrice = parseFloat(priceInput.getAttribute('data-initial-price')) || 0;
        var taxRate = parseFloat(taxInput.value) || 0;

        var subtotal = quantity * price;
        var taxAmount = (subtotal * taxRate) / 100;
        var total = subtotal + taxAmount;

        row.querySelector(".tax-sum").innerText = taxAmount.toFixed(2) + "₺";
        row.querySelector(".total-sum").innerText = total.toFixed(2) + "₺";

        updateGrandTotal();
    }

    // Update grand total
    function updateGrandTotal() {
        var grandTotal = 0;
        var subTotal = 0;
        var totalDiscount = 0;
        var totalTax = 0;

        var rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
            var quantity = parseFloat(row.querySelector('input[name="product_quantity[]"]').value) || 0;
            var price = parseFloat(row.querySelector('input[name="product_price[]"]').value) || 0;
            var initialPrice = parseFloat(row.querySelector('input[name="product_price[]"]').getAttribute('data-initial-price')) || 0;
            var taxRate = parseFloat(row.querySelector('input[name="product_tax[]"]').value) || 0;

            var subtotal = quantity * price;
            var initialSubtotal = quantity * initialPrice;
            var discount = initialSubtotal - subtotal;
            var taxAmount = (subtotal * taxRate) / 100;
            var total = subtotal + taxAmount;

            subTotal += initialSubtotal;
            totalDiscount += discount;
            totalTax += taxAmount;
            grandTotal += total;
        });

        document.getElementById("sub-total").innerText = subTotal.toFixed(2) + "₺";
        document.getElementById("total-discount").innerText = totalDiscount.toFixed(2) + "₺";
        document.getElementById("total-amount").innerText = (subTotal - totalDiscount).toFixed(2) + "₺";
        document.getElementById("total-tax").innerText = totalTax.toFixed(2) + "₺";
        document.getElementById("grand-total").innerText = (subTotal - totalDiscount + totalTax).toFixed(2) + "₺";
    }

    document.addEventListener('DOMContentLoaded', () => {
        var quantityInputs = document.querySelectorAll('input[name="product_quantity[]"]');
        var priceInputs = document.querySelectorAll('input[name="product_price[]"]');
        var taxInputs = document.querySelectorAll('input[name="product_tax[]"]');

        var debouncedUpdateCalculations = debounce(function () {
            var row = this.closest('tr');
            updateCalculations(row);
        }, 300);

        quantityInputs.forEach(input => input.addEventListener('input', debouncedUpdateCalculations));
        priceInputs.forEach(input => input.addEventListener('input', debouncedUpdateCalculations));
        taxInputs.forEach(input => input.addEventListener('input', debouncedUpdateCalculations));
    });
</script>
</head>

<body>
    <div class="animate__animated p-6" :class="[$store.app.animation]">
        <!-- start main content section -->
        <div x-data="invoiceAdd">

            <div class="flex flex-col gap-2.5 xl:flex-row">
                <div class="panel flex-1 px-0 py-6 ltr:lg:mr-6 rtl:lg:ml-6">

                    <div class="px-4">
                        <div class="mb-6 w-full ltr:lg:mr-6 rtl:lg:ml-6">

                            <div class="flex items-center">

                                <label for="client" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Cari Seçin</label>

                                <select required id="client" name="client" class="form-select flex-1"
                                    onchange="loadClientData()">
                                    <option value="">Cari Seçin</option>
                                    <option value="new">Yeni Cari Oluştur</option>
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
                                    
                                    <optgroup label="Müşteriler">
                                        <?php
                                            while ($rowClient = $resultClient->fetch_assoc()) {
                                                echo '<option value="' . $rowClient['id'] . '">' . $rowClient['username'] . '</option>';
                                            }
                                        ?>
                                    </optgroup>
                                    
                                    <optgroup label="Bayiler">
                                        <?php
                                            while ($rowDealer = $resultDealer->fetch_assoc()) {
                                                echo '<option value="' . $rowDealer['id'] . '">' . $rowDealer['username'] . '</option>';
                                            }
                                        ?>
                                    </optgroup>
                                    
                                    <optgroup label="Tedarikçiler">
                                        <?php
                                            while ($rowSupplier = $resultSupplier->fetch_assoc()) {
                                                echo '<option value="' . $rowSupplier['id'] . '">' . $rowSupplier['username'] . '</option>';
                                            }
                                        ?>
                                    </optgroup>
                                    
                                    <!--<optgroup label="Bireysel Hesap">
                                        <?php
                                            /*while ($rowIndividual = $resultIndividual->fetch_assoc()) {
                                                echo '<option value="' . $rowIndividual['username'] . '">' . $rowIndividual['username'] . '</option>';
                                            }*/
                                        ?>
                                    </optgroup>
                                    
                                    <optgroup label="Personel">
                                        <?php
                                            /*while ($rowEmployee = $resultEmployee->fetch_assoc()) {
                                                echo '<option value="' . $rowEmployee['username'] . '">' . $rowEmployee['username'] . '</option>';
                                            }*/
                                        ?>
                                    </optgroup>
                                    
                                    <optgroup label="Ortaklar">
                                        <?php
                                            /*while ($rowManager = $resultManager->fetch_assoc()) {
                                                echo '<option value="' . $rowManager['username'] . '">' . $rowManager['username'] . '</option>';
                                            }*/
                                        ?>
                                    </optgroup>-->
                                </select>

                            </div>

                        </div>
                    </div>

                    <hr class="my-6 border-[#e0e6ed] dark:border-[#1b2e4b]">

                    <div class="mt-8 px-4">
                        <form method="POST" id="client-form" action="save_client.php">
                            <div class="flex flex-col justify-between lg:flex-row" id="client-info">
                                <div class="mb-6 w-full lg:w-1/2 ltr:lg:mr-6 rtl:lg:ml-6 maxTablet-m0">

                                    <div class="text-lg font-semibold">Cari Bilgileri</div>

                                    <div id="save-clientName-container" style="display:none">
                                        <div class="mt-4 flex items-center">
                                            <label for="reciever-name" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                                Cari Adı
                                            </label>
                                            <input type="name" id="username" name="username"
                                                class="form-input flex-1" x-model="" placeholder="Cari Adı" required>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="clientTitle" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Firma
                                            Ünvanı</label>
                                        <input type="text" id="clientTitle" name="clientTitle" class="form-input flex-1"
                                            placeholder="Firma Ünvanı Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="taxNumber" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">VK No</label>
                                        <input id="taxNumber" type="text" name="taxNumber" class="form-input flex-1"
                                            placeholder="Vergi Kimlik No Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="taxOffice" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Vergi
                                            Dairesi</label>
                                        <input id="taxOffice" type="text" name="taxOffice" class="form-input flex-1"
                                            placeholder="Vergi Dairesi Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="name" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Yetkili</label>
                                        <input id="name" type="text" name="name" class="form-input flex-1"
                                            placeholder="Yetkili Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="phone" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Yetkili
                                            Telefon</label>
                                        <input id="phone" type="text" name="phone"
                                            class="form-input flex-1" placeholder="Telefon Numarası Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="web" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Web</label>
                                        <input id="web" type="url" name="web" class="form-input flex-1"
                                            placeholder="Web Adresi Girin" disabled>
                                    </div>

                                </div>
                                <div class="w-full lg:w-1/2">

                                    <div class="text-lg font-semibold maxTablet-hidden"><br></div>
                                    
                                    <div class="mt-4 flex items-center" id="save-accountType" style="display:none;">
                                        <label for="accountType" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Cari Tipi</label>
                                        <select required id="accountType" name="accountType" class="form-select flex-1">
                                            <option value="">Cari Tipi</option>
                                            <option value="client">Müşteri</option>
                                            <option value="dealer">Bayi</option>
                                            <option value="supplier">Tedarikçi</option>
                                        </select>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="address" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Adres</label>
                                        <input id="address" type="text" name="address" class="form-input flex-1"
                                            placeholder="Adres Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="district" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">İlçe</label>
                                        <input id="district" type="text" name="district"
                                            class="form-input flex-1" placeholder="İlçe Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="city" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">İl</label>
                                        <input id="city" type="text" name="city"
                                            class="form-input flex-1" placeholder="İl Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="country" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Ülke</label>
                                        <input id="country" type="text" name="country"
                                            class="form-input flex-1" placeholder="Ülke Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="phoneCompany" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Telefon</label>
                                        <input id="phoneCompany" type="text" name="phoneCompany" class="form-input flex-1"
                                            placeholder="Telefon Numarası Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="email" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">e-Mail</label>
                                        <input id="email" type="email" name="email" class="form-input flex-1"
                                            placeholder="e-Mail Adresi Girin" disabled>
                                    </div>

                                </div>
                            </div>
                            <div class="mt-8 px-4" id="save-button-container" style="display:none;">
                                <button type="button" class="btn btn-success w-full gap-2" onclick="saveNewClient()"
                                    id="saveNewClientButton">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                                        <path
                                            d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 11.6585 22 11.4878 21.9848 11.3142C21.9142 10.5049 21.586 9.71257 21.0637 9.09034C20.9516 8.95687 20.828 8.83317 20.5806 8.58578L15.4142 3.41944C15.1668 3.17206 15.0431 3.04835 14.9097 2.93631C14.2874 2.414 13.4951 2.08581 12.6858 2.01515C12.5122 2 12.3415 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z"
                                            stroke="currentColor" stroke-width="1.5" />
                                        <path
                                            d="M17 22V21C17 19.1144 17 18.1716 16.4142 17.5858C15.8284 17 14.8856 17 13 17H11C9.11438 17 8.17157 17 7.58579 17.5858C7 18.1716 7 19.1144 7 21V22"
                                            stroke="currentColor" stroke-width="1.5" />
                                        <path opacity="0.5" d="M7 8H13" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                    Kaydet
                                </button>
                            </div>
                        </form>
                    </div>

                    <hr class="my-4 border-[#e0e6ed] dark:border-[#1b2e4b]">

                    <form id="orderForm" action="./pages/manager/invoice/modules/process_order.php" method="POST">

                        <div style="display:none">
                            <input required class="form-input flex-1" id="clientIDcontainer" name="clientID" value="">
                        </div>  

                        <div class="mt-4 px-4">

                            <div class="flex flex-col justify-between lg:flex-row">


                                <div class="mb-6 w-full lg:w-1/2 ltr:lg:mr-6 rtl:lg:ml-6 maxTablet-m0">

                                    <div class="text-lg font-semibold">Belge Bilgileri</div>

                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            Belge Başlığı
                                        </label>

                                        <input required type="text" name="title" class="form-input flex-1">
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Belge Türü</label>
                                        <select name="type" class="form-select flex-1">
                                            <option value="">Belge Türü Seçiniz</option>
                                            <option value="sale">Satış Faturası</option>
                                            <option value="purchase">Alış Faturası</option>
                                        </select>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Belge Durumu</label>
                                        <select required name="status" class="form-select flex-1">
                                            <option value="onOffer">Belge Durumu Seçiniz</option>
                                            <option value="onOffer">Teklif Aşamasında</option>
                                            <option value="onOrder">Sipariş Aşamasında</option>
                                            <option value="completed">Tamamlandı</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="w-full lg:w-1/2">

                                    <div class="text-lg font-semibold maxTablet-hidden"><br></div>

                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            GİB Fatura No
                                        </label>

                                        <input type="text" name="docNumber" class="form-input w-2/3 lg:w-[250px]">
                                    </div>


                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            Belge Tarihi
                                        </label>
                                        <input type="date" name="date" class="form-input w-2/3 lg:w-[250px]" />
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            Ödeme Tarihi
                                        </label>

                                        <input type="date" name="datePayment" class="form-input w-2/3 lg:w-[250px]" />
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Ürün</th>
                                            <th class="w-1">Adet</th>
                                            <th class="w-1">Fiyat</th>
                                            <th class="w-1">KDV(%)</th>
                                            <th class="w-1">KDV(₺)</th>
                                            <th>Toplam</th>
                                            <th class="w-1"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-if="items.length <= 0">
                                            <tr>
                                                <td colspan="5" class="!text-center font-semibold">Ürün Yok</td>
                                            </tr>
                                        </template>
                                        <template x-for="(item, i) in items" :key="i">
                                            <tr class="border-b border-[#e0e6ed] align-top dark:border-[#1b2e4b]">
                                                <td>
                                                    <input type="text" class="form-input min-w-[200px]"
                                                        placeholder="Ürün Adı Girin" x-model="item.title"
                                                        name="product_name[]" oninput="searchProduct(this)" autocomplete="off">
                                                    <div class="suggestions-container"></div>
                                                    <textarea class="form-textarea mt-4"
                                                        placeholder="(Opsiyonel) Ürün Açıklaması"
                                                        x-model="item.description"
                                                        name="product_description[]"></textarea>
                                                </td>
                                                <td><input type="float" class="form-input w-32" placeholder="Adet"
                                                        name="product_quantity[]"
                                                        oninput="updateCalculations(this.closest('tr'))"></td>
                                                <td><input type="float" class="form-input w-32" placeholder="Fiyat"
                                                        name="product_price[]"
                                                        oninput="updateCalculations(this.closest('tr'))"></td>
                                                <td><input type="number" class="form-input w-32" placeholder="KDV Oranı"
                                                        name="product_tax[]"
                                                        oninput="updateCalculations(this.closest('tr'))"></td>
                                                <td class="tax-sum"></td>
                                                <td class="total-sum"></td>
                                                <td>
                                                    <button type="button" @click="removeItem(item)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px"
                                                            height="24px" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="h-5 w-5">
                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-6 flex flex-col justify-between px-4 sm:flex-row">
                                <div class="mb-6 sm:mb-0">
                                    <button type="button" class="btn btn-primary" @click="addItem()">Ürün Ekle</button>
                                </div>
                                <div class="sm:w-2/5">
                                    <div class="flex items-center justify-between">
                                        <div>Ara Toplam</div>
                                        <div id="sub-total">0.00₺</div>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between">
                                        <div>İskonto (₺)</div>
                                        <div id="total-discount">0.00₺</div>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between">
                                        <div>Toplam (₺)</div>
                                        <div id="total-amount">0.00₺</div>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between">
                                        <div>Vergi (₺)</div>
                                        <div id="total-tax">0.00₺</div>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between font-semibold">
                                        <div> Genel Toplam</div>
                                        <div id="grand-total">0.00₺</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 px-4">
                            <label for="note">Notes</label>
                            <textarea name="note" class="form-textarea min-h-[130px]"
                                placeholder="Notes...."></textarea>
                        </div>

                        <!-- Eylemler//Butonlar -->
                        <div class="panel">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1">

                                <button type="submit" class="btn btn-success w-full gap-2" id="saveOrderButton">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                                        <path
                                            d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 11.6585 22 11.4878 21.9848 11.3142C21.9142 10.5049 21.586 9.71257 21.0637 9.09034C20.9516 8.95687 20.828 8.83317 20.5806 8.58578L15.4142 3.41944C15.1668 3.17206 15.0431 3.04835 14.9097 2.93631C14.2874 2.414 13.4951 2.08581 12.6858 2.01515C12.5122 2 12.3415 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z"
                                            stroke="currentColor" stroke-width="1.5" />
                                        <path
                                            d="M17 22V21C17 19.1144 17 18.1716 16.4142 17.5858C15.8284 17 14.8856 17 13 17H11C9.11438 17 8.17157 17 7.58579 17.5858C7 18.1716 7 19.1144 7 21V22"
                                            stroke="currentColor" stroke-width="1.5" />
                                        <path opacity="0.5" d="M7 8H13" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                    Kaydet
                                </button>

                                <!--<button type="button" class="btn btn-info w-full gap-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                                        <path
                                            d="M17.4975 18.4851L20.6281 9.09373C21.8764 5.34874 22.5006 3.47624 21.5122 2.48782C20.5237 1.49939 18.6511 2.12356 14.906 3.37189L5.57477 6.48218C3.49295 7.1761 2.45203 7.52305 2.13608 8.28637C2.06182 8.46577 2.01692 8.65596 2.00311 8.84963C1.94433 9.67365 2.72018 10.4495 4.27188 12.0011L4.55451 12.2837C4.80921 12.5384 4.93655 12.6658 5.03282 12.8075C5.22269 13.0871 5.33046 13.4143 5.34393 13.7519C5.35076 13.9232 5.32403 14.1013 5.27057 14.4574C5.07488 15.7612 4.97703 16.4131 5.0923 16.9147C5.32205 17.9146 6.09599 18.6995 7.09257 18.9433C7.59255 19.0656 8.24576 18.977 9.5522 18.7997L9.62363 18.79C9.99191 18.74 10.1761 18.715 10.3529 18.7257C10.6738 18.745 10.9838 18.8496 11.251 19.0285C11.3981 19.1271 11.5295 19.2585 11.7923 19.5213L12.0436 19.7725C13.5539 21.2828 14.309 22.0379 15.1101 21.9985C15.3309 21.9877 15.5479 21.9365 15.7503 21.8474C16.4844 21.5244 16.8221 20.5113 17.4975 18.4851Z"
                                            stroke="currentColor" stroke-width="1.5" />
                                        <path opacity="0.5" d="M6 18L21 3" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                    Gönder
                                </button>

                                <a href="apps-invoice-preview.html" class="btn btn-primary w-full gap-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                                        <path opacity="0.5"
                                            d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                        <path
                                            d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                    </svg>
                                    Önizleme
                                </a>

                                <button type="button" class="btn btn-secondary w-full gap-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                                        <path opacity="0.5"
                                            d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                        <path d="M12 2L12 15M12 15L9 11.5M12 15L15 11.5" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    İndir/Yazdır
                                </button>
                                -->
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- end main content section -->
    </div>
</body>

</html>