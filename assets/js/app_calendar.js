document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'tr', // Takvimi Türkçe yapar
        timeZone: 'Europe/Istanbul',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: events, // PHP'den gelen JSON verileri burada kullanılıyor
        dayMaxEvents: true, // +5 görünümü için
        moreLinkClick: 'day', // Güne tıklayınca gün görünümüne geçiş
        eventClick: function(info) {
            var eventObj = info.event;

            if (eventObj.extendedProps.className === 'primary') {
                // Project edit modal
                document.getElementById('projectId').value = eventObj.id || '';
                document.getElementById('projectTitle').value = eventObj.title || '';
                document.getElementById('projectDateDeadline').value = eventObj.startStr || '';
                document.getElementById('projectModal').style.display = 'block';
            } else {
                // Event edit modal
                document.getElementById('eventId').value = eventObj.id || '';
                document.getElementById('eventTitle').value = eventObj.title || '';
                document.getElementById('eventDateStart').value = eventObj.startStr || '';
                document.getElementById('eventDateEnd').value = eventObj.endStr || '';
                document.getElementById('eventDescription').value = eventObj.extendedProps.description || '';

                let badgeRadioButtons = document.getElementsByName('badge');
                badgeRadioButtons.forEach(function(radio) {
                    if (radio.value === eventObj.classNames[0]) {
                        radio.checked = true;
                    }
                });

                document.getElementById('eventModal').style.display = 'block';
            }
        }
    });

    calendar.render();

    // Proje düzenleme formu gönderimi
    document.getElementById('projectForm').addEventListener('submit', function (e) {
        e.preventDefault();

        let projectId = document.getElementById('projectId').value;
        let title = document.getElementById('projectTitle').value;
        let dateDeadline = document.getElementById('projectDateDeadline').value;

        $.ajax({
            url: 'update_project.php',
            type: 'POST',
            data: {
                id: projectId,
                title: title,
                dateDeadline: dateDeadline
            },
            success: function (response) {
                if (response.success) {
                    calendar.refetchEvents(); // Takvimi yeniden yükle
                    closeModal(); // Modali kapat
                } else {
                    alert('Proje güncellenirken bir hata oluştu.');
                }
            }
        });
    });

    // Etkinlik düzenleme formu gönderimi
    document.getElementById('eventForm').addEventListener('submit', function (e) {
        e.preventDefault();

        var eventData = {
            id: document.getElementById('eventId').value,
            title: document.getElementById('eventTitle').value,
            start: document.getElementById('eventDateStart').value,
            end: document.getElementById('eventDateEnd').value,
            description: document.getElementById('eventDescription').value,
            badge: document.querySelector('input[name="badge"]:checked').value
        };

        // AJAX request to update event
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_event.php", true);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var updatedEvent = JSON.parse(xhr.responseText);
                var event = calendar.getEventById(updatedEvent.id);
                event.setProp('title', updatedEvent.title);
                event.setDates(updatedEvent.start, updatedEvent.end);
                event.setExtendedProp('description', updatedEvent.description);
                event.setProp('className', updatedEvent.badge);

                closeModal(); // Close the modal after saving
            }
        };
        xhr.send(JSON.stringify(eventData));
    });

    // Modal kapatma tıklamasını sadece modal dışında bir alanda olması durumunda işle
    document.getElementById('eventModal').addEventListener('click', function (event) {
        if (event.target === this) {
            closeModal();
        }
    });

    document.getElementById('projectModal').addEventListener('click', function (event) {
        if (event.target === this) {
            closeModal();
        }
    });
});

function closeModal() {
    document.getElementById('eventModal').style.display = 'none';
    document.getElementById('projectModal').style.display = 'none';
}
