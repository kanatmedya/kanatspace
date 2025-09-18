<!-- start header section -->
<header class="z-40" :class="{'dark' : $store.app.semidark && $store.app.menu === 'horizontal'}">
    <div class="shadow-sm">
        <div class="relative flex w-full items-center bg-white px-5 py-2.5 dark:bg-[#0e1726]">
            <div class="horizontal-logo flex items-center justify-between ltr:mr-2 rtl:ml-2 lg:hidden">
                <a href="/" class="main-logo flex shrink-0 items-center">
                    <img class="inline w-8 ltr:-ml-1 rtl:-mr-1" src="./uploads/logo/kanatmedyafavicon.ico"
                        alt="image" />
                    <span
                        class="hidden align-middle text-2xl font-semibold transition-all duration-300 ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light md:inline">Kanat
                        Space</span>
                </a>

                <a href="javascript:;"
                    class="collapse-icon flex flex-none rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary ltr:ml-2 rtl:mr-2 dark:bg-dark/40 dark:text-[#d0d2d6] dark:hover:bg-dark/60 dark:hover:text-primary lg:hidden"
                    @click="$store.app.toggleSidebar()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 7L4 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path opacity="0.5" d="M20 12L4 12" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" />
                        <path d="M20 17L4 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </a>
            </div>

            <div class="hidden ltr:mr-2 rtl:ml-2 sm:block">
                <ul class="flex items-center space-x-2 rtl:space-x-reverse dark:text-[#d0d2d6]">

                    <!-- Projects -->
                    <li>
                        <a id="projectAddButton"
                            class="block tooltip rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5"
                                    d="M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797M10.1002 15.5876L8.4126 13.9"
                                    stroke="currentColor" stroke-width="1.5" />
                            </svg>
                            <span class="tooltiptext">Proje Oluştur</span>
                        </a>
                    </li>

                    <?php

                    $accountingAccess = false;
                    $invoiceAccess = false;

                    if ($accountingAccess == true) {
                        echo '

                        <!-- Accounting -->
                        <li>
                            <a href="transferAdd"
                                class="block tooltip rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.1,10.4c-.2,4.9-4.2,8.7-9.1,8.7s-5.9-1.5-7.6-4c0,0,0,.2,0,.2v3.4c0,.2-.2.4-.4.4s-.4-.2-.4-.4v-3.4c0-.8.7-1.5,1.5-1.5h3.4c.2,0,.4.2.4.4s-.2.4-.4.4h-3.4s0,0-.1,0c1.6,2.4,4.1,3.8,7,3.8,4.5,0,8.2-3.5,8.4-8,0-.2.2-.4.4-.4h0c.2,0,.4.2.4.4ZM10,1.6c2.8,0,5.4,1.4,7,3.8,0,0,0,0-.1,0h-3.4c-.2,0-.4.2-.4.4s.2.4.4.4h3.4c.8,0,1.5-.7,1.5-1.5V1.3c0-.2-.2-.4-.4-.4s-.4.2-.4.4v3.4c0,0,0,.2,0,.2C15.9,2.4,13.1.9,10,.9,5.1.9,1.1,4.7.9,9.6c0,.2.2.4.4.4h0c.2,0,.4-.2.4-.4C1.8,5.1,5.5,1.6,10,1.6ZM7.6,16.4c.8.3,1.6.4,2.4.4,3.8,0,6.8-3.1,6.8-6.8s-.1-1.4-.3-2c0-.2-.3-.3-.5-.3-.2,0-.3.3-.3.5.2.6.3,1.2.3,1.8,0,3.4-2.7,6.1-6.1,6.1s-1.5-.1-2.1-.4c-.2,0-.4,0-.5.2,0,.2,0,.4.2.5h0ZM10,3.9c.7,0,1.5.1,2.1.4.2,0,.4,0,.5-.2,0-.2,0-.4-.2-.5-.8-.3-1.6-.4-2.4-.4-3.8,0-6.8,3.1-6.8,6.8s.1,1.4.3,2c0,.2.2.3.4.3s0,0,.1,0c.2,0,.3-.3.3-.5-.2-.6-.3-1.2-.3-1.8,0-3.4,2.7-6.1,6.1-6.1h0ZM9.4,7.7h1.3c.4,0,.9.2.9.8s.2.4.4.4.4-.2.4-.4c0-.9-.7-1.5-1.6-1.5h-.3v-.4c0-.2-.2-.4-.4-.4s-.4.2-.4.4v.4h-.3c-.9,0-1.6.7-1.6,1.6s.6,1.5,1.4,1.6l1.7.3c.4,0,.7.4.7.9s-.4.9-.9.9h-1.3c-.4,0-.9-.2-.9-.8s-.2-.4-.4-.4-.4.2-.4.4c0,.9.7,1.5,1.6,1.5h.3v.4c0,.2.2.4.4.4s.4-.2.4-.4v-.4h.3c.9,0,1.6-.7,1.6-1.6s-.6-1.5-1.4-1.6l-1.7-.3c-.4,0-.7-.4-.7-.9s.4-.9.9-.9Z"
                                        fill="#000" stroke-width="0" />
                                </svg>
                                <span class="tooltiptext">Gelir/Gider Ekle</span>
                            </a>
                        </li>

                        ';
                    }

                    if ($invoiceAccess == true) {
                        echo '
                        
                        <!-- Chat -->
                        <li>
                            <a href="invoiceAdd"
                                class="block tooltip rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.2,10.8h-6.3c-.9,0-1.6.7-1.6,1.6v1.6c0,.9.7,1.6,1.6,1.6h6.3c.9,0,1.6-.7,1.6-1.6v-1.6c0-.9-.7-1.6-1.6-1.6ZM13.9,13.9c0,.4-.4.8-.8.8h-6.3c-.4,0-.8-.4-.8-.8v-1.6c0-.4.4-.8.8-.8h6.3c.4,0,.8.4.8.8v1.6ZM5.3,8c0-.2.2-.4.4-.4h3.2c.2,0,.4.2.4.4s-.2.4-.4.4h-3.2c-.2,0-.4-.2-.4-.4ZM5.3,4.9c0-.2.2-.4.4-.4h3.2c.2,0,.4.2.4.4s-.2.4-.4.4h-3.2c-.2,0-.4-.2-.4-.4ZM16.4,4.8l-2.7-2.7c-1-1-2.3-1.5-3.6-1.5h-4.3c-2,0-3.5,1.6-3.5,3.5v11.8c0,2,1.6,3.5,3.5,3.5h8.7c2,0,3.5-1.6,3.5-3.5v-7.5c0-1.4-.5-2.7-1.5-3.6h0ZM15.8,5.3c.4.4.8.9,1,1.5h-4c-.7,0-1.2-.5-1.2-1.2V1.6c.6.2,1.1.5,1.5,1l2.7,2.7h0ZM17.1,15.9c0,1.5-1.2,2.8-2.8,2.8H5.7c-1.5,0-2.8-1.2-2.8-2.8V4.1c0-1.5,1.2-2.8,2.8-2.8h4.3c.3,0,.5,0,.8,0v4.3c0,1.1.9,2,2,2h4.3c0,.3,0,.5,0,.8v7.5Z"
                                        fill="#000" stroke-width="0" />
                                </svg>
                                <span class="tooltiptext">Fatura Oluştur/Ekle</span>
                            </a>
                        </li>
                        
                        ';
                    }
                    ?>
                
                </ul>
            </div>

            <div x-data="header"
                class="flex items-center space-x-1.5 ltr:ml-auto rtl:mr-auto rtl:space-x-reverse dark:text-[#d0d2d6] sm:flex-1 ltr:sm:ml-0 sm:rtl:mr-0 lg:space-x-2">
                <!-- Search -->
                <div class="sm:ltr:mr-auto sm:rtl:ml-auto" x-data="{ search: false }" @click.outside="search = false">

                    <button type="button"
                        class="search_btn rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 dark:bg-dark/40 dark:hover:bg-dark/60 sm:hidden"
                        @click="search = ! search">
                        <svg class="mx-auto h-4.5 w-4.5 dark:text-[#d0d2d6]" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>

                <!-- Theme -->
                <div>
                    <a href="javascript:;" x-cloak x-show="$store.app.theme === 'light'" href="javascript:;"
                        class="flex items-center rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60"
                        @click="$store.app.toggleTheme('dark')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="1.5" />
                            <path d="M12 2V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M12 20V22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M4 12L2 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M22 12L20 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M19.7778 4.22266L17.5558 6.25424" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M4.22217 4.22266L6.44418 6.25424" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M6.44434 17.5557L4.22211 19.7779" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M19.7778 19.7773L17.5558 17.5551" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </a>
                    <a href="javascript:;" x-cloak x-show="$store.app.theme === 'dark'" href="javascript:;"
                        class="flex items-center rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60"
                        @click="$store.app.toggleTheme('system')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M21.0672 11.8568L20.4253 11.469L21.0672 11.8568ZM12.1432 2.93276L11.7553 2.29085V2.29085L12.1432 2.93276ZM21.25 12C21.25 17.1086 17.1086 21.25 12 21.25V22.75C17.9371 22.75 22.75 17.9371 22.75 12H21.25ZM12 21.25C6.89137 21.25 2.75 17.1086 2.75 12H1.25C1.25 17.9371 6.06294 22.75 12 22.75V21.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75V1.25C6.06294 1.25 1.25 6.06294 1.25 12H2.75ZM15.5 14.25C12.3244 14.25 9.75 11.6756 9.75 8.5H8.25C8.25 12.5041 11.4959 15.75 15.5 15.75V14.25ZM20.4253 11.469C19.4172 13.1373 17.5882 14.25 15.5 14.25V15.75C18.1349 15.75 20.4407 14.3439 21.7092 12.2447L20.4253 11.469ZM9.75 8.5C9.75 6.41182 10.8627 4.5828 12.531 3.57467L11.7553 2.29085C9.65609 3.5593 8.25 5.86509 8.25 8.5H9.75ZM12 2.75C11.9115 2.75 11.8077 2.71008 11.7324 2.63168C11.6686 2.56527 11.6538 2.50244 11.6503 2.47703C11.6461 2.44587 11.6482 2.35557 11.7553 2.29085L12.531 3.57467C13.0342 3.27065 13.196 2.71398 13.1368 2.27627C13.0754 1.82126 12.7166 1.25 12 1.25V2.75ZM21.7092 12.2447C21.6444 12.3518 21.5541 12.3539 21.523 12.3497C21.4976 12.3462 21.4347 12.3314 21.3683 12.2676C21.2899 12.1923 21.25 12.0885 21.25 12H22.75C22.75 11.2834 22.1787 10.9246 21.7237 10.8632C21.286 10.804 20.7293 10.9658 20.4253 11.469L21.7092 12.2447Z"
                                fill="currentColor" />
                        </svg>
                    </a>
                    <a href="javascript:;" x-cloak x-show="$store.app.theme === 'system'" href="javascript:;"
                        class="flex items-center rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60"
                        @click="$store.app.toggleTheme('light')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3 9C3 6.17157 3 4.75736 3.87868 3.87868C4.75736 3 6.17157 3 9 3H15C17.8284 3 19.2426 3 20.1213 3.87868C21 4.75736 21 6.17157 21 9V14C21 15.8856 21 16.8284 20.4142 17.4142C19.8284 18 18.8856 18 17 18H7C5.11438 18 4.17157 18 3.58579 17.4142C3 16.8284 3 15.8856 3 14V9Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5" d="M22 21H2" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path opacity="0.5" d="M15 15H9" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                    </a>
                </div>

                <?php include "./modules/commentNotifications/commentNotifications.php"; ?>

                <!-- Profile -->
                <div class="dropdown flex-shrink-0" x-data="dropdown" @click.outside="open = false">
                    <a href="javascript:;" class="group relative" @click="toggle()">
                        <span><img class="h-9 w-9 rounded-full object-cover saturate-50 group-hover:saturate-100"
                                src="<?php echo $_SESSION['pic']; ?>" alt="image" /></span>
                    </a>
                    <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                        class="top-11 w-[230px] !py-0 font-semibold text-dark ltr:right-0 rtl:left-0 dark:text-white-dark dark:text-white-light/90">
                        <li>
                            <div class="flex items-center px-4 py-4">
                                <div class="flex-none">
                                    <img class="h-10 w-10 rounded-md object-cover" src="<?php echo $_SESSION['pic']; ?>"
                                        alt="image" />
                                </div>
                                <div class="truncate ltr:pl-4 rtl:pr-4">
                                    <h4 class="text-base">
                                        <?php echo $_SESSION['name']; ?><span
                                            class="rounded bg-success-light px-1 text-xs text-success ltr:ml-2 rtl:ml-2">CL</span>
                                    </h4>
                                    <a class="text-black/60 hover:text-primary dark:text-dark-light/60 dark:hover:text-white"
                                        href="javascript:;"><?php echo $_SESSION['email']; ?></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="settingsUser" class="dark:hover:text-white" @click="toggle">
                                <svg class="h-4.5 w-4.5 shrink-0 ltr:mr-2 rtl:ml-2" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                Profilin</a>
                        </li>
                        <li>
                            <a href="#" class="dark:hover:text-white" @click="toggle">
                                <svg class="h-4.5 w-4.5 shrink-0 ltr:mr-2 rtl:ml-2" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5"
                                        d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path
                                        d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908L18 8"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                                Gelen Kutusu</a>
                        </li>
                        <li>
                            <a href="#" class="dark:hover:text-white" @click="toggle">
                                <svg class="h-4.5 w-4.5 shrink-0 ltr:mr-2 rtl:ml-2" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.75736 10 5.17157 10 8 10H16C18.8284 10 20.2426 10 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M6 10V8C6 4.68629 8.68629 2 12 2C15.3137 2 18 4.68629 18 8V10"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <g opacity="0.5">
                                        <path
                                            d="M9 16C9 16.5523 8.55228 17 8 17C7.44772 17 7 16.5523 7 16C7 15.4477 7.44772 15 8 15C8.55228 15 9 15.4477 9 16Z"
                                            fill="currentColor" />
                                        <path
                                            d="M13 16C13 16.5523 12.5523 17 12 17C11.4477 17 11 16.5523 11 16C11 15.4477 11.4477 15 12 15C12.5523 15 13 15.4477 13 16Z"
                                            fill="currentColor" />
                                        <path
                                            d="M17 16C17 16.5523 16.5523 17 16 17C15.4477 17 15 16.5523 15 16C15 15.4477 15.4477 15 16 15C16.5523 15 17 15.4477 17 16Z"
                                            fill="currentColor" />
                                    </g>
                                </svg>
                                Ekranı Kilitle</a>
                        </li>
                        <li class="border-t border-white-light dark:border-white-light/10">
                            <a href="logout" class="!py-3 text-danger" @click="toggle">
                                <svg class="h-4.5 w-4.5 rotate-90 ltr:mr-2 rtl:ml-2" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5"
                                        d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Sign Out
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- end header section -->