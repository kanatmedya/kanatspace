<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Assignee Selector</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 kütüphanesi -->
    <style>
        .profile-container {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .profile {
            margin-right: 10px;
            position: relative;
            display: inline-block;
            text-align: center;
        }
        .profile img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            cursor: pointer;
        }
        .profile .remove-user {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: rgba(255, 0, 0, 0.5);
            color: white;
            font-size: 24px;
            line-height: 50px;
            text-align: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .profile:hover .remove-user {
            opacity: 1;
        }
        .profile img:hover {
            opacity: 0.7;
        }
        .profile .tooltip {
            display: none;
            position: absolute;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%);
            background-color: black;
            color: white;
            text-align: center;
            border-radius: 5px;
            padding: 5px;
            z-index: 1;
            white-space: nowrap;
        }
        .profile:hover .tooltip {
            display: block;
        }
    </style>
</head>
<body>

<h1>Projeye Görevli Ekle</h1>

<div class="profile-container" id="profileContainer">
    <!-- Mevcut görevli kişilerin profil fotoğrafları burada listelenecek -->
</div>

<form id="assigneeForm">
    <label for="assignee">Görevli Seç:</label>
    <input type="text" id="assignee" name="assignee">
    <input type="hidden" id="assigneeId" name="assigneeId">
    <input type="hidden" id="projectId" name="projectId" value="<?php echo $_GET['id']; ?>"> <!-- Mevcut proje ID'sini dinamik olarak buraya ekle -->
    <button type="submit">Ekle</button>
</form>

<script>
    $(document).ready(function() {
        function loadAssignees() {
            //console.log("loadAssignees fonksiyonu çalışıyor...");
            $.ajax({
                url: "test-get_assignees.php",
                method: "GET",
                data: { projectId: $("#projectId").val() },
                dataType: "json",
                success: function(data) {
                    //console.log("loadAssignees verisi alındı:", data);
                    if (data.status === "success") {
                        let profiles = '';
                        $.each(data.assignees, function(index, user) {
                            profiles += `<div class="profile" data-user-id="${user.id}">
                                            <img src="${user.profilePicture}" alt="${user.name}">
                                            <div class="tooltip">${user.name} ${user.surname}</div>
                                            <div class="remove-user">X</div>
                                        </div>`;
                        });
                        $("#profileContainer").html(profiles);
                        attachRemoveHandlers(); // Yeni yüklenen kullanıcılar için silme işlemi tanımlama
                    } else {
                        $("#profileContainer").html(''); // Eğer hiç görevli kalmadıysa container'ı temizle
                    }
                },
                error: function(xhr, status, error) {
                    console.error("loadAssignees AJAX hatası:", error); // AJAX hatası durumunda log ekleyin
                }
            });
        }

        function attachRemoveHandlers() {
            $(".remove-user").off("click").on("click", function() {
                const userId = $(this).closest(".profile").data("user-id");

                //console.log("Silme işlemi başlatıldı, userId:", userId); // Silme işlemi başlatıldığında log ekleyin

                $.ajax({
                    url: "test-remove_assignee.php",
                    method: "POST",
                    data: {
                        projectId: $("#projectId").val(),
                        userId: userId
                    },
                    success: function(response) {
                        //console.log("Silme işlemi tamamlandı, response:", response); // Silme işlemi sonrası log ekleyin

                        // Gelen response'u JSON olarak parse et
                        let jsonResponse;
                        try {
                            jsonResponse = JSON.parse(response);
                        } catch (e) {
                            c//onsole.error("Response JSON olarak parse edilemedi:", e);
                            return;
                        }

                        // JSON parse edildikten sonra status kontrolü
                        if (jsonResponse && jsonResponse.status === "success") {
                            //console.log("Silme işlemi başarıyla tamamlandı."); // Başarı durumunda log ekleyin
                            loadAssignees();

                            // AutoComplete ve buton durumunu kontrol et
                            $("#assigneeForm button[type='submit']").removeAttr("disabled");
                            $("#assignee").val(''); // Input'u temizle
                            $("#assigneeId").val('');
                        } else {
                            const errorMessage = jsonResponse.message || "Belirtilmemiş hata";
                            console.error("Silme işlemi başarısız:", errorMessage); // Hata durumunda log ekleyin
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX hatası:", error); // AJAX hatası durumunda log ekleyin
                    }
                });
            });
        }

        // AutoComplete işlevi
        $("#assignee").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "test-get_users.php",
                    dataType: "json",
                    data: { term: request.term },
                    success: function(data) {
                        //console.log(data); // JSON yanıtını konsolda kontrol edin
                        if (data.length === 1 && data[0].label === "Eşleşen Veri Yok") {
                            // Eğer eşleşen veri yoksa butonu devre dışı bırak
                            $("#assigneeId").val('');
                            $("#assigneeForm button[type='submit']").attr("disabled", true);
                        } else {
                            $("#assigneeForm button[type='submit']").removeAttr("disabled");
                            response($.map(data, function(item) {
                                return {
                                    label: item.name + " " + item.surname,
                                    value: item.name + " " + item.surname,
                                    id: item.id
                                };
                            }));
                        }
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                if (ui.item.id) {
                    $("#assigneeId").val(ui.item.id);
                } else {
                    $("#assigneeId").val('');
                }
            }
        });

        // Formun AJAX ile gönderilmesi
        $("#assigneeForm").on("submit", function(event) {
            event.preventDefault();

            const selectedUserId = $("#assigneeId").val();

            if (selectedUserId) {
                // Mevcut kullanıcıları kontrol et
                let alreadyAssigned = false;
                $(".profile").each(function() {
                    if ($(this).data("user-id") == selectedUserId) {
                        alreadyAssigned = true;
                        return false; // Döngüyü durdur
                    }
                });

                if (alreadyAssigned) {
                    // SweetAlert uyarısı
                    Swal.fire({
                        icon: 'warning',
                        title: 'Bu kişi zaten eklenmiş!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    $.ajax({
                        url: "test-add_assignee.php",
                        method: "POST",
                        data: $(this).serialize(),
                        dataType: "json",
                        success: function(data) {
                            if (data.status === "success") {
                                // Form input alanlarını temizle
                                $("#assignee").val('');
                                $("#assigneeId").val('');

                                // Görevli listesini yeniden yükle
                                loadAssignees();
                            }
                        }
                    });
                }
            } else {
                // Eğer kullanıcı ID seçilmediyse ekleme işlemi yapılmaz
                Swal.fire({
                    icon: 'error',
                    title: 'Geçerli bir kullanıcı seçin!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });

        // Sayfa yüklendiğinde görevli listesini yükle
        loadAssignees();
    });
</script>

</body>
</html>
