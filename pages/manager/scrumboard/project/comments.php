<?php
// Önceki yorum verileri için değişkenler
$previousUser = null;
$previousTime = null;

$date = new DateTime($rowCom['dateCreate']);
$user = $rowCom['user'];
$activeUserID = $_SESSION['userID'];
$context = $rowCom['value'];

// Kullanıcı profil fotoğrafı
$profilePicture = '';

// Profil fotoğrafı ve kullanıcı bilgilerini alalım
$query = "SELECT profilePicture, name, surname FROM ac_users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $profilePicture = $row['profilePicture']; // Profil fotoğrafı burada alınır
    $firstName = $row['name'];
    $lastNameInitial = substr($row['surname'], 0, 1) . '.';
    $displayName = $firstName . ' ' . $lastNameInitial;
}

// Fotoğraf yolları
$photos = !empty($rowCom['photos']) ? json_decode($rowCom['photos'], true) : [];
// Belge yolları
$documents = !empty($rowCom['documents']) ? json_decode($rowCom['documents'], true) : [];
// Ses dosyaları yolları
$audios = !empty($rowCom['audios']) ? json_decode($rowCom['audios'], true) : [];

// Kullanıcı kimliği kontrolü
$isCurrentUser = ($user == $activeUserID);
$isAdmin = ($_SESSION['status'] == '1');

// Yorum bloğu
$showDate = true;

// Eğer önceki kullanıcı ile aynıysa ve zaman farkı dakikalar bazında aynıysa tarih göstermemek
if ($previousUser === $user && $previousTime !== null && $previousTime->format('Y-m-d H:i') === $date->format('Y-m-d H:i')) {
    $showDate = false;
} else {
    $showDate = true;
}

// Mevcut kullanıcı ve zamanı kaydet
$previousUser = $user;
$previousTime = $date;

?>
<div class="comment sm:flex" style="flex-direction: <?php echo $isCurrentUser ? 'row-reverse' : 'row'; ?>;">
    <?php if (!$isCurrentUser && !empty($profilePicture)): ?>
        <div class="relative ml-3 mr-1">
            <img src="<?php echo htmlspecialchars($profilePicture ?: '/uploads/users/profile/default.jpg'); ?>" alt="image"
                 class="h-9 w-9 mx-auto rounded-full object-cover"  
                 style="border: 1px solid #ececec; padding: 2px;"/>
        </div>
    <?php endif; ?>

    <div class="flex" style="flex-direction: column; align-items: <?php echo $isCurrentUser ? 'flex-end' : 'flex-start'; ?>; word-break: break-all; overflow-wrap: break-word; max-width: calc(100% - 52px);">
        <div style="background:<?php echo $isCurrentUser ? '#ececec' : 'rgb(209, 231, 221, 0.5)'; ?>; padding:5px; border-radius: 10px;">
            <p><?php echo nl2br(convertRichText(htmlspecialchars($context))); ?></p>

            <?php if (!empty($photos)): ?>
                <div class="grid mb-2 grid-cols-3 sm:grid-cols-5 lg:grid-cols-3 xl:grid-cols-5 gap-3 mt-3">
                    <?php foreach ($photos as $photo): ?>
                        <img src="<?php echo htmlspecialchars($photo); ?>" alt="image"
                             class="w-full min-w-[100px] max-w-[200px] rounded-md relative top-0 transition-all duration-300 hover:-top-0.5 hover:shadow-none photo-thumbnail"
                             onclick="openModal('<?php echo htmlspecialchars($photo); ?>')"/>
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

        <?php if ($isCurrentUser): ?>
    <span style="font-size:x-small;">
        <button class="edit text-danger delete-comment" data-comment-id="<?php echo $rowCom['id']; ?>" style="font-size:x-small;margin-left:5px">Sil</button>
        <button class="edit text-primary ml-10" style="margin-left:5px">Düzenle</button>
        <?php if ($showDate): ?>
            <?php echo formatDateTimeRelative($date); ?>
        <?php endif; ?>
    </span>
<?php elseif (isset($_SESSION['status']) && $_SESSION['status'] == 1): ?>
    <span style="font-size:x-small;">
        <strong><?php echo htmlspecialchars($displayName); ?></strong>
        <?php if ($showDate): ?>
            <?php echo formatDateTimeRelative($date); ?>
        <?php endif; ?>
        <button class="edit text-primary ml-10" style="margin-left:5px">Düzenle</button>
        <button class="edit text-danger delete-comment" data-comment-id="<?php echo $rowCom['id']; ?>" style="font-size:x-small;margin-left:5px">Sil</button>
    </span>
<?php else: ?>
    <span style="font-size:x-small;">
        <strong><?php echo htmlspecialchars($displayName); ?></strong>
        <?php if ($showDate): ?>
            <?php echo formatDateTimeRelative($date); ?>
        <?php endif; ?>
    </span>
<?php endif; ?>

    </div>
</div>
