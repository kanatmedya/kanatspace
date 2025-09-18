// Sayfa yüklendiğinde varsayılan filtre değerleriyle takvimi yükle
$(document).ready(function() {
    
    filterCalendar(); // Sayfa yüklendiğinde otomatik filtrele
    
    let selectedDate = '';

    // Seçim modalını açan fonksiyon
    window.openSelectionModal = function(date) {
        selectedDate = date;
        $('#addSelectionModal').show(); // Seçim modalını göster
    };

    // Seçim modalını kapatma fonksiyonu
    $('#addSelectionModalClose').click(function() {
        $('#addSelectionModal').hide(); // Seçim modalını gizle
    });

    // Proje ekleme modalını aç
    $('#openProjectModalButton').click(function() {
        $('#addSelectionModal').hide(); // Seçim modalını kapat
        $('#deadlineDateCalendar').val(selectedDate); // Proje modalında teslim tarihini ayarla
        $('#projectAddModalCalendar').show(); // Proje modalını göster
    });

    // Etkinlik ekleme modalını aç
    $('#openEventModalButton').click(function() {
        $('#addSelectionModal').hide(); // Seçim modalını kapat
        $('#createDateStart').val(selectedDate);  // Etkinlik modalında başlangıç tarihini ayarla
        $('#createEventModal').show(); // Etkinlik modalını göster
    });

    // Modal dışına tıklanınca kapatma
    $(window).click(function(event) {
        if (event.target === $('#addSelectionModal')[0]) {
            $('#addSelectionModal').hide(); // Seçim modalını gizle
        }
        if (event.target === $('#projectAddModalCalendar')[0]) {
            $('#projectAddModalCalendar').hide(); // Proje modalını gizle
        }
        if (event.target === $('#createEventModal')[0]) {
            $('#createEventModal').hide(); // Etkinlik modalını gizle
        }
    });

    // Proje modalını kapatma
    $('#projectAddModalCalendarClose').click(function() {
        $('#projectAddModalCalendar').hide(); // Proje modalını gizle
    });

    // Etkinlik modalını kapatma
    $('#createEventModalClose').click(function() {
        $('#createEventModal').hide(); // Etkinlik modalını gizle
    });

    $('#filterButton').click(function() {
        // Filtrele butonuna tıklanınca filtreleme yap
        filterCalendar();
    });

    // Önceki ve Sonraki Ay butonlarına tıklama işlevlerini ekleyelim
    $('#prevMonth').click(function() {
        changeMonth(-1); // Önceki ay
    });

    $('#nextMonth').click(function() {
        changeMonth(1); // Sonraki ay
    });
});

// Proje düzenleme modalını açma
function openEditProjectModal(projectId) {
    // Proje bilgilerini AJAX ile çek
    $.ajax({
        url: 'pages/manager/calendar/get_project.php', // Proje bilgilerini getiren PHP dosyası
        method: 'GET',
        data: { id: projectId },
        success: function(response) {
            const project = JSON.parse(response);
            $('#editProjectId').val(project.id);
            $('#editProjectTitle').val(project.title);
            $('#editProjectType').val(project.projectType);
            $('#editDeadlineDate').val(project.deadlineDate);
            $('#editDeadlineTime').val(project.deadlineTime);
            $('#editProjectClient').val(project.client);
            $('#editProjectStatus').val(project.status);
            $('#editProjectDescription').val(project.description);

            // Görevlileri listele
            $('#editProfileContainer').empty();
            project.assignees.forEach(assignee => {
                $('#editProfileContainer').append(`
                    <div class="profile" data-user-id="${assignee.id}">
                        <img src="${assignee.profilePicture}" alt="${assignee.name}">
                        <div class="tooltip">${assignee.name}</div>
                        <div class="remove-user">X</div>
                    </div>
                `);
            });
            attachRemoveHandlers(); // Görevli silme butonlarını aktif et
            $('#editProjectModal').show(); // Modalı göster
        }
    });
}

// Proje bilgilerini kaydetme
$('#editProjectForm').submit(function(e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.ajax({
        url: 'pages/manager/calendar/update_project.php',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response === 'success') {
                $('#editProjectModal').hide();
                filterCalendar(); // Filtreleme işlemi
            } else {
                alert('Bir hata oluştu');
            }
        }
    });
});

// Proje silme
$('#deleteProjectButton').click(function() {
    const projectId = $('#editProjectId').val();
    if (confirm('Bu projeyi silmek istediğinizden emin misiniz?')) {
        $.ajax({
            url: 'pages/manager/calendar/delete_project.php',
            method: 'POST',
            data: { id: projectId },
            success: function(response) {
                if (response === 'success') {
                    $('#editProjectModal').hide();
                    filterCalendar(); // Filtreleme işlemi
                } else {
                    alert('Bir hata oluştu');
                }
            }
        });
    }
});


function filterCalendar() {
    var client = $('#filterClient').val();
    var user = $('#filterUser').val();
    var projectStatus = $('#filterProjects').val();
    var eventStatus = $('#filterEvents').val();
    var month = $('#currentMonth').val();
    var year = $('#currentYear').val();

    $.ajax({
        url: 'pages/manager/calendar/filter.php',
        type: 'GET',
        data: {
            client: client,
            user: user,
            projectStatus: projectStatus,
            eventStatus: eventStatus,
            month: month,
            year: year
        },
        success: function(response) {
            $('#calendarTable').html(response); // Gelen veriyi tabloya yerleştir
        },
        error: function(xhr, status, error) {
            console.log('Hata oluştu: ', error);
            alert("Bir hata oluştu. Lütfen tekrar deneyin.");
        }
    });
}

// Ay değiştirme fonksiyonu
function changeMonth(direction) {
    var currentMonth = parseInt($('#currentMonth').val());
    var currentYear = parseInt($('#currentYear').val());

    var newMonth = currentMonth + direction;
    var newYear = currentYear;

    // Eğer ay 0'dan küçükse, yılı geri al ve Aralık ayına geç
    if (newMonth < 1) {
        newMonth = 12;
        newYear -= 1;
    }

    // Eğer ay 13'ten büyükse, yılı ileri al ve Ocak ayına geç
    if (newMonth > 12) {
        newMonth = 1;
        newYear += 1;
    }

    // Yeni ay ve yıl bilgilerini AJAX ile sunucuya gönder
    $.ajax({
        url: 'pages/manager/calendar/filter.php',
        type: 'GET',
        data: {
            client: $('#filterClient').val(),
            user: $('#filterUser').val(),
            projectStatus: $('#filterProjects').val(),
            eventStatus: $('#filterEvents').val(),
            month: newMonth,  // Yeni ay
            year: newYear  // Yeni yıl
        },
        success: function(response) {
            $('#calendarTable').html(response);  // Takvim güncellemesi
            $('#currentMonth').val(newMonth);    // Yeni ayı input'a yaz
            $('#currentYear').val(newYear);      // Yeni yılı input'a yaz
            
            // Yeni ay ve yıl bilgisine göre başlığı güncelle
            updateCalendarTitle(newMonth, newYear);
        },
        error: function(xhr, status, error) {
            console.log('Hata oluştu: ', error);
            alert("Bir hata oluştu. Lütfen tekrar deneyin.");
        }
    });
}

// Başlığı güncellemek için fonksiyon
function updateCalendarTitle(month, year) {
    const monthNames = [
        "Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", 
        "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"
    ];
    
    var monthName = monthNames[month - 1];  // JavaScript'te aylar 0'dan başladığı için -1 yapıyoruz
    $('#calendarTitle').text(monthName + ' ' + year);  // Yeni ay ve yılı başlıkta göster
}

$('#filterButton').click(function() {
    // Ekran yükleyici aktif
    $('.screen_loader').show(); 

    var client = $('#filterClient').val();
    var user = $('#filterUser').val();
    var projectStatus = $('#filterProjects').val();
    var eventStatus = $('#filterEvents').val();
    
    // month ve year değişkenlerini HTML'deki hidden inputlardan alıyoruz
    var month = $('#currentMonth').val();
    var year = $('#currentYear').val();
    
    // Eğer month ve year parametreleri boşsa, default değerler ile gönderiyoruz
    if (!month) {
        month = new Date().getMonth() + 1; // JavaScript'te ay 0'dan başlar, bu yüzden 1 ekliyoruz
    }
    if (!year) {
        year = new Date().getFullYear();
    }

    $.ajax({
        url: 'pages/manager/calendar/filter.php',
        type: 'GET',
        data: {
            client: client,
            user: user,
            projectStatus: projectStatus,
            eventStatus: eventStatus,
            month: month, // month değeri buradan gönderilecek
            year: year // year değeri buradan gönderilecek
        },
        success: function(response) {
            $('#calendarTable').html(response);
            console.log(response);  // Konsolda çıktı kontrolü
            // Yükleyici gizleniyor
            $('.screen_loader').hide(); 
        },
        error: function(xhr, status, error) {
            console.log('Hata oluştu: ', error);
            alert("Bir hata oluştu. Lütfen tekrar deneyin.");
            // Yükleyici gizleniyor
            $('.screen_loader').hide(); 
        }
    });
});

function openModal(type, id) {
    $('#editType').val(type);
    $('#editId').val(id);
    $('#modalTitle').text(type === 'event' ? 'Etkinlik Düzenle' : 'Proje Düzenle');
    
    if (type === 'event') {
        $('#startDateInputs').show();
        $('#allDayCheckbox').show();
        $('#completeEventButton').show();
        $('#deleteButton').show();
        $('#completeButton').hide();
    } else {
        $('#startDateInputs').hide();
        $('#allDayCheckbox').hide();
        $('#deleteButton').hide();
        $('#completeEventButton').hide();
        $('#completeButton').show();
    }
    
    // AJAX ile mevcut veriyi çek
    $.ajax({
        url: 'pages/manager/calendar/get_item_data.php',
        method: 'GET',
        data: { type: type, id: id },
        success: function(response) {
            var data = JSON.parse(response);
            $('#editTitle').val(data.title);
            if (type === 'event') {
                $('#editDateStart').val(data.dateStart.split(' ')[0]);
                $('#editTimeStart').val(data.dateStart.split(' ')[1]);
                $('#editTimeEnd').val(data.dateEnd.split(' ')[1]);
                $('#editAllDay').prop('checked', data.dateType === 'allday');
            } else {
                $('#editDateStart').val(data.dateStart.split(' ')[0]);
                $('#editTimeStart').val(data.dateStart.split(' ')[1]);
            }
            $('#editModal').show();
        }
    });
}

function closeModal() {
    $('#editModal').hide();
}

function openCreateModal(date) {
    $('#createDateStart').val(date);
    $('#createEventModal').show();
}

function closeCreateModal() {
    $('#createEventModal').hide();
}

let currentProjectId = null;

function openProjectModal(id, title) {
    currentProjectId = id;
    $('#projectModalTitle').text(title);
    $('#projectModal').show();
}

function closeProjectModal() {
    $('#projectModal').hide();
}

function openEditModal() {
    closeProjectModal();
    openModal('project', currentProjectId);
}

function viewProject() {
    window.location.href = 'project?id=' + currentProjectId;
}

function viewProjectNewTab() {
    window.open('project?id=' + currentProjectId, "_blank");
}

$('#editForm').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    
    if ($('#editType').val() === 'event') {
        formData += '&dateStart=' + $('#editDateStart').val() + ' ' + $('#editTimeStart').val();
        formData += '&dateEnd=' + $('#editDateStart').val() + ' ' + $('#editTimeEnd').val();
        formData += '&allDay=' + $('#editAllDay').prop('checked');
    } else {
        formData += '&dateDeadline=' + $('#editDateStart').val() + ' ' + $('#editTimeStart').val();
    }
    
    $.ajax({
        url: 'pages/manager/calendar/update_item.php',
        method: 'POST',
        data: formData,
        success: function(response) {
            if(response === 'success') {
                closeModal();
                filterCalendar(); // Takvimi Güncelle
            } else {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        }
    });
});

$('#deleteButton').click(function() {
    if (confirm('Bu etkinliği silmek istediğinizden emin misiniz?')) {
        $.ajax({
            url: 'pages/manager/calendar/delete_item.php',
            method: 'POST',
            data: {
                type: 'event',
                id: $('#editId').val()
            },
            success: function(response) {
                if(response === 'success') {
                    closeModal();
                filterCalendar(); // Takvimi Güncelle
                } else {
                    alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                }
            }
        });
    }
});

$('#completeButton').click(function() {
    if (confirm('Bu projeyi tamamlamak istediğinizden emin misiniz?')) {
        $.ajax({
            url: 'pages/manager/calendar/complete_project.php',
            method: 'POST',
            data: {
                id: $('#editId').val()
            },
            success: function(response) {
                if(response === 'success') {
                    closeModal();
                filterCalendar(); // Takvimi Güncelle
                } else {
                    alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                }
            }
        });
    }
});

$('#completeEventButton').click(function() {
    if (confirm('Bu projeyi tamamlamak istediğinizden emin misiniz?')) {
        $.ajax({
            url: 'pages/manager/calendar/complete_event.php',
            method: 'POST',
            data: {
                id: $('#editId').val()
            },
            success: function(response) {
                if(response === 'success') {
                    closeModal();
                filterCalendar(); // Takvimi Güncelle
                } else {
                    alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                }
            }
        });
    }
});

$('#createEventForm').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    formData += '&dateStart=' + $('#createDateStart').val() + ' ' + $('#createTimeStart').val();
    formData += '&dateEnd=' + $('#createDateStart').val() + ' ' + $('#createTimeEnd').val();
    formData += '&allDay=' + $('#createAllDay').prop('checked');
    
    $.ajax({
        url: 'pages/manager/calendar/create_event.php',
        method: 'POST',
        data: formData,
        success: function(response) {
            if(response === 'success') {
                closeCreateModal();
                filterCalendar(); // Takvimi Güncelle
            } else {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        }
    });
});

$('#editAllDay').change(function() {
    if($(this).is(':checked')) {
        $('#editTimeStart').val('00:00').prop('disabled', true);
        $('#editTimeEnd').val('23:59').prop('disabled', true);
    } else {
        $('#editTimeStart').prop('disabled', false);
        $('#editTimeEnd').prop('disabled', false);
    }
});

$('#createAllDay').change(function() {
    if($(this).is(':checked')) {
        $('#createTimeStart').val('00:00').prop('disabled', true);
        $('#createTimeEnd').val('23:59').prop('disabled', true);
    } else {
        $('#createTimeStart').prop('disabled', false);
        $('#createTimeEnd').prop('disabled', false);
    }
});