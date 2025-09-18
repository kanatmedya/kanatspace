<!-- Update Project Invoice Modal -->
<style>
#updateProjectInvoiceModal {
    display: none;
}
#updateProjectInvoiceModal.show {
    display: block;
    z-index: 999999;
}
</style>

<div id="updateProjectInvoiceModal" class="modal hidden fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full modal-content">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Fatura Bilgisini Güncelle</h3>
                <div class="flex">
                    <label for="invoiceId" class="mr-4">Fatura ID:</label>
                    <input type="number" name="invoiceId" id="invoiceId" class="form-input rounded-md" placeholder="Fatura ID girin">
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse" style="display: flex; justify-content: space-around;">
                <button type="button" id="saveInvoice" class="btn btn-primary gap-2">Kaydet</button>
                <button type="button" id="cancelInvoice" class="btn btn-warning gap-2">Fatura İptali</button> <!-- Eğer fatura varsa görülecek ve invoice_id null yapıp status_invoice 0 yapıp log_projects_invoices'a da remove ekleyecek -->
                <button type="button" id="cancelInvoiceModal" class="btn btn-danger gap-2">Kapat</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Fatura İptali butonuna tıklandığında çalışacak
    $('#cancelInvoice').click(function() {
        var projectId = $('#updateProjectInvoiceModal').data('project-id');

        // Eğer proje ID alınamazsa uyarı göster
        if (!projectId) {
            alert('Geçersiz proje ID. Lütfen veriyi kontrol edin.');
            return;
        }

        // Kullanıcıdan onay alın
        if (!confirm('Bu faturayı iptal etmek istediğinize emin misiniz?')) {
            return;
        }

        // AJAX isteği
        $.ajax({
            url: 'pages/manager/scrumboard/modules/ajax-cancel_invoice.php',
            type: 'POST',
            data: {
                id: projectId,
                invoice_id: null, // Fatura ID null yapılacak
                action: 'cancel' // İşlem türü belirtiyoruz
            },
            success: function(response) {
                console.log('Fatura İptali Yanıtı:', response); // Gelen yanıtı kontrol et

                if (response.status === 'success') {
                    alert(response.message);
                    // Gerekirse modal'ı kapat
                    $('#updateProjectInvoiceModal').removeClass('show');
                } else {
                    alert('Hata: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Hata Detayları:', xhr.responseText);
                alert('Bir hata oluştu: ' + xhr.responseText);
            }
        });
    });
    
    // Modal'ı açmak için
    $('.updateInvoice').click(function() {
    // Modal'ı göster
    $('#updateProjectInvoiceModal').addClass('show');

    // Proje ID'sini al
    var projectId = $(this).closest('.task-item').data('id');
    console.log('Alınan Proje ID:', projectId); // Debugging için

    // Eğer proje ID alınamazsa bir uyarı göster
    if (!projectId) {
        alert('Geçersiz proje ID. Lütfen veriyi kontrol edin.');
        return;
    }

    // AJAX ile mevcut fatura ID'sini çek
    $.ajax({
        url: 'pages/manager/scrumboard/modules/ajax-get_invoice.php',
        type: 'POST',
        data: { id: projectId },
        success: function(response) {
            console.log('Fatura Bilgisi:', response); // Gelen veriyi kontrol et
            if (response.status === 'success') {
                $('#invoiceId').val(response.data.invoice_id);
            } else {
                alert('Hata: ' + response.message);
            }
        },
        error: function(xhr) {
            console.error('Hata Detayı:', xhr.responseText);
        }
    });

    // Modal'a project-id bilgisini ekle
    $('#updateProjectInvoiceModal').data('project-id', projectId);
});


    // Modal'ı kapatmak için
    $('#cancelInvoiceModal').click(function() {
        $('#updateProjectInvoiceModal').removeClass('show');
    });

    // Modal dışına tıklanıldığında kapatmak için
    $(document).mouseup(function(e) {
        var modal = $("#updateProjectInvoiceModal .modal-content");
        if (!modal.is(e.target) && modal.has(e.target).length === 0) {
            $('#updateProjectInvoiceModal').removeClass('show');
        }
    });

    // Enter tuşuna basıldığında kaydet butonunu tetiklemek için
    $(document).keydown(function(event) {
        if (event.key === "Enter" && $('#updateProjectInvoiceModal').hasClass('show')) {
            $('#saveInvoice').click();
        }
    });

    // Kaydetme işlemi
    $('#saveInvoice').click(function() {
        var projectId = $('#updateProjectInvoiceModal').data('project-id');
        var invoiceId = $('#invoiceId').val();
    
        if (!invoiceId) {
            alert('Lütfen geçerli bir Fatura ID giriniz.');
            return;
        }
    
        $.ajax({
            url: 'pages/manager/scrumboard/modules/ajax-update_invoice.php',
            type: 'POST',
            data: {
                id: projectId,
                invoice_id: invoiceId
            },
            success: function(response) {
                console.log('Sunucu Yanıtı:', response); // Debugging için
                if (response.status === 'success') {
                    alert(response.message);
                } else {
                    alert('Hata: ' + response.message);
                }
                $('#updateProjectInvoiceModal').removeClass('show');
            },
            error: function(xhr, status, error) {
                console.error('Hata Detayları:', xhr.responseText); // Debugging için
                alert('Bir hata oluştu: ' + xhr.responseText);
            }
        });
    });
});
</script>
