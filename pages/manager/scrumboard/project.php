<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

include_once ROOT_PATH . "assets/php/formatDateTimeRelative.php";
?>
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
        z-index: 9999;
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
        font-size: 14px;
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
        padding: 10px;
        border-radius: 50%;
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

    function getProjectTypeValue($projectTypeID, $conn)
    {
        $query = "SELECT value FROM projects_types WHERE id = ?";

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $projectTypeID);
            $stmt->execute();
            $stmt->bind_result($value);

            if ($stmt->fetch()) {
                return $value;
            } else {
                return null;
            }

            $stmt->close();
        } else {
            return "Error: " . $conn->error;
        }
    }


    if (!empty($_POST["mail"])) {

    } else {
    $projectID = $_GET['id'];
    $sqlProject = "SELECT * FROM projects WHERE id='$projectID'";
    $resProject = $conn->query($sqlProject);
    $row = $resProject->fetch_array();

    if ($projectID && $activeUser) {
        $sqlComments = "SELECT id FROM projects_comments WHERE related = ?";
        $stmtComments = $conn->prepare($sqlComments);
        $stmtComments->bind_param("i", $projectID);
        $stmtComments->execute();
        $resultComments = $stmtComments->get_result();

        $commentIds = [];
        while ($rowReaded = $resultComments->fetch_assoc()) {
            $commentIds[] = $rowReaded['id'];
        }

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

    $client = $row['client_id'];
    
    // Sorguyu hazırlayın
    $sqlClientName = "SELECT username FROM users_client WHERE id = ?";
    $stmtClientName = $conn->prepare($sqlClientName);
    if ($stmtClientName) {
        // Değeri bağlayın ve sorguyu çalıştırın
        $stmtClientName->bind_param("i", $client);
        $stmtClientName->execute();
        
        // Sonuçları alın
        $resultClientName = $stmtClientName->get_result();
        if ($resultClientName->num_rows > 0) {
            $rowClientName = $resultClientName->fetch_assoc();
            $clientName = $rowClientName['username']; // Username değeri $username değişkenine atanıyor
        } else {
            $clientName = null; // Eğer kullanıcı bulunmazsa null atanır
        }
        
        // Belleği temizleyin
        $stmtClientName->close();
    } else {
        $clientName = null; // Sorgu başarısız olursa null atanır
    }
    
    
    $projectTypeID = $row['projectType'];
    $projectType = getProjectTypeValue($projectTypeID, $conn);

    $sqlProjectCom = "SELECT * FROM projects_comments WHERE related='$projectID' ORDER BY dateCreate DESC";
    $resProjectCom = $conn->query($sqlProjectCom);

    $currentCom = 0;
    $numCom = $resProjectCom->num_rows;
    
    // START Proje ile ilgili olan bildirimleri okundu ve ses çalındı işaretliyor
        $sessionUserID = $_SESSION['userID']; // Oturumdaki kullanıcı ID'si
        
        // SQL sorgusu
        $sqlCommendRead = "
            UPDATE nt_comments
            INNER JOIN projects_comments
            ON nt_comments.comment_id = projects_comments.id
            SET nt_comments.soundNotify = 1, nt_comments.readed = 1
            WHERE projects_comments.related = ? AND nt_comments.username = ?
        ";
        
        // Hazırlık ve yürütme
        $stmtCommendRead = $conn->prepare($sqlCommendRead);
        $stmtCommendRead->bind_param('ii', $projectID, $sessionUserID);
        
        if ($stmtCommendRead->execute()) {
            
        } else {
            echo "Error updating records: " . $stmtCommendRead->error;
        }
        
        // END
    ?>
    <div class="grid grid-cols-1 gap-6 pt-2 lg:grid-cols-3">
        <div class="flex flex-col lg:col-span-2 gap-5">
            <div class="panel flex justify-between" style="align-items: center;">
                <div class="ml-2">
                    <?php
                        $status = $row["status_invoice"];
                        $title = $status == 0 ? "Fatura Yok" : "Fatura Hazır";
                        $bgClass = $status == 0 ? "bg-danger/20 text-danger" : "bg-success/20 text-success";
                    ?>
                    <div>
                        <div style="display: flex;gap: .3rem;margin-bottom: .3rem;align-items: center;">
                            <a title="<?php echo $title; ?>" 
                               class="flex items-center rounded-full px-2 py-1 text-xs font-semibold <?php echo $bgClass; ?>"
                               style="text-wrap: nowrap;width: 1.5rem;height: 1.5rem;">
                                <i class="fa-solid fa-file-invoice"></i>
                            </a>
                            
                            <h2 style="font-size: x-large;">
                                <strong><?php echo $row['title'];?></strong>
                            </h2>
                        </div>
                    </div>
                    <?php echo $row['status'];?>
                    <h2 style="font-size: medium;margin-top:.25rem"><?php echo $clientName .' - '. $projectType;?></h2>
                </div>
                
<div class="flex" id="employeesDIV" style="align-items: center;gap:2px">
    <div class="profile-container" id="profileContainer" style="margin-bottom: 0px;align-items: center;">
        <?php
        // projects_assignees tablosundan atanan kişileri ac_users tablosuyla birleştirerek al
        $sqlAssignees = "
            SELECT u.id, u.profilePicture, CONCAT(u.name, ' ', u.surname) AS full_name
            FROM projects_assignees pa
            JOIN ac_users u ON pa.user_id = u.id
            WHERE pa.project_id = '$projectID'
        ";

        $resAssignees = $conn->query($sqlAssignees);
        if (!$resAssignees) {
            echo 'Hata: ' . $conn->error;
        } else {
            if ($resAssignees->num_rows > 0) {
                while ($rowAssignee = $resAssignees->fetch_assoc()) {
                    echo '
                        <div class="profile" data-user-id="' . $rowAssignee['id'] . '">
                            <img class="h-9 w-9 mx-auto rounded-full object-cover" style="border: 1px solid #ececec; padding: 2px;" src="' . $rowAssignee['profilePicture'] . '" alt="' . $rowAssignee['full_name'] . '">
                            <span class="remove-user" data-user-id="' . $rowAssignee['id'] . '">&times;</span>
                            <span class="tooltip">' . $rowAssignee['full_name'] . '</span>
                        </div>
                    ';
                }
            } else {
                echo '<p>Bu proje için atanan kişi bulunamadı.</p>';
            }
        }
        ?>
    </div>

    <!-- Görevli Ekle Input'u (başlangıçta gizli) -->
    <div id="employeeInputContainer" style="display:none;">
        <input type="text" id="employeeSearch" placeholder="Görevli Ekle" class="form-input">
        <input type="hidden" id="employeeId">
    </div>

    <!-- Görevli Ekle Butonu -->
    <button id="toggleEmployeeInput" class="btn btn-primary h-9 w-9 mx-auto rounded-full" style="border: 1px solid #ececec; padding: 2px;">+</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script>
$(document).ready(function() {
    // + Butonuna tıklayınca görevli ekleme input'unu açıp kapatma
    $('#toggleEmployeeInput').click(function() {
        $('#employeeInputContainer').toggle(); // Görevli ekleme input'unu aç/kapat
    });

    // Görevli eklemek için autocomplete
    $('#employeeSearch').autocomplete({
        source: 'pages/manager/scrumboard/project/autocompleteEmp.php', // Autocomplete kaynağı
        minLength: 2, // En az 2 karakter girilmeden arama yapılmaz
        select: function(event, ui) {
            $('#employeeId').val(ui.item.id); // Seçilen görevlinin ID'sini saklayın
            addEmployeeToProject(ui.item.id, ui.item.label, ui.item.profilePicture); // Yeni görevliyi ekle
        }
    });

    // Görevli ekleme fonksiyonu
    function addEmployeeToProject(userId, username, profilePicture) {
        $.ajax({
            url: 'pages/manager/scrumboard/project/add_employee_to_project.php', // Backend'e görevli ekleme isteği
            type: 'POST',
            data: { projectId: <?php echo json_encode($projectID); ?>, userId: userId },
            success: function(response) {
                var res = JSON.parse(response);
                $('#employeeSearch').val(''); // Input temizleme

                if (res.status === 'success') {
                    // SweetAlert başarı mesajı (sağ üstte küçük)
                    Swal.fire({
                        icon: 'success',
                        title: 'Görevli başarıyla eklendi!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    var profileHtml = `
                        <div class="profile" data-user-id="${userId}">
                            <img  class="h-9 w-9 mx-auto rounded-full object-cover" style="border: 1px solid #ececec; padding: 2px;" src="${profilePicture}" alt="${username}">
                            <span class="remove-user" data-user-id="${userId}">&times;</span>
                            <span class="tooltip">${username}</span>
                        </div>
                    `;
                    $('#profileContainer').append(profileHtml); // Yeni görevliyi ekleyin
                } else {
                    // SweetAlert hata mesajı (sağ üstte küçük)
                    Swal.fire({
                        icon: 'error',
                        title: 'Görevli eklenemedi!',
                        text: res.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
        });
    }

    // Görevli silme işlevselliği
    $(document).on('click', '.remove-user', function() {
        var userId = $(this).data('user-id');

        // Silme onayı
        Swal.fire({
            title: 'Bu görevlendirmeyi silmek istediğinize emin misiniz?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'Hayır',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'pages/manager/scrumboard/project/remove_employee_from_project.php', // Backend'e silme isteği
                    type: 'POST',
                    data: { projectId: <?php echo json_encode($projectID); ?>, userId: userId },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            // SweetAlert başarı mesajı (sağ üstte küçük)
                            Swal.fire({
                                icon: 'success',
                                title: 'Görevli başarıyla silindi!',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            $(`div[data-user-id="${userId}"]`).remove(); // Profil div'ini sil
                        } else {
                            // SweetAlert hata mesajı (sağ üstte küçük)
                            Swal.fire({
                                icon: 'error',
                                title: 'Görevli silinemedi!',
                                text: res.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            }
        });
    });
});
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
                
            </div>
            <div class="panel">
                <div class="ml-2">
                    <h2 style="font-size: medium;"><strong>Açıklama</strong></h2>
                    <h2 style="font-size: medium;"><?php echo $descripton ;?></h2>
                </div>
            </div>
        </div>

        <div class="panel" style="height:100%">
            <div class="-mx-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 pt-0 dark:border-[#1b2e4b] dark:text-white-light">
                <h5 class="text-lg font-semibold" id="tabTitles">Yorumlar</h5>
                <div x-data="dropdown" @click.outside="open = false" class="dropdown" id="selectTab">
                    <a href="javascript:;" @click="toggle">
                        <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                            <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                            <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                        </svg>
                    </a>
                    <ul x-show="open" x-transition="" x-transition.duration.300ms="" class="ltr:right-0 rtl:left-0" style="display: none;">
                        <li><a href="#" id="tabCommentsBtn">Yorumlar</a></li>
                        <li><a href="#" id="tabActivityBtn">Aktiviteler</a></li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col gap-2 mt-2" id="tabComments">

                <!-- Yorum Ekleme -->
                <div class="commentAdd-input-container">
                    <textarea id="commentAddText" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" rows="4"></textarea>
                    <div class="attach-icon" id="attachIcon">
                        <button type="button" class="btn-commentmodal btn btn-primary gap-2"><i class="fa-solid fa-paperclip"></i></button>
                        <div class="attach-options" id="attachOptions">
                            <button class="attach-option">
                                <label for="attachPhotos">
                                    <i class="fa-solid fa-photo-film"></i>
                                    <span>Fotoğraf</span>
                                </label>
                                <input type="file" id="attachPhotos" accept="image/*" multiple>
                            </button>
                            <div class="attach-option">
                                <label for="attachDocuments">
                                    <i class="fa-solid fa-file"></i>
                                    <span>Belge</span>
                                </label>
                                <input type="file" id="attachDocuments" multiple>
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
                <div class="flex flex-col gap-2" id="commentsContainer">

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
    var lastCommentCount = 0; // Son yorum sayısı

    function loadComments() {
        $.ajax({
            url: 'pages/manager/scrumboard/modules/load_comments.php',
            type: 'GET',
            data: {
                id: <?php echo json_encode($projectID); ?>,
                commentCount: lastCommentCount // Son bilinen yorum sayısını gönderiyoruz
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    if (data.commentCount != lastCommentCount) {
                        $('#commentsContainer').html(data.comments); // Yorumları güncelle
                        lastCommentCount = data.commentCount; // Son yorum sayısını güncelle
                    }
                } catch (error) {
                    console.error('JSON parsing error:', error);
                }
            },
            error: function(xhr, status, error) {
                console.error('Yorumları yüklerken bir hata oluştu:', xhr, status, error);
            }
        });
    }

    loadComments(); // Sayfa yüklendiğinde yorumları getir
    setInterval(loadComments, 1000); // 1 saniyede bir kontrol et

                        
    // Attach icon tıklanınca aç/kapat
    $('#attachIcon').click(function(event) {
        event.stopPropagation(); // Attach icon'a tıklanıldığında olayın dışarı yayılmasını engeller
        $('#attachOptions').toggleClass('show');
    });

    // Sayfanın herhangi bir yerine tıklanınca eğer menu açıksa kapat
    $(document).click(function(event) {
        if (!$(event.target).closest('#attachIcon').length && !$(event.target).closest('#attachOptions').length) {
            // Attach icon veya attach-options dışında bir yere tıklandığında menüyü kapat
            $('#attachOptions').removeClass('show');
        }
    });

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

                        $('#attachPhotos').change(function() {
                            previewFiles(this);
                        });

                        $('#attachDocuments').change(function() {
                            previewFiles(this);
                        });

                        $('#attachAudios').change(function() {
                            previewFiles(this);
                        });

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

$('#saveCommentAdd').click(function() {
    var commentAddText = $('#commentAddText').val();
    var commentAddPhotos = $('#attachPhotos')[0].files;
    var commentAddDocuments = $('#attachDocuments')[0].files;
    var commentAddAudios = $('#attachAudios')[0].files;
    var projectId = <?php echo json_encode($projectID); ?>;

    if (!commentAddText && commentAddPhotos.length === 0 && commentAddDocuments.length === 0 && commentAddAudios.length === 0) {
        alert('Lütfen bir yorum veya dosya ekleyiniz.');
        return;
    }

    var formData = new FormData();
    formData.append('id', projectId);
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

    $.ajax({
        url: 'pages/manager/scrumboard/modules/save_comment.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status === 'success') {
                // Yorum başarıyla eklendikten sonra SweetAlert göster
                Swal.fire({
                    icon: 'success',
                    title: 'Yorum Eklendi!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500
                });

                // Formu ve dosya önizlemelerini temizle
                $('#commentAddText').val('');
                $('#attachPhotos').val('');
                $('#attachDocuments').val('');
                $('#attachAudios').val('');
                $('#filePreview').html('');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Yorum Eklenemedi!',
                    text: response.message
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Hata detayları:', xhr, status, error);
            Swal.fire({
                icon: 'error',
                title: 'Bir hata oluştu!',
                text: xhr.responseText
            });
        }
    });
});


$(document).on('click', '.delete-comment', function() {
    var commentId = $(this).data('comment-id');
    if (confirm('Bu yorumu silmek istediğinize emin misiniz?')) {
        $.ajax({
            url: 'pages/manager/scrumboard/modules/delete_comment.php',
            type: 'POST',
            data: { commentId: commentId },
            success: function(response) {
                if (response.status === 'success') {
                    // SweetAlert ile silindi mesajı göster
                    Swal.fire({
                        icon: 'success',
                        title: 'Yorum Silindi!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // Yorumları tekrar yükle
                    loadComments();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Yorum Silinemedi!',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Hata detayları:', xhr, status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'Bir hata oluştu!',
                    text: xhr.responseText
                });
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
                }}
                ?>
                </div>
            </div>
            
            

            <div class="flex flex-col gap-2 mt-2" id="tabActivity">
                   <style>
                       .icon-circle {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            width: 40px; /* Sabit genişlik */
                            height: 40px; /* Sabit yükseklik */
                            border-radius: 50%; /* Tamamen yuvarlak */
                            font-size: 20px;
                            border: 1px solid #ececec;
                            padding: 2px;
                            flex-shrink: 0; /* Daralmayı engelle */
                        }
                    </style>
                
<?php
// log_projects_assignees ve diğer tabloları birleştirerek verileri çek
$queryActivityLog = "
-- Görevli değişikliği aktiviteleri
SELECT 
    lpa.user_id AS user_id,
    lpa.related_user_id AS related_user_id,
    lpa.request_type AS action,
    lpa.dateCreate AS dateCreate,
    u1.name AS user_name,
    u1.surname AS user_surname,
    u2.name AS related_user_name,
    u2.surname AS related_user_surname,
    'assignee_change' AS activity_type,
    NULL AS old_status,
    NULL AS new_status,
    NULL AS invoice_id
FROM 
    log_projects_assignees lpa
JOIN 
    ac_users u1 ON lpa.user_id = u1.id
JOIN 
    ac_users u2 ON lpa.related_user_id = u2.id
WHERE 
    lpa.project_id = ?

UNION

-- Proje durumu değişikliği aktiviteleri
SELECT 
    lp.user AS user_id,
    NULL AS related_user_id,
    NULL AS action,
    lp.time AS dateCreate,
    u.name AS user_name,
    u.surname AS user_surname,
    NULL AS related_user_name,
    NULL AS related_user_surname,
    'status_change' AS activity_type,
    lp.old AS old_status,
    lp.new AS new_status,
    NULL AS invoice_id
FROM 
    log_projects lp
JOIN 
    ac_users u ON lp.user = u.id
WHERE 
    lp.project_id = ?

UNION

-- Proje oluşturulma aktivitesi
SELECT 
    p.creator AS user_id,
    NULL AS related_user_id,
    NULL AS action,
    p.dateCreate AS dateCreate,
    u.name AS user_name,
    u.surname AS user_surname,
    NULL AS related_user_name,
    NULL AS related_user_surname,
    'project_created' AS activity_type,
    NULL AS old_status,
    NULL AS new_status,
    NULL AS invoice_id
FROM 
    projects p
JOIN 
    ac_users u ON p.creator = u.id
WHERE 
    p.id = ?

UNION

-- Fatura ile ilgili aktiviteler
SELECT 
    lpi.user_id AS user_id,
    NULL AS related_user_id,
    lpi.request_type AS action,
    lpi.created_at AS dateCreate,
    u.name AS user_name,
    u.surname AS user_surname,
    NULL AS related_user_name,
    NULL AS related_user_surname,
    'invoice_action' AS activity_type,
    NULL AS old_status,
    NULL AS new_status,
    lpi.invoice_id AS invoice_id
FROM 
    log_projects_invoices lpi
JOIN 
    ac_users u ON lpi.user_id = u.id
WHERE 
    lpi.project_id = ?

ORDER BY 
    dateCreate DESC;
";

// Sorguyu çalıştırmak için parametreler ekle
$stmt = $conn->prepare($queryActivityLog);
if (!$stmt) {
    die('Sorgu hatası: ' . $conn->error);
}
$stmt->bind_param("iiii", $projectID, $projectID, $projectID, $projectID);
$stmt->execute();
$resultActivityLog = $stmt->get_result();

if (!$resultActivityLog) {
    die('Sorgu sonucu hatası: ' . $conn->error);
}

// Verileri ekrana yazdır
if ($resultActivityLog->num_rows > 0) {
    while ($rowActivityLog = $resultActivityLog->fetch_assoc()) {
        $date = new DateTime($rowActivityLog['dateCreate']);
        $formattedDate = formatDateTimeRelative($date); // Tarihi istediğin formata göre düzenleyebilirsin

        $userInitial = mb_substr($rowActivityLog['user_surname'], 0, 1, 'UTF-8') . '.';
        $relatedUserInitial = $rowActivityLog['related_user_surname'] ? mb_substr($rowActivityLog['related_user_surname'], 0, 1, 'UTF-8') . '.' : '';

        if ($rowActivityLog['activity_type'] == 'project_created') {
            // Proje oluşturulma durumu (Primary renk)
            echo '<div style="display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center; justify-content: flex-start;">
                <i class="btn btn-primary icon-circle" style="margin-right: 0.25rem;">+</i>
                <div><p><b>' . $rowActivityLog['user_name'] . ' ' . $userInitial . '</b> projeyi oluşturdu.</p><p style="font-size: smaller;">' . $formattedDate . '</p></div></div>';
        } elseif ($rowActivityLog['activity_type'] == 'status_change') {
            // Proje durumu değişikliği
            echo '<div style="display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center; justify-content: flex-start;">
                <i class="btn btn-primary icon-circle" style="margin-right: 0.25rem;">~</i>
                <div><p><b>' . $rowActivityLog['user_name'] . ' ' . $userInitial . '</b> projeyi <b>' . $rowActivityLog['old_status'] . '</b> durumundan <b>' . $rowActivityLog['new_status'] . '</b> durumuna taşıdı.</p><p style="font-size: smaller;">' . $formattedDate . '</p></div></div>';
        } elseif ($rowActivityLog['activity_type'] == 'assignee_change') {
            // Görevli ekleme/çıkarma durumu
            $action = ($rowActivityLog['action'] == 'add') ? 'projeye eklendi.' : 'projeden çıkarıldı.';
            $icon = ($rowActivityLog['action'] == 'add') ? 
                '<i class="btn btn-success icon-circle" style="margin-right: 0.25rem;">+</i>' :
                '<i class="btn btn-danger icon-circle" style="margin-right: 0.25rem;">-</i>';

            echo '<div style="display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center; justify-content: flex-start;">'.$icon.'<div><p><b>' . $rowActivityLog['related_user_name'].' '.$relatedUserInitial.'</b> '.$action.'</p><p style="font-size: smaller;">'. $rowActivityLog['user_name'] .' '. $userInitial .' '. $formattedDate . '</p></div></div>';
        } elseif ($rowActivityLog['activity_type'] == 'invoice_action') {
            // Fatura ekleme/çıkarma durumu
            $action = ($rowActivityLog['action'] == 'add') ? 
                ' no\'lu faturaya bu projeyi ekledi.' : 
                ' no\'lu faturadan bu projeyi çıkardı.';
            $icon = ($rowActivityLog['action'] == 'add') ? 
                '<i class="btn btn-success icon-circle" style="margin-right: 0.25rem;">+</i>' :
                '<i class="btn btn-danger icon-circle" style="margin-right: 0.25rem;">-</i>';

            echo '<div style="display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center; justify-content: flex-start;">'.$icon.'<div><p><b>' . $rowActivityLog['user_name'] . ' ' . $userInitial . '</b> ' . '<a href="invoice?id=' . $rowActivityLog['invoice_id'] . ' "> #' . $rowActivityLog['invoice_id'] . '</a>'. $action . '</p><p style="font-size: smaller;">' . $formattedDate . '</p></div></div>';
        }
    }
} else {
    echo "Veri bulunamadı.";
}
?>



                
            </div>
            
<script>
    $(document).ready(function() {
        // Varsayılan olarak tabComments görünsün, tabActivity gizli olsun
        $('#tabComments').show();
        $('#tabActivity').hide();
        updateTabTitle('Yorumlar'); // Başlangıçta başlığı "Yorumlar" yap

        // tabCommentsBtn'ye tıklandığında tabComments görünsün, tabActivity gizlensin
        $('#tabCommentsBtn').click(function() {
            $('#tabComments').show();
            $('#tabActivity').hide();
            $('#selectTab ul').hide(); // Dropdown'u kapat
            updateTabTitle('Yorumlar'); // Başlığı "Yorumlar" yap
        });

        // tabActivityBtn'ye tıklandığında tabActivity görünsün, tabComments gizlensin
        $('#tabActivityBtn').click(function() {
            $('#tabActivity').show();
            $('#tabComments').hide();
            $('#selectTab ul').hide(); // Dropdown'u kapat
            updateTabTitle('Aktiviteler'); // Başlığı "Aktiviteler" yap
        });

        // Başlığı güncelleyen fonksiyon
        function updateTabTitle(title) {
            $('#tabTitles').text(title); // h5 başlığını güncelle
        }
    });
</script>

        </div>
    </div>

                <div id="photoModal" class="modalBigPhoto" style="display: none;">
                    <span class="close" onclick="closeModal()">×</span>
                    <img class="modalBigPhoto-content" id="photoModalContent" src="">
                    <div class="caption" id="photoCaption"></div>
                </div>

                <script>
                    function openModal(src) {
                        document.getElementById('photoModalContent').src = src;
                        document.getElementById('photoCaption').innerHTML = 'Comment Photo';
                        document.getElementById('photoModal').style.display = 'flex';
                    }

                    function closeModal() {
                        document.getElementById('photoModal').style.display = 'none';
                    }
                </script>

                <style>
                    .modalBigPhoto {
                        display: none;
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

                <div id="commentsContainer"></div>

            </div>
