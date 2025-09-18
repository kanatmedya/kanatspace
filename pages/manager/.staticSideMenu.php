<div class="main-container min-h-screen text-black dark:text-white-dark"
     :class="[$store.app.navbar]">

    <!-- start sidebar section -->
    <div :class="{'dark text-white-dark' : $store.app.semidark}">
        <nav x-data="sidebar"
             class="sidebar fixed top-0 bottom-0 z-50 h-full min-h-screen w-[200px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] transition-all duration-300">
            <div class="h-full bg-white dark:bg-[#0e1726]">
                <div style=" display: flex;flex-direction: column;justify-content: space-between;">
                    <div>
                        <div class="border-b border-white-light"
                             style="--tw-shadow: 0 3px 25px 0 #5e5c9a1a; --tw-shadow-colored: 5px 0 25px 0 var(--tw-shadow-color); box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);">
                            <!-- Sidebar Header -->
                            <div class="flex items-center justify-between px-4 py-3">
                                <a href="/" class="main-logo flex shrink-0 items-center">
                                    <img class="ml-[5px] w-8 flex-none"
                                         src="uploads/logo/kanatmedyafavicon.ico"
                                         alt="image" />
                                    <span
                                            class="align-middle text-2xl font-semibold ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light lg:inline">Kanat
                                    Space</span>
                                </a>
                                <a href="javascript:;"
                                   class="collapse-icon flex h-8 w-8 items-center rounded-full transition duration-300 hover:bg-gray-500/10 rtl:rotate-180 dark:text-white-light dark:hover:bg-dark-light/10"
                                   @click="$store.app.toggleSidebar()">
                                    <svg class="m-auto h-5 w-5" width="20" height="20"
                                         viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13 19L7 12L13 5" stroke="currentColor"
                                              stroke-width="1.5"
                                              stroke-linecap="round" stroke-linejoin="round" />
                                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5"
                                              stroke="currentColor"
                                              stroke-width="1.5" stroke-lineccap="round"
                                              stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                            <!-- Sidebar Header -->
                            <div class="sm:hidden">
                                <ul
                                        class="flex items-center space-x-2 rtl:space-x-reverse dark:text-[#d0d2d6] space-y-0.5 p-4 py-0 pb-2">
                                    <!-- Accounting -->
                                    <li>
                                        <a href="/transferAdd"
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

                                    <!-- Projects -->
                                    <li>
                                        <a id="projectAddButton2"
                                           class="block tooltip rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.5"
                                                      d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5"
                                                      stroke="currentColor" stroke-width="1.5"
                                                      stroke-linecap="round" />
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

                                    <!-- Chat -->
                                    <li>
                                        <a href="/invoiceAdd"
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
                                </ul>
                            </div>
                        </div>

                        <style>
                            .sideMenu {
                                height: calc(100vh - 125px);
                            }

                            @media (max-width: 639px) {
                                .sideMenu {
                                    height: calc(100vh - 170px);
                                }
                            }
                        </style>

                        <!-- Sidebar -->
                        <ul class="perfect-scrollbar sideMenu relative space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-2 font-semibold" x-data="{ activeDropdown: 'dashboard' }">
                            <!-- buradaki daha title'ye göre değişmeli -->

                            <!-- Dashboard -->
                            <li class="nav-item">
                                <a href="/" class="group">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                  d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                                  fill="currentColor" />
                                            <path
                                                    d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z"
                                                    fill="currentColor" />
                                        </svg>
                                        <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                            Ana Sayfa
                                        </span>
                                    </div>
                                </a>
                            </li>


                            <!-- Apps -->
                            <h2
                                    class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                                <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.5" fill="none" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>İş Takip</span>
                            </h2>

                            <li class="nav-item">
                                <ul>

                                    <!-- Scrumboard -->
                                    <li class="nav-item">
                                        <a href="/projects" class="group">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-table-columns"></i>
                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                Projeler</span>
                                            </div>
                                        </a>
                                    </li>

                                    <!-- Takvim -->
                                    <li class="nav-item">
                                        <a href="/calendar" class="group">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-calendar-check"></i>
                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                Takvim</span>
                                            </div>
                                        </a>
                                    </li>

                                    <!-- Hatırlatıcı -->
                                    <li class="nav-item">
                                        <a href="/projects" class="group">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-note-sticky"></i>
                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                Hatırlatıcı
                                            </span>
                                            </div>
                                        </a>
                                    </li>

                                    <!-- Apps -->
                                    <h2
                                            class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                                        <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.5" fill="none" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <span>Satış</span>
                                    </h2>
                                    <!-- Teklifler  -->
                                    <li class="nav-item">
                                        <a href="/invoicesOffer" class="group">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-clipboard-list"></i>
                                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                    Teklifler <!--Buraya Tab Ekle Alış Satış-->
                                                </span>
                                            </div>
                                        </a>
                                    </li>

                                    <!-- Siparişler  -->
                                    <li class="nav-item">
                                        <a href="/invoicesOrder" class="group">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-clipboard-check"></i>
                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                Siparişler</span>
                                            </div>
                                        </a>
                                    </li>

                                    <!-- Siparişler  -->
                                    <li class="nav-item">
                                        <a href="/dispatchPurchase" class="group">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-clipboard-check"></i>
                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                İrsaliyeler</span> <!--Buraya Tab Ekle Gelen Giden -->
                                            </div>
                                        </a>
                                    </li>

                                    <!--İrsaliyeler
                                    <li class="menu nav-item">
                                        <button type="button" class="nav-link group"
                                                :class="{'active' : activeDropdown === 'dispatch'}"
                                                @click="activeDropdown === 'dispatch' ? activeDropdown = null : activeDropdown = 'dispatch'">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-truck-arrow-right"></i>
                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                İrsaliyeler</span>
                                            </div>
                                            <div class="rtl:rotate-180"
                                                 :class="{'!rotate-90' : activeDropdown === 'dispatch'}">
                                                <svg width="16" height="16" viewBox="0 0 24 24"
                                                     fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 5L15 12L9 19" stroke="currentColor"
                                                          stroke-width="1.5"
                                                          stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </button>
                                        <ul x-cloak x-show="activeDropdown === 'dispatch'" x-collapse
                                            class="sub-menu text-gray-500">
                                            <li>
                                                <a href="/dispatchPurchase">Alış</a>
                                            </li>
                                            <li>
                                                <a href="/dispatchSale">Satış</a>
                                            </li>

                                        </ul>
                                    </li>-->

                                    <!-- invoices  -->
                                    <li class="menu nav-item">
                                        <button type="button" class="nav-link group"
                                                :class="{'active' : activeDropdown === 'invoices'}"
                                                @click="activeDropdown === 'invoices' ? activeDropdown = null : activeDropdown = 'invoices'">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-truck"></i>
                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                Faturalar</span>
                                            </div>
                                            <div class="rtl:rotate-180"
                                                 :class="{'!rotate-90' : activeDropdown === 'invoices'}">
                                                <svg width="16" height="16" viewBox="0 0 24 24"
                                                     fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 5L15 12L9 19" stroke="currentColor"
                                                          stroke-width="1.5"
                                                          stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </button>
                                        <ul x-cloak x-show="activeDropdown === 'invoices'" x-collapse
                                            class="sub-menu text-gray-500">
                                            <li>
                                                <a href="/invoicesPurchase">Alış</a>
                                            </li>
                                            <li>
                                                <a href="/invoicesSale">Satış</a>
                                            </li>

                                        </ul>
                                    </li>


                                    <li class="menu nav-item">
                                        <button type="button" class="nav-link group"
                                                :class="{'active' : activeDropdown === 'stockTracing'}"
                                                @click="activeDropdown === 'stockTracing' ? activeDropdown = null : activeDropdown = 'stockTracing'">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-clipboard"></i>
                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                Ürünler
                                            </span>
                                            </div>
                                            <div class="rtl:rotate-180"
                                                 :class="{'!rotate-90' : activeDropdown === 'stockTracing'}">
                                                <svg width="16" height="16" viewBox="0 0 24 24"
                                                     fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 5L15 12L9 19" stroke="currentColor"
                                                          stroke-width="1.5"
                                                          stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </button>
                                        <ul x-cloak x-show="activeDropdown === 'stockTracing'" x-collapse
                                            class="sub-menu text-gray-500">
                                            <li>
                                                <a href="/stockProducts">Ürünler</a> <!-- Stoksuz Satış, Stoktan Satış-->
                                            </li>
                                            <li>
                                                <a href="/stockProducts">Hizmetler</a> <!-- Stok Takibi Olmadan Satış -->
                                            </li>
                                            <li>
                                                <a href="/stockProducts">Abonelikler</a> <!-- Stok Takibi Olmadan ve Tekrarlayan Satış -->
                                            </li>
                                            <li>
                                                <a href="/stockCategories">Kategori Yönetimi</a> <!-- Ürün, Hizmet ve Abonelik Kategorileri Ayrı Ayrı Olacak veya bir kategori birden fazla alanda kullanılabilir olacak -->
                                            </li>
                                            <!--<li>
                                                <a href="/stockCategoriesSub">Alt Kategoriler</a>
                                            </li>-->
                                            <li>
                                                <a href="/">Stok Hareketleri</a>
                                            </li>

                                        </ul>
                                    </li>


                                    <!-- Apps -->
                                    <h2
                                            class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                                        <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.5" fill="none" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <span>Muhasebe</span>
                                    </h2>

                                    <!-- Kasa -->
                                    <li class="menu nav-item">
                                        <button type="button" class="nav-link group"
                                                :class="{'active' : activeDropdown === 'vault'}"
                                                @click="activeDropdown === 'vault' ? activeDropdown = null : activeDropdown = 'vault'">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-vault"></i>
                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                Kasa</span>
                                            </div>
                                            <div class="rtl:rotate-180"
                                                 :class="{'!rotate-90' : activeDropdown === 'vault'}">
                                                <svg width="16" height="16" viewBox="0 0 24 24"
                                                     fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 5L15 12L9 19" stroke="currentColor"
                                                          stroke-width="1.5"
                                                          stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </button>
                                        <ul x-cloak x-show="activeDropdown === 'vault'" x-collapse
                                            class="sub-menu text-gray-500">
                                            <li>
                                                <a href="/vault">Kasalar</a>
                                            </li>
                                            <li>
                                                <a href="/#vaultActivities">Hesap Defteri</a>
                                            </li>
                                            <li>
                                                <a href="/#vaultPortfolio">Çek/Senet Portföyü</a>
                                            </li>
                                            <li>
                                                <a href="/incomeCategory">Gelir/Gider Kategorileri</a>
                                            </li>

                                        </ul>
                                    </li>

                                    <!-- Cariler -->
                                    <li class="menu nav-item">
                                        <button type="button" class="nav-link group"
                                                :class="{'active' : activeDropdown === 'chequingAccount'}"
                                                @click="activeDropdown === 'chequingAccount' ? activeDropdown = null : activeDropdown = 'chequingAccount'">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-user-tag"></i>
                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                Cari Hesaplar</span>
                                            </div>
                                            <div class="rtl:rotate-180"
                                                 :class="{'!rotate-90' : activeDropdown === 'chequingAccount'}">
                                                <svg width="16" height="16" viewBox="0 0 24 24"
                                                     fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 5L15 12L9 19" stroke="currentColor"
                                                          stroke-width="1.5"
                                                          stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </button>
                                        <ul x-cloak x-show="activeDropdown === 'chequingAccount'" x-collapse
                                            class="sub-menu text-gray-500">
                                            <li>
                                                <a href="/accountAdd">Yeni Hesap Oluştur</a>
                                            </li>
                                            <li>
                                                <a href="/clients">Müşteri</a>
                                            </li>
                                            <li>
                                                <a href="/dealers">Bayi</a>
                                            </li>
                                            <li>
                                                <a href="/suppliers">Tedarikçi</a>
                                            </li>
                                            <li>
                                                <a href="/accounts-individual">Bireysel</a>
                                            </li>

                                        </ul>
                                    </li>

                                    <!-- Ödemeler -->
                                    <li class="menu nav-item">
                                        <button type="button" class="nav-link group"
                                                :class="{'active' : activeDropdown === 'payments'}"
                                                @click="activeDropdown === 'payments' ? activeDropdown = null : activeDropdown = 'payments'">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-credit-card"></i>
                                                <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                    Ödemeler
                                                </span>
                                            </div>
                                            <div class="rtl:rotate-180"
                                                 :class="{'!rotate-90' : activeDropdown === 'payments'}">
                                                <svg width="16" height="16" viewBox="0 0 24 24"
                                                     fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 5L15 12L9 19" stroke="currentColor"
                                                          stroke-width="1.5"
                                                          stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </button>
                                        <ul x-cloak x-show="activeDropdown === 'payments'" x-collapse
                                            class="sub-menu text-gray-500">
                                            <li>
                                                <a href="/payments">Ödemeler</a>
                                            </li>
                                            <li>
                                                <a href="/paymentOrders">Ödeme Talimatları</a>
                                            </li>

                                        </ul>
                                    </li>
                                    

                                    <!-- Personeller -->
                                    <li class="nav-item">
                                        <a href="/employees" class="group">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-user-tie"></i>
                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                Personel</span>
                                            </div>
                                        </a>
                                    </li>


                                    <!-- Apps -->
                                    <h2
                                            class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                                        <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.5" fill="none" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <span>Yönetim</span>
                                    </h2>

                                    <!-- Şirket -->
                                    <li class="nav-item">
                                        <a href="/company" class="group">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-building"></i>
                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                Şirket</span>
                                            </div>
                                        </a>
                                    </li>
                                    <!-- Raporlar -->
                                    <li class="menu nav-item">
                                        <button type="button" class="nav-link group"
                                                :class="{'active' : activeDropdown === 'report'}"
                                                @click="activeDropdown === 'report' ? activeDropdown = null : activeDropdown = 'report'">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-chart-simple"></i>

                                                <span
                                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">
                                                Raporlar</span>
                                            </div>
                                            <div class="rtl:rotate-180"
                                                 :class="{'!rotate-90' : activeDropdown === 'report'}">
                                                <svg width="16" height="16" viewBox="0 0 24 24"
                                                     fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 5L15 12L9 19" stroke="currentColor"
                                                          stroke-width="1.5"
                                                          stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </button>
                                        <ul x-cloak x-show="activeDropdown === 'report'" x-collapse
                                            class="sub-menu text-gray-500">
                                            <li>
                                                <a href="/#">Personel Raporu</a>
                                            </li>
                                            <li>
                                                <a href="/#">Harcama Raporu</a>
                                            </li>
                                            <li>
                                                <a href="/#">Çek/Senet Raporu</a>
                                            </li>
                                            <li>
                                                <a href="/#">Cari Hesap Dökümü</a>
                                            </li>
                                            <li>
                                                <a href="/#">Fatura Raporu</a>
                                            </li>
                                            <li>
                                                <a href="/#">Genel Rapor</a>
                                            </li>

                                        </ul>
                                    </li>


                                </ul>
                            </li>


                        </ul>
                        <!-- Sidebar -->
                    </div>

                    <!-- Sidebar Footer -->
                    <div
                            class="flex items-center justify-between px-4 py-3 mb-5 border-t border-white-light"
                            style="background:#fdfdfd;
                        --tw-shadow: 0 -3px 25px 0 #5e5c9a1a;
    --tw-shadow-colored: 5px 0 25px 0 var(--tw-shadow-color);
    box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);">
                        <div class="dropdown flex-shrink-0" x-data="dropdown"
                             @click.outside="open = false"
                             style="width: 100%;justify-content: space-between;display: flex;flex-wrap: nowrap;flex-direction: row;gap: 8px;align-items: center;">

                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                                class="w-[230px] !py-0 font-semibold text-dark dark:text-white-dark dark:text-white-light/90"
                                style="margin-top: -260px;">
                                <li>
                                    <div class="flex items-center px-4 py-4">
                                        <div class="flex-none">
                                            <img class="h-10 w-10 rounded-md object-cover"
                                                 src="<?php echo $_SESSION['pic']; ?>" alt="image" />
                                        </div>
                                        <div class="truncate ltr:pl-4 rtl:pr-4">
                                            <h4 class="text-base">
                                            <span
                                                    class="rounded bg-success-light px-1 text-xs text-success">
                                                M</span>
                                                <?php echo $_SESSION['name'];?>
                                            </h4>
                                            <a
                                                    class="text-black/60 hover:text-primary dark:text-dark-light/60 dark:hover:text-white"
                                                    href="javascript:;">
                                                <?php echo $_SESSION['email'];?>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a href="/settingsUser" class="dark:hover:text-white" @click="toggle">
                                        <svg class="h-4.5 w-4.5 shrink-0 ltr:mr-2 rtl:ml-2" width="18"
                                             height="18"
                                             viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12" cy="6" r="4" stroke="currentColor"
                                                    stroke-width="1.5" />
                                            <path opacity="0.5"
                                                  d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z"
                                                  stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                        Profilin</a>
                                </li>
                                <li>
                                    <a href="/#" class="dark:hover:text-white" @click="toggle">
                                        <svg class="h-4.5 w-4.5 shrink-0 ltr:mr-2 rtl:ml-2" width="18"
                                             height="18"
                                             viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                  d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                                  stroke="currentColor" stroke-width="1.5" />
                                            <path
                                                    d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908L18 8"
                                                    stroke="currentColor" stroke-width="1.5"
                                                    stroke-linecap="round" />
                                        </svg>
                                        Gelen Kutusu</a>
                                </li>
                                <li>
                                    <a href="/#" class="dark:hover:text-white" @click="toggle">
                                        <svg class="h-4.5 w-4.5 shrink-0 ltr:mr-2 rtl:ml-2" width="18"
                                             height="18"
                                             viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                    d="M2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.75736 10 5.17157 10 8 10H16C18.8284 10 20.2426 10 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16Z"
                                                    stroke="currentColor" stroke-width="1.5" />
                                            <path opacity="0.5"
                                                  d="M6 10V8C6 4.68629 8.68629 2 12 2C15.3137 2 18 4.68629 18 8V10"
                                                  stroke="currentColor" stroke-width="1.5"
                                                  stroke-linecap="round" />
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
                                        Kilitle</a>
                                </li>
                                <li class="border-t border-white-light dark:border-white-light/10">
                                    <a href="/logout" class="!py-3 text-danger" @click="toggle">
                                        <svg class="h-4.5 w-4.5 rotate-90 ltr:mr-2 rtl:ml-2" width="18"
                                             height="18"
                                             viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                  d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                                                  stroke="currentColor" stroke-width="1.5"
                                                  stroke-linecap="round" />
                                            <path d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5"
                                                  stroke="currentColor"
                                                  stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round" />
                                        </svg>
                                        Sign Out </a>
                                </li>
                            </ul>
                            <div
                                    style="
                                display: flex;
                                flex-wrap: nowrap;
                                flex-direction: row;
                                gap: 8px;
                                align-items: center;
                                ">
                                <a href="javascript:;" class="group relative" @click="toggle()">
                                <span>
                                    <img
                                            class="h-9 w-9 rounded-full object-cover saturate-50 group-hover:saturate-100"
                                            src="<?php echo $_SESSION['pic']; ?>" alt="image" />
                                </span>
                                </a>

                                <div>
                                    <h4 class="text-base">
                                        <strong>
                                            <?php echo $_SESSION['name'];?>
                                        </strong>
                                    </h4>
                                    <span class="rounded bg-success-light px-1 text-xs text-success">
                                    <?php echo $_SESSION['position'];?>
                                </span>
                                </div>
                            </div>
                            <div>

                                <a href="/logout"
                                   class="block tooltip rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60">
                                    <svg width="20" height="20"
                                         viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                              d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                                              stroke="currentColor" stroke-width="1.5"
                                              stroke-linecap="round" />
                                        <path d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5"
                                              stroke="currentColor"
                                              stroke-width="1.5" stroke-linecap="round"
                                              stroke-linejoin="round" />
                                    </svg>
                                    <span class="tooltiptext">Çıkış</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar Footer -->

                </div>
            </div>
        </nav>
    </div>
    <!-- end sidebar section -->

    <div class="main-content flex flex-col min-h-screen">