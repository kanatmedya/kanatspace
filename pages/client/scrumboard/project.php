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

    $client = $row['client'];
    $projectTypeID = $row['projectType'];
    $projectType = getProjectTypeValue($projectTypeID, $conn);

    $sqlProjectCom = "SELECT * FROM projects_comments WHERE related='$projectID' ORDER BY dateCreate DESC";
    $resProjectCom = $conn->query($sqlProjectCom);

    $currentCom = 0;
    $numCom = $resProjectCom->num_rows;
    ?>
    <div class="grid grid-cols-1 gap-6 pt-2 lg:grid-cols-3">
        <div class="flex flex-col lg:col-span-2 gap-5">
            <div class="panel flex justify-between" style="align-items: center;">
                <div class="ml-2">
                    <h2 style="font-size: x-large;"><strong><?php echo $row['title'];?></strong></h2>
                    <h2 style="font-size: medium;margin-top:8px"><?php echo $client .' - '. $projectType;?></h2>
                </div>
                <div class="flex">
                    <?php
                    // projects_assignees tablosundan atanan kişileri ac_users tablosuyla birleştirerek al
                    $sqlAssignees = "
    SELECT u.profilePicture 
    FROM projects_assignees pa
    JOIN ac_users u ON pa.user_id = u.id
    WHERE pa.project_id = '$projectID'
";

                    $resAssignees = $conn->query($sqlAssignees);
                    if ($resAssignees->num_rows > 0) {
                        while ($rowAssignee = $resAssignees->fetch_assoc()) {
                            if (!empty($rowAssignee['profilePicture'])) {
                                echo '<img class="h-9 w-9 rounded-full border-2 border-white object-cover transition-all duration-300 dark:border-dark" src="' . $rowAssignee['profilePicture'] . '" alt="Assignee">';
                            } else {
                                echo '<svg class="h-9 w-9 rounded-full border-2 border-white object-cover transition-all duration-300 dark:border-dark" xmlns="http://www.w3.org/2000/svg">
                <circle cx="18" cy="18" r="18" fill="#ccc" />
                <text x="16" y="20" font-size="14" text-anchor="middle" fill="#fff">?</text>
            </svg>';
                            }
                        }
                    } else {
                        echo '<p>No assignees found for this project.</p>';
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
            <div class="flex flex-col gap-2" id="commentsContainer">

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
                        // Yorumları yükle ve yenile
                        function loadComments() {
                            $.ajax({
                                url: 'pages/manager/scrumboard/modules/load_comments.php',
                                type: 'GET',
                                data: { id: <?php echo json_encode($projectID); ?> },
                                success: function(response) {
                                    $('#commentsContainer').html(response);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Yorumları yüklerken bir hata oluştu:', xhr, status, error);
                                }
                            });
                        }

                        loadComments();
                        setInterval(loadComments, 1000);

                        $('#attachIcon').click(function() {
                            $('#attachOptions').toggleClass('show');
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
                                            loadComments();
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
