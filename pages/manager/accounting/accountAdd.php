<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    // Formdan gelen veriler
    $clientTitle = $_POST['clientTitle'] ?? '';
    $taxNumber = $_POST['taxNumber'] ?? '';
    $taxOffice = $_POST['taxOffice'] ?? '';
    $address = $_POST['address'] ?? '';
    $addressDistrict = $_POST['addressDistrict'] ?? '';
    $addressProvince = $_POST['addressProvince'] ?? '';
    $addressCountry = $_POST['addressCountry'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $web = $_POST['web'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $contactPhone = $_POST['contactPhone'] ?? '';
    $userType = $_POST['userType'] ?? '';


    // SQL sorgusu
    $sql = "INSERT INTO users_client (clientTitle, taxNumber, taxOffice, address, district, city, country, phoneCompany, email, web, name, phone, accountType)
    VALUES ('$clientTitle', '$taxNumber', '$taxOffice', '$address', '$addressDistrict', '$addressProvince', '$addressCountry', '$phone', '$email', '$web', '$contact', '$contactPhone', '$userType')";

    if ($conn->query($sql) === TRUE) {
        echo "Yeni kayıt başarıyla oluşturuldu";
    } else {
        echo "Hata: " . $sql . "<br>" . $conn->error;
    }

}
?>

<div class="animate__animated p-6" :class="[$store.app.animation]">

    <!-- start main content section -->
    <div x-data="invoiceAdd">

        <div class="flex flex-col gap-2.5 xl:flex-row">
            <div class="panel flex-1 px-0 py-6 ltr:lg:mr-6 rtl:lg:ml-6">
                <div class="px-4">
                    <form method="POST" id="client-form" action="accountAdd.php">
                        <div class="mt-4 flex items-center">
                            <label for="userType" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Müşteri Tipi</label>
                            <select id="userType" name="userType" class="form-input flex-1">
                                <option value="client">Müşteri</option>
                                <option value="supplier">Tedarikçi</option>
                                <option value="dealer">Bayi</option>
                            </select>
                        </div>
                        <div class="flex flex-col justify-between lg:flex-row" id="client-info">
                            <div class="mb-6 w-full lg:w-1/2 ltr:lg:mr-6 rtl:lg:ml-6 maxTablet-m0">

                                <div class="text-lg font-semibold">Cari Hesap Oluştur</div>

                                <div class="mt-4 flex items-center">
                                    <label for="clientTitle" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Firma
                                        Ünvanı</label>
                                    <input type="text" id="clientTitle" name="clientTitle" class="form-input flex-1"
                                        placeholder="Firma Ünvanı Girin" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="taxNumber" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">VK No</label>
                                    <input id="taxNumber" type="text" name="taxNumber" class="form-input flex-1"
                                        placeholder="Vergi Kimlik No Girin" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="taxOffice" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Vergi
                                        Dairesi</label>
                                    <input id="taxOffice" type="text" name="taxOffice" class="form-input flex-1"
                                        placeholder="Vergi Dairesi Girin" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="address" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Adres</label>
                                    <input id="address" type="text" name="address" class="form-input flex-1"
                                        placeholder="Adres Girin" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="addressDistrict" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">İlçe</label>
                                    <input id="addressDistrict" type="text" name="addressDistrict"
                                        class="form-input flex-1" placeholder="İlçe Girin" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="addressProvince" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">İl</label>
                                    <input id="addressProvince" type="text" name="addressProvince"
                                        class="form-input flex-1" placeholder="İl Girin" >
                                </div>

                            </div>
                            <div class="w-full lg:w-1/2">

                                <div class="text-lg font-semibold maxTablet-hidden"><br></div>

                                <div class="mt-4 flex items-center">
                                    <label for="addressCountry" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Ülke</label>
                                    <input id="addressCountry" type="text" name="addressCountry"
                                        class="form-input flex-1" placeholder="Ülke Girin" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="phone" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Telefon</label>
                                    <input id="phone" type="text" name="phone" class="form-input flex-1"
                                        placeholder="Telefon Numarası Girin" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="email" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">e-Mail</label>
                                    <input id="email" type="email" name="email" class="form-input flex-1"
                                        placeholder="e-Mail Adresi Girin" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="web" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Web</label>
                                    <input id="web"  name="web" class="form-input flex-1"
                                        placeholder="Web Adresi Girin" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="contact" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Yetkili</label>
                                    <input id="contact" type="text" name="contact" class="form-input flex-1"
                                        placeholder="Yetkili Girin" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="contactPhone" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">Yetkili Telefon</label>
                                    <input id="contactPhone" type="text" name="contactPhone" class="form-input flex-1"
                                        placeholder="Telefon Numarası Girin" >
                                </div>

                            </div>
                        </div>
                        <div class="mt-8 px-4" id="save-button-container">
                            <button type="submit" class="btn btn-success w-full gap-2" id="saveNewClientButton">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-2 rtl:ml-2">
                                    <path d="M3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 11.6585 22 11.4878 21.9848 11.3142C21.9142 10.5049 21.586 9.71257 21.0637 9.09034C20.9516 8.95687 20.828 8.83317 20.5806 8.58578L15.4142 3.41944C15.1668 3.17206 15.0431 3.04835 14.9097 2.93631C14.2874 2.414 13.4951 2.08581 12.6858 2.01515C12.5122 2 12.3415 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355Z" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M17 22V21C17 19.1144 17 18.1716 16.4142 17.5858C15.8284 17 14.8856 17 13 17H11C9.11438 17 8.17157 17 7.58579 17.5858C7 18.1716 7 19.1144 7 21V22" stroke="currentColor" stroke-width="1.5"/>
                                    <path opacity="0.5" d="M7 8H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
