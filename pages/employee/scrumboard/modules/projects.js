document.addEventListener('DOMContentLoaded', function () {
    const projectAddLink = document.querySelector('a[href="projectAdd"]');
    const projectModal = document.getElementById('projectModal');
    const closeModalButton = document.getElementById('closeModal');
    const projectForm = document.getElementById('projectForm');

    // Link tıklamasını engelle ve modal'ı aç
    projectAddLink.addEventListener('click', function (event) {
        event.preventDefault();
        projectModal.classList.remove('hidden');
    });

    // Modal'ı kapatma işlemi
    closeModalButton.addEventListener('click', function () {
        projectModal.classList.add('hidden');
    });

    // Formun AJAX ile gönderilmesi
    projectForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(projectForm);

        fetch('pages/manager/scrumboard/modules/projectAdd.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                alert(data); // İşlem sonucunu göster
                projectModal.classList.add('hidden'); // Modal'ı kapat
                location.reload(); // Sayfayı yenile
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const sortableLists = document.querySelectorAll('.sortable-list');
    sortableLists.forEach(list => {
        new Sortable(list, {
            group: 'shared',
            animation: 150,
            onEnd: function (evt) {
                const itemEl = evt.item;  // dragged HTMLElement
                const newStatus = evt.to.getAttribute('data-status');
                const itemId = itemEl.getAttribute('data-id');

                if (newStatus === 'Tamamlandı') {
                    // Tamamlandı sütununa taşınan öğeyi en başa ekle
                    evt.to.prepend(itemEl);
                }

                // Tüm task-item'lerin data-order değerlerini güncelle
                const items = evt.to.children;
                for (let i = 0; i < items.length; i++) {
                    items[i].setAttribute('data-order', i + 1);
                }

                // Güncellenmiş sırayı ve data-order'leri veritabanına kaydet
                const orders = Array.from(items).map(item => ({
                    id: item.getAttribute('data-id'),
                    order: item.getAttribute('data-order'),
                    status: newStatus
                }));

                // AJAX isteği ile veritabanını güncelle
                $.ajax({
                    url: 'pages/employee/scrumboard/modules/update_order.php',
                    type: 'POST',
                    data: {
                        orders: JSON.stringify(orders)
                    },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            },
        });
    });
});