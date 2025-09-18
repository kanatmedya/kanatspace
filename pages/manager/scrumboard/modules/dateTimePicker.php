<!-- Date Time Picker -->
    <style>
#dateTimePickerModal {
    display: none;
}
#dateTimePickerModal.show {
    display: block;
    z-index:999999;
}


    </style>
    
    
<!-- Date Time Picker Modal -->
<div id="dateTimePickerModal" class="modal hidden fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full modal-content ">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Teslim Tarihini Değiştir</h3>
                        <div class="flex">
                            <input type="date" name="deadlineDate" class="form-input rounded-none rounded-tl-md rounded-bl-md" value="2024-07-26">
                            <input type="time" name="deadlineTime" class="form-input rounded-none rounded-tr-md rounded-br-md" value="18:30">
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse" style="display: flex;justify-content: space-around;">
                <button type="button" id="saveDateTime" class="btn btn-primary gap-2">Kaydet</button>
                <button type="button" id="cancelModal" class="btn btn-danger gap-2">İptal</button>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Modal'ı açmak için
    $('.projectDate').click(function() {
        console.log("Modal açılmaya çalışılıyor");
        $('#dateTimePickerModal').addClass('show');
        // Proje ID'sini almak
        var projectId = $(this).closest('.task-item').data('id');
        console.log("Project ID: " + projectId);
        $('#dateTimePickerModal').data('project-id', projectId);

        // AJAX ile dateDeadline bilgisini çekmek
        $.ajax({
            url: 'pages/manager/scrumboard/modules/get_date.php',
            type: 'POST',
            data: { id: projectId },
            success: function(response) {
                if (response.status === 'success') {
                    var deadline = new Date(response.dateDeadline);
                    // Tarih ve saati inputlara yazmak
                    $('#dateTimePickerModal input[name="deadlineDate"]').val(deadline.toISOString().slice(0, 10));
                    $('#dateTimePickerModal input[name="deadlineTime"]').val(deadline.toTimeString().slice(0, 5));
                } else {
                    alert('Hata: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Hata detayları:', xhr, status, error);
                alert('Bir hata oluştu: ' + xhr.responseText);
            }
        });
    });

    // Modal'ı kapatmak için
    $('#cancelModal').click(function() {
        $('#dateTimePickerModal').removeClass('show');
    });

    // Modal dışına tıklanıldığında modal'ı kapatmak için
    $(document).mouseup(function(e) {
        var modal = $("#dateTimePickerModal .modal-content");
        if (!modal.is(e.target) && modal.has(e.target).length === 0) {
            $('#dateTimePickerModal').removeClass('show');
        }
    });

    // Enter tuşuna basıldığında kaydet butonunu tetiklemek için
    $(document).keydown(function(event) {
        if (event.key === "Enter" && $('#dateTimePickerModal').hasClass('show')) {
            $('#saveDateTime').click();
        }
    });

    // Tarihi kaydetmek için
    $('#saveDateTime').click(function() {
        var projectId = $('#dateTimePickerModal').data('project-id');
        var date = $('#dateTimePickerModal input[name="deadlineDate"]').val();
        var time = $('#dateTimePickerModal input[name="deadlineTime"]').val();
        var dateTime = date + ' ' + time;

        if (!date || !time) {
            alert('Lütfen geçerli bir tarih ve saat seçiniz.');
            return;
        }

        $.ajax({
            url: 'pages/manager/scrumboard/modules/update_date.php',
            type: 'POST',
            data: {
                id: projectId,
                dateDeadline: dateTime
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                } else {
                    alert('Hata: ' + response.message);
                }
                $('#dateTimePickerModal').removeClass('show');
            },
            error: function(xhr, status, error) {
                console.error('Hata detayları:', xhr, status, error);
                alert('Bir hata oluştu: ' + xhr.responseText);
            }
        });
    });
});
</script>
