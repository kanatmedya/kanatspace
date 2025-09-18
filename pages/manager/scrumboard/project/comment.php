<?php

$date = new DateTime($rowCom['dateCreate']);
$user = $rowCom['user'];
$activeUserID = $_SESSION['userID'];
$context = $rowCom['value'];

// Kullanıcı profil fotoğrafı
$profilePicture = '';

    $query = "SELECT profilePicture FROM ac_users WHERE id = '$user'";
    $result = mysqli_query($conn, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $profilePicture = $row['profilePicture'];
    }

// Fotoğraf yolları
$photos = !empty($rowCom['photos']) ? json_decode($rowCom['photos'], true) : [];
// Belge yolları
$documents = !empty($rowCom['documents']) ? json_decode($rowCom['documents'], true) : [];
// Ses dosyaları yolları
$audios = !empty($rowCom['audios']) ? json_decode($rowCom['audios'], true) : [];

// SQL sorgusu
$sqlProjectCom44 = "SELECT * FROM ac_users WHERE id = ?";

// Hazırlanmış ifade (prepared statement) kullan
$stmtProjectCom44 = $conn->prepare($sqlProjectCom44);

// Parametreyi bağla (related alanına $projectID değerini bağlama)
$stmtProjectCom44->bind_param("i", $user);

// Sorguyu çalıştır
$stmtProjectCom44->execute();

// Sonuçları al
$resProjectCom44 = $stmtProjectCom44->get_result();

// Sonuçları kullanma veya ekrana yazdırma
while ($rowUser44 = $resProjectCom44->fetch_assoc()) {
    $fullName = $rowUser44['name'] . ' ' . $rowUser44['surname'];
    $profile = $rowUser44['profilePicture'];
}


if ($user == $activeUserID): ?>
<div class="comment sm:flex" style="flex-direction: row-reverse;">
    <div class="relative ml-3 mr-1">
        <img src="<?php echo htmlspecialchars($profilePicture ?: '/uploads/users/profile/default.jpg'); ?>" alt="image" class="mx-auto rounded-full"  style="max-width:32px"/>
    </div>
    <div class="flex" style="flex-direction: column;align-items: flex-end;word-break: break-all; overflow-wrap: break-word;">
        <div style="background:#ececec;padding:5px;align-items: center;border-radius: 10px;">
            <p class="">
                <?php echo nl2br(convertRichText(htmlspecialchars($context))); ?>
            </p>
            <?php if (!empty($photos)): ?>
            <div class="grid mb-2 grid-cols-3 sm:grid-cols-5 lg:grid-cols-3 xl:grid-cols-5 gap-3 mt-3">
                <?php foreach ($photos as $photo): ?>
                <img src="<?php echo htmlspecialchars($photo); ?>" alt="image"
                    class="w-full rounded-md relative top-0 transition-all duration-300 hover:-top-0.5 hover:shadow-none photo-thumbnail"
                    onclick="openModal('<?php echo htmlspecialchars($photo); ?>')" />
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if (!empty($documents)): ?>
            <div>
                <?php foreach ($documents as $document): ?>
                <a href="<?php echo htmlspecialchars($document['url']); ?>" target="_blank">📄 <?php echo htmlspecialchars($document['name']); ?></a><br>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if (!empty($audios)): ?>
            <div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                <?php foreach ($audios as $audio): ?>
                <audio controls>
                    <source src="<?php echo htmlspecialchars($audio); ?>" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php if ($user == $activeUser || $_SESSION['status'] == '1'): ?>
        <span>
            <button class="edit text-danger delete-comment" data-comment-id="<?php echo $rowCom['id']; ?>" style="font-size:x-small;margin-left:5px">
                Sil
            </button>
            <button class="edit text-primary" style="font-size:x-small">
                Düzenle
            </button>
            <span style="font-size:x-small;margin-left:5px">
                <?php echo formatDateTimeRelative($date); ?>
            </span>
        </span>
        <?php endif; ?>
    </div>
</div>

<?php else: ?>
<div class="comment sm:flex">
    <div class="relative mx-auto mb-5 sm:mb-0 ltr:sm:mr-3 rtl:sm:ml-3 z-[2]">
        <img src="<?php echo $profile; ?>" alt="image"
            class="mx-auto rounded-full" style="max-width:32px"/>
    </div>
    <div class="flex-1">
        <p class="flex text-center text-base ltr:sm:text-left rtl:sm:text-right"
            style="justify-content: space-between;">
        </p>
        <div style="background:rgb(209, 231, 221,0.5);padding:5px;align-items: center;border-radius: 10px;">
            <p class="text-white-dark dark:text-white-light notification dark:text-white-light">
                <?php echo nl2br(convertRichText(htmlspecialchars($context))); ?>
            </p>
            <?php if (!empty($photos)): ?>
            <div class="grid mb-2 grid-cols-3 sm:grid-cols-5 lg:grid-cols-3 xl:grid-cols-5 gap-3 mt-3">
                <?php foreach ($photos as $photo): ?>
                <img src="<?php echo htmlspecialchars($photo); ?>" alt="image"
                    class="w-full rounded-md relative top-0 transition-all duration-300 hover:-top-0.5 hover:shadow-none photo-thumbnail"
                    onclick="openModal('<?php echo htmlspecialchars($photo); ?>')" />
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if (!empty($documents)): ?>
            <div>
                <?php foreach ($documents as $document): ?>
                <a href="<?php echo htmlspecialchars($document['url']); ?>" target="_blank">📄 <?php echo htmlspecialchars($document['name']); ?></a><br>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if (!empty($audios)): ?>
            <div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                <?php foreach ($audios as $audio): ?>
                <audio controls>
                    <source src="<?php echo htmlspecialchars($audio); ?>" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php if ($user == $activeUser || $_SESSION['status'] == '1'): ?>
        <span style="font-size:x-small">
            <span>
                <strong>
                    <?php echo $fullName; ?>
                </strong>
                <?php echo formatDateTimeRelative($date); ?>
            </span>
            <button class="edit text-primary ml-10" style="margin-left:5px">
                Düzenle
            </button>
            <button class="edit text-danger delete-comment" data-comment-id="<?php echo $rowCom['id']; ?>" style="font-size:x-small;margin-left:5px">
                Sil
            </button>
        </span>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

