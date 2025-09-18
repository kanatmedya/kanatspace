<?php

$date = new DateTime($rowCom['dateCreate']);
$user = $rowCom['user'];
$context = $rowCom['value'];

// Kullanıcı profil fotoğrafı
$profilePicture = '';
foreach (['users_manager', 'users_employee', 'users_manager'] as $table) {
    $query = "SELECT profilePicture FROM $table WHERE username = '$user'";
    $result = mysqli_query($conn, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $profilePicture = $row['profilePicture'];
        break;
    }
}

// Fotoğraf yolları
$photos = !empty($rowCom['photos']) ? json_decode($rowCom['photos'], true) : [];
// Belge yolları
$documents = !empty($rowCom['documents']) ? json_decode($rowCom['documents'], true) : [];
// Ses dosyaları yolları
$audios = !empty($rowCom['audios']) ? json_decode($rowCom['audios'], true) : [];

if ($user == $activeUser): ?>
<div class="comment sm:flex text-right rtl:sm:text-left" style="direction: rtl;">
    <div class="relative ml-3 mr-1">
        <img src="<?php echo htmlspecialchars($profilePicture ?: '/uploads/users/profile/default.jpg'); ?>" alt="image" class="w-8 h-8 mx-auto rounded-full" />
    </div>
    <div class="flex-1">
        <div style="background:#ececec;padding:5px;align-items: center;border-radius: 10px;">
            <p class="text-white-dark font-semibold">
                <?php echo htmlspecialchars($context); ?>
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
            <span style="font-size:x-small;margin-left:5px">
                <?php echo formatDateTimeRelative($date); ?>
            </span>
            <button class="edit text-primary" style="font-size:x-small">
                Düzenle
            </button>
            <button class="edit text-danger delete-comment" data-comment-id="<?php echo $rowCom['id']; ?>" style="font-size:x-small;margin-left:5px">
                Sil
            </button>
        </span>
        <?php endif; ?>
    </div>
</div>

<?php else: ?>
<div class="comment sm:flex">
    <div class="relative mx-auto mb-5 sm:mb-0 ltr:sm:mr-3 rtl:sm:ml-3 z-[2]">
        <img src="<?php echo htmlspecialchars($profilePicture ?: '/uploads/users/profile/default.jpg'); ?>" alt="image"
            class="w-8 h-8 mx-auto rounded-full" />
    </div>
    <div class="flex-1">
        <p class="flex text-center text-base ltr:sm:text-left rtl:sm:text-right"
            style="justify-content: space-between;">
        </p>
        <div style="background:rgb(209, 231, 221,0.5);padding:5px;align-items: center;border-radius: 10px;">
            <p class="text-white-dark font-semibold">
                <?php echo htmlspecialchars($context); ?>
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
                    <?php echo htmlspecialchars($user); ?>
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

