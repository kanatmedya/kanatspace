<?php
include ('./assets/db/db_connect.php'); // Tam yolu belirtiyoruz

// Session'daki kullanıcı adını alın
$username = $_SESSION['name'];

// Geçerli tarihi ve bir hafta önceki tarihi alın
$currentDate = date('Y-m-d H:i:s');
$oneWeekAgo = date('Y-m-d H:i:s', strtotime('-1 week'));

$query = "SELECT * FROM notes WHERE user = '$username' AND (status = 1 OR (status = 0 AND dateUpdate > '$oneWeekAgo')) ORDER BY status ASC, orderNumber DESC, dateUpdate DESC";
$result = mysqli_query($conn, $query);
?>
<div class="panel h-full">
    <div
        class="-mx-5 mb-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 pt-0 dark:border-[#1b2e4b] dark:text-white-light">
        <h5 class="text-lg font-semibold">Not</h5>
        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
            <a href="javascript:;" @click="toggle">
                <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70" viewBox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"></circle>
                </svg>
            </a>
            <ul x-show="open" x-transition="" x-transition.duration.300ms="" class="ltr:right-0 rtl:left-0"
                style="display: none;">
                <li><a href="javascript:;" @click="toggle">View All</a></li>
                <li><a href="javascript:;" @click="toggle">Mark as Read</a></li>
            </ul>
        </div>
    </div>
    <div class="grid" style="width:100%">
        <input required="" type="text" name="title" placeholder="Notunuz..." id="note-input" class="form-input flex-1">
    </div>
    <div class="perfect-scrollbar relative mt-5 -mr-3 h-[360px] pr-3 ps ps--active-y">
        <div class="space-y-7">
            <div id="note-list">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="flex items-center mb-2" data-id="<?= $row['id']; ?>">
                        <input type="checkbox" class="mr-2" <?= $row['status'] == 1 ? 'checked' : ''; ?>
                            onclick="updateNoteStatus(<?= $row['id']; ?>)">
                        <h5 class="font-semibold dark:text-white-light"
                            style="<?= $row['status'] == 1 ? 'text-decoration: line-through; color: gray;' : ''; ?>">
                            <?= htmlspecialchars($row['note']); ?>
                        </h5>
                        <button class="ml-2" onclick="deleteNoteConfirm(<?= $row['id']; ?>)">
                            <svg class="h-5 w-5 text-red-500 hover:text-red-700" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/notes/notesUpdate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>