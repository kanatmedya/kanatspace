document.addEventListener('DOMContentLoaded', function () {
    const noteInput = document.getElementById('note-input');

    if (!noteInput) {
        console.error('Element(s) not found:', {
            noteInput: noteInput ? 'found' : 'not found'
        });
        return;
    }

    noteInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            addNote();
        }
    });

    function addNote() {
        const note = noteInput.value;
        const orderNumber = document.querySelectorAll('#note-list .flex').length + 1;
        fetch('pages/manager/dashboard/modules/notesAdd.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ note, orderNumber })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchNotes(); // Notları yeniden yükle
                noteInput.value = '';
            }
        })
        .catch(error => console.error('Error adding note:', error));
    }

    window.updateNoteStatus = function (id) {
        fetch('pages/manager/dashboard/modules/notesUpdateStatus.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchNotes(); // Notları yeniden yükle
            }
        })
        .catch(error => console.error('Error updating note status:', error));
    }

    window.deleteNoteConfirm = function (id) {
        Swal.fire({
            title: 'Notu silmek istediğinizden emin misiniz?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'Hayır'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteNote(id);
            }
        });
    };

    function deleteNote(id) {
        fetch('pages/manager/dashboard/modules/notesDelete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchNotes(); // Notları yeniden yükle
            }
        })
        .catch(error => console.error('Error deleting note:', error));
    }

    function fetchNotes() {
        fetch('pages/manager/dashboard/modules/notesGet.php')
        .then(response => response.json())
        .then(notes => {
            const noteList = document.getElementById('note-list');
            noteList.innerHTML = ''; // Mevcut notları temizle

            notes.forEach(note => {
                const newNote = document.createElement('div');
                newNote.className = 'flex items-center mb-2';
                newNote.dataset.id = note.id;
                newNote.innerHTML = `
                    <input type="checkbox" class="mr-2" ${note.status == 1 ? 'checked' : ''} onclick="updateNoteStatus(${note.id})">
                    <h5 class="flex-1 font-semibold dark:text-white-light editable" ondblclick="editNoteContent(this, ${note.id})" style="${note.status == 1 ? 'text-decoration: line-through; color: gray;' : ''}">
                        ${note.note}
                    </h5>
                    <button class="ml-2" onclick="deleteNoteConfirm(${note.id})">
                        <svg class="h-5 w-5 text-red-500 hover:text-red-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;
                noteList.appendChild(newNote);
            });
        })
        .catch(error => console.error('Error fetching notes:', error));
    }

    window.editNoteContent = function (element, id) {
        const noteContent = element.textContent;

        element.innerHTML = `<input type="text" class="form-input" value="${noteContent}" onkeypress="saveNoteEdit(event, ${id})" onblur="cancelEdit(this, '${noteContent}')">`;
        element.querySelector('input').focus();
    };

    window.saveNoteEdit = function (event, id) {
        if (event.key === 'Enter') {
            const inputElement = event.target;
            const updatedNote = inputElement.value;

            fetch('pages/manager/dashboard/modules/notesUpdate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id, note: updatedNote })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchNotes(); // Notları yeniden yükle
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Not Güncellendi',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
            .catch(error => console.error('Error updating note:', error));
        }
    };

    window.cancelEdit = function (inputElement, originalContent) {
        const parent = inputElement.parentNode;
        parent.innerHTML = originalContent;
    };

    fetchNotes(); // Sayfa yüklendiğinde notları getir
});

