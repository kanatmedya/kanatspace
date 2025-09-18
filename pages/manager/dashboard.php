<?php

include "assets/db/db_functions.php";

$months = array(
    'January' => 'Ocak',
    'February' => 'Şubat',
    'March' => 'Mart',
    'April' => 'Nisan',
    'May' => 'Mayıs',
    'June' => 'Haziran',
    'July' => 'Temmuz',
    'August' => 'Ağustos',
    'September' => 'Eylül',
    'October' => 'Ekim',
    'November' => 'Kasım',
    'December' => 'Aralık'
);

?>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary">Dashboard</a>
            </li>
        </ul>
        <div class="pt-5">
            <div class="mb-6 grid grid-cols-2 gap-6 sm:grid-cols-4 xl:grid-cols-4">

                <div class="panel h-full p-0">
                    <a href="invoicesOffer">
                        <div class="flex p-5">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                    <path
                                        d="M10 22C14.4183 22 18 18.4183 18 14C18 9.58172 14.4183 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2355 2.28008 16.4056 2.7802 17.4502C2.95209 17.8093 3.01245 18.2161 2.90955 18.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L5.39939 21.0904C5.78393 20.9876 6.19071 21.0479 6.54976 21.2198C7.5944 21.7199 8.76449 22 10 22Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 4.91015 19.0899 2 15.5 2C12.7977 2 10.4806 3.64899 9.5 5.9956"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <g opacity="0.5">
                                        <path
                                            d="M7.5 14C7.5 14.5523 7.05228 15 6.5 15C5.94772 15 5.5 14.5523 5.5 14C5.5 13.4477 5.94772 13 6.5 13C7.05228 13 7.5 13.4477 7.5 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M11 14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14C9 13.4477 9.44772 13 10 13C10.5523 13 11 13.4477 11 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M14.5 14C14.5 14.5523 14.0523 15 13.5 15C12.9477 15 12.5 14.5523 12.5 14C12.5 13.4477 12.9477 13 13.5 13C14.0523 13 14.5 13.4477 14.5 14Z"
                                            fill="currentColor" />
                                    </g>
                                </svg>
                            </div>
                            <div class="font-semibold ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">
                                    <?php echo db_num_rows("SELECT * FROM invoices WHERE status = 'onOffer'") ?></p>
                                <h5 class="text-xs text-[#506690]">Teklif</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="panel h-full p-0">
                    <a href="invoicesOrder">
                        <div class="flex p-5">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                    <path
                                        d="M10 22C14.4183 22 18 18.4183 18 14C18 9.58172 14.4183 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2355 2.28008 16.4056 2.7802 17.4502C2.95209 17.8093 3.01245 18.2161 2.90955 18.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L5.39939 21.0904C5.78393 20.9876 6.19071 21.0479 6.54976 21.2198C7.5944 21.7199 8.76449 22 10 22Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 4.91015 19.0899 2 15.5 2C12.7977 2 10.4806 3.64899 9.5 5.9956"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <g opacity="0.5">
                                        <path
                                            d="M7.5 14C7.5 14.5523 7.05228 15 6.5 15C5.94772 15 5.5 14.5523 5.5 14C5.5 13.4477 5.94772 13 6.5 13C7.05228 13 7.5 13.4477 7.5 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M11 14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14C9 13.4477 9.44772 13 10 13C10.5523 13 11 13.4477 11 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M14.5 14C14.5 14.5523 14.0523 15 13.5 15C12.9477 15 12.5 14.5523 12.5 14C12.5 13.4477 12.9477 13 13.5 13C14.0523 13 14.5 13.4477 14.5 14Z"
                                            fill="currentColor" />
                                    </g>
                                </svg>
                            </div>
                            <div class="font-semibold ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">
                                    <?php echo db_num_rows("SELECT * FROM invoices WHERE status = 'onOrder'") ?></p>
                                <h5 class="text-xs text-[#506690]">Sipariş</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="panel h-full p-0">
                    <a href="invoicesPurchase">
                        <div class="flex p-5">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                    <path
                                        d="M10 22C14.4183 22 18 18.4183 18 14C18 9.58172 14.4183 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2355 2.28008 16.4056 2.7802 17.4502C2.95209 17.8093 3.01245 18.2161 2.90955 18.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L5.39939 21.0904C5.78393 20.9876 6.19071 21.0479 6.54976 21.2198C7.5944 21.7199 8.76449 22 10 22Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 4.91015 19.0899 2 15.5 2C12.7977 2 10.4806 3.64899 9.5 5.9956"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <g opacity="0.5">
                                        <path
                                            d="M7.5 14C7.5 14.5523 7.05228 15 6.5 15C5.94772 15 5.5 14.5523 5.5 14C5.5 13.4477 5.94772 13 6.5 13C7.05228 13 7.5 13.4477 7.5 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M11 14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14C9 13.4477 9.44772 13 10 13C10.5523 13 11 13.4477 11 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M14.5 14C14.5 14.5523 14.0523 15 13.5 15C12.9477 15 12.5 14.5523 12.5 14C12.5 13.4477 12.9477 13 13.5 13C14.0523 13 14.5 13.4477 14.5 14Z"
                                            fill="currentColor" />
                                    </g>
                                </svg>
                            </div>
                            <div class="font-semibold ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">
                                    <?php echo db_num_rows("SELECT * FROM dispatch") ?>
                                </p>
                                <h5 class="text-xs text-[#506690]">İrsaliye</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="panel h-full p-0">
                    <a href="invoicesSale">
                        <div class="flex p-5">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                    <path
                                        d="M10 22C14.4183 22 18 18.4183 18 14C18 9.58172 14.4183 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2355 2.28008 16.4056 2.7802 17.4502C2.95209 17.8093 3.01245 18.2161 2.90955 18.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L5.39939 21.0904C5.78393 20.9876 6.19071 21.0479 6.54976 21.2198C7.5944 21.7199 8.76449 22 10 22Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 4.91015 19.0899 2 15.5 2C12.7977 2 10.4806 3.64899 9.5 5.9956"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <g opacity="0.5">
                                        <path
                                            d="M7.5 14C7.5 14.5523 7.05228 15 6.5 15C5.94772 15 5.5 14.5523 5.5 14C5.5 13.4477 5.94772 13 6.5 13C7.05228 13 7.5 13.4477 7.5 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M11 14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14C9 13.4477 9.44772 13 10 13C10.5523 13 11 13.4477 11 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M14.5 14C14.5 14.5523 14.0523 15 13.5 15C12.9477 15 12.5 14.5523 12.5 14C12.5 13.4477 12.9477 13 13.5 13C14.0523 13 14.5 13.4477 14.5 14Z"
                                            fill="currentColor" />
                                    </g>
                                </svg>
                            </div>
                            <div class="font-semibold ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">
                                    <?php echo db_num_rows("SELECT * FROM invoices WHERE status = 'completed' AND type = 'sale'") ?>
                                </p>
                                <h5 class="text-xs text-[#506690]">Fatura</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="mb-6 grid grid-cols-2 gap-6 sm:grid-cols-4 xl:grid-cols-4">

                <div class="panel h-full p-0">
                    <a href="employees">
                        <div class="flex p-5">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                    <path
                                        d="M10 22C14.4183 22 18 18.4183 18 14C18 9.58172 14.4183 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2355 2.28008 16.4056 2.7802 17.4502C2.95209 17.8093 3.01245 18.2161 2.90955 18.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L5.39939 21.0904C5.78393 20.9876 6.19071 21.0479 6.54976 21.2198C7.5944 21.7199 8.76449 22 10 22Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 4.91015 19.0899 2 15.5 2C12.7977 2 10.4806 3.64899 9.5 5.9956"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <g opacity="0.5">
                                        <path
                                            d="M7.5 14C7.5 14.5523 7.05228 15 6.5 15C5.94772 15 5.5 14.5523 5.5 14C5.5 13.4477 5.94772 13 6.5 13C7.05228 13 7.5 13.4477 7.5 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M11 14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14C9 13.4477 9.44772 13 10 13C10.5523 13 11 13.4477 11 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M14.5 14C14.5 14.5523 14.0523 15 13.5 15C12.9477 15 12.5 14.5523 12.5 14C12.5 13.4477 12.9477 13 13.5 13C14.0523 13 14.5 13.4477 14.5 14Z"
                                            fill="currentColor" />
                                    </g>
                                </svg>
                            </div>
                            <div class="font-semibold ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">
                                    <?php echo db_num_rows("SELECT * FROM ac_users WHERE status = 1 AND (userType = 1 OR userType = 2)") ?></p>
                                <h5 class="text-xs text-[#506690]">Personel</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="panel h-full p-0">
                    <a href="clients">
                        <div class="flex p-5">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                    <path
                                        d="M10 22C14.4183 22 18 18.4183 18 14C18 9.58172 14.4183 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2355 2.28008 16.4056 2.7802 17.4502C2.95209 17.8093 3.01245 18.2161 2.90955 18.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L5.39939 21.0904C5.78393 20.9876 6.19071 21.0479 6.54976 21.2198C7.5944 21.7199 8.76449 22 10 22Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 4.91015 19.0899 2 15.5 2C12.7977 2 10.4806 3.64899 9.5 5.9956"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <g opacity="0.5">
                                        <path
                                            d="M7.5 14C7.5 14.5523 7.05228 15 6.5 15C5.94772 15 5.5 14.5523 5.5 14C5.5 13.4477 5.94772 13 6.5 13C7.05228 13 7.5 13.4477 7.5 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M11 14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14C9 13.4477 9.44772 13 10 13C10.5523 13 11 13.4477 11 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M14.5 14C14.5 14.5523 14.0523 15 13.5 15C12.9477 15 12.5 14.5523 12.5 14C12.5 13.4477 12.9477 13 13.5 13C14.0523 13 14.5 13.4477 14.5 14Z"
                                            fill="currentColor" />
                                    </g>
                                </svg>
                            </div>
                            <div class="font-semibold ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">
                                    <?php echo db_num_rows("SELECT * FROM users_client WHERE status = 1 AND accountType = 'client'") ?>
                                </p>
                                <h5 class="text-xs text-[#506690]">Müşteri</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="panel h-full p-0">
                    <a href="suppliers">
                        <div class="flex p-5">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                    <path
                                        d="M10 22C14.4183 22 18 18.4183 18 14C18 9.58172 14.4183 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2355 2.28008 16.4056 2.7802 17.4502C2.95209 17.8093 3.01245 18.2161 2.90955 18.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L5.39939 21.0904C5.78393 20.9876 6.19071 21.0479 6.54976 21.2198C7.5944 21.7199 8.76449 22 10 22Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 4.91015 19.0899 2 15.5 2C12.7977 2 10.4806 3.64899 9.5 5.9956"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <g opacity="0.5">
                                        <path
                                            d="M7.5 14C7.5 14.5523 7.05228 15 6.5 15C5.94772 15 5.5 14.5523 5.5 14C5.5 13.4477 5.94772 13 6.5 13C7.05228 13 7.5 13.4477 7.5 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M11 14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14C9 13.4477 9.44772 13 10 13C10.5523 13 11 13.4477 11 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M14.5 14C14.5 14.5523 14.0523 15 13.5 15C12.9477 15 12.5 14.5523 12.5 14C12.5 13.4477 12.9477 13 13.5 13C14.0523 13 14.5 13.4477 14.5 14Z"
                                            fill="currentColor" />
                                    </g>
                                </svg>
                            </div>
                            <div class="font-semibold ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">
                                    <?php echo db_num_rows("SELECT * FROM users_client WHERE status = 1 AND accountType = 'supplier'") ?>
                                </p>
                                <h5 class="text-xs text-[#506690]">Tedarikçi</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="panel h-full p-0">
                    <a href="dealers">
                        <div class="flex p-5">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                    <path
                                        d="M10 22C14.4183 22 18 18.4183 18 14C18 9.58172 14.4183 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2355 2.28008 16.4056 2.7802 17.4502C2.95209 17.8093 3.01245 18.2161 2.90955 18.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L5.39939 21.0904C5.78393 20.9876 6.19071 21.0479 6.54976 21.2198C7.5944 21.7199 8.76449 22 10 22Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 4.91015 19.0899 2 15.5 2C12.7977 2 10.4806 3.64899 9.5 5.9956"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <g opacity="0.5">
                                        <path
                                            d="M7.5 14C7.5 14.5523 7.05228 15 6.5 15C5.94772 15 5.5 14.5523 5.5 14C5.5 13.4477 5.94772 13 6.5 13C7.05228 13 7.5 13.4477 7.5 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M11 14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14C9 13.4477 9.44772 13 10 13C10.5523 13 11 13.4477 11 14Z"
                                            fill="currentColor" />
                                        <path
                                            d="M14.5 14C14.5 14.5523 14.0523 15 13.5 15C12.9477 15 12.5 14.5523 12.5 14C12.5 13.4477 12.9477 13 13.5 13C14.0523 13 14.5 13.4477 14.5 14Z"
                                            fill="currentColor" />
                                    </g>
                                </svg>
                            </div>
                            <div class="font-semibold ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">
                                    <?php echo db_num_rows("SELECT * FROM users_client WHERE status = 1 AND accountType = 'dealer'") ?>
                                </p>
                                <h5 class="text-xs text-[#506690]">Bayi</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="mb-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="panel h-full">
                    <!-- statistics -->
                    <div class="mb-5 flex items-center justify-between dark:text-white-light">
                        <h5 class="text-lg font-semibold">Gelir/Gider</h5>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <a href="javascript:;" @click="toggle">
                                <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                        stroke-width="1.5" />
                                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </a>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                                class="ltr:right-0 rtl:left-0">
                                <li><a href="javascript:;" @click="toggle">Bu Hafta</a></li>
                                <li><a href="javascript:;" @click="toggle">Geçen Hafta</a></li>
                                <li><a href="javascript:;" @click="toggle">Bu Ay</a></li>
                                <li><a href="javascript:;" @click="toggle">Geçen Ay</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="grid gap-8 text-sm font-bold text-[#515365] sm:grid-cols-2">
                        <div>
                            <div>
                                <div>Gelir</div>
                                <div class="text-lg text-[#f8538d]">
                                    <?php //echo db_sum_format("SELECT SUM(amount) as amount FROM accounting_transactions WHERE type='1'") ?>
                                </div>
                            </div>
                            <div x-ref="totalVisit" class="overflow-hidden"></div>
                        </div>

                        <div>
                            <div>
                                <div>Gider</div>
                                <div class="text-lg text-[#f8538d]">
                                    <?php //echo db_sum_format("SELECT SUM(amount) as amount FROM accounting_transactions WHERE type='0'") ?>
                                </div>
                            </div>
                            <div x-ref="paidVisit" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="panel h-full">
                    <!-- statistics -->
                    <div class="mb-5 flex items-center justify-between dark:text-white-light">
                        <h5 class="text-lg font-semibold">Alış/Satış</h5>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <a href="javascript:;" @click="toggle">
                                <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                        stroke-width="1.5" />
                                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </a>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                                class="ltr:right-0 rtl:left-0">
                                <li><a href="javascript:;" @click="toggle">Bu Hafta</a></li>
                                <li><a href="javascript:;" @click="toggle">Geçen Hafta</a></li>
                                <li><a href="javascript:;" @click="toggle">Bu Ay</a></li>
                                <li><a href="javascript:;" @click="toggle">Geçen Ay</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="grid gap-8 text-sm font-bold text-[#515365] sm:grid-cols-2">
                        <div>
                            <div>
                                <div>Alış</div>
                                <div class="text-lg text-[#f8538d]">
                                    <?php //echo db_sum_format("SELECT SUM(amount) as amount FROM invoices WHERE type='purchase'") ?>
                                </div>
                            </div>
                            <div x-ref="totalVisit1" class="overflow-hidden"></div>
                        </div>

                        <div>
                            <div>
                                <div>Satış</div>
                                <div class="text-lg text-[#f8538d]">
                                    <?php //echo db_sum_format("SELECT SUM(amount) as amount FROM invoices WHERE type='sale' AND status='completed'") ?>
                                </div>
                            </div>
                            <div x-ref="paidVisit1" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="panel h-full">
                    <div class="flex items-center justify-between" style="overflow-y: scroll;flex-direction: column;max-height: 106px;">
                        <?php
                        echo'<link rel="stylesheet" type="text/css" href="pages/manager/dashboard/notice.css" />';
                        
                        $sqlNotice = "SELECT * FROM `notice` WHERE dateDeadline > NOW() ORDER BY `id` DESC";
                        $sqlNotice = $conn->query($sqlNotice);
                        
                        if ($sqlNotice->num_rows > 0) {
                            // Duyurular varsa, döngü ile her birini göster
                            while ($row = $sqlNotice->fetch_assoc()) {
                                include "dashboard/notice.php";
                            }
                        } else {
                            // Duyuru yoksa, ortalanmış mesaj göster
                            echo '<div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
                                    <p style="font-size: 18px; color: #999;">Yeni Duyuru Yok</p>
                                  </div>';
                        }

                        ?>
                    </div>
                </div>
                
                <div class="panel h-full">
                    <div
                        class="-mx-5 mb-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 pt-0 dark:border-[#1b2e4b] dark:text-white-light">
                        <h5 class="text-lg font-semibold">İş Özeti</h5>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <a href="javascript:;" @click="toggle">
                                <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                        stroke-width="1.5" />
                                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </a>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                                class="ltr:right-0 rtl:left-0">
                                <li><a href="javascript:;" @click="toggle">Benim Projelerim</a></li>
                                <li><a href="javascript:;" @click="toggle">Ekip Projeleri</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="perfect-scrollbar relative -mr-3 h-[360px] pr-3">
                        <div class="space-y-7">
                        </div>
                    </div>
                </div>
                
                <div class="panel h-full" id="upcomingPaymentsPanel">
    <!-- statistics -->
    <div
        class="-mx-5 mb-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 pt-0 dark:border-[#1b2e4b] dark:text-white-light">
        <h5 class="text-lg font-semibold">Yaklaşan Ödemeler</h5>
        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
            <a href="javascript:;" @click="toggle">
                <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70"
                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                        stroke-width="1.5" />
                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                </svg>
            </a>
            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="ltr:right-0 rtl:left-0">
                <li><a href="javascript:;" @click="toggleVisibility()">Göster/Gizle</a></li>
            </ul>
        </div>
    </div>
    <div class="grid gap-8 text-sm font-bold text-[#515365] sm:grid-cols-2">
        <div>
            <div>
                <div>Alacaklar</div>
                <div id="receivablesAmount" class="text-lg text-success" style="display: none;">0.000,00₺</div>
                <br>
                <div class="perfect-scrollbar relative -mr-3 pr-3" style="height: 350px;">
                    <div id="receivablesList" class="space-y-7" style="display: none;">
                        <?php
                            echo "";
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div>
                <?php
                    $sqlStaticsGiveTotal = "
                        SELECT 
                            SUM(acc_rec.installment_amount) AS total_amount
                        FROM accounting_recurring_transactions acc_rec
                        INNER JOIN accounting_scheduled_payments acc_sched
                        ON acc_rec.transaction_id = acc_sched.id
                        WHERE acc_rec.payment_status = 0 
                        AND acc_sched.type = 0
                    ";
                    
                    $resultStaticsGiveTotal = $conn->query($sqlStaticsGiveTotal);
                    if ($resultStaticsGiveTotal->num_rows > 0) {
                        $rowStaticsGiveTotal = $resultStaticsGiveTotal->fetch_assoc();
                        $totalAmountStaticsGive = $rowStaticsGiveTotal['total_amount'];
                        $giveTotal = number_format($totalAmountStaticsGive, 2, ',', '.') . "₺";
                    }
                ?>
                <div>Verecekler</div>
                <div id="payablesAmount" class="text-lg text-danger" style="display: none;"><?php echo $giveTotal ?></div>
                <br>
                <div class="perfect-scrollbar relative -mr-3 pr-3" style="height: 350px;">
                    <div id="payablesList" class="space-y-7" style="display: none;">
                        <?php
                            $sqlStaticsGive = "
                                SELECT 
                                    acc_rec.installment_amount, 
                                    acc_sched.reciever, 
                                    acc_rec.due_date
                                FROM accounting_recurring_transactions acc_rec
                                INNER JOIN accounting_scheduled_payments acc_sched
                                ON acc_rec.transaction_id = acc_sched.id
                                WHERE acc_rec.payment_status = 0 
                                AND acc_sched.type = 0
                                ORDER BY acc_rec.due_date
                            ";
                            
                            $resultStaticsGive = $conn->query($sqlStaticsGive);
                            if ($resultStaticsGive->num_rows > 0) {
                                while ($rowStaticsGive = $resultStaticsGive->fetch_assoc()) {
                                    $dueDateStaticsGive = strtotime($rowStaticsGive['due_date']);
                                    $turkishMonthStaticsGive = $months[date('F', $dueDateStaticsGive)];
                                    $formattedDateStaticsGive = date('d', $dueDateStaticsGive) . ' ' . $turkishMonthStaticsGive . ' ' . date('Y', $dueDateStaticsGive);
                                    $currentDate = time();
                                    $textClass = ($dueDateStaticsGive < $currentDate) ? 'text-danger' : 'text-primary';
                                    ?>
                                    <div>
                                        <div class="text-sm text-gray-500"><?php echo $rowStaticsGive['reciever']; ?></div>
                                        <div class="flex justify-between items-center border-b pb-2">
                                            <div class="text-base text-sm"><?php echo $formattedDateStaticsGive; ?></div>
                                            <div class="text-base text-sm <?php echo $textClass; ?>"><?php echo number_format($rowStaticsGive['installment_amount'], 2, ',', '.'); ?>₺</div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<p class='text-center text-gray-500'>No payments found.</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleVisibility() {
        const receivablesList = document.getElementById('receivablesList');
        const payablesList = document.getElementById('payablesList');
        const receivablesAmount = document.getElementById('receivablesAmount');
        const payablesAmount = document.getElementById('payablesAmount');

        // Toggle visibility
        receivablesList.style.display = receivablesList.style.display === 'none' ? 'block' : 'none';
        payablesList.style.display = payablesList.style.display === 'none' ? 'block' : 'none';
        receivablesAmount.style.display = receivablesAmount.style.display === 'none' ? 'block' : 'none';
        payablesAmount.style.display = payablesAmount.style.display === 'none' ? 'block' : 'none';

        // Change button text
        const dropdownLink = document.querySelector('.dropdown a');
        dropdownLink.textContent = dropdownLink.textContent === 'Show' ? 'Hide' : 'Show';
    }
</script>


                <!-- Notlar -->
                <?php include ("dashboard/notes.php"); ?>
            </div>

            <div class="mb-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

            </div>

            <div class="mb-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Son Aktiviteler -->
                <div class="panel h-full">
                    <div
                        class="-mx-5 mb-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 pt-0 dark:border-[#1b2e4b] dark:text-white-light">
                        <h5 class="text-lg font-semibold">Son Hareketler</h5>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <a href="javascript:;" @click="toggle">
                                <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                        stroke-width="1.5" />
                                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </a>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                                class="ltr:right-0 rtl:left-0">
                                <li><a href="javascript:;" @click="toggle">View All</a></li>
                                <li><a href="javascript:;" @click="toggle">Mark as Read</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="perfect-scrollbar relative -mr-3 h-[360px] pr-3">
                        <div class="space-y-7"><!--
                            <div class="flex">
                                <div
                                    class="relative z-10 shrink-0 before:absolute before:top-10 before:left-4 before:h-[calc(100%-24px)] before:w-[2px] before:bg-white-dark/30 ltr:mr-2 rtl:ml-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-secondary text-white shadow shadow-secondary">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-semibold dark:text-white-light">
                                        New project created : <a href="javascript:;" class="text-success">[865] Makterm
                                            Katalog</a> <span class="text-white-dark">by Emin İ.</span>
                                    </h5>
                                    <p class="text-xs text-white-dark">Today 16:01</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div
                                    class="relative z-10 shrink-0 before:absolute before:top-10 before:left-4 before:h-[calc(100%-24px)] before:w-[2px] before:bg-white-dark/30 ltr:mr-2 rtl:ml-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-success text-white shadow-success">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                                stroke="currentColor" stroke-width="1.5" />
                                            <path
                                                d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908L18 8"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-semibold dark:text-white-light">
                                        New comment on <a href="javascript:;" class="text-success">[865] Makterm
                                            Katalog</a> <span class="text-white-dark">by</span> Cihad Turunç: <p
                                            class="text-white-dark">döneceğim size emin bey</p>
                                    </h5>
                                    <p class="text-xs text-white-dark">Today 13:56</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div
                                    class="relative z-10 shrink-0 before:absolute before:top-10 before:left-4 before:h-[calc(100%-24px)] before:w-[2px] before:bg-white-dark/30 ltr:mr-2 rtl:ml-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-success text-white shadow-success">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                                stroke="currentColor" stroke-width="1.5" />
                                            <path
                                                d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908L18 8"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-semibold dark:text-white-light">
                                        New comment on <a href="javascript:;" class="text-success">[865] Makterm
                                            Katalog</a> <span class="text-white-dark">by</span> Emin İnci: <p
                                            class="text-white-dark">Fiyat teklifi verildi daha sonra tasarım yapılacak.
                                        </p>
                                    </h5>
                                    <p class="text-xs text-white-dark">Today 13:43</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div
                                    class="relative z-10 shrink-0 before:absolute before:top-10 before:left-4 before:h-[calc(100%-24px)] before:w-[2px] before:bg-white-dark/30 ltr:mr-2 rtl:ml-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5" d="M4 12.9L7.14286 16.5L15 7.5" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M20.0002 7.5625L11.4286 16.5625L11.0002 16" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-semibold dark:text-white-light">
                                        Task Upgraded : <a href="javascript:;" class="text-success">[865] Makterm
                                            Katalog</a> <span class="text-white-dark">by</span> Emin İnci
                                    </h5>
                                    <p class="text-xs text-white-dark">Today 13:43</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div
                                    class="relative z-10 shrink-0 before:absolute before:top-10 before:left-4 before:h-[calc(100%-24px)] before:w-[2px] before:bg-white-dark/30 ltr:mr-2 rtl:ml-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-danger text-white">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5" d="M4 12.9L7.14286 16.5L15 7.5" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M20.0002 7.5625L11.4286 16.5625L11.0002 16" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-semibold dark:text-white-light">
                                        Task Completed : <a href="javascript:;" class="text-success">[865] Makterm
                                            Katalog</a> <span class="text-white-dark">by</span> Emin İnci
                                    </h5>
                                    <p class="text-xs text-white-dark">Today 13:43</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div
                                    class="relative z-10 shrink-0 before:absolute before:top-10 before:left-4 before:h-[calc(100%-24px)] before:w-[2px] before:bg-white-dark/30 ltr:mr-2 rtl:ml-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-warning text-white">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M15.3929 4.05365L14.8912 4.61112L15.3929 4.05365ZM19.3517 7.61654L18.85 8.17402L19.3517 7.61654ZM21.654 10.1541L20.9689 10.4592V10.4592L21.654 10.1541ZM3.17157 20.8284L3.7019 20.2981H3.7019L3.17157 20.8284ZM20.8284 20.8284L20.2981 20.2981L20.2981 20.2981L20.8284 20.8284ZM14 21.25H10V22.75H14V21.25ZM2.75 14V10H1.25V14H2.75ZM21.25 13.5629V14H22.75V13.5629H21.25ZM14.8912 4.61112L18.85 8.17402L19.8534 7.05907L15.8947 3.49618L14.8912 4.61112ZM22.75 13.5629C22.75 11.8745 22.7651 10.8055 22.3391 9.84897L20.9689 10.4592C21.2349 11.0565 21.25 11.742 21.25 13.5629H22.75ZM18.85 8.17402C20.2034 9.3921 20.7029 9.86199 20.9689 10.4592L22.3391 9.84897C21.9131 8.89241 21.1084 8.18853 19.8534 7.05907L18.85 8.17402ZM10.0298 2.75C11.6116 2.75 12.2085 2.76158 12.7405 2.96573L13.2779 1.5653C12.4261 1.23842 11.498 1.25 10.0298 1.25V2.75ZM15.8947 3.49618C14.8087 2.51878 14.1297 1.89214 13.2779 1.5653L12.7405 2.96573C13.2727 3.16993 13.7215 3.55836 14.8912 4.61112L15.8947 3.49618ZM10 21.25C8.09318 21.25 6.73851 21.2484 5.71085 21.1102C4.70476 20.975 4.12511 20.7213 3.7019 20.2981L2.64124 21.3588C3.38961 22.1071 4.33855 22.4392 5.51098 22.5969C6.66182 22.7516 8.13558 22.75 10 22.75V21.25ZM1.25 14C1.25 15.8644 1.24841 17.3382 1.40313 18.489C1.56076 19.6614 1.89288 20.6104 2.64124 21.3588L3.7019 20.2981C3.27869 19.8749 3.02502 19.2952 2.88976 18.2892C2.75159 17.2615 2.75 15.9068 2.75 14H1.25ZM14 22.75C15.8644 22.75 17.3382 22.7516 18.489 22.5969C19.6614 22.4392 20.6104 22.1071 21.3588 21.3588L20.2981 20.2981C19.8749 20.7213 19.2952 20.975 18.2892 21.1102C17.2615 21.2484 15.9068 21.25 14 21.25V22.75ZM21.25 14C21.25 15.9068 21.2484 17.2615 21.1102 18.2892C20.975 19.2952 20.7213 19.8749 20.2981 20.2981L21.3588 21.3588C22.1071 20.6104 22.4392 19.6614 22.5969 18.489C22.7516 17.3382 22.75 15.8644 22.75 14H21.25ZM2.75 10C2.75 8.09318 2.75159 6.73851 2.88976 5.71085C3.02502 4.70476 3.27869 4.12511 3.7019 3.7019L2.64124 2.64124C1.89288 3.38961 1.56076 4.33855 1.40313 5.51098C1.24841 6.66182 1.25 8.13558 1.25 10H2.75ZM10.0298 1.25C8.15538 1.25 6.67442 1.24842 5.51887 1.40307C4.34232 1.56054 3.39019 1.8923 2.64124 2.64124L3.7019 3.7019C4.12453 3.27928 4.70596 3.02525 5.71785 2.88982C6.75075 2.75158 8.11311 2.75 10.0298 2.75V1.25Z"
                                                fill="currentColor" />
                                            <path opacity="0.5"
                                                d="M13 2.5V5C13 7.35702 13 8.53553 13.7322 9.26777C14.4645 10 15.643 10 18 10H22"
                                                stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-semibold dark:text-white-light">
                                        Documents Submitted to <a href="javascript:;" class="text-success">[865] Makterm
                                            Katalog</a> <span class="text-white-dark">by</span> Emin İnci
                                    </h5>
                                    <p class="text-xs text-white-dark">Today 13:43</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div
                                    class="relative z-10 shrink-0 before:absolute before:top-10 before:left-4 before:h-[calc(100%-24px)] before:w-[2px] before:bg-white-dark/30 ltr:mr-2 rtl:ml-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-dark text-white">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" class="h-4 w-4">
                                            <path opacity="0.5"
                                                d="M2 17C2 15.1144 2 14.1716 2.58579 13.5858C3.17157 13 4.11438 13 6 13H18C19.8856 13 20.8284 13 21.4142 13.5858C22 14.1716 22 15.1144 22 17C22 18.8856 22 19.8284 21.4142 20.4142C20.8284 21 19.8856 21 18 21H6C4.11438 21 3.17157 21 2.58579 20.4142C2 19.8284 2 18.8856 2 17Z"
                                                stroke="currentColor" stroke-width="1.5" />
                                            <path opacity="0.5"
                                                d="M2 6C2 4.11438 2 3.17157 2.58579 2.58579C3.17157 2 4.11438 2 6 2H18C19.8856 2 20.8284 2 21.4142 2.58579C22 3.17157 22 4.11438 22 6C22 7.88562 22 8.82843 21.4142 9.41421C20.8284 10 19.8856 10 18 10H6C4.11438 10 3.17157 10 2.58579 9.41421C2 8.82843 2 7.88562 2 6Z"
                                                stroke="currentColor" stroke-width="1.5" />
                                            <path d="M11 6H18" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path d="M6 6H8" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path d="M11 17H18" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path d="M6 17H8" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-semibold dark:text-white-light">Server rebooted successfully</h5>
                                    <p class="text-xs text-white-dark">Today 13:43</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="shrink-0 ltr:mr-2 rtl:ml-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-dark text-white">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" class="h-4 w-4">
                                            <path opacity="0.5"
                                                d="M2 17C2 15.1144 2 14.1716 2.58579 13.5858C3.17157 13 4.11438 13 6 13H18C19.8856 13 20.8284 13 21.4142 13.5858C22 14.1716 22 15.1144 22 17C22 18.8856 22 19.8284 21.4142 20.4142C20.8284 21 19.8856 21 18 21H6C4.11438 21 3.17157 21 2.58579 20.4142C2 19.8284 2 18.8856 2 17Z"
                                                stroke="currentColor" stroke-width="1.5" />
                                            <path opacity="0.5"
                                                d="M2 6C2 4.11438 2 3.17157 2.58579 2.58579C3.17157 2 4.11438 2 6 2H18C19.8856 2 20.8284 2 21.4142 2.58579C22 3.17157 22 4.11438 22 6C22 7.88562 22 8.82843 21.4142 9.41421C20.8284 10 19.8856 10 18 10H6C4.11438 10 3.17157 10 2.58579 9.41421C2 8.82843 2 7.88562 2 6Z"
                                                stroke="currentColor" stroke-width="1.5" />
                                            <path d="M11 6H18" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path d="M6 6H8" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path d="M11 17H18" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path d="M6 17H8" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-semibold dark:text-white-light">Server rebooted successfully</h5>
                                    <p class="text-xs text-white-dark">Yesterday 13:43</p>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>

                <!-- Stok Durumu -->
                <div class="panel h-full">
                    <div
                        class="-mx-5 mb-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 pt-0 dark:border-[#1b2e4b] dark:text-white-light">
                        <h5 class="text-lg font-semibold">Stok Durumu</h5>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <a href="javascript:;" @click="toggle">
                                <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                        stroke-width="1.5" />
                                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </a>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                                class="ltr:right-0 rtl:left-0">
                                <li><a href="javascript:;" @click="toggle">View All</a></li>
                                <li><a href="javascript:;" @click="toggle">Mark as Read</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="perfect-scrollbar relative -mr-3 h-[360px] pr-3">
                        <div class="space-y-7">
                            <!--
                            include ("dashboard/reportStockEntry.php");
                            include ("dashboard/reportStockExit.php");
                            include ("dashboard/reportStockUpdate.php");
                            --!>
                        </div>
                    </div>
                </div>

                <!-- Personel İstatistikleri -->
                <div class="panel h-full">
                    <div
                        class="-mx-5 mb-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 pt-0 dark:border-[#1b2e4b] dark:text-white-light">
                        <h5 class="text-lg font-semibold">Personel İstatistikleri</h5>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <a href="javascript:;" @click="toggle">
                                <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                        stroke-width="1.5" />
                                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </a>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                                class="ltr:right-0 rtl:left-0">
                                <li><a href="javascript:;" @click="toggle">View All</a></li>
                                <li><a href="javascript:;" @click="toggle">Mark as Read</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="perfect-scrollbar relative -mr-3 h-[360px] pr-3">
                        <div class="space-y-7">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end main content section -->

</div>