document.addEventListener('DOMContentLoaded', function () {
    const projectForm = document.getElementById('projectForm');

    projectForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(projectForm);
        fetch('projectAdd.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    const newProjectId = data.projectId;
                    $("#projectId").val(newProjectId);  // Yeni ID'yi input alanına ekle
                    loadAssignees();  // Görevli listesini yükle
                } else {
                    alert('Proje eklenemedi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

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
                        profiles += `<div class="profile" data-user-id="${user.id}">
                                        <img src="${user.profilePicture}" alt="${user.name}">
                                        <div class="tooltip">${user.name} ${user.surname}</div>
                                        <div class="remove-user">X</div>
                                    </div>`;
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

    function attachRemoveHandlers() {
        $(".remove-user").off("click").on("click", function() {
            const userId = $(this).closest(".profile").data("user-id");
            const projectId = $("#projectId").val();

            $.ajax({
                url: "test-remove_assignee.php",
                method: "POST",
                data: {
                    projectId: projectId,
                    userId: userId
                },
                success: function(response) {
                    if (response.status === "success") {
                        loadAssignees();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Kullanıcı silinemedi:", error);
                }
            });
        });
    }

    // AutoComplete işlevi
    $("#employee").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "test-get_users.php",
                dataType: "json",
                data: { term: request.term },
                success: function(data) {
                    if (data.length === 1 && data[0].label === "Eşleşen Veri Yok") {
                        $("#employeeId").val('');
                        $("#saveOrderButton").attr("disabled", true);
                    } else {
                        $("#saveOrderButton").removeAttr("disabled");
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
                $("#employeeId").val(ui.item.id);
            } else {
                $("#employeeId").val('');
            }
        }
    });

    // Modalı açma ve kapatma işlemleri
    const projectModal = document.getElementById('projectModal');
    const closeModalButton = document.getElementById('closeModal');

    function openModal() {
        projectModal.classList.remove('hidden');
        projectModal.style.display = 'block';
    }

    function closeModal() {
        projectModal.classList.add('hidden');
        projectModal.style.display = 'none';
    }

    closeModalButton.addEventListener('click', closeModal);

    window.addEventListener('click', function (event) {
        if (event.target == projectModal) {
            closeModal();
        }
    });
});
