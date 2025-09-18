$(document).ready(function() {
    let selectedEmployeeIds = [];

    $("#employeeCalendar").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "test-get_users.php",
                dataType: "json",
                data: { term: request.term },
                success: function(data) {
                    if (data.length === 0) {
                        response([{ label: 'Eşleşen Veri Yok', value: '' }]);
                    } else {
                        response($.map(data, function(item) {
                            return {
                                label: item.name + " " + item.surname,
                                value: item.name + " " + item.surname,
                                id: item.id,
                                profilePicture: item.profilePicture || 'path_to_default_image/default-profile-picture.png'
                            };
                        }));
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Autocomplete AJAX hatası:", error);
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            event.preventDefault();
            if (ui.item.id) {
                selectedEmployeeIds.push(ui.item.id);
                $("#employeeIdCalendar").val(selectedEmployeeIds.join(','));
                const profileHtml = `
                <div class="profile" data-user-id="${ui.item.id}">
                    <img src="${ui.item.profilePicture}" alt="${ui.item.label}">
                    <div class="tooltip">${ui.item.label}</div>
                    <div class="remove-user">X</div>
                </div>`;
                $("#profileContainerCalendar").append(profileHtml);
                $("#employeeCalendar").val('');
                attachRemoveHandlers();
            }
        }
    });

    const projectModal = document.getElementById('projectAddModalCalendar');
    const projectAddModalCalendarButton = document.getElementById('projectAddModalCalendarClose');

    function openProjectAddModalCalendar() {
        projectModal.classList.remove('hidden');
        projectModal.style.display = 'block';
    }

    function closeProjectAddModalCalendar() {
        projectModal.classList.add('hidden');
        projectModal.style.display = 'none';
    }

    document.querySelectorAll('.projectAddButtonCalendar').forEach(button => {
        button.addEventListener('click', openProjectAddModalCalendar);
    });

    if (projectAddModalCalendarButton) {
        projectAddModalCalendarButton.addEventListener('click', closeProjectAddModalCalendar);
    }

    window.addEventListener('click', function(event) {
        if (event.target === projectModal) {
            closeProjectAddModalCalendar();
        }
    });

    function attachRemoveHandlers() {
        $(".remove-user").off("click").on("click", function() {
            const userId = $(this).closest(".profile").data("user-id");
            selectedEmployeeIds = selectedEmployeeIds.filter(id => id !== userId.toString());
            $("#employeeIdCalendar").val(selectedEmployeeIds.join(','));
            $(this).closest(".profile").remove();
        });
    }

    const projectForm = document.getElementById('projectFormCalendar');

    projectForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(projectForm);

        fetch('pages/manager/scrumboard/modules/projectAdd.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                if (data.projectId) {
                    $("#projectIdCalendar").val(data.projectId);
                    loadAssignees();
                }
                closeProjectAddModalCalendar();
                resetModalForm();
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
            alert('Proje kaydedilirken bir hata oluştu.');
        });
    });

    function resetModalForm() {
        $("#projectFormCalendar")[0].reset();
        selectedEmployeeIds = [];
        $("#profileContainerCalendar").empty();
        $("#employeeIdCalendar").val('');
    }

    function loadAssignees() {
        const projectId = $("#projectIdCalendar").val();
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
                        const profilePicture = user.profilePicture ? user.profilePicture : 'path_to_default_image/default-profile-picture.png';
                        profiles += `<div class="profile" data-user-id="${user.id}">
                                        <img src="${profilePicture}" alt="${user.name}">
                                        <div class="tooltip">${user.name} ${user.surname}</div>
                                        <div class="remove-user">X</div>`;
                    });
                    $("#profileContainerCalendar").html(profiles);
                    attachRemoveHandlers();
                } else {
                    $("#profileContainerCalendar").html('');
                }
            },
            error: function(xhr, status, error) {
                console.error("loadAssignees AJAX hatası:", error);
            }
        });
    }
});
