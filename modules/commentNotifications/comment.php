<!-- Bildirim START -->
 <div x-for="notification in notifications">
    <li class="dark:text-white-light/90">
        <div class="group flex items-center px-4 py-2" @click.self="toggle">
            <div class="grid place-content-center rounded">
                <div class="relative h-12 w-12">
                    <img class="h-12 w-12 rounded-full object-cover" src="<?php echo $comAuthorPP; ?>"
                        alt="<?php echo $comAuthor ?> Profil Fotoğrafı" />
                </div>
            </div>

            <!-- Bildirim İçeriği -->
            <div class="flex flex-auto ltr:pl-3 rtl:pr-3">
                <div class="ltr:pr-3 rtl:pl-3">
                    <h6>
                        <strong><?php echo $comAuthor ?></strong>
                    </h6>
                    <h6><?php echo $comText ?></h6>
                    <span class="block text-xs font-normal dark:text-gray-500"><?php echo $comTime ?></span>
                </div>
                <?php 
                if($comReaded==0){
                    echo'
                    <!-- Okundu İşaretle START -->
                    <button type="button"
                        class="notify-read text-neutral-300 hover:text-primary group-hover:opacity-100 ltr:ml-auto rtl:mr-auto"
                        data-id="'.$rowModNot['id'].'">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                              <path d="M18,7.8l-6.7,8.7M5.3,12.5l5.5,4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <!-- Okundu İşaretle END -->
                    ';
                }
                ?>

            </div>
            <!-- Bildirim İçeriği END -->


        </div>
    </li>
</div>
<!-- Bildirim END -->
