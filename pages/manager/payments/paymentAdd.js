$(document).ready(function() {
    // Formu gönderme işlemi
    $("form").on("submit", function(event) {
        event.preventDefault(); // Formun varsayılan davranışını engelle

        $.ajax({
    url: "pages/manager/payments/ajax_paymentAdd.php", // PHP dosyasının yolu
    type: "POST",
    data: $(this).serialize(), // Form verilerini gönder
    success: function(response) {
        console.log(response);  // Yanıtı konsolda göster
        
        // Kategori ve işlem türü validasyonu
        let categorySub = $('select[name="categorySub"]').val();
        let category = $('select[name="category"]').val();
        let recurrencePeriod = $('select[name="recurrence_period"]').val();
        let type = $('select[name="type"]').val();
        let sender = $('select[name="sender"]').val();
        let reciever = $('select[name="reciever"]').val();
        let isValid = true;
        let errorMessage = "Lütfen aşağıdaki alanları doldurun:<br>";

        if (type === "-1") {
            isValid = false;
            errorMessage += "- İşlem Türü<br>";
        }

        if (recurrencePeriod === "-1") {
            isValid = false;
            errorMessage += "- Tekrar Türü<br>";
        }

        if (category === "-1") {
            isValid = false;
            errorMessage += "- Kategori<br>";
        }

        if (categorySub === "-1") {
            isValid = false;
            errorMessage += "- Alt Kategori<br>";
        }

        if (sender === "-1") {
            isValid = false;
            errorMessage += "- Gönderici<br>";
        }

        if (reciever === "-1") {
            isValid = false;
            errorMessage += "- Alıcı<br>";
        }

        // Eğer geçersiz alanlar varsa formu gönderme ve uyarı göster
        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Eksik Bilgi',
                html: '<p class="sweetDanger">' + errorMessage + '</p>'
            });
            return;  // Formu gönderme
        }
        
        // JSON formatını doğrulamak için parse edelim
        var jsonResponse = JSON.parse(response);

        if (jsonResponse.inserted_id) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Ödeme planı başarıyla kaydedildi.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                // Oluşturulan satırın ID'sini alın ve sayfaya yönlendirin
                window.location.href = "paymentOrders"; // jsonResponse.inserted_id;
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Hata: ' + jsonResponse.error,
                showConfirmButton: true
            });
        }
    },
    error: function(response) {
        console.log(response);
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Hata: Ödeme planı kaydedilemedi!',
            showConfirmButton: true
        });
    }
});

    });

    // İşlem türü seçildiğinde kategori güncellenir
    $('select[name="type"]').change(function() {
        var type = $(this).val();
        if (type != "-1") {
            updateCategories(type);
        } else {
            // İşlem türü seçilmediğinde kategori inputlarına 'Lütfen İşlem Türü Seçiniz' eklenir
            $('select[name="category"]').html('<option value="-1">Lütfen İşlem Türü Seçiniz</option>');
            $('select[name="categorySub"]').html('<option value="-1">Lütfen İşlem Türü Seçiniz</option>');
        }
    });

    // Kategori seçildiğinde alt kategorileri güncelle
    $('select[name="category"]').change(function() {
        var categoryId = $(this).val();
        if (categoryId != "-1") {
            updateSubCategories(categoryId);
        } else {
            $('select[name="categorySub"]').html('<option value="-1">Lütfen Ana Kategori Seçiniz</option>');
        }
    });

    // Ana kategori ve alt kategori verilerini güncelleyen fonksiyon
    function updateCategories(type) {
        $.ajax({
            url: 'pages/manager/payments/ajax_get_categories.php',
            type: 'POST',
            data: { type: type },
            success: function(response) {
                // AJAX isteği başarılı olduğunda kategorileri günceller
                $('select[name="category"]').html(response);
                $('select[name="categorySub"]').html('<option value="-1">Lütfen Ana Kategori Seçiniz</option>');
            },
            error: function() {
                alert('Kategori verileri alınırken bir hata oluştu.'); // AJAX hatası için
            }
        });
    }

    function updateSubCategories(categoryId) {
        $.ajax({
            url: 'pages/manager/payments/ajax_get_sub_categories.php',
            type: 'POST',
            data: { categoryId: categoryId },
            success: function(response) {
                $('select[name="categorySub"]').html(response);
            },
            error: function() {
                alert('Alt kategori verileri alınırken bir hata oluştu.');
            }
        });
    }

    // Tekrar Türü seçildiğinde taksit veya tekrar seçeneklerini göster ve diğerlerini temizle
    $('select[name="recurrence_period"]').change(function() {
        updatePaymentPlanDescription();
        if ($(this).val() == 'taksit') {
            $("#installment_amount-section").show();
            $("#installment_count-section").show();
            $("#repeat-section").hide();
            $('input[name="repeat_count"]').val('');
        } else {
            $("#installment_amount-section").hide();
            $("#installment_count-section").hide();
            $("#repeat-section").show();
            $('input[name="installment_count"]').val('');
            $('input[name="installment_amount"]').val('');
        }
    });

    // Taksit sayısı ve tutar değiştikçe taksit tutarını hesapla ve açıklamayı güncelle
    $('input[name="installment_count"], input[name="amount"]').on('input', function() {
        calculateInstallmentAmount();
        updatePaymentPlanDescription();
    });
    
    function calculateInstallmentAmount() {
        var amount = parseFloat($('input[name="amount"]').val());
        var installmentCount = parseInt($('input[name="installment_count"]').val());
    
        if (installmentCount > 0 && amount > 0) {
            var installmentAmount = (amount / installmentCount).toFixed(2);
            $('input[name="installment_amount"]').val(installmentAmount);
        } else {
            $('input[name="installment_amount"]').val(''); // Geçersiz durumlarda temizle
        }
    }

    // Ödeme planı açıklaması güncelleme fonksiyonu
    function updatePaymentPlanDescription() {
        var amount = parseFloat($('input[name="amount"]').val());
        var recurrencePeriod = $('select[name="recurrence_period"]').val();
        var installmentCount = parseInt($('input[name="installment_count"]').val());
        var repeatCount = parseInt($('input[name="repeat_count"]').val());
        var firstDueDate = $('input[name="date"]').val();
        var description = '';

        // İlk ödeme tarihi
        var firstDate = new Date(firstDueDate);
        var lastDate;

        if (recurrencePeriod === 'taksit') {
            if (installmentCount > 0 && amount > 0) {
                var installmentAmount = (amount / installmentCount).toFixed(2);
                var totalAmount = (installmentAmount * installmentCount).toFixed(2);
                lastDate = new Date(firstDate);
                lastDate.setMonth(lastDate.getMonth() + (installmentCount - 1));

                description = `${installmentCount} Taksit ${installmentAmount}₺, Toplam ${totalAmount}₺ Tutarında ${installmentCount} İşlem Kaydı Oluşturulacaktır. İlk Taksit: ${firstDueDate}, Son Taksit: ${lastDate.toISOString().split('T')[0]}`;
            }
        } else if (recurrencePeriod === 'monthly') {
            if (repeatCount > 0 && amount > 0) {
                var totalAmount = (repeatCount * amount).toFixed(2);
                lastDate = new Date(firstDate);
                lastDate.setMonth(lastDate.getMonth() + (repeatCount - 1));

                description = `Aylık ${repeatCount} Tekrar ${amount}₺, Toplam ${totalAmount}₺ Tutarında ${repeatCount} İşlem Kaydı Oluşturulacaktır. İlk İşlem: ${firstDueDate}, Son İşlem: ${lastDate.toISOString().split('T')[0]}`;
            }
        } else if (recurrencePeriod === 'weekly') {
            if (repeatCount > 0 && amount > 0) {
                var totalAmount = (repeatCount * amount).toFixed(2);
                lastDate = new Date(firstDate);
                lastDate.setDate(lastDate.getDate() + (7 * (repeatCount - 1)));

                description = `Haftalık ${repeatCount} Tekrar ${amount}₺, Toplam ${totalAmount}₺ Tutarında ${repeatCount} İşlem Kaydı Oluşturulacaktır. İlk İşlem: ${firstDueDate}, Son İşlem: ${lastDate.toISOString().split('T')[0]}`;
            }
        } else if (recurrencePeriod === 'daily') {
            if (repeatCount > 0 && amount > 0) {
                var totalAmount = (repeatCount * amount).toFixed(2);
                lastDate = new Date(firstDate);
                lastDate.setDate(lastDate.getDate() + (repeatCount - 1));

                description = `Günlük ${repeatCount} Tekrar ${amount}₺, Toplam ${totalAmount}₺ Tutarında ${repeatCount} İşlem Kaydı Oluşturulacaktır. İlk İşlem: ${firstDueDate}, Son İşlem: ${lastDate.toISOString().split('T')[0]}`;
            }
        } else if (recurrencePeriod === 'yearly') {
            if (repeatCount > 0 && amount > 0) {
                var totalAmount = (repeatCount * amount).toFixed(2);
                lastDate = new Date(firstDate);
                lastDate.setFullYear(lastDate.getFullYear() + (repeatCount - 1));

                description = `Yıllık ${repeatCount} Tekrar ${amount}₺, Toplam ${totalAmount}₺ Tutarında ${repeatCount} İşlem Kaydı Oluşturulacaktır. İlk İşlem: ${firstDueDate}, Son İşlem: ${lastDate.toISOString().split('T')[0]}`;
            }
        }

        $('input[name="paymentPlanDescription"]').val(description);
    }

    // Gönderici ve alıcı bilgilerini çekme
    updateSenderReceiver();

    function updateSenderReceiver() {
        $.ajax({
            url: 'pages/manager/payments/ajax_get_accounts.php',
            type: 'GET',
            dataType: 'json', // JSON formatını belirtin
            success: function(response) {
                $('select[name="sender"]').html(response.senders); // Gelen sender seçenekleri
                $('select[name="reciever"]').html(response.recievers); // Gelen receiver seçenekleri
            },
            error: function() {
                alert('Gönderici ve alıcı verileri alınırken bir hata oluştu.');
            }
        });
    }
});
