<?php

// Veritabanı bağlantısı
include ("./assets/db/db_connect.php");

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Müşteri isimlerini çekme
$sql = "SELECT * FROM users_client";
$result = $conn->query($sql);

$clients = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row['username'];
    }
}

?>
<script>

    function clientInfoShow() {

        document.getElementById("client-info").style.display = "flex";
        document.getElementById("client-info-hr").style.display = "block";
        document.getElementById("clientInfoShowBtn").style.display = "none";
        document.getElementById("clientInfoHideBtn").style.display = "block";

    }

    function clientInfoHide() {

        document.getElementById("client-info").style.display = "none";
        document.getElementById("client-info-hr").style.display = "none";
        document.getElementById("clientInfoShowBtn").style.display = "block";
        document.getElementById("clientInfoHideBtn").style.display = "none";

    }

    function loadClientData() {
        var client = document.getElementById("client").value;

        if (client === "new") {
            enableFormFields();
            clearFormFields();
            document.getElementById("save-button-container").style.display = "block";
            document.getElementById("save-clientName-container").style.display = "block";
            document.getElementById("client-info").style.display = "flex";
            document.getElementById("client-info-hr").style.display = "block";
            document.getElementById("clientInfoShowBtn").style.display = "none";
            document.getElementById("clientInfoHideBtn").style.display = "none";
        } else {
            disableFormFields();
            document.getElementById("save-button-container").style.display = "none";
            document.getElementById("save-clientName-container").style.display = "none";
            document.getElementById("client-info").style.display = "none";
            document.getElementById("client-info-hr").style.display = "none";
            document.getElementById("clientInfoShowBtn").style.display = "block";
            document.getElementById("clientInfoHideBtn").style.display = "none";
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
        document.getElementById("clientName").value = clientData.username || "";
        document.getElementById("clientTitle").value = clientData.clientTitle || "";
        document.getElementById("taxOffice").value = clientData.taxOffice || "";
        document.getElementById("taxNumber").value = clientData.taxNumber || "";
        document.getElementById("address").value = clientData.address || "";
        document.getElementById("addressDistrict").value = clientData.district || "";
        document.getElementById("addressProvince").value = clientData.city || "";
        document.getElementById("addressCountry").value = clientData.country || "";
        document.getElementById("phone").value = clientData.phoneCompany || "";
        document.getElementById("email").value = clientData.email || "";
        document.getElementById("web").value = clientData.web || "";
        document.getElementById("contact").value = clientData.name || "";
        document.getElementById("contactPhone").value = clientData.phone || "";
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
                        var product_basis = row.querySelector('input[name="product_basis[]"]');

                        priceInput.value = product.price;
                        taxInput.value = product.tax;
                        product_basis=product.basis;
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
        var product_basis = row.querySelector('input[name="product_basis[]"]');

        var quantity = parseFloat(quantityInput.value) || 0;
        var price = parseFloat(priceInput.value) || 0;
        var initialPrice = parseFloat(priceInput.getAttribute('data-initial-price')) || 0;
        var taxRate = parseFloat(taxInput.value) || 0;



        var subtotal = quantity * price;
        var taxAmount = (subtotal * taxRate) / 100;
        var total = subtotal + taxAmount;
        var basis=quantity * price;
        product_basis.value=basis;


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

                            <div class="flex items-center gap-3">

                                <label for="client" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Cari Seçin</label>

                                <select required id="client" name="client" class="form-select flex-1"
                                    onchange="loadClientData()">
                                    <option value="">Müşteri Seçin</option>
                                    <option value="new">Yeni Müşteri Oluştur</option>
                                    <?php
                                    $sqlClient = "SELECT * FROM users_client";
                                    $resultClient = $conn->query($sqlClient);

                                    while ($rowClient = $resultClient->fetch_assoc()) {
                                        echo '<option value="' . $rowClient['id'] . '">' . $rowClient['username'] . '</option>';
                                    }
                                    ?>
                                </select>

                                <button type="button" class="btn btn-primary" onclick="clientInfoShow()"
                                    id="clientInfoShowBtn" style="display:none">
                                    Bilgileri Göster
                                </button>

                                <button type="button" class="btn btn-primary" onclick="clientInfoHide()"
                                    id="clientInfoHideBtn" style="display:none">
                                    Bilgileri Gizle
                                </button>

                            </div>

                        </div>
                    </div>

                    <hr class="my-6 border-[#e0e6ed] dark:border-[#1b2e4b]" id="client-info-hr" style="display:none">

                    <div class="mt-8 px-4">
                        <form method="POST" id="client-form" action="save_client.php">
                            <div class="flex flex-col justify-between lg:flex-row" id="client-info"
                                style="display:none">
                                <div class="mb-6 w-full lg:w-1/2 ltr:lg:mr-6 rtl:lg:ml-6 maxTablet-m0">

                                    <div class="text-lg font-semibold">Cari Bilgileri</div>

                                    <div id="save-clientName-container" style="display:none">
                                        <div class="mt-4 flex items-center">
                                            <label for="reciever-name" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                                Cari Adı
                                            </label>
                                            <input type="name" id="clientName" name="clientName"
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
                                        <label for="address" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Adres</label>
                                        <input id="address" type="text" name="address" class="form-input flex-1"
                                            placeholder="Adres Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="addressDistrict" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">İlçe</label>
                                        <input id="addressDistrict" type="text" name="addressDistrict"
                                            class="form-input flex-1" placeholder="İlçe Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="addressProvince" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">İl</label>
                                        <input id="addressProvince" type="text" name="addressProvince"
                                            class="form-input flex-1" placeholder="İl Girin" disabled>
                                    </div>

                                </div>
                                <div class="w-full lg:w-1/2">

                                    <div class="text-lg font-semibold maxTablet-hidden"><br></div>

                                    <div class="mt-4 flex items-center">
                                        <label for="addressCountry" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Ülke</label>
                                        <input id="addressCountry" type="text" name="addressCountry"
                                            class="form-input flex-1" placeholder="Ülke Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="phone" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Telefon</label>
                                        <input id="phone" type="text" name="phone" class="form-input flex-1"
                                            placeholder="Telefon Numarası Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="email" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">e-Mail</label>
                                        <input id="email" type="email" name="email" class="form-input flex-1"
                                            placeholder="e-Mail Adresi Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="web" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Web</label>
                                        <input id="web" type="url" name="web" class="form-input flex-1"
                                            placeholder="Web Adresi Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="contact" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Yetkili</label>
                                        <input id="contact" type="text" name="contact" class="form-input flex-1"
                                            placeholder="Yetkili Girin" disabled>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label for="contactPhone" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Yetkili
                                            Telefon</label>
                                        <input id="contactPhone" type="text" name="contactPhone"
                                            class="form-input flex-1" placeholder="Telefon Numarası Girin" disabled>
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

                    <form id="orderForm" action="./pages/manager/invoice/process_dispatch.php" method="POST">

                        <div style="display:none">
                            <input required class="form-input flex-1" id="clientIDcontainer" name="clientID" value="">
                        </div>

                        <div class="mt-4 px-4">

                            <div class="flex flex-col justify-between lg:flex-row  gap-4">


                                <div class="mb-6 w-full lg:w-1/2 ltr:lg:mr-6 rtl:lg:ml-6 maxTablet-m0">

                                    <div class="text-lg font-semibold">İrsaliye Bilgileri</div>

                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            İrsaliye Başlığı (Opsiyonel)
                                        </label>

                                        <input type="text" name="title" class="form-input  flex-1" />
                                    </div>


                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            İrsaliye Tarihi
                                        </label>
                                        <input type="date" name="date" class="form-input  flex-1" />
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            İrsaliye Tipi
                                        </label>
                                        <select name="type" class="form-select flex-1">
                                            <option value="">İrsaliye Tipi Seçiniz</option>
                                            <option value="purchase">Mal Girişi</option>
                                            <option value="sale">Mal Çıkışı</option>
                                        </select>
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            İrsaliye No
                                        </label>

                                        <input type="text" name="docNumber" class="form-input  flex-1" />
                                    </div>

                                </div>
                                <div class="w-full lg:w-1/2">

                                    <div class="text-lg font-semibold maxTablet-hidden"><br></div>

                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            Plaka No
                                        </label>

                                        <input type="text" name="plaka" class="form-input  flex-1" />
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            Teslim Eden
                                        </label>

                                        <input type="text" name="teslimEden" class="form-input  flex-1" />
                                    </div>

                                    <div class="mt-4 flex items-center">
                                        <label class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            Teslim Alan
                                        </label>

                                        <input type="text" name="teslimAlan" class="form-input  flex-1" />
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
                                            <th class="w-1">Matrah</th>
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
                                                        name="product_name[]" oninput="searchProduct(this)"
                                                        autocomplete="off">
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
                                                <td class="matrah" ><input disabled type="text" class="form-input w-32" placeholder="KDV Oranı"
                                                        name="product_basis[]"
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
                            <label for="note">Not</label>
                            <textarea name="note" class="form-textarea min-h-[130px]"
                                placeholder="Not Yazınız"></textarea>
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