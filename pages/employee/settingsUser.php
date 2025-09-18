<?php
if (isset($_SESSION['status']) && isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    $stmt = $conn->prepare("SELECT * FROM ac_users WHERE id = ?");
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Kullanıcı bulunamadı.";
        exit;
    }
} else {
    echo "Kullanıcı oturumu aktif değil.";
    exit;
}

//$birthdayDate = new DateTime($row['birthday']);
//$birthday = $birthdayDate->format('Y-m-d');
?>


<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline"><?php echo $row['name'] . ' ' . $row['surname'];?></a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Hesap Ayarları</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="mb-5 flex items-center justify-between">
                <h5 class="text-lg font-semibold dark:text-white-light">Ayarlar</h5>
            </div>
            <div x-data="{tab: 'home'}">
                <ul
                    class="mb-5 overflow-y-auto whitespace-nowrap border-b border-[#ebedf2] font-semibold dark:border-[#191e3a] sm:flex">
                    <li class="inline-block">
                        <a href="javascript:;"
                            class="flex gap-2 border-b border-transparent p-4 hover:border-primary hover:text-primary"
                            :class="{'!border-primary text-primary' : tab == 'home'}" @click="tab='home'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                <path opacity="0.5"
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                            </svg>
                            Profil
                        </a>
                    </li>
                    <li class="inline-block">
                        <a href="javascript:;"
                            class="flex gap-2 border-b border-transparent p-4 hover:border-primary hover:text-primary"
                            :class="{'!border-primary text-primary' : tab == 'sessions'}" @click="tab='sessions'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                <ellipse opacity="0.5" cx="12" cy="17" rx="7" ry="4" stroke="currentColor"
                                    stroke-width="1.5" />
                            </svg>
                            Oturumlar
                        </a>
                    </li>
                    <li class="inline-block">
                        <a href="javascript:;"
                            class="flex gap-2 border-b border-transparent p-4 hover:border-primary hover:text-primary"
                            :class="{'!border-primary text-primary' : tab == 'preferences'}" @click="tab='preferences'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                <ellipse opacity="0.5" cx="12" cy="17" rx="7" ry="4" stroke="currentColor"
                                    stroke-width="1.5" />
                            </svg>
                            Tercihler
                        </a>
                    </li>
                    <li class="inline-block">
                        <a href="javascript:;"
                            class="flex gap-2 border-b border-transparent p-4 hover:border-primary hover:text-primary"
                            :class="{'!border-primary text-primary' : tab == 'payment-details'}"
                            @click="tab='payment-details'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 6V18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            Banka Bilgileri
                        </a>
                    </li>
                    <li class="inline-block">
                        <a href="javascript:;"
                            class="flex gap-2 border-b border-transparent p-4 hover:border-primary hover:text-primary"
                            :class="{'!border-primary text-primary' : tab == 'danger-zone'}" @click="tab='danger-zone'">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                <path
                                    d="M16.1007 13.359L16.5562 12.9062C17.1858 12.2801 18.1672 12.1515 18.9728 12.5894L20.8833 13.628C22.1102 14.2949 22.3806 15.9295 21.4217 16.883L20.0011 18.2954C19.6399 18.6546 19.1917 18.9171 18.6763 18.9651M4.00289 5.74561C3.96765 5.12559 4.25823 4.56668 4.69185 4.13552L6.26145 2.57483C7.13596 1.70529 8.61028 1.83992 9.37326 2.85908L10.6342 4.54348C11.2507 5.36691 11.1841 6.49484 10.4775 7.19738L10.1907 7.48257"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5"
                                    d="M18.6763 18.9651C17.0469 19.117 13.0622 18.9492 8.8154 14.7266C4.81076 10.7447 4.09308 7.33182 4.00293 5.74561"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5"
                                    d="M16.1007 13.3589C16.1007 13.3589 15.0181 14.4353 12.0631 11.4971C9.10807 8.55886 10.1907 7.48242 10.1907 7.48242"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            Hesap Dondur
                        </a>
                    </li>
                </ul>
                <template x-if="tab === 'home'">
                    <div>
                        <form id="userInfoForm" class="mb-5 rounded-md border border-[#ebedf2] bg-white p-4 dark:border-[#191e3a] dark:bg-[#0e1726]">
    <h6 class="mb-5 text-lg font-bold">Kullanıcı Bilgileri</h6>
    <div class="flex flex-col sm:flex-row">
        <div class="relative mb-5 w-full sm:w-2/12 ltr:sm:mr-4 rtl:sm:ml-4">
            <img id="profilePicture" src="<?php echo htmlspecialchars($row['profilePicture'] ?: '/uploads/users/profile/default.jpg');?>" alt="image"
                class="mx-auto h-20 w-20 rounded-full object-cover md:h-32 md:w-32" />
            <div class="mx-auto h-20 w-20 rounded-full object-cover md:h-32 md:w-32 absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity cursor-pointer" id="profilePictureOverlay">
                <i class="fa-solid fa-cloud-arrow-up text-white text-2xl"></i>
                <input type="file" id="profilePictureInput" name="profilePicture" accept="image/*" class="hidden" />
            </div>
        </div>
        <div class="grid flex-1 grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="name">Adınız</label>
                <input id="name" name="name" type="text" value="<?php echo $row['name'];?>" class="form-input" required />
            </div>
            <div>
                <label for="surname">Soyadınız</label>
                <input id="surname" name="surname" type="text" value="<?php echo $row['surname'];?>" class="form-input" required />
            </div>
            <div>
                <label for="profession">Meslek</label>
                <input id="profession" name="profession" type="text" value="<?php echo $row['position'];?>" class="form-input" readonly/>
            </div>
            <div>
                <label for="birthday">Doğum Günü</label>
                <input id="birthday" name="birthday" type="date" value="<?php echo $row['birthday'];?>" class="form-input" required />
            </div>
            <div>
                <label for="phone">Telefon (Şahsi)</label>
                <input id="phone" name="phone" type="text" value="<?php echo $row['phonePersonal'];?>" class="form-input" required />
            </div>
            <div>
                <label for="phoneCompany">Telefon (Kurumsal)</label>
                <input id="phoneCompany" name="phoneCompany" type="text" value="<?php echo $row['phone'];?>" class="form-input" required />
            </div>
            <div>
                <label for="emailPersonal">Email (Şahsi)</label>
                <input id="emailPersonal" name="emailPersonal" type="email" value="<?php echo $row['emailPersonal'];?>" class="form-input" required />
            </div>
            <div>
                <label for="email">Email (Kurumsal)</label>
                <input id="email" name="email" type="email" value="<?php echo $row['mail'];?>" class="form-input" required />
            </div>
            <div class="mt-3 sm:col-span-2">
                <button type="submit" class="btn btn-primary" style="width:100%">Güncelle</button>
            </div>
        </div>
    </div>
</form>

                        
                        <form id="passwordForm" class="mb-5 rounded-md border border-[#ebedf2] bg-white p-4 dark:border-[#191e3a] dark:bg-[#0e1726]">
                            <h6 class="mb-5 text-lg font-bold">Şifreni Değiştir</h6>
                            <div class="grid grid-cols-2 gap-5 sm:grid-cols-2">
                                <div class="flex">
                                    <input id="newPassword" name="newPassword" type="password" placeholder="Yeni Şifre" class="form-input" required />
                                </div>
                                <div class="flex">
                                    <input id="confirmPassword" name="confirmPassword" type="password" placeholder="Yeni Şifre" class="form-input" required />
                                </div>
                                <div class="flex">
                                    <input id="currentPassword" name="currentPassword" type="password" placeholder="Mevcut Şifre" class="form-input" required />
                                </div>
                                <div class="flex">
                                    <button type="submit" class="btn btn-primary" style="width:100%">Güncelle</button>
                                </div>
                            </div>
                        </form>
                        
                        <form id="socialMediaForm" class="rounded-md border border-[#ebedf2] bg-white p-4 dark:border-[#191e3a] dark:bg-[#0e1726]">
                            <h6 class="mb-5 text-lg font-bold">Sosyal Medya</h6>
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div class="flex">
                                    <div
                                        class="flex items-center justify-center rounded bg-[#eee] px-3 font-semibold ltr:mr-2 rtl:ml-2 dark:bg-[#1b2e4b]">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </div>
                                    <input id="sc_linkedin" name="sc_linkedin" type="text" value="<?php echo $row['sc_linkedin'];?>" class="form-input" />
                                </div>
                                <div class="flex">
                                    <div
                                        class="flex items-center justify-center rounded bg-[#eee] px-3 font-semibold ltr:mr-2 rtl:ml-2 dark:bg-[#1b2e4b]">
                                        <i class="fa-brands fa-behance"></i>
                                    </div>
                                    <input id="sc_behance" name="sc_behance" type="text" value="<?php echo $row['sc_behance'];?>" class="form-input" />
                                </div>
                                <div class="flex">
                                    <div
                                        class="flex items-center justify-center rounded bg-[#eee] px-3 font-semibold ltr:mr-2 rtl:ml-2 dark:bg-[#1b2e4b]">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </div>
                                    <input id="sc_facebook" name="sc_facebook" type="text" value="<?php echo $row['sc_facebook'];?>" class="form-input" />
                                </div>
                                <div class="flex">
                                    <div
                                        class="flex items-center justify-center rounded bg-[#eee] px-3 font-semibold ltr:mr-2 rtl:ml-2 dark:bg-[#1b2e4b]">
                                        <i class="fa-brands fa-instagram"></i>
                                    </div>
                                    <input id="sc_instagram" name="sc_instagram" type="text" value="<?php echo $row['sc_instagram'];?>" class="form-input" />
                                </div>
                                <div class="flex">
                                    <div
                                        class="flex items-center justify-center rounded bg-[#eee] px-3 font-semibold ltr:mr-2 rtl:ml-2 dark:bg-[#1b2e4b]">
                                        <i class="fa-brands fa-twitter"></i>
                                    </div>
                                    <input id="sc_twitter" name="sc_twitter" type="text" value="<?php echo $row['sc_twitter'];?>" class="form-input" />
                                </div>
                                <div class="flex">
                                    <button type="submit" class="btn btn-primary" style="width:100%">Güncelle</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </template>
                
                
                <!-- Oturumlar -->
                <template x-if="tab === 'sessions'">
                    <div class="switch">
                        <!-- Sessions tab content -->
                        <div id="sessionList"></div>
                        <script>
                        // Tarih Formatını Dönüştürme                       
                        function formatDate(dateString) {
    // Tarihi Date nesnesine dönüştürelim
    const date = new Date(dateString);

    // Gün, Ay ve Yılı alalım
    const day = String(date.getDate()).padStart(2, '0');  // Gün (2 haneli)
    const month = String(date.getMonth() + 1).padStart(2, '0');  // Ay (0-11 arası olduğu için 1 ekliyoruz, 2 haneli)
    const year = date.getFullYear();  // Yıl (4 haneli)

    // İstenilen formatta geri döndür
    return `${day}.${month}.${year}`;
}
                        
        // Oturumları listeleme fonksiyonu
        function loadSessions() {
            $.ajax({
                url: 'pages/employee/settingsUserAjaxSessions.php',
                type: 'POST',
                data: { action: 'list' },
                dataType: 'json',
                success: function(data) {
                    var sessionList = '';
                    if (data.length > 0) {
                        data.forEach(function(session) {
                            sessionList += `
                                <form class="mb-5 rounded-md border border-[#ebedf2] bg-white p-4 dark:border-[#191e3a] dark:bg-[#0e1726]" style="
                                    display: flex;
                                    align-items: center;
                                    flex-wrap: nowrap;
                                    flex-direction: row;
                                    justify-content: space-between;">
                                    <h6 class="font-bold">${session.sessionName}</h6>
                                    <h6>${session.operating_system} ${session.device_type}</h6>
                                    <h6>${session.browser_name} - ${session.browser_version}</h6>
                                    <h6>${session.ip_address}</h6>
                                    <h6>${formatDate(session.dateCreate)} - ${formatDate(session.expiry)}</h6>
                                    <div class="flex">
                                        <button type="button" class="btn btn-success" style="width:100%" onclick="openRenameModal('${session.token}')">Adlandır</button>
                                    </div>
                                    <div class="flex">
                                        <button type="button" class="btn btn-danger" style="width:100%" onclick="deleteSession('${session.token}')">Kapat</button>
                                    </div>
                                </form>`;
                        });
                    } else {
                        sessionList = '<p class="text-center">Aktif oturum bulunamadı.</p>';
                    }
                    $('#sessionList').html(sessionList);
                }
            });
        }

        // Oturum kapatma fonksiyonu
        function deleteSession(token) {
            $.ajax({
                url: 'pages/employee/settingsUserAjaxSessions.php',
                type: 'POST',
                data: { action: 'delete', token: token },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        loadSessions(); // Listeyi yenile
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        }

        // Sayfa yüklendiğinde oturumları yükle
        $(document).ready(function() {
            loadSessions();
        });
        
        // Modalı açma fonksiyonu
        function openRenameModal(sessionToken) {
            document.getElementById('renameSessionModal').style.display = 'block';
            document.getElementById('sessionTokenInput').value = sessionToken; // Session token'ı gizli input'a ekliyoruz
        }
        
        // Modalı kapatma fonksiyonu
        function closeModal() {
            document.getElementById('renameSessionModal').style.display = 'none';
        }
        
        // İsimlendirme işlemi için form submit
        document.getElementById('renameSessionForm').addEventListener('submit', function(event) {
            event.preventDefault();
        
            const sessionName = document.getElementById('sessionNameInput').value;
            const sessionToken = document.getElementById('sessionTokenInput').value;
        
            fetch('pages/employee/settingsUserAjaxSessionsRename.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `sessionToken=${sessionToken}&sessionName=${sessionName}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Oturum adı başarıyla güncellendi.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    closeModal(); // Modalı kapat
                    loadSessions(); // Oturum listesini yenile
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Bir hata oluştu',
                    text: 'İsimlendirme yapılamadı.'
                });
            });
        });

    </script>
                    </div>
                </template>
                
                <!-- Rename Modal -->
                <div id="renameSessionModal" class="modal" style="display: none;">
                  <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h4 class="mb-5">Oturumu Adlandır</h4>
                    <form id="renameSessionForm" style="gap: 1rem;display: flex;">
                      <input type="text" id="sessionNameInput" name="sessionName" maxlength="24" placeholder="Yeni oturum adı" class="form-input" required>
                      <input type="hidden" id="sessionTokenInput" name="sessionToken">
                      <button type="submit" class="btn btn-primary">Kaydet</button>
                    </form>
                  </div>
                </div>
                
                <style>
                  .modal { display: none; position: fixed; z-index: 99999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
                  .modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 400px; }
                  .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
                  .close:hover, .close:focus { color: black; text-decoration: none; cursor: pointer; }
                </style>
                
                
                <!-- Banka Bilgileri -->
                <template x-if="tab === 'payment-details'">
                    <div>
                        <!-- Payment details tab content -->
                        Çok Yakında
                    </div>
                </template>
                
                
                <!-- Tercihler -->
                <template x-if="tab === 'preferences'">
                    <div class="switch">
                        <!-- Preferences tab content -->
                        Çok Yakında
                    </div>
                </template>
                <template x-if="tab === 'danger-zone'">
                    <div class="switch">
                        <!-- Danger zone tab content -->
                        Çok Yakında
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('userInfoForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('pages/employee/settingsUserAjax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Bilgiler başarıyla güncellendi.',
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Hata',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Hata:', error);
        Swal.fire({
            icon: 'error',
            title: 'Sunucu Hatası',
            text: 'Bir hata oluştu. Lütfen tekrar deneyin.'
        });
    });
});

    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('pages/employee/settingsPasswordAjax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Şifre güncellendi.');
            } else {
                console.error('Hata:', data.message);
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error('Hata:', error);
            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
        });
    });

    document.getElementById('socialMediaForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('pages/employee/settingsSocialMediaAjax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Sosyal medya bilgileri güncellendi.');
            } else {
                console.error('Hata:', data.message);
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error('Hata:', error);
            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
        });
    });

    document.getElementById('profilePictureOverlay').addEventListener('click', function() {
        document.getElementById('profilePictureInput').click();
    });

    document.getElementById('profilePictureInput').addEventListener('change', function() {
    const fileInput = this;
    const formData = new FormData();
    formData.append('profilePicture', fileInput.files[0]);
    
    // Profil fotoğrafı yüklenirken gösterilecek bildirim
    let loadingSwal = Swal.fire({
        title: 'Yükleniyor...',
        text: 'Profil fotoğrafınız yükleniyor.',
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        willOpen: () => {
            Swal.showLoading();  // Yükleme animasyonu
        }
    });

    // Profil fotoğrafını yükle
    fetch('pages/employee/settingsProfilePictureAjax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Eğer yükleme başarılıysa 2 saniye göster
        setTimeout(() => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Profil fotoğrafı güncellendi.',
                    showConfirmButton: false,
                    timer: 1000 // En az 2 saniye görünmesini sağlıyor
                });
                document.getElementById('profilePicture').src = data.newImageUrl;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Yükleme Başarısız!',
                    text: data.message
                });
            }
        }, 1000); // En az 1 saniye süren işlemlerde bile toplamda 2 saniye bildirim görünsün
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Bir hata oluştu',
            text: 'Profil fotoğrafı yüklenemedi.'
        });
    });
});

});

function showAlert(message) {
    const toast = window.Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000, // Gösterim süresi 5000 milisaniye (5 saniye)
        padding: '2em',
        showClass: {
            popup: 'animate__animated animate__fadeInDown' // Açılma animasyonu
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp' // Kapanma animasyonu
        }
    });
    toast.fire({
        icon: 'success',
        title: message,
        padding: '2em',
    });
}
</script>
