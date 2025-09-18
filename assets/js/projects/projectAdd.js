$(document).ready(function() {
    //console.log("Document is ready");

    let selectedEmployeeIds = [];

    // Autocomplete işlevi
    $("#employee").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "test-get_users.php",  // Kullanıcıları getiren PHP dosyası
                dataType: "json",
                data: {
                    term: request.term  // Arama terimi
                },
                success: function(data) {
                    if (data.length === 0) {
                        response([{ label: 'Eşleşen Veri Yok', value: '' }]);
                    } else {
                        response($.map(data, function(item) {
                            return {
                                label: item.name + " " + item.surname,
                                value: item.name + " " + item.surname,
                                id: item.id,
                                profilePicture: item.profilePicture || 'path_to_default_image/default-profile-picture.png' // Profil resmini ekle veya varsayılan resim
                            };
                        }));
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Autocomplete AJAX hatası:", error);
                }
            });
        },
        minLength: 2,  // Minimum 2 karakter girildiğinde arama yap
        select: function(event, ui) {
            event.preventDefault(); // Varsayılan seçimi engelle
            if (ui.item.id) {
                // Seçilen kişinin ID'sini diziye ekle
                selectedEmployeeIds.push(ui.item.id);
                $("#employeeId").val(selectedEmployeeIds.join(',')); // IDs'yi virgülle ayırarak input'a ekle

                // Görevli listesine ekle
                const profileHtml = `
                <div class="profile" data-user-id="${ui.item.id}">
                    <img src="${ui.item.profilePicture}" alt="${ui.item.label}">
                    <div class="tooltip">${ui.item.label}</div>
                    <div class="remove-user">X</div>
                </div>`;
                $("#profileContainer").append(profileHtml);

                // Input alanını temizle
                $("#employee").val('');  // name='employee' input'unu temizle
                attachRemoveHandlers(); // Yeni eklenen öğeler için silme işlemini tanımla
            }
        }
    });

    // Modal açma/kapatma işlemleri
    const projectModal = document.getElementById('projectAddModal');
    const closeModalButton = document.getElementById('closeModal');

    // Modal açma işlevi
    function openModal() {
        projectModal.classList.remove('hidden');
        projectModal.style.display = 'block';
        //console.log("Modal açıldı");
    }

    // Modal kapatma işlevi
    function closeModal() {
        projectModal.classList.add('hidden');
        projectModal.style.display = 'none';
        //console.log("Modal kapatıldı");
    }

    // Modal açma butonlarına toplu event listener ekleme
    document.querySelectorAll('.projectAddButton').forEach(button => {
        button.addEventListener('click', openModal);
    });

    // Modal kapatma butonuna tıklama
    if (closeModalButton) {
        closeModalButton.addEventListener('click', closeModal);
    }

    // Modal dışında bir yere tıklanırsa modal'ı kapatma
    window.addEventListener('click', function (event) {
        if (event.target === projectModal) {
            closeModal();
        }
    });

    // Görevli kaldırma işlemi
    function attachRemoveHandlers() {
        $(".remove-user").off("click").on("click", function() {
            const userId = $(this).closest(".profile").data("user-id");

            // Diziden kullanıcı ID'sini kaldır
            selectedEmployeeIds = selectedEmployeeIds.filter(id => id !== userId.toString());
            $("#employeeId").val(selectedEmployeeIds.join(','));

            // Görüntülenen görevli profilini kaldır
            $(this).closest(".profile").remove();
        });
    }

    // Proje formunu AJAX ile gönderme işlemi
    const projectForm = document.getElementById('projectForm');

    projectForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(projectForm);

        fetch('pages/manager/scrumboard/modules/projectAdd.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                return response.text();  // Yanıtı metin olarak al
            })
            .then(text => {
                //console.log("Raw response text:", text);  // Yanıtı konsola yazdır
                let data;
                try {
                    data = JSON.parse(text);  // JSON olarak çözümlemeye çalış
                } catch (e) {
                    console.error("Yanıt JSON olarak parse edilemedi:", e, text);
                    alert('Proje kaydedilirken bir hata oluştu: Yanıt JSON formatında değil.');
                    return;
                }

                if (data.status === "success") {
                    if (data.projectId) {
                        $("#projectId").val(data.projectId);  // projectId'yi gizli input'a yerleştir
                        loadAssignees();  // Görevli listesini yeniden yükle
                    }
                    closeModal();  // Modalı kapat
                    resetModalForm(); // Formu sıfırla
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Proje başarıyla kaydedildi.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    alert("Proje kaydedilirken bir hata oluştu: " + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Proje kaydedilirken bir hata oluştu.');  // Hata mesajı
            });
    });

    // Modal formunu sıfırlama işlemi
    function resetModalForm() {
        $("#projectForm")[0].reset(); // Formdaki tüm inputları sıfırla
        selectedEmployeeIds = []; // Seçilen görevli ID'lerini sıfırla
        $("#profileContainer").empty(); // Görevli listesini temizle
        $("#employeeId").val(''); // Gizli input alanını temizle
    }

    function loadAssignees() {
        const projectId = $("#projectId").val();
        if (!projectId) {
            console.error("Proje ID'si mevcut değil.");
            return;
        }

        $.ajax({
            url: "test-get_assignees.php",
            method: "GET",
            data: { projectId: projectId },
            dataType: "json",
            success: function(data) {
                if (data.status === "success") {
                    let profiles = '';
                    $.each(data.assignees, function(index, user) {
                        const profilePicture = user.profilePicture ? user.profilePicture : 'path_to_default_image/default-profile-picture.png'; // Boşsa varsayılan resim
                        profiles += `<div class="profile" data-user-id="${user.id}">
                                        <img src="${profilePicture}" alt="${user.name}">
                                        <div class="tooltip">${user.name} ${user.surname}</div>
                                        <div class="remove-user">X</div>`;
                    });
                    $("#profileContainer").html(profiles);
                    attachRemoveHandlers();
                } else {
                    $("#profileContainer").html('');
                }
            },
            error: function(xhr, status, error) {
                console.error("loadAssignees AJAX hatası:", error);
            }
        });
    }
});
