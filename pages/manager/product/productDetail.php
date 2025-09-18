<?php

include ("../../../db/db_connect.php");

$sor = "SELECT * FROM products WHERE id=$selected_id";

$selected_result = $conn->query($sor);
$selected_product = $selected_result->fetch_assoc();
$image_path = htmlspecialchars(trim($selected_product['photos']));
 ?>  
<div>

                        <div class="panel gap-10 lg:flex">
                            <div class="mx-auto sm:w-1/2 lg:w-1/3">
                                <div class="sticky top-20 z-[1]">
                                    <div class="relative">
                                        <img src="assets/images/product/product-7.jpg" alt="" class="h-auto w-full rounded" />
                                        <a
                                            href="javascript:;"
                                            class="swiper-button-prev-ex1 swiper-button-disabled absolute top-1/2 grid -translate-y-1/2 place-content-center rounded-full border border-primary p-1 text-primary transition hover:border-primary hover:bg-primary hover:text-white ltr:left-2 rtl:right-2"
                                            tabindex="-1"
                                            role="button"
                                            aria-label="Previous slide"
                                            aria-controls="swiper-wrapper-10d386118d4e4d5fd"
                                            aria-disabled="true"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="feather feather-chevron-left h-5 w-5 rtl:rotate-180"
                                            >
                                                <polyline points="15 18 9 12 15 6"></polyline>
                                            </svg>
                                        </a>

                                        <a
                                            href="javascript:;"
                                            class="swiper-button-next-ex1 absolute top-1/2 grid -translate-y-1/2 place-content-center rounded-full border border-primary p-1 text-primary transition hover:border-primary hover:bg-primary hover:text-white ltr:right-2 rtl:left-2"
                                            tabindex="0"
                                            role="button"
                                            aria-label="Next slide"
                                            aria-controls="swiper-wrapper-10d386118d4e4d5fd"
                                            aria-disabled="false"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="feather feather-chevron-right h-5 w-5 rtl:rotate-180"
                                            >
                                                <polyline points="9 18 15 12 9 6"></polyline>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="mt-4 grid grid-cols-3 gap-4">
                                        <img src="assets/images/product/product-2.jpg" alt="" class="w-full rounded" />
                                        <img src="assets/images/product/product-7.jpg" alt="" class="w-full rounded" />
                                        <img src="assets/images/product/product-4.jpg" alt="" class="w-full rounded" />
                                    </div>
                                </div>
                            </div>
                            <div class="mt-10 lg:mt-0 lg:w-2/3">
                                <h2 class="mb-3 text-lg font-bold text-dark dark:text-white md:text-xl">Full Sleeve Sweatshirt for Men (Pink)</h2>
                                <div class="flex flex-wrap gap-4">
                                    <div class="">Tommy Hilfiger</div>
                                    <div>|</div>
                                    <div class="">Seller : <b>Zoetic Fashion</b></div>
                                    <div>|</div>
                                    <div class="">Published : <b>26 Mar, 2021</b></div>
                                </div>
                                <div class="mt-6 flex gap-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffc107" class="h-4 w-4">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            fill="#ffc107"
                                            d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                        />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffc107" class="h-4 w-4">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            fill="#ffc107"
                                            d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                        />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffc107" class="h-4 w-4">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            fill="#ffc107"
                                            d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                        />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffc107" class="h-4 w-4">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            fill="#ffc107"
                                            d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                        />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#E0E6ED" class="h-4 w-4">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            fill="#E0E6ED"
                                            d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                        />
                                    </svg>
                                    <div class="ltr:ml-2 rtl:mr-2">( <strong>5.50k</strong> Customer Review )</div>
                                </div>
                                <div class="my-4">
                                    <div class="mb-1 font-bold text-success">Special price</div>
                                    <span class="text-xl"><b>$120.40</b></span>
                                </div>
                                <div class="grid gap-5 xl:grid-cols-2">
                                    <div>
                                        <h5 class="mb-3 font-bold">Sizes :</h5>
                                        <div class="flex items-center gap-2">
                                            <label class="relative flex h-10 w-10 items-center justify-center">
                                                <input type="radio" name="size_radio" class="peer absolute h-full w-full cursor-pointer opacity-0" />
                                                <span
                                                    class="flex h-full w-full flex-1 items-center justify-center rounded-full border border-dark/20 bg-transparent transition peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary"
                                                    >S</span
                                                >
                                            </label>
                                            <label class="relative flex h-10 w-10 items-center justify-center">
                                                <input type="radio" name="size_radio" class="peer absolute h-full w-full cursor-pointer opacity-0" />
                                                <span
                                                    class="flex h-full w-full flex-1 items-center justify-center rounded-full border border-dark/20 bg-transparent transition peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary"
                                                    >M</span
                                                >
                                            </label>
                                            <label class="relative flex h-10 w-10 items-center justify-center">
                                                <input type="radio" name="size_radio" class="peer absolute h-full w-full cursor-pointer opacity-0" />
                                                <span
                                                    class="flex h-full w-full flex-1 items-center justify-center rounded-full border border-dark/20 bg-transparent transition peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary"
                                                    >L</span
                                                >
                                            </label>
                                            <label class="relative flex h-10 w-10 items-center justify-center">
                                                <input type="radio" name="size_radio" class="peer absolute h-full w-full cursor-pointer opacity-0" />
                                                <span
                                                    class="flex h-full w-full flex-1 items-center justify-center rounded-full border border-dark/20 bg-transparent transition peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary"
                                                    >XL</span
                                                >
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-3 font-bold">Colors :</h5>
                                        <div class="flex items-center gap-2">
                                            <label class="relative flex h-10 w-10 items-center justify-center">
                                                <input type="radio" name="color_radio" class="peer absolute h-full w-full cursor-pointer opacity-0" />
                                                <span
                                                    class="flex h-full w-full flex-1 items-center justify-center rounded-full border-2 border-dark/20 bg-secondary transition peer-checked:border-black"
                                                ></span>
                                            </label>
                                            <label class="relative flex h-10 w-10 items-center justify-center">
                                                <input type="radio" name="color_radio" class="peer absolute h-full w-full cursor-pointer opacity-0" />
                                                <span
                                                    class="flex h-full w-full flex-1 items-center justify-center rounded-full border-2 border-dark/20 bg-white transition peer-checked:border-black"
                                                ></span>
                                            </label>
                                            <label class="relative flex h-10 w-10 items-center justify-center">
                                                <input type="radio" name="color_radio" class="peer absolute h-full w-full cursor-pointer opacity-0" />
                                                <span
                                                    class="flex h-full w-full flex-1 items-center justify-center rounded-full border-2 border-dark/20 bg-primary transition peer-checked:border-black"
                                                ></span>
                                            </label>
                                            <label class="relative flex h-10 w-10 items-center justify-center">
                                                <input type="radio" name="color_radio" class="peer absolute h-full w-full cursor-pointer opacity-0" />
                                                <span
                                                    class="flex h-full w-full flex-1 items-center justify-center rounded-full border-2 border-dark/20 bg-dark transition peer-checked:border-black"
                                                ></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-8">
                                    <h5 class="mb-3 font-bold">Description :</h5>
                                    <p>
                                        Tommy Hilfiger men striped pink sweatshirt. Crafted with cotton. Material composition is 100% organic cotton. This is
                                        one of the world’s leading designer lifestyle brands and is internationally recognized for celebrating the essence of
                                        classic American cool style, featuring preppy with a twist designs.
                                    </p>
                                </div>
                                <div class="mt-8 grid gap-5 md:grid-cols-2">
                                    <div>
                                        <h5 class="mb-3 font-bold">Features :</h5>
                                        <ul class="list-inside list-disc space-y-2">
                                            <li>Full Sleeve</li>
                                            <li>Cotton</li>
                                            <li>All Sizes available</li>
                                            <li>4 Different Color</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h5 class="mb-3 font-bold">Services :</h5>
                                        <ul class="list-inside list-disc space-y-2">
                                            <li>10 Days Replacement</li>
                                            <li>Cash on Delivery available</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mt-8">
                                    <h5 class="mb-3 font-bold">Product Description :</h5>
                                    <div class="mb-5" x-data="{ tab: 'specification'}">
                                        <div>
                                            <ul class="mt-3 mb-5 flex flex-wrap border-b border-white-light dark:border-[#191e3a]">
                                                <li>
                                                    <a
                                                        href="javascript:"
                                                        class="relative -mb-[1px] flex items-center p-5 py-3 font-bold before:absolute before:bottom-0 before:left-0 before:right-0 before:m-auto before:h-[1px] before:w-0 before:bg-primary before:transition-all before:duration-700 hover:text-primary hover:before:w-full"
                                                        :class="{'before:w-full text-primary bg-primary/10' : tab === 'specification'}"
                                                        @click="tab='specification'"
                                                        >Specification</a
                                                    >
                                                </li>
                                                <li>
                                                    <a
                                                        href="javascript:"
                                                        class="relative -mb-[1px] flex items-center p-5 py-3 font-bold before:absolute before:bottom-0 before:left-0 before:right-0 before:m-auto before:h-[1px] before:w-0 before:bg-primary before:transition-all before:duration-700 hover:text-primary hover:before:w-full"
                                                        :class="{'before:w-full text-primary bg-primary/10' : tab === 'details'}"
                                                        @click="tab='details'"
                                                        >Details</a
                                                    >
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="flex-1 text-sm">
                                            <template x-if="tab === 'specification'">
                                                <div class="table-responsive mt-5 overflow-x-auto">
                                                    <table class="table-striped table-hover text-left 2xl:w-1/2">
                                                        <tr>
                                                            <td class="font-bold">Category</td>
                                                            <td>T-Shirt</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-bold">Brand</td>
                                                            <td>Tommy Hilfiger</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-bold">Color</td>
                                                            <td>Blue</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-bold">Material</td>
                                                            <td>Cotton</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-bold">Weight</td>
                                                            <td>140 Gram</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </template>
                                            <template x-if="tab === 'details'">
                                                <div>
                                                    <h3 class="mb-3 text-lg font-bold md:text-xl">Tommy Hilfiger Sweatshirt for Men (Pink)</h3>
                                                    <p>
                                                        Tommy Hilfiger men striped pink sweatshirt. Crafted with cotton. Material composition is 100% organic
                                                        cotton. This is one of the world’s leading designer lifestyle brands and is internationally recognized
                                                        for celebrating the essence of classic American cool style, featuring preppy with a twist designs.
                                                    </p>
                                                    <ul class="mt-4 list-inside list-disc space-y-2">
                                                        <li>Machine Wash</li>
                                                        <li>Fit Type: Regular</li>
                                                        <li>100% Cotton</li>
                                                        <li>Long sleeve</li>
                                                    </ul>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-8 xl:w-1/3">
                                    <h5 class="mb-3 font-bold">Customer reviews</h5>
                                    <div class="flex items-center justify-between rounded-md bg-white-dark/5 p-4">
                                        <div class="flex gap-0.5">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="#ffc107"
                                                class="h-4 w-4"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    fill="#ffc107"
                                                    d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                                />
                                            </svg>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="#ffc107"
                                                class="h-4 w-4"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    fill="#ffc107"
                                                    d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                                />
                                            </svg>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="#ffc107"
                                                class="h-4 w-4"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    fill="#ffc107"
                                                    d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                                />
                                            </svg>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="#ffc107"
                                                class="h-4 w-4"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    fill="#ffc107"
                                                    d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                                />
                                            </svg>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="#E0E6ED"
                                                class="h-4 w-4"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    fill="#E0E6ED"
                                                    d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                                />
                                            </svg>
                                        </div>
                                        <span class="font-bold">4.1 out of 5</span>
                                    </div>
                                    <p class="mt-1.5">Total <span class="font-bold">5.50k</span> reviews</p>
                                </div>
                                <div class="divide-y divide-white-dark/40">
                                    <div class="mt-5 pt-5">
                                        <div class="flex items-center gap-4">
                                            <img src="assets/images/profile-1.jpeg" alt="" class="h-8 w-8 rounded-full" />
                                            <h6>Mohammed Suhail</h6>
                                        </div>
                                        <div class="my-4 flex items-center gap-4">
                                            <div class="flex items-center gap-1 rounded bg-success py-1 px-2 text-white">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="#ffc107"
                                                    class="h-4 w-4"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        fill="#ffc107"
                                                        d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                                    />
                                                </svg>
                                                <span>4</span>
                                            </div>
                                            <h6 class="text-base font-semibold">Good Product</h6>
                                            <span class="text-xs">12 July 2021</span>
                                        </div>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut placerat eros ac dolor sodales, nec dapibus nunc rutrum.
                                            Sed non molestie eros, vel semper enim. Nulla facilisi. Proin in urna id metus imperdiet iaculis. Vestibulum
                                            dignissim purus sit amet arcu luctus, quis interdum ligula lacinia.
                                        </p>
                                        <p>
                                            Nunc ac finibus leo. Ut iaculis lectus fringilla felis luctus malesuada. Vestibulum gravida, orci et elementum
                                            vehicula, magna turpis finibus leo, et posuere neque elit et nisl. Morbi dignissim quam mauris, non finibus orci
                                            lacinia in. Nulla nec nibh arcu. Etiam id odio quis dui lacinia sollicitudin. Ut a sodales odio. Curabitur a quam
                                            ipsum. Proin ut arcu non dolor malesuada finibus. In diam ipsum, elementum ut ultrices nec, elementum in sem.
                                            Integer nec pellentesque sem.
                                        </p>
                                    </div>
                                    <div class="mt-5 pt-5">
                                        <div class="flex items-center gap-4">
                                            <img src="assets/images/profile-1.jpeg" alt="" class="h-8 w-8 rounded-full" />
                                            <h6>Mohammed Suhail</h6>
                                        </div>
                                        <div class="my-4 flex items-center gap-4">
                                            <div class="flex items-center gap-1 rounded bg-success py-1 px-2 text-white">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="#ffc107"
                                                    class="h-4 w-4"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        fill="#ffc107"
                                                        d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                                    />
                                                </svg>
                                                <span>4</span>
                                            </div>
                                            <h6 class="text-base font-semibold">Good Product</h6>
                                            <span class="text-xs">12 July 2021</span>
                                        </div>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut placerat eros ac dolor sodales, nec dapibus nunc rutrum.
                                            Sed non molestie eros, vel semper enim. Nulla facilisi. Proin in urna id metus imperdiet iaculis. Vestibulum
                                            dignissim purus sit amet arcu luctus, quis interdum ligula lacinia.
                                        </p>
                                        <p>
                                            Nunc ac finibus leo. Ut iaculis lectus fringilla felis luctus malesuada. Vestibulum gravida, orci et elementum
                                            vehicula, magna turpis finibus leo, et posuere neque elit et nisl. Morbi dignissim quam mauris, non finibus orci
                                            lacinia in. Nulla nec nibh arcu. Etiam id odio quis dui lacinia sollicitudin. Ut a sodales odio. Curabitur a quam
                                            ipsum. Proin ut arcu non dolor malesuada finibus. In diam ipsum, elementum ut ultrices nec, elementum in sem.
                                            Integer nec pellentesque sem.
                                        </p>
                                    </div>
                                    <div class="mt-5 pt-5">
                                        <div class="flex items-center gap-4">
                                            <img src="assets/images/profile-1.jpeg" alt="" class="h-8 w-8 rounded-full" />
                                            <h6>Mohammed Suhail</h6>
                                        </div>
                                        <div class="my-4 flex items-center gap-4">
                                            <div class="flex items-center gap-1 rounded bg-success py-1 px-2 text-white">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="#ffc107"
                                                    class="h-4 w-4"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        fill="#ffc107"
                                                        d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                                                    />
                                                </svg>
                                                <span>4</span>
                                            </div>
                                            <h6 class="text-base font-semibold">Good Product</h6>
                                            <span class="text-xs">12 July 2021</span>
                                        </div>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut placerat eros ac dolor sodales, nec dapibus nunc rutrum.
                                            Sed non molestie eros, vel semper enim. Nulla facilisi. Proin in urna id metus imperdiet iaculis. Vestibulum
                                            dignissim purus sit amet arcu luctus, quis interdum ligula lacinia.
                                        </p>
                                        <p>
                                            Nunc ac finibus leo. Ut iaculis lectus fringilla felis luctus malesuada. Vestibulum gravida, orci et elementum
                                            vehicula, magna turpis finibus leo, et posuere neque elit et nisl. Morbi dignissim quam mauris, non finibus orci
                                            lacinia in. Nulla nec nibh arcu. Etiam id odio quis dui lacinia sollicitudin. Ut a sodales odio. Curabitur a quam
                                            ipsum. Proin ut arcu non dolor malesuada finibus. In diam ipsum, elementum ut ultrices nec, elementum in sem.
                                            Integer nec pellentesque sem.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>