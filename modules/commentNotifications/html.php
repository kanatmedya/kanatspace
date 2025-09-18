<!-- Notifications -->
<div id="notificationDropdown" class="dropdown" x-data="dropdown" @click.outside="open = false">
    <a href="javascript:;"
        class="relative block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60"
        @click="toggle">
        <!-- Notification Icon SVG -->
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M22 10C22.0185 10.7271 22 11.0542 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H13"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path
                d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <circle cx="19" cy="5" r="3" stroke="currentColor" stroke-width="1.5"></circle>
        </svg>
        <span class="absolute top-0 flex h-3 w-3 ltr:right-0 rtl:left-0" id="notification-badge"></span>
    </a>
    <ul id="notificationModal" x-cloak x-show="open" x-transition x-transition.duration.300ms
        class="top-11 w-[300px] divide-y !py-0 text-dark ltr:-right-2 rtl:-left-2 dark:divide-white/10 dark:text-white-dark sm:w-[350px]">
        <div class="flex items-center justify-between px-4 py-2 font-semibold hover:!bg-transparent">
            <h4 class="text-lg">Yorumlar</h4>
            <!-- Görülmemiş Bildirim Sayısı START //Eğer Görülmemiş Bildirim varsa yazacak -->
            <div>
                <span class="badge bg-primary/80" id="unread-count"></span>
            </div>
            <!-- Görülmemiş Bildirim Sayısı END -->
        </div>
        <div id="notification-list">
            <!-- Notifications will be injected here -->
        </div>
        <div class="p-4" style="display:flex;gap:5px">
            <button id="view-all" class="btn btn-primary btn-small block w-full"  @click="open = false">Tümünü Gör</button>
        </div>
    </ul>
</div>

<!-- Modal -->
<div id="allNotificationsModal" class="panel modalAllNotifications hidden top-11 w-[300px] divide-y !py-0 text-dark ltr:right-10 rtl:left-10 dark:divide-white/10 dark:text-white-dark sm:w-[350px] block" style="padding: .0rem !important;position: fixed;">
    <div class="px-4 py-2 font-semibold">
        <div class="text-lg" style="">
            <div style="display: flex;justify-content: space-between;">
                <div>
                    <span style=" display: block;">
                        Yorumlar
                    </span>
                </div>
                <div>
                    <span class="badge bg-primary/80" id="unread-count-modal" style="display: block;">
                        Yeni Yorum Yok
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div id="all-notifications-list" style="display: flex;flex-direction: column;overflow-y: scroll;">
        
    </div>
    
    <div class="p-4" style="display:flex;gap:5px">
        <button id="closeModal_AllComments" class="btn btn-secondary btn-small block w-full">Kapat</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const viewAllButton = document.getElementById('view-all');
        const modal = document.getElementById('allNotificationsModal');
        const modalContent = document.getElementById('all-notifications-list');
        const closeModalButton = document.getElementById('closeModal_AllComments');

        if (viewAllButton) {
            viewAllButton.addEventListener('click', () => {
                fetch('modules/commentNotifications/fetch-all-notifications.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            modalContent.innerHTML = ''; // Clear existing content

                            data.notifications.forEach(notification => {
                                const item = document.createElement('div');
                                item.className = notification.readed == 1 ? 'notification dark:text-white-light/90' : 'notification dark:text-white-light/90';
                                item.innerHTML = `
                                <div class="group flex items-center px-4 py-2">
                                    <div class="grid place-content-center rounded">
                                        <div class="relative h-12 w-12">
                                            <img class="h-12 w-12 rounded-full object-cover" src="${notification.profilePicture}" alt="${notification.author} Profil Fotoğrafı" />
                                        </div>
                                    </div>
                                    <div class="flex flex-auto ltr:pl-3 rtl:pr-3">
                                        <a href="project?id=${notification.projectID}">
                                            <div class="ltr:pr-3 rtl:pl-3">
                                                <h6><strong>${notification.author}</strong></h6>
                                                <h6>${notification.commentText}</h6>
                                                <span class="block text-xs font-normal dark:text-gray-500">${notification.dateCreate}</span>
                                            </div>
                                        </a>
                                        ${notification.readed == 0 ? `
                                            <button type="button" class="notify-read text-neutral-300 hover:text-primary group-hover:opacity-100 ltr:ml-auto rtl:mr-auto" data-id="${notification.id}">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                                                    <path d="M18,7.8l-6.7,8.7M5.3,12.5l5.5,4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                                </svg>
                                            </button>
                                        ` : ''}
                                    </div>
                                </div>
                            `;
                                modalContent.appendChild(item);
                            });

                            modal.classList.remove('hidden');
                            modal.classList.add('flex');

                        } else {
                            console.error('Failed to fetch all notifications:', data.error);
                            alert('Tüm yorumlar yüklenirken bir hata oluştu.');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                    });
            });
        }

        if (closeModalButton) {
            closeModalButton.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('block');
            });
        }

        const fetchNotifications = () => {
    //console.log('Fetching notifications...');
fetch('modules/commentNotifications/fetch-notifications.php')
    .then(response => {
        //console.log('Response received:', response);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })

        .then(data => {
            if (data.success) {
                const list = document.getElementById('notification-list');
                const badge = document.getElementById('notification-badge');
                const unreadCount = document.getElementById('unread-count');
                let previousNotifications = list.innerHTML; // Önceki bildirimleri sakla

                if (!list || !badge || !unreadCount) {
                    console.error('Notification list, badge, or unread count element not found');
                    return;
                }

                list.innerHTML = '';
                let hasUnreadNotifications = false;

                data.notifications.forEach(notification => {
                    const item = document.createElement('li');
                    item.className = notification.comReaded == 1 ? 'notification-read dark:text-white-light/90' : 'dark:text-white-light/90';
                    item.innerHTML = `
                    <div class="group flex items-center px-4 py-2">
                        <div class="grid place-content-center rounded">
                            <div class="relative h-12 w-12">
                                <img class="h-12 w-12 rounded-full object-cover" src="${notification.profilePicture}" alt="${notification.author} Profil Fotoğrafı" />
                            </div>
                        </div>
                        <div class="flex flex-auto ltr:pl-3 rtl:pr-3">
                            <a href="project?id=${notification.projectID}">
                                <div class="ltr:pr-3 rtl:pl3">
                                    <h6><strong>${notification.author}</strong></h6>
                                    <h6>${notification.comText}</h6>
                                    <span class="block text-xs font-normal dark:text-gray-500">${notification.comTime}</span>
                                </div>
                            </a>
                            ${notification.comReaded == 0 ? `
                            <button type="button" class="notify-read text-neutral-300 hover:text-primary group-hover:opacity-100 ltr:ml-auto rtl:mr-auto" data-id="${notification.id}">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                                    <path d="M18,7.8l-6.7,8.7M5.3,12.5l5.5,4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </button>
                            ` : ''}
                        </div>
                    </div>
                    `;
                    list.appendChild(item);

                    if (notification.comReaded == 0) {
                        hasUnreadNotifications = true;
                    }
                });

                // Güncellenmiş bildirim sayısını göster
                unreadCount.innerText = data.numNewCom > 0 ? `${data.numNewCom} Yeni` : 'Yeni Yorum Yok';

                badge.innerHTML = hasUnreadNotifications ? `
                <span class="absolute -top-[3px] inline-flex h-full w-full animate-ping rounded-full bg-success/50 opacity-75 ltr:-left-[3px] rtl:-right-[3px]"></span>
                <span class="relative inline-flex h-[6px] w-[6px] rounded-full bg-success"></span>
                ` : '';

                // Yeni bildirim eklenip eklenmediğini kontrol et ve playAudio() fonksiyonunu çağır
                if (data.playSound) {
                    playAudio();
                }
            } else {
                console.error('Failed to fetch notifications:', data.error);
            }
        }) .catch(error => {
        console.error('Error fetching notifications:', error.message);
    });
};


        fetchNotifications();
        setInterval(fetchNotifications, 1000); // Her 1 saniyede bir yenile

        document.addEventListener('click', (event) => {
            const target = event.target.closest('.notify-read');
            if (target) {
                const button = target;
                const notificationId = button.getAttribute('data-id');

                fetch('modules/commentNotifications/update-notification.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': 'csrf-token-here' // Gerekiyorsa CSRF token ekleyin
                    },
                    body: JSON.stringify({ id: notificationId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            button.classList.add('read');
                            button.closest('li').classList.add('notification-read');
                        } else {
                            console.error('Failed to update notification:', data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });

        // MutationObserver to observe changes in notifications
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    // Additional processing if needed
                }
            });
                    // console.log('Notification list updated.');
        });

        const config = { childList: true, subtree: true };
        const notificationList = document.getElementById('notification-list');
        if (notificationList) {
            observer.observe(notificationList, config);
        }

    });
</script>
