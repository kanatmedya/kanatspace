<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
/* Modal gösterme ve gizleme */
#addCommentModal {
    display: none;
}
#addCommentModal.show {
    display: block;
    z-index: 999999;
}

/* Yorum ekleme ikonu */
.add-comment-icon {
    cursor: pointer;
    margin-left: 8px;
    display: none;
}
.projectTitle:hover .add-comment-icon {
    display: inline-block;
}

/* Chat tarzı yorumlar */
.comment-container {
    max-height: 300px;
    overflow-y: auto;
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
    opacity: 0;
    transition: opacity 0.5s ease-in-out; /* Geçiş animasyonu */
}

.comment {
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 10px;
    max-width: 80%;
    word-wrap: break-word;
    position: relative; /* Silme ikonunun konumunu ayarlamak için */
    opacity: 0; /* Yorumlar başlangıçta görünmez */
    transition: opacity 0.5s ease-in-out; /* Geçiş animasyonu */
}

.comment.user .username {
    display:none;
}

.comment.user {
    background-color: #d1e7dd;
    align-self: flex-end;
    text-align: right;
}
.comment.other {
    background-color: #f8d7da;
    align-self: flex-start;
    text-align: left;
}
.comment .username {
    font-weight: bold;
}
.comment .text {
    margin-top: 5px;
}
.comment .date {
    font-size: 10px;
    color: #666;
    margin-top: 5px;
    text-align: right;
}

/* Silme ikonu */
.comment .delete-icon {
    display: none;
    position: absolute;
    top: 5px;
    right: 5px;
    cursor: pointer;
}
.comment:hover .delete-icon {
    display: block;
}

/* Gönder butonu ve textarea yan yana */
.comment-input-container {
    display: flex;
    align-items: center;
    position: relative;
}
#commentText {
    flex-grow: 1;
    margin-right: 10px;
}

/* Ataç simgesi ve dosya seçme animasyonu */
.attach-icon {
    cursor: pointer;
    font-size: 24px;
    position: relative;
}
.attach-options {
    display: none;
    position: absolute;
    bottom: 40px;
    left: 0;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    border-radius: 8px;
    z-index: 9999; /* Daha yüksek bir z-index değeri */
    overflow: hidden;
}
.attach-options.show {
    display: block;
}
.attach-option {
    padding: 5px 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    transition: background 0.2s;
    font-size: 14px; /* Boyutu küçültmek için */
}
.attach-option:hover {
    background: #f0f0f0;
}
.attach-option input {
    display: none;
}
.attach-option label {
    display: flex;
    flex-direction: row;
    gap: 10px;
}

/* Gönder butonu */
#saveComment {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #25D366;
    cursor: pointer;
    transition: background 0.3s;
}
#saveComment:hover {
    background: #1DA851;
}

.btn-commentmodal {
    padding: .5rem .5rem;
    border-color: unset;
    padding: 10px; /* Padding eklemek */
    border-radius: 50%; /* Yuvarlak şekil için */
}

/* Loader */
.loader {
    border: 4px solid #f3f3f3;
    border-radius: 50%;
    border-top: 4px solid #3498db;
    width: 20px;
    height: 20px;
    animation: spin 2s linear infinite;
    display: none;
    margin: 0 auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Yorum fotoğrafı */
.comment-photo {
    max-width: 100%;
    max-height: 150px;
    margin-top: 10px;
    border-radius: 5px;
    cursor: pointer;
}

/* Büyük resim modal */
#photoModal {
    display: none;
    position: fixed;
    z-index: 1000000; /* addCommentModal'ın üstünde görünmesi için */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden; /* Scrollbar oluşmasını önlemek için */
    background-color: rgba(0, 0, 0, 0.9);
}
#photoModal .modal-content {
    margin: auto;
    display: block;
    width: 100%;
    max-width: 700px;
    max-height: 70vh; /* Maksimum yüksekliği belirleyin */
    object-fit: contain; /* Fotoğrafın tam olarak sığmasını sağlayın */
    background-color: unset;
    border: 0;
}
#photoModal .caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
}
#photoModal .close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}
#photoModal .close:hover,
#photoModal .close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

/* Carousel */
.carousel-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    overflow-x: auto; /* Karusel içeriklerini kaydırılabilir yap */
}
.carousel-container img {
    max-width: 100px;
    max-height: 100px;
    margin: 0 5px;
    cursor: pointer;
    transition: transform 0.2s;
}
.carousel-container img:hover {
    transform: scale(1.1);
}

/* Dosya önizleme alanı */
.file-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}
.file-preview-item {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.file-preview-item img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 5px;
}
.file-preview-item .remove-file {
    position: absolute;
    top: -5px;
    right: -5px;
    background: red;
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 12px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<!-- Yorum Ekle Modal -->
<div id="addCommentModal" class="modal hidden fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="modal-content inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Yorumlar</h3>
                <div class="loader" id="commentLoader"></div>
                <div class="comment-container" id="commentContainer">
                    <!-- Yorumlar burada görünecek -->
                </div>
                <div class="mt-2 comment-input-container">
                    <textarea id="commentText" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" rows="4"></textarea>
                    <div class="attach-icon" id="attachIcon">
                        <button type="button" class="btn-commentmodal btn btn-primary gap-2"><i class="fa-solid fa-paperclip"></i></button>
                        <div class="attach-options" id="attachOptions">
                            <div class="attach-option">
                                <label for="attachPhotos">
                                    <i class="fa-solid fa-photo-film"></i>
                                    <span>Fotoğraf</span>
                                </label>
                                <input type="file" id="attachPhotos" accept="image/*" multiple>
                            </div>
                            <div class="attach-option">
                                <label for="attachDocuments">
                                    <i class="fa-solid fa-file"></i>
                                    <span>Belge</span>
                                </label>
                                <input type="file" id="attachDocuments" accept=".pdf,.psd,.ai,.svg,.cdr,.psb,.esp,.doc,.docx,.txt" multiple>
                            </div>
                            <div class="attach-option">
                                <label for="attachAudios">
                                    <i class="fa-solid fa-music"></i>
                                    <span>Ses</span>
                                </label>
                                <input type="file" id="attachAudios" accept="audio/*" multiple>
                            </div>
                        </div>
                    </div>
                    <div class="attach-icon">
                        <button type="button" id="saveComment" class="btn-commentmodal btn btn-primary gap-2">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
                <div class="file-preview" id="filePreview"></div>
            </div>
        </div>
    </div>
</div>

<!-- Büyük Resim Modal -->
<div id="photoModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="photoModalContent">
    <div class="caption" id="photoCaption"></div>
    <div class="carousel-container" id="carouselContainer">
        <!-- Carousel images will be appended here -->
    </div>
</div>

<script>
$(document).ready(function() {
    var currentUser = '<?php echo $_SESSION["name"]; ?>'; // PHP'den oturumdaki kullanıcı ismini al

    // Ataç simgesine tıklama olayını ekleyin
    $('#attachIcon').click(function() {
        $('#attachOptions').toggleClass('show');
    });

    // Dosya önizlemesi ve kaldırma işlevselliği
    function previewFiles(inputElement) {
        var files = inputElement.files;
        var previewElement = $('#filePreview');
        Array.from(files).forEach(function(file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var previewHtml = `
                    <div class="file-preview-item">
                        <img src="${e.target.result}" alt="${file.name}">
                        <button class="remove-file" data-file-name="${file.name}">&times;</button>
                    </div>
                `;
                previewElement.append(previewHtml);
            };
            reader.readAsDataURL(file);
        });
    }

    // Fotoğraf dosyaları seçildiğinde
    $('#attachPhotos').change(function() {
        previewFiles(this);
    });

    // Belge dosyaları seçildiğinde
    $('#attachDocuments').change(function() {
        previewFiles(this);
    });

    // Ses dosyaları seçildiğinde
    $('#attachAudios').change(function() {
        previewFiles(this);
    });

    // Önizleme dosyasını kaldırma
    $(document).on('click', '.remove-file', function() {
        var fileName = $(this).data('file-name');
        var inputElement = $('#attachPhotos')[0].files.length ? $('#attachPhotos')[0] :
                           $('#attachDocuments')[0].files.length ? $('#attachDocuments')[0] :
                           $('#attachAudios')[0].files.length ? $('#attachAudios')[0] : null;

        if (inputElement) {
            var dt = new DataTransfer();
            Array.from(inputElement.files).forEach(file => {
                if (file.name !== fileName) {
                    dt.items.add(file);
                }
            });
            inputElement.files = dt.files;
        }
        $(this).closest('.file-preview-item').remove();
    });

    // Yorum ekleme modal'ı açmak için
    $('.add-comment-icon').click(function() {
        $('#addCommentModal').addClass('show');
        var projectId = $(this).closest('.task-item').data('id');
        $('#addCommentModal').data('project-id', projectId);

        // Loader'ı göster
        $('#commentLoader').show();
        $('#commentContainer').css('opacity', 0); // Yorumları gizle

        // AJAX ile eski yorumları çekmek
        $.ajax({
            url: 'pages/manager/scrumboard/modules/get_comments.php',
            type: 'POST',
            data: { id: projectId },
            success: function(response) {
                $('#commentLoader').hide(); // Loader'ı gizle
                $('#commentContainer').css('opacity', 1); // Yorumları göster

                if (response.status === 'success') {
                    var comments = response.comments;
                    var commentContainer = $('#commentContainer');
                    commentContainer.empty();

                    comments.forEach(function(comment) {
                        var commentClass = (comment.user === currentUser) ? 'user' : 'other';
                        var commentHtml = `
                            <div class="comment ${commentClass}" data-comment-id="${comment.id}">
                                <div class="username">${comment.user}</div>
                                <div class="text" style="text-align: left">${comment.value.replace(/\n/g, '<br>')}</div>
                                <div class="date">${comment.dateCreate}</div>
                                ${comment.photos ? JSON.parse(comment.photos).map(photo => `<img src="${photo}" alt="Comment Photo" class="comment-photo">`).join('') : ''}
                                ${comment.documents ? JSON.parse(comment.documents).map(doc => `<a href="${doc.url}" target="_blank">📄 ${doc.name}</a>`).join('') : ''}
                                ${comment.audios ? JSON.parse(comment.audios).map(audio => `<audio controls><source src="${audio}" type="audio/mpeg">Your browser does not support the audio element.</audio>`).join('') : ''}
                            </div>
                        `;
                        commentContainer.append(commentHtml);

                        // Yeni eklenen yorumun görünmesini sağla
                        commentContainer.children().last().css('opacity', 1);
                    });

                    // Scrollbar'ı en alta ayarla
                    setTimeout(function() {
                        commentContainer.scrollTop(commentContainer[0].scrollHeight);
                    }, 100); // Yüklemeyi tamamlamak için kısa bir gecikme ekleyin
                } else {
                    alert('Hata: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                $('#commentLoader').hide(); // Loader'ı gizle hata durumunda
                $('#commentContainer').css('opacity', 1); // Yorumları göster hata durumunda
                console.error('Hata detayları:', xhr, status, error);
                alert('Bir hata oluştu: ' + xhr.responseText);
            }
        });
    });

    // Yorum ekleme modal'ı dışına tıklanıldığında kapatmak için
    $(document).mouseup(function(e) {
        var modal = $("#addCommentModal .modal-content");
        var photoModal = $("#photoModal");
        if (!modal.is(e.target) && modal.has(e.target).length === 0 && !photoModal.is(e.target) && photoModal.has(e.target).length === 0) {
            $('#addCommentModal').removeClass('show');
        }
    });

    // Esc tuşuna basıldığında modalı kapatmak için
    $(document).keyup(function(event) {
        if (event.key === "Escape") {
            $('#addCommentModal').removeClass('show');
            $('#photoModal').hide();
        }
    });

    // Yorum kaydetmek için
    function saveComment() {
        var projectId = $('#addCommentModal').data('project-id');
        var commentText = $('#commentText').val();
        var commentPhotos = $('#attachPhotos')[0].files;
        var commentDocuments = $('#attachDocuments')[0].files;
        var commentAudios = $('#attachAudios')[0].files;

        if (!commentText && commentPhotos.length === 0 && commentDocuments.length === 0 && commentAudios.length === 0) {
            alert('Lütfen bir yorum veya dosya ekleyiniz.');
            return;
        }

        var formData = new FormData();
        formData.append('id', projectId);
        formData.append('comment', commentText);
        Array.from(commentPhotos).forEach((file, i) => {
            formData.append('photos[]', file);
        });
        Array.from(commentDocuments).forEach((file, i) => {
            formData.append('documents[]', file);
        });
        Array.from(commentAudios).forEach((file, i) => {
            formData.append('audios[]', file);
        });

        // Yükleme durumunu göstermek için
        var totalSize = Array.from(commentPhotos).concat(Array.from(commentDocuments), Array.from(commentAudios)).reduce((acc, file) => acc + file.size, 0);
        var uploadedSize = 0;

        if (totalSize > 0) {
            $('#uploadProgress').show();
            var uploadStatus = $('<div id="uploadStatus" class="upload-status">Yükleniyor: 0% (0 / ' + (totalSize / (1024 * 1024)).toFixed(2) + ' MB)</div>');
            $('#filePreview').before(uploadStatus);
        }

        $.ajax({
            url: 'pages/manager/scrumboard/modules/save_comment.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        var percentComplete = (e.loaded / e.total) * 100;
                        uploadedSize = e.loaded;
                        if (totalSize > 0) {
                            $('#uploadStatus').text('Yükleniyor: ' + percentComplete.toFixed(2) + '% (' + (uploadedSize / (1024 * 1024)).toFixed(2) + ' / ' + (totalSize / (1024 * 1024)).toFixed(2) + ' MB)');
                        }
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Yeni yorumu ekleyelim
                    var commentClass = 'user';
                    var currentDate = new Date().toLocaleString(); // Yorum eklenme zamanını al
                    var commentHtml = `
                        <div class="comment ${commentClass}" data-comment-id="${response.commentId}">
                            <div class="username">${currentUser}</div>
                            <div class="text">${commentText}</div>
                            <div class="date">${currentDate}</div>
                            ${response.photos ? response.photos.map(photo => `<img src="${photo}" alt="Comment Photo" class="comment-photo">`).join('') : ''}
                            ${response.documents ? response.documents.map(doc => `<a href="${doc.url}" target="_blank">📄 ${doc.name}</a>`).join('') : ''}
                            ${response.audios ? response.audios.map(audio => `<audio controls><source src="${audio}" type="audio/mpeg">Your browser does not support the audio element.</audio>`).join('') : ''}
                        </div>
                    `;
                    var commentContainer = $('#commentContainer');
                    commentContainer.append(commentHtml);

                    // Yeni eklenen yorumun görünmesini sağla
                    commentContainer.children().last().css('opacity', 1);
                    
                    $('#commentText').val('');
                    $('#attachPhotos').val('');
                    $('#attachDocuments').val('');
                    $('#attachAudios').val('');
                    $('#filePreview').empty();
                    $('#uploadStatus').remove();
                    $('#uploadProgress').hide();

                    // Scrollbar'ı en alta ayarla
                    setTimeout(function() {
                        commentContainer.scrollTop(commentContainer[0].scrollHeight);
                    }, 100); // Yüklemeyi tamamlamak için kısa bir gecikme ekleyin
                } else {
                    alert('Hata: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Hata detayları:', xhr, status, error);
                alert('Bir hata oluştu: ' + xhr.responseText);
                $('#uploadStatus').remove();
                $('#uploadProgress').hide();
            }
        });
    }

    $('#saveComment').click(saveComment);

    // Shift + Enter ile gönder
    $('#commentText').keydown(function(event) {
        if (event.key === "Enter" && event.shiftKey) {
            saveComment();
            event.preventDefault();
        }
    });

    // Yorum silmek için
    

    // Büyük resim modalını açmak için
    $('#commentContainer').on('click', '.comment-photo', function() {
        var imgSrc = $(this).attr('src');
        var commentId = $(this).closest('.comment').data('comment-id');

        $('#photoModal').show();
        $('#photoModalContent').attr('src', imgSrc);
        $('#photoCaption').text($(this).attr('alt'));

        // Carousel için fotoğrafları ekleyelim
        var carouselContainer = $('#carouselContainer');
        carouselContainer.empty();
        $('#commentContainer').find('.comment[data-comment-id="' + commentId + '"] .comment-photo').each(function() {
            var carouselImg = $('<img>').attr('src', $(this).attr('src'));
            carouselContainer.append(carouselImg);
        });
    });

    // Büyük resim modalını kapatmak için
    $('#photoModal .close').click(function() {
        $('#photoModal').hide();
    });

    // Carousel'deki fotoğraflara tıklanınca büyük resimde göster
    $('#carouselContainer').on('click', 'img', function() {
        $('#photoModalContent').attr('src', $(this).attr('src'));
    });
});
</script>
