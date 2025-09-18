<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/pages/manager/calendar/projectAddModalCalendar.css">

<div id="projectAddModalCalendar" class="modal hidden">
    <div class="projectAddModalCalendar-Content">
        <div class="mb-4">
            <span id="projectAddModalCalendarClose" class="close">&times;</span>
            <h2>Proje Ekle</h2>
        </div>

        <form id="projectFormCalendar" method="post">
            <div class="panel grid grid-rows gap-4 px-6 pt-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2">
                    <!-- Proje Başlığı -->
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p>Proje Başlığı</p>
                            <input name="title" required type="text" placeholder="Proje Başlığını Giriniz" class="form-input" />
                        </div>
                    </div>

                    <!-- Proje Türü ve Teslim Tarihi -->
                    <div>
                        <p>Proje Türü</p>
                        <select name="projectType" required class="form-select text-white-dark">
                            <option value="">Proje Türü Seçiniz</option>
                            <?php
                            $sqlClient = "SELECT * FROM projects_types ORDER BY vIndex";
                            $resultClient = $conn->query($sqlClient);

                            while ($rowClient = $resultClient->fetch_assoc()) {
                                echo '<option value="' . $rowClient['id'] . '">' . $rowClient['value'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p>Teslim Tarihi</p>
                        <div class="flex">
                            <input type="date" id="deadlineDateCalendar" name="deadlineDate" class="form-input rounded-none rounded-tl-md rounded-bl-md" />
                            <input type="time" name="deadlineTime" class="form-input rounded-none rounded-tr-md rounded-br-md" value="18:30" />
                        </div>
                    </div>
                </div>

                <!-- Cari Hesap ve Gizlilik -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2">
                                        <div>
                        <p>Cari Hesabı Seçiniz</p>
                        <select name="client" required class="form-select text-white-dark">
                            <option value="">Hesap Seçiniz</option>
                            <?php
                            $sqlCompany = "SELECT * FROM settings WHERE type='companyNameFull'";
                            $resultCompany = $conn->query($sqlCompany);

                            while ($rowCompany = $resultCompany->fetch_assoc()) {
                                echo '<option value="' . $rowCompany['value'] . '">' . $rowCompany['value'] . '</option>';
                            }

                            echo'<optgroup class="font-semibold" label="Müşteriler">';

                            $sqlClient = "SELECT * FROM users_client WHERE accountType='client' ORDER BY username ASC";
                            $resultClient = $conn->query($sqlClient);

                            while ($rowClient = $resultClient->fetch_assoc()) {
                                echo '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
                            }
                            
                            echo '</optgroup>';

                            echo'<optgroup class="font-semibold" label="Bayiler">';

                            $sqlClient = "SELECT * FROM users_client WHERE accountType='dealer' ORDER BY username ASC";
                            $resultClient = $conn->query($sqlClient);

                            while ($rowClient = $resultClient->fetch_assoc()) {
                                echo '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
                            }
                            
                            echo '</optgroup>';
                            
                            echo'<optgroup class="font-semibold" label="Tedarikçiler">';

                            $sqlClient = "SELECT * FROM users_client WHERE accountType='supplier' ORDER BY username ASC";
                            $resultClient = $conn->query($sqlClient);

                            while ($rowClient = $resultClient->fetch_assoc()) {
                                echo '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
                            }
                            
                            echo '</optgroup>';
                            ?>
                        </select>
                    </div>

                    <div>
                        <p>Gizlilik</p>
                        <select name="visibility" class="form-select text-white-dark">
                            <option value="hidden">Sadece Görevli</option>
                            <option value="team">Sadece Ekip</option>
                            <option value="manager">Sadece Yönetici</option>
                            <option value="user-client" selected>Cari ve Görevli</option>
                            <option value="team-client">Cari ve Ekip</option>
                            <option value="team-client">Cari ve Yönetici</option>
                        </select>
                    </div>
                </div>

                <!-- Görevliler -->
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p>Görevliler</p>
                        <input name="employee" id="employeeCalendar" type="text" class="form-input text-white-dark" placeholder="Lütfen Sistemde Kayıtlı Tam Adı Giriniz"/>
                        <input type="hidden" id="employeeIdCalendar" name="employeeId"> <!-- Seçilen görevlinin ID'si -->
                    </div>
                    <div class="profile-container" id="profileContainerCalendar">
                        <!-- Eklenen görevli kişilerin profil fotoğrafları burada listelenecek -->
                    </div>
                </div>

                <!-- Açıklama -->
                <div class="grid grid-cols-1 gap-4">
                    <p>Açıklama</p>
                    <textarea name="description" type="text" placeholder="Proje hakkında açıklama giriniz" class="form-input" style="min-height:75px;"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-full gap-2 mt-5" id="saveNewProjectButtonCalendar">
                Kaydet
            </button>
        </form>
    </div>
</div>

<!-- Project Add Modal -->
<script src="/pages/manager/calendar/projectAddModalCalendar.js"></script>