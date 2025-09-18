<?php
    $sqlTab = "SELECT * FROM invoices WHERE type = 'offer'";
    $resTab = $conn->query($sqlTab);
    $totalNum = $resTab->num_rows;
    
    $sqlTotalSum = "SELECT SUM(amount) as amount FROM invoices WHERE type = 'offer'";
    $resTotalSum = $conn->query($sqlTotalSum);
    $rowTotalSum = $resTotalSum->fetch_array();
    $totalSum = number_format($rowTotalSum['amount'], 2, ',', '.');
?>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h1 style="font-size:24px;margin-right:20px">Teklifler / Proforma Faturalar</h1><br>
    <div class="mb-6 grid gap-6 sm:grid-cols-3 xl:grid-cols-4">
        
        <div class="panel h-full p-0">
            <div class="flex p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary dark:bg-primary dark:text-white-light">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                        <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5"></circle>
                        <path opacity="0.5" d="M18 9C19.6569 9 21 7.88071 21 6.5C21 5.11929 19.6569 4 18 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        <path opacity="0.5" d="M6 9C4.34315 9 3 7.88071 3 6.5C3 5.11929 4.34315 4 6 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        <ellipse cx="12" cy="17" rx="6" ry="4" stroke="currentColor" stroke-width="1.5"></ellipse>
                        <path opacity="0.5" d="M20 19C21.7542 18.6153 23 17.6411 23 16.5C23 15.3589 21.7542 14.3847 20 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        <path opacity="0.5" d="M4 19C2.24575 18.6153 1 17.6411 1 16.5C1 15.3589 2.24575 14.3847 4 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg>
                </div>
                <div class="font-semibold ltr:ml-3 rtl:mr-3">
                    <p class="text-xl dark:text-white-light"><?php echo $totalNum?></p>
                    <h5 class="text-xs text-[#506690]">Toplam Sipariş Sayısı</h5>
                </div>
            </div>
        </div>

        <div class="panel h-full p-0">
            <div class="flex p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-danger/10 text-danger dark:bg-danger dark:text-white-light">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                        <path d="M10.0464 14C8.54044 12.4882 8.67609 9.90087 10.3494 8.22108L15.197 3.35462C16.8703 1.67483 19.4476 1.53865 20.9536 3.05046C22.4596 4.56228 22.3239 7.14956 20.6506 8.82935L18.2268 11.2626" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        <path opacity="0.5" d="M13.9536 10C15.4596 11.5118 15.3239 14.0991 13.6506 15.7789L11.2268 18.2121L8.80299 20.6454C7.12969 22.3252 4.55237 22.4613 3.0464 20.9495C1.54043 19.4377 1.67609 16.8504 3.34939 15.1706L5.77323 12.7373" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg>
                </div>
                <div class="font-semibold ltr:ml-3 rtl:mr-3">
                    <p class="text-xl dark:text-white-light"><?php echo $totalSum; ?></p>
                    <h5 class="text-xs text-[#506690]">Toplam Sipariş Tutarı</h5>
                </div>
            </div>
        </div>

        <div class="panel h-full p-0">
            <div class="flex p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                        <path d="M10 22C14.4183 22 18 18.4183 18 14C18 9.58172 14.4183 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2355 2.28008 16.4056 2.7802 17.4502C2.95209 17.8093 3.01245 18.2161 2.90955 18.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L5.39939 21.0904C5.78393 20.9876 6.19071 21.0479 6.54976 21.2198C7.5944 21.7199 8.76449 22 10 22Z" stroke="currentColor" stroke-width="1.5"></path>
                        <path opacity="0.5" d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 4.91015 19.0899 2 15.5 2C12.7977 2 10.4806 3.64899 9.5 5.9956" stroke="currentColor" stroke-width="1.5"></path>
                        <g opacity="0.5">
                            <path d="M7.5 14C7.5 14.5523 7.05228 15 6.5 15C5.94772 15 5.5 14.5523 5.5 14C5.5 13.4477 5.94772 13 6.5 13C7.05228 13 7.5 13.4477 7.5 14Z" fill="currentColor"></path>
                            <path d="M11 14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14C9 13.4477 9.44772 13 10 13C10.5523 13 11 13.4477 11 14Z" fill="currentColor"></path>
                            <path d="M14.5 14C14.5 14.5523 14.0523 15 13.5 15C12.9477 15 12.5 14.5523 12.5 14C12.5 13.4477 12.9477 13 13.5 13C14.0523 13 14.5 13.4477 14.5 14Z" fill="currentColor"></path>
                        </g>
                    </svg>
                </div>
                <div class="font-semibold ltr:ml-3 rtl:mr-3">
                    <p class="text-xl dark:text-white-light">18.2%</p>
                    <h5 class="text-xs text-[#506690]">Alış Teklifleri</h5>
                </div>
            </div>
        </div>

        <div class="panel h-full p-0">
            <div class="flex p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                        <path d="M10 22C14.4183 22 18 18.4183 18 14C18 9.58172 14.4183 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2355 2.28008 16.4056 2.7802 17.4502C2.95209 17.8093 3.01245 18.2161 2.90955 18.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L5.39939 21.0904C5.78393 20.9876 6.19071 21.0479 6.54976 21.2198C7.5944 21.7199 8.76449 22 10 22Z" stroke="currentColor" stroke-width="1.5"></path>
                        <path opacity="0.5" d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 4.91015 19.0899 2 15.5 2C12.7977 2 10.4806 3.64899 9.5 5.9956" stroke="currentColor" stroke-width="1.5"></path>
                        <g opacity="0.5">
                            <path d="M7.5 14C7.5 14.5523 7.05228 15 6.5 15C5.94772 15 5.5 14.5523 5.5 14C5.5 13.4477 5.94772 13 6.5 13C7.05228 13 7.5 13.4477 7.5 14Z" fill="currentColor"></path>
                            <path d="M11 14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14C9 13.4477 9.44772 13 10 13C10.5523 13 11 13.4477 11 14Z" fill="currentColor"></path>
                            <path d="M14.5 14C14.5 14.5523 14.0523 15 13.5 15C12.9477 15 12.5 14.5523 12.5 14C12.5 13.4477 12.9477 13 13.5 13C14.0523 13 14.5 13.4477 14.5 14Z" fill="currentColor"></path>
                        </g>
                    </svg>
                </div>
                <div class="font-semibold ltr:ml-3 rtl:mr-3">
                    <p class="text-xl dark:text-white-light">18.2%</p>
                    <h5 class="text-xs text-[#506690]">Satış Teklifleri</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- start main content section -->
    <div x-data="invoiceList">
        <script src="assets/js/simple-datatables.js"></script>
        
        <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
            <div class="px-5">
                <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                    <div class="mb-5 flex items-center gap-2">
                        <button type="button" class="btn btn-danger gap-2" @click="deleteRow()">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round"></path>
                                <path
                                    d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round"></path>
                                <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round"></path>
                                <path opacity="0.5"
                                    d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                                    stroke="currentColor" stroke-width="1.5"></path>
                            </svg>
                            Sil
                        </button>
                        <a href="apps-invoice-add.html" class="btn btn-primary gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="h-5 w-5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Oluştur
                        </a>
                    </div>
                </div>
            </div>
            <div class="invoice-table">
                <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                    <div class="dataTable-top">
                        <div class="dataTable-search"><input class="dataTable-input" placeholder="Search..."
                                type="text"></div>
                    </div>
                    <div class="dataTable-container">
                        <table id="myTable" class="whitespace-nowrap dataTable-table">
                            <thead>
                                <tr>
                                    <th data-sortable="false" style="width: 5.70044%;"><input type="checkbox"
                                            class="form-checkbox" :checked="checkAllCheckbox" :value="checkAllCheckbox"
                                            @change="checkAll($event.target.checked)"></th>
                                    <th data-sortable="" style="width: 10.2948%;"><a href="#" class="dataTable-sorter">Belge No</a></th>
                                    <th data-sortable="" style="width: 11.1456%;"><a href="#" class="dataTable-sorter">Tarih</a></th>
                                    <th data-sortable="" style="width: 18.803%;"><a href="#" class="dataTable-sorter">Müşteri</a></th>
                                    <th data-sortable="" style="width: 21.0151%;"><a href="#" class="dataTable-sorter">Açıklama</a></th>
                                    <th data-sortable="" style="width: 11.0606%;"><a href="#" class="dataTable-sorter">Tutar</a></th>
                                    <th data-sortable="" style="width: 10.0396%;"><a href="#" class="dataTable-sorter">Belge Oluştur</a></th>
                                    <th data-sortable="false" style="width: 11.9114%;">Eylemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    while($rowTab = $resTab->fetch_array()){
                                        include "row.php";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="dataTable-bottom">
                        <div class="dataTable-info">Showing 1 to 10 of 12 entries</div>
                        <div class="dataTable-dropdown"><label><span class="ml-2"><select class="dataTable-selector">
                                        <option value="10" selected="">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select></span></label></div>
                        <nav class="dataTable-pagination">
                            <ul class="dataTable-pagination-list">
                                <li class="pager"><a href="#" data-page="1"><svg width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            class="w-4.5 h-4.5 rtl:rotate-180">
                                            <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg></a></li>
                                <li class="active"><a href="#" data-page="1">1</a></li>
                                <li class=""><a href="#" data-page="2">2</a></li>
                                <li class="pager"><a href="#" data-page="2"><svg width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            class="w-4.5 h-4.5 rtl:rotate-180">
                                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg></a></li>
                                <li class="pager"><a href="#" data-page="2"><svg width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            class="w-4.5 h-4.5 rtl:rotate-180">
                                            <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end main content section -->

</div>