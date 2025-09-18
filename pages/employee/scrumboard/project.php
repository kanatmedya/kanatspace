<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    /* Gönder butonu ve textarea yan yana */
    .commentAdd-input-container {
        display: flex;
        align-items: center;
        position: relative;
    }
    #commentAddText {
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
    #saveCommentAdd {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #25D366;
        cursor: pointer;
        transition: background 0.3s;
    }
    #saveCommentAdd:hover {
        background: #1DA851;
    }

    .btn-commentmodal {
        padding: .5rem .5rem;
        border-color: unset;
        padding: 10px; /* Padding eklemek */
        border-radius: 50%; /* Yuvarlak şekil için */
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

<div class="animate__animated p-6" :class="[$store.app.animation]">
<?php

$activeUser = $_SESSION['name'];

//Fonksiyonlar
function formatDateTimeRelative($date)
{
    $now = new DateTime();
    $today = new DateTime($now->format('Y-m-d'));
    $yesterday = (clone $today)->modify('-1 day');
    $tomorrow = (clone $today)->modify('+1 day');
    $lastWeek = (clone $today)->modify('-7 days');
    $nextWeek = (clone $today)->modify('+7 days');
    $startOfWeek = (clone $today)->modify('last Monday');
    $endOfWeek = (clone $startOfWeek)->modify('+6 days');
    $lastYear = (clone $today)->format('Y') - 1;

    $interval = $now->diff($date);

    // Eğer bugün ise
    if ($date >= $today && $date < $tomorrow) {
        if ($interval->s < 60) {
            return $interval->invert ? $interval->s . ' Saniye Sonra' : $interval->s . ' Saniye Önce';
        } elseif ($interval->i < 60) {
            return $interval->invert ? $interval->i . ' Dakika Sonra' : $interval->i . ' Dakika Önce';
        } elseif ($interval->h < 24) {
            return $interval->invert ? $interval->h . ' Saat ' . $interval->i . ' Dakika Sonra' : $interval->h . ' Saat ' . $interval->i . ' Dakika Önce';
        }
    }

    // Eğer dün ise
    if ($date >= $yesterday && $date < $today) {
        return 'Dün, ' . $date->format('H:i');
    }

    // Eğer yarın ise
    if ($date >= $tomorrow && $date < $nextWeek) {
        return 'Yarın, ' . $date->format('H:i');
    }

    // Eğer bu hafta içinde ise
    if ($date >= $startOfWeek && $date <= $endOfWeek) {
        return $date->format('l');
    }

    // Eğer geçen hafta ise
    if ($date >= $lastWeek && $date < $startOfWeek) {
        return 'Geçen ' . $date->format('l, H:i');
    }

    // Eğer haftaya ise
    if ($date >= $nextWeek && $date < (clone $nextWeek)->modify('+7 days')) {
        return 'Haftaya ' . $date->format('l, H:i');
    }

    // Eğer bu yıl içinde ise
    if ($date->format('Y') == $today->format('Y')) {
        return $date->format('j F');
    }

    // Eğer bu yıl içinde değil ise
    return $date->format('j F Y');
}

function getProjectTypeValue($projectTypeID, $conn)
{
    // SQL sorgusu
    $query = "SELECT value FROM projects_types WHERE id = ?";

    // Prepared statement kullanarak güvenli bir şekilde sorguyu çalıştırma
    if ($stmt = $conn->prepare($query)) {
        // Parametreyi bağlama
        $stmt->bind_param("i", $projectTypeID);

        // Sorguyu çalıştırma
        $stmt->execute();

        // Sonuçları bağlama
        $stmt->bind_result($value);

        // Sonuçları alma
        if ($stmt->fetch()) {
            return $value;
        } else {
            return null; // Eşleşen satır bulunamadıysa null döndür
        }

        // Statement'i kapatma
        $stmt->close();
    } else {
        // Sorgu hazırlama başarısız olduysa hata mesajı
        return "Error: " . $conn->error;
    }
}


if (!empty($_POST["mail"])) {

} else {
    $projectID = $_GET['id']; // Proje ID'sini GET parametresinden al
    $sqlProject = "SELECT * FROM projects WHERE id='$projectID'";
    $resProject = $conn->query($sqlProject);
    $row = $resProject->fetch_array();
    
    if ($projectID && $activeUser) {
    // Projects_comments tablosunda related sütunu eşleşen tüm satırları alın
    $sqlComments = "SELECT id FROM projects_comments WHERE related = ?";
    $stmtComments = $conn->prepare($sqlComments);
    $stmtComments->bind_param("i", $projectID);
    $stmtComments->execute();
    $resultComments = $stmtComments->get_result();

    // Eşleşen comment_id'lerini alın
    $commentIds = [];
    while ($rowReaded = $resultComments->fetch_assoc()) {
        $commentIds[] = $rowReaded['id'];
    }

    // Eğer comment_id'ler bulunduysa notifications tablosunu güncelleyin
    if (!empty($commentIds)) {
        $commentIdsStr = implode(',', $commentIds);
        $currentDateTime = date('Y-m-d H:i:s');

        $sqlUpdateNotifications = "UPDATE nt_comments SET readed = 1, readedDate = ? WHERE comment_id IN ($commentIdsStr) AND username = ?";
        $stmtUpdate = $conn->prepare($sqlUpdateNotifications);
        $stmtUpdate->bind_param("ss", $currentDateTime, $activeUser);
        $stmtUpdate->execute();
    }
}
    
    $descripton = $row['description'];
    
    if($descripton==''){
        $descripton = '<i style="font-size:small">Açıklama Eklenmemiş</i>';
    }

    $empExp = $row['employee'];
    $empExp = explode(",", $empExp);
    
    $client = $row['client'];
    $projectTypeID = $row['projectType'];
    $projectType = getProjectTypeValue($projectTypeID, $conn);

    $sqlProjectCom = "SELECT * FROM projects_comments WHERE related='$projectID' ORDER BY dateCreate DESC";
    $resProjectCom = $conn->query($sqlProjectCom);

    //Numbers
    $currentCom = 0;
    $numCom = $resProjectCom->num_rows;
    ?>
        <div class="grid grid-cols-1 gap-6 pt-2 lg:grid-cols-3">
            <!-- Sol Panel -->
            <div class="flex flex-col lg:col-span-2 gap-5">
                <div class="panel flex justify-between" style="align-items: center;">
                    <div class="ml-2">
                        <h2 style="font-size: x-large;"><strong><?php echo $row['title'];?></strong></h2>
                        <h2 style="font-size: medium;margin-top:8px"><?php echo $client .' - '. $projectType;?></h2>
                    </div>
                    <div class="flex">
                        <?php
                        foreach ($empExp as $emp) {

        $sqlEmp = "SELECT ppLetter,ppColorBG,ppColorText,profilePicture FROM users_employee WHERE name = '$emp'";
        $resEmp = $conn->query($sqlEmp);
        if ($resEmp->num_rows > 0) {
            while ($rowEmp = $resEmp->fetch_array()) {
                if ($rowEmp['profilePicture'] != '') {
                    echo '<img class="h-9 w-9 rounded-full border-2 border-white object-cover transition-all duration-300 dark:border-dark" src="' . $rowEmp['profilePicture'] . '" alt="' . $emp . '">';
                } else {
                    echo '<svg class="h-9 w-9 rounded-full border-2 border-white object-cover transition-all duration-300 dark:border-dark" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="18" cy="18" r="18" fill="' . $rowEmp['ppColorBG'] . '" />
                        <text x="16" y="20" font-size="14" text-anchor="middle" fill="' . $rowEmp['ppColorText'] . '">' . $rowEmp['ppLetter'] . '</text>
                    </svg>';
                }
            }
        }

        $sqlMan = "SELECT ppLetter,ppColorBG,ppColorText,profilePicture FROM users_manager WHERE name = '$emp'";
        $resMan = $conn->query($sqlMan);
        if ($resMan->num_rows > 0) {
            while ($rowMan = $resMan->fetch_array()) {
                if ($rowMan['profilePicture'] != '') {
                    echo '<img class="h-9 w-9 rounded-full border-2 border-white object-cover transition-all duration-300 dark:border-dark" src="' . $rowMan['profilePicture'] . '" alt="' . $emp . '">';
                } else {
                    echo '<svg class="h-9 w-9 rounded-full border-2 border-white object-cover transition-all duration-300 dark:border-dark" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="18" cy="18" r="18" fill="' . $rowMan['ppColorBG'] . '" />
                        <text x="16" y="20" font-size="14" text-anchor="middle" fill="' . $rowMan['ppColorText'] . '">' . $rowMan['ppLetter'] . '</text>
                    </svg>';
                }
            }
        }

    }
    ?>
                    </div>
                </div>
                <div class="panel">
                    <div class="ml-2">
                        <h2 style="font-size: medium;"><strong>Açıklama</strong></h2>
                        <h2 style="font-size: medium;"><?php echo $descripton ;?></h2>
                    </div>
                </div>
            </div>
            
            <!-- Sağ Panel -->
            <div class="panel" style="height:100%">
                <div class="-mx-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 pt-0 dark:border-[#1b2e4b] dark:text-white-light">
                    <h5 class="text-lg font-semibold">Etkinlik</h5>
                    <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                        <a href="javascript:;" @click="toggle">
                            <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                                <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                                <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                            </svg>
                        </a>
                        <ul x-show="open" x-transition="" x-transition.duration.300ms="" class="ltr:right-0 rtl:left-0" style="display: none;">
                            <li><a href="">Yorumlar</a></li>
                            <li><a href="">Detaylar</a></li>
                        </ul>
                    </div>
                </div>
            <div class="flex flex-col gap-2">
                
                <!-- Yorum Ekleme -->
                <div class="mt-2 commentAdd-input-container">
    <textarea id="commentAddText" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" rows="4"></textarea>
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
        <button type="button" id="saveCommentAdd" class="btn-commentmodal btn btn-primary gap-2">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</div>
<div class="file-preview" id="filePreview"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Ataç simgesine tıklama olayını ekleyin
    $('#attachIcon').click(function() {
        console.log("Attach icon clicked!"); // Test mesajı
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

    // Yorum kaydetmek için
    $('#saveCommentAdd').click(function() {
        console.log("Save button clicked!"); // Test mesajı
        var commentAddText = $('#commentAddText').val();
        var commentAddPhotos = $('#attachPhotos')[0].files;
        var commentAddDocuments = $('#attachDocuments')[0].files;
        var commentAddAudios = $('#attachAudios')[0].files;
        var projectId = <?php echo json_encode($projectID); ?>; // PHP'den projectId'yi al

        if (!commentAddText && commentAddPhotos.length === 0 && commentAddDocuments.length === 0 && commentAddAudios.length === 0) {
            alert('Lütfen bir yorum veya dosya ekleyiniz.');
            return;
        }

        var formData = new FormData();
        formData.append('id', projectId); // Proje ID'sini form verisine ekleyin
        formData.append('comment', commentAddText);
        Array.from(commentAddPhotos).forEach((file, i) => {
            formData.append('photos[]', file);
        });
        Array.from(commentAddDocuments).forEach((file, i) => {
            formData.append('documents[]', file);
        });
        Array.from(commentAddAudios).forEach((file, i) => {
            formData.append('audios[]', file);
        });

        // AJAX ile gönderim
        $.ajax({
            url: 'pages/manager/scrumboard/modules/save_comment.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    // Başarılı mesaj
                    alert('Yorum başarıyla eklendi.');
                    $('#commentAddText').val('');
                    $('#attachPhotos').val('');
                    $('#attachDocuments').val('');
                    $('#attachAudios').val('');
                    $('#filePreview').empty();
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

    // Yorum silme işlevselliği
    $(document).on('click', '.delete-comment', function() {
        var commentId = $(this).data('comment-id');
        if (confirm('Bu yorumu silmek istediğinize emin misiniz?')) {
            $.ajax({
                url: 'pages/manager/scrumboard/modules/delete_comment.php',
                type: 'POST',
                data: { commentId: commentId },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Yorum başarıyla silindi.');
                        location.reload(); // Sayfayı yenileyerek yorumu kaldır
                    } else {
                        alert('Hata: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Hata detayları:', xhr, status, error);
                    alert('Bir hata oluştu: ' + xhr.responseText);
                }
            });
        }
    });
});
</script>
            
    <?php
        $index = 1;
        while ($rowCom = $resProjectCom->fetch_array()) {

            include "project/comments.php";
            $index += 1;
        }
        echo '</div></div></div>';
}
?>

<!-- Modal Yapısı -->
<div id="photoModal" class="modalBigPhoto" style="display: none;">
    <span class="close" onclick="closeModal()">×</span>
    <img class="modalBigPhoto-content" id="photoModalContent" src="">
    <div class="caption" id="photoCaption"></div>
</div>

<script>
    function openModal(src) {
        document.getElementById('photoModalContent').src = src;
        document.getElementById('photoCaption').innerHTML = 'Comment Photo'; // Caption içeriğini değiştirebilirsiniz
        document.getElementById('photoModal').style.display = 'flex';
        document.getElementById('carouselContainer').innerHTML = '<img src="' + src + '">';
    }

    function closeModal() {
        document.getElementById('photoModal').style.display = 'none';
    }
</script>

<style>
    .modalBigPhoto {
        display: none;
        /* Başlangıçta gizli */
        flex-direction: column;
        position: fixed;
        z-index: 100;
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.9);
    }

    .modalBigPhoto-content {
        overflow: auto;
        margin: auto;
        display: flex;
        flex-direction: column;
        width: auto;
        max-width: 95%;
        background: unset;
        border: unset;
    }

    .caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
    }

    .carousel-container {
        text-align: center;
    }

    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }
    
    .edit {
        opacity:0;
        margin-bottom:2px;
    }
    
    .comment:hover .edit {
        opacity:1; !important;
        transition: opacity 0.2s ease-in-out;
    }
</style>

</div>
