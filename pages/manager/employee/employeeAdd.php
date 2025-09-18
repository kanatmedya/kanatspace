<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    // Formdan gelen veriler
    $name = $_POST['name'] ?? '';
    $department = $_POST['department'] ?? '';
    $position = $_POST['position'] ?? '';
    $phone_num = $_POST['phone_num'] ?? '';
    $emailCorporate = $_POST['emailCorporate'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $dateStart = $_POST['dateStart'] ?? '';
    $salary = $_POST['salary'] ?? '';
    $annualLeave = $_POST['annualLeave'] ?? '';
    $workDays = $_POST['workDays'] ?? '';
    $workHourStart = $_POST['workHourStart'] ?? '';
    $workHourEnd = $_POST['workHourEnd'] ?? '';
    $salaryFood = $_POST['salaryFood'] ?? '';
    $salaryRoad = $_POST['salaryRoad'] ?? '';


    // SQL sorgusu
    $sql = "INSERT INTO users_employee (status, userType, name,department,position,phone,email,emailPersonal,password,dateStart,salary,annualLeave,workDays,workHourStart,workHourEnd,salaryFood,salaryRoad)
    VALUES (1,2,'$name','$department','$position','$phone_num','$emailCorporate','$email','$password','$dateStart','$salary','$annualLeave','$workDays','$workHourStart','$workHourEnd','$salaryFood','$salaryRoad')";

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
                    <form method="POST" id="client-form" action="employeeAdd.php">
                        <div class="flex flex-col justify-between lg:flex-row" id="client-info">
                            <div class="mb-6 w-full lg:w-1/2 ltr:lg:mr-6 rtl:lg:ml-6 maxTablet-m0">

                                <div class="text-lg font-semibold">Personel Ekle</div>

                                <div class="mt-4 flex items-center">
                                    <label for="name" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Adı Soyadı    
                                    </label>
                                    <input type="text" id="name" name="name" class="form-input flex-1"
                                        placeholder="" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="department" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Departman
                                    </label>
                                    <input id="department" type="text" name="department" class="form-input flex-1"
                                        placeholder="" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="position" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Pozisyon
                                    </label>
                                    <input id="position" type="text" name="position" class="form-input flex-1"
                                        placeholder="" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="phone_num" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Telefon
                                    </label>
                                    <input id="phone_num" type="text" name="phone_num" class="form-input flex-1"
                                        placeholder="" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="emailCorporate" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Kurumsal Mail
                                    </label>
                                    <input id="emailCorporate" type="text" name="emailCorporate"
                                        class="form-input flex-1" placeholder="" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="email" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Kişisel Mail
                                    </label>
                                    <input id="email" type="text" name="email"
                                        class="form-input flex-1" placeholder="" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="password" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Space Şifresi
                                    </label>
                                    <input id="password" type="text" name="password"
                                        class="form-input flex-1" placeholder="" >
                                </div>

                            </div>
                            <div class="w-full lg:w-1/2">

                                <div class="text-lg font-semibold maxTablet-hidden"><br></div>

                                <div class="mt-4 flex items-center">
                                    <label for="dateStart" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        İşa Başlama
                                    </label>
                                        <input type="date" name="dateStart"
                                class="form-input flex-1"
                                value="<?php echo date('Y-m-d'); ?>" />
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="salary" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Maaş
                                    </label>
                                    <input id="salary" type="text" name="salary" class="form-input flex-1"
                                        placeholder="" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="annualLeave" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Yıllık İzin    
                                    </label>
                                    <input id="annualLeave" type="text" name="annualLeave" class="form-input flex-1"
                                        placeholder="" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="workDays" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            Çalışma Günleri
                                    </label>
                                    <input id="workDays" type="text" name="workDays" class="form-input flex-1"
                                        placeholder="" >
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="workHourStart" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                            Mesai Saatleri
                                    </label>
                                    <input type="time" name="workHourStart" class="form-input flex-1" value="08:30" />
                                    <input type="time" name="workHourEnd" class="form-input flex-1" value="18:30" />
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="salaryFood" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Yemek Ücreti
                                    </label>
                                        <select id="salaryFood" name="salaryFood" required class="form-input flex-1">
                                             <option value="false">Verilmiyor</option>
                                             <option value="true">Veriliyor</option>
                                        </select>
                                </div>

                                <div class="mt-4 flex items-center">
                                    <label for="salaryRoad" class="mb-0 w-1/3 ltr:mr-2 rtl:ml-2">
                                        Yol Ücreti
                                    </label>
                                        <select id="salaryRoad" name="salaryRoad" required class="form-input flex-1">
                                             <option value="false">Verilmiyor</option>
                                             <option value="true">Veriliyor</option>
                                        </select>
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
