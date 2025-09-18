
document.addEventListener('DOMContentLoaded', function () {
    const projectAddButton = document.getElementById('projectAddButton');
    const projectModal = document.getElementById('projectModal');
    const closeModalButton = document.getElementById('closeModal');
    const projectForm = document.getElementById('projectForm');

    // Butona tıklama ve modal'ı açma
    projectAddButton.addEventListener('click', function () {
        projectModal.classList.remove('hidden');
        projectModal.style.display = 'block';
    });

    // Modal'ı kapatma işlemi
    closeModalButton.addEventListener('click', function () {
        projectModal.classList.add('hidden');
        projectModal.style.display = 'none';
    });

    // Modal dışında bir yere tıklanırsa modal'ı kapatma
    window.addEventListener('click', function (event) {
        if (event.target == projectModal) {
            projectModal.classList.add('hidden');
            projectModal.style.display = 'none';
        }
    });

    // Formun AJAX ile gönderilmesi
    projectForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(projectForm);
        fetch('./pages/client/scrumboard/modules/projectAdd.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                alert(data); // İşlem sonucunu göster
                projectModal.classList.add('hidden'); // Modal'ı kapat
                projectModal.style.display = 'none';
                location.reload(); // Sayfayı yenile
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});