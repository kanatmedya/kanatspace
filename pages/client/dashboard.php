<?php

include "assets/db/db_functions.php";

// Bu ayın ilk günü ve şu anki tarih
$startDate = date('Y-m-01 00:00:00');
$endDate = date('Y-m-d H:i:s');

// Şu anki tarih ve saat
$currentDateTime = date('Y-m-d H:i:s');

$name = $_SESSION['name'];
$cID = $_SESSION['userID'];
$likeName = "%$name%";

?>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div x-data="analytics">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <!--<li class="before:mr-1 before:content-['/'] rtl:before:ml-1">
                <span>Analytics</span>
            </li>-->
        </ul>
        <div class="pt-5">
            <div class="mb-6 grid grid-cols-2 gap-6 sm:grid-cols-4 xl:grid-cols-4">

                <div class="panel h-full p-0">
                    <a href="projects">
                        <div class="flex p-5">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-danger/10 text-danger dark:bg-danger dark:text-white-light">
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
                                    <?php
                                    // SQL sorgusu
                                    $sqlProjectPassed = "SELECT COUNT(*) FROM projects WHERE client = ? AND active = 1 AND status = 'Onayda'";
                                    $stmtProjectPassed = $conn->prepare($sqlProjectPassed);
                                    
                                    // Prepare işlemi başarılı mı kontrol et
                                    if ($stmtProjectPassed === false) {
                                        die("Sorgu hazırlama hatası: " . $conn->error);
                                    }
                                    
                                    // Parametreleri bağla
                                    $stmtProjectPassed->bind_param('s', $name);
                                    
                                    // Sorguyu çalıştır
                                    $stmtProjectPassed->execute();
                                    
                                    // Sonucu al ve ekrana yazdır
                                    $stmtProjectPassed->bind_result($count);
                                    $stmtProjectPassed->fetch();
                                    echo $count;
                                    
                                    // Bağlantıyı kapat
                                    $stmtProjectPassed->close();
                                    ?>
                                </p>
                                <h5 class="text-xs text-[#506690]">Onayınızı Bekleyen</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="panel h-full p-0">
                    <a href="projects">
                        <div class="flex p-5">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary dark:bg-primary dark:text-white-light">
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
                                    <?php
                                    
                                    // SQL sorgusu
                                    $sqlProjectTotal = "SELECT COUNT(*) FROM projects WHERE client = ? AND active = 1 AND (status<>'Tamamlandı' AND status<>'Reddedildi')";
                                    $stmtProjectTotal  = $conn->prepare($sqlProjectTotal);
                                    
                                    // Parametreleri bağla
                                    $stmtProjectTotal ->bind_param('s', $name);
                                    
                                    // Sorguyu çalıştır
                                    $stmtProjectTotal ->execute();
                                    
                                    // Sonucu al ve ekrana yazdır
                                    $stmtProjectTotal ->bind_result($count);
                                    $stmtProjectTotal ->fetch();
                                    
                                    echo $count;
                                    
                                    $stmtProjectTotal->close();
                                    ?></p>
                                <h5 class="text-xs text-[#506690]">Aktif Proje</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="panel h-full p-0">
                    <a href="projects">
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
                                    <?php
                                    
                                    // SQL sorgusu
                                    $sqlProjectDevam = "SELECT COUNT(*) FROM projects WHERE client = ? AND active = 1 AND (status = 'Devam Eden')";
                                    
                                    $stmtProjectDevam  = $conn->prepare($sqlProjectDevam);
                                    
                                    // Parametreleri bağla
                                    $stmtProjectDevam->bind_param('s', $name);
                                    
                                    // Sorguyu çalıştır
                                    $stmtProjectDevam->execute();
                                    
                                    // Sonucu al ve ekrana yazdır
                                    $stmtProjectDevam->bind_result($count);
                                    $stmtProjectDevam->fetch();
                                    
                                    echo $count;
                                    
                                    $stmtProjectDevam->close();
                                    
                                    ?>
                                </p>
                                <h5 class="text-xs text-[#506690]">Devam Eden</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="panel h-full p-0">
                    <a href="projects">
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
                                    <?php
                                    // SQL sorgusu
                                    $sqlProjectPassed = "SELECT COUNT(*) FROM projects WHERE client = ?  AND active = 1 AND status = 'Tamamlandı'";
                                    $stmtProjectPassed = $conn->prepare($sqlProjectPassed);
                                    
                                    // Prepare işlemi başarılı mı kontrol et
                                    if ($stmtProjectPassed === false) {
                                        die("Sorgu hazırlama hatası: " . $conn->error);
                                    }
                                    
                                    // Parametreleri bağla
                                    $stmtProjectPassed->bind_param('s', $name);
                                    
                                    // Sorguyu çalıştır
                                    $stmtProjectPassed->execute();
                                    
                                    // Sonucu al ve ekrana yazdır
                                    $stmtProjectPassed->bind_result($count);
                                    $stmtProjectPassed->fetch();
                                    echo $count;
                                    
                                    // Bağlantıyı kapat
                                    $stmtProjectPassed->close();
                                    ?>
                                </p>
                                <h5 class="text-xs text-[#506690]">Tamamlanan</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="mb-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <div class="panel h-full">
                    <div class="-mx-5 mb-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 pt-0 dark:border-[#1b2e4b] dark:text-white-light">
                        <h5 class="text-lg font-semibold">Duyurular</h5>
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
                    <div class="flex items-center justify-between">

                    </div>
                </div>
                
                <div class="panel h-full">
                    <div class="-mx-5 mb-5 flex items-start justify-between border-b border-[#e0e6ed] p-5 pt-0 dark:border-[#1b2e4b] dark:text-white-light">
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

                <!-- Son Aktiviteler -->
                <?php include ("dashboard/notes.php"); ?>
            </div>