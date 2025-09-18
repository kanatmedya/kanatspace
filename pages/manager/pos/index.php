    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Select2 CSS dosyası -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="pages/manager/pos/styles.css">

    <div class="container mx-auto px-4 py-2 min-h-screen flex flex-col">
        <div class="grid grid-cols-12 gap-4 mb-4">
            <!-- Sol Taraf: Barkod Arama -->
            <div class="col-span-7">
                <div class="flex items-center h-full">
                    <div class="flex-grow">
                        <input id="barcodeInput" type="text" class="w-full border-2 border-gray-300 p-3 h-12 rounded-md focus:outline-none focus:border-blue-500 text-base" placeholder="BARKOD VE ÜRÜN ARAMA" aria-label="Barkod ve ürün arama">
                    </div>
                    <button class="ml-2 bg-blue-500 text-white h-12 w-12 rounded-md hover:bg-blue-600 focus:outline-none flex items-center justify-center" aria-label="Ara" tabindex="0" onclick="handleSearch()" onkeydown="handleKeyDown(event, handleSearch)">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            <!-- Sağ Taraf: Cari Hesap -->
            <div class="col-span-5">
                <div class="flex items-center h-full">
                    <div class="flex-grow">
                        <select id="accountSelection" class="w-full border-2 border-gray-300 p-3 h-12 rounded-md focus:outline-none focus:border-blue-500 text-base" aria-label="Cari hesap seçimi" data-placeholder="CARİ HESAP SEÇİMİ">
                            <option value="">CARİ HESAP SEÇİMİ</option>
                            <option value="1" data-info="Genel perakende müşteri bilgileri, VNo: 123456">Genel Müşteri</option>
                            <option value="2" data-info="Market alışveriş bilgileri, Son işlem: 15.03.2023">Market Müşteri</option>
                            <option value="3" data-info="Toptan müşteri bilgileri, Bakiye: 12.500₺">Toptan Müşteri</option>
                            <option value="4" data-info="Satış temsilcisi: Ahmet Yılmaz, Tel: 0555 123 4567">Kurumsal Müşteri</option>
                            <option value="5" data-info="Adres: İstanbul, Tel: 0212 123 45 67, Vergi D: Beşiktaş">X Firma</option>
                            <option value="6" data-info="Adres: Ankara, Tel: 0312 123 45 67, Vergi D: Çankaya">Y Firma</option>
                            <option value="7" data-info="Adres: İzmir, Tel: 0232 123 45 67, Vergi D: Konak">Z Firma</option>
                            <option value="8" data-info="Vergi No: 1234567890, Son alışveriş: 25.000₺">A Şirketi</option>
                            <option value="9" data-info="Vergi No: 0987654321, E-mail: info@bsirketi.com">B Şirketi</option>
                            <option value="10" data-info="Bakiye: 45.750,00₺, Ödeme vadesi: 30 gün">C Şirketi</option>
                            <option value="11" data-info="Perakende satış, İlgili kişi: Mehmet Kaya">Demir Ltd.</option>
                            <option value="12" data-info="Toptan alım, Sektör: İnşaat, Tel: 0212 987 65 43">Yapı Market</option>
                            <option value="13" data-info="Adres: Antalya, Turizm, İlgili: Ayşe Demir">Sahil Otel</option>
                            <option value="14" data-info="Adres: Bursa, Telefon: 0224 567 89 01">Tekstil A.Ş.</option>
                            <option value="15" data-info="E-ticaret müşterisi, Son sipariş: 27.03.2023">Online Satış</option>
                        </select>
                    </div>
                    <button class="ml-2 bg-blue-500 text-white h-12 w-12 rounded-md hover:bg-blue-600 focus:outline-none flex items-center justify-center" aria-label="Tam ekran" tabindex="0" onclick="handleFullScreen()" onkeydown="handleKeyDown(event, handleFullScreen)">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4">
            <!-- Sol Taraf: Barkod ve Sepet -->
            <div class="col-span-7 flex flex-col">
                <!-- Sepet İçeriği -->
                <div class="flex-grow mb-2">
                    <h2 class="text-xl font-bold text-center mb-3">SEPETTEKİ ÜRÜNLER</h2>
                    <div class="overflow-auto h-[450px] border border-gray-300 rounded-md">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ürün</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Miktar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KDV</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fiyat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toplam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlem</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="cartProducts">
                                <!-- Sepetteki ürünler buraya gelecek -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Alt kısım - Toplam, Sepet Butonları ve İşlemler -->
                <div class="bg-gray-100 p-2 rounded-md">
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex-1">
                            <div class="flex items-center mb-1">
                                <span class="text-gray-600 w-20 text-sm">VERGİLER</span>
                                <span class="text-gray-600 font-medium" id="taxTotal">0,00₺</span>
                            </div>
                            <div class="flex items-center font-bold">
                                <span class="w-20 text-base">TOPLAM</span>
                                <span id="grandTotal" class="text-base">0,00₺</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="bg-red-500 text-white py-1.5 px-4 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 font-medium shadow-sm transition-all text-sm" aria-label="Sepeti sil" title="Sepeti kaldır" tabindex="0" onclick="handleDeleteCart()" onkeydown="handleKeyDown(event, handleDeleteCart)">
                                <i class="fas fa-trash mr-1"></i> SİL
                            </button>
                            <button class="bg-green-500 text-white py-1.5 px-4 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 font-medium shadow-sm transition-all text-sm" aria-label="Yeni sepet" title="Yeni sepet ekle" tabindex="0" onclick="handlePutCartOnHold()" onkeydown="handleKeyDown(event, handlePutCartOnHold)">
                                <i class="fas fa-plus mr-1"></i> BEKLET
                            </button>
                        </div>
                    </div>

                    <!-- Sepet seçimleri -->
                    <div class="flex mt-1 items-center">
                        <!-- Kaydırılabilir sepet butonları konteyneri -->
                        <div id="cartButtonsScroll" class="flex-grow overflow-x-auto py-1 scrollbar-hide" style="scroll-behavior: smooth; -webkit-overflow-scrolling: touch;">
                            <div id="cartButtonsContainer" class="flex space-x-1 px-1">
                                <!-- Sepet butonları script.js tarafından dinamik olarak eklenecek -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sağ Taraf: Cari Hesap, Kategoriler, Ödeme Seçenekleri -->
            <div class="col-span-5 flex flex-col">
                <!-- Kategoriler ve Ürünler -->
                <div class="grid grid-cols-12 gap-0 mb-4">
                    <!-- Kategori Listesi -->
                    <div id="categoryContainer" class="col-span-4 overflow-auto h-80 border border-gray-300 rounded-l-md rounded-r-none border-r-0">
                        <ul class="divide-y divide-gray-200" id="categoryList">
                            <li class="category-item p-2 hover:bg-blue-50 cursor-pointer" data-kategori="kirtasiye" onclick="handleCategorySelect('kirtasiye')" onkeydown="handleKeyDown(event, function() { handleCategorySelect('kirtasiye'); })" tabindex="0">Kırtasiye Ürünleri</li>
                            <li class="category-item p-2 hover:bg-blue-50 cursor-pointer" data-kategori="elektrik" onclick="handleCategorySelect('elektrik')" onkeydown="handleKeyDown(event, function() { handleCategorySelect('elektrik'); })" tabindex="0">Elektrik ve Elektronik</li>
                            <li class="category-item p-2 hover:bg-blue-50 cursor-pointer" data-kategori="bilgisayar" onclick="handleCategorySelect('bilgisayar')" onkeydown="handleKeyDown(event, function() { handleCategorySelect('bilgisayar'); })" tabindex="0">Bilgisayar Malzemeleri</li>
                            <li class="category-item p-2 hover:bg-blue-50 cursor-pointer" data-kategori="gida" onclick="handleCategorySelect('gida')" onkeydown="handleKeyDown(event, function() { handleCategorySelect('gida'); })" tabindex="0">Gıda</li>
                            <li class="category-item p-2 hover:bg-blue-50 cursor-pointer" data-kategori="oyuncak" onclick="handleCategorySelect('oyuncak')" onkeydown="handleKeyDown(event, function() { handleCategorySelect('oyuncak'); })" tabindex="0">Seks Oyuncakları</li>
                            <li class="category-item p-2 hover:bg-blue-50 cursor-pointer" data-kategori="kitap" onclick="handleCategorySelect('kitap')" onkeydown="handleKeyDown(event, function() { handleCategorySelect('kitap'); })" tabindex="0">Kitap</li>
                            <li class="category-item p-2 hover:bg-blue-50 cursor-pointer" data-kategori="manav" onclick="handleCategorySelect('manav')" onkeydown="handleKeyDown(event, function() { handleCategorySelect('manav'); })" tabindex="0">Manav</li>
                            <li class="category-item p-2 hover:bg-blue-50 cursor-pointer" data-kategori="bakim" onclick="handleCategorySelect('bakim')" onkeydown="handleKeyDown(event, function() { handleCategorySelect('bakim'); })" tabindex="0">Kişisel Bakım</li>
                            <li class="category-item p-2 hover:bg-blue-50 cursor-pointer" data-kategori="sarkuteri" onclick="handleCategorySelect('sarkuteri')" onkeydown="handleKeyDown(event, function() { handleCategorySelect('sarkuteri'); })" tabindex="0">Şarküteri</li>
                            <li class="category-item p-2 hover:bg-blue-50 cursor-pointer" data-kategori="hijyen" onclick="handleCategorySelect('hijyen')" onkeydown="handleKeyDown(event, function() { handleCategorySelect('hijyen'); })" tabindex="0">Hijyen</li>
                            <li class="category-item p-2 hover:bg-blue-50 cursor-pointer" data-kategori="boya" onclick="handleCategorySelect('boya')" onkeydown="handleKeyDown(event, function() { handleCategorySelect('boya'); })" tabindex="0">Boya (Kadın)</li>
                        </ul>
                    </div>

                    <!-- Ürün Grid'i -->
                    <div class="col-span-8 overflow-auto h-80 border border-gray-300 rounded-r-md">
                        <div class="grid grid-cols-2 gap-2 p-2" id="productsGrid">
                            <!-- Ürünler script.js tarafından dinamik olarak eklenecek -->
                        </div>
                    </div>
                </div>

                <!-- Ödeme Alanı -->
                <div class="mt-auto">
                    <!-- Ödeme Seçenekleri Butonlar -->
                    <div class="grid grid-cols-4 gap-2 mb-4">
                        <button id="paymentBtn" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none active:bg-blue-700" aria-label="Ödeme" tabindex="0" onclick="handlePaymentSelection('odeme')" onkeydown="handleKeyDown(event, function() { handlePaymentSelection('odeme'); })">
                            ÖDEME
                        </button>
                        <button id="changeBtn" class="bg-white text-gray-700 py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-100 focus:outline-none" aria-label="Paraüstü" tabindex="0" onclick="handlePaymentSelection('paraustu')" onkeydown="handleKeyDown(event, function() { handlePaymentSelection('paraustu'); })">
                            Paraüstü
                        </button>
                        <button id="discountBtn" class="bg-white text-gray-700 py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-100 focus:outline-none" aria-label="İskonto" tabindex="0" onclick="handlePaymentSelection('iskonto')" onkeydown="handleKeyDown(event, function() { handlePaymentSelection('iskonto'); })">
                            İSKONTO
                        </button>
                        <button id="taxBtn" class="bg-white text-gray-700 py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-100 focus:outline-none" aria-label="Vergi" tabindex="0" onclick="handlePaymentSelection('vergi')" onkeydown="handleKeyDown(event, function() { handlePaymentSelection('vergi'); })">
                            VERGİ
                        </button>
                    </div>

                    <!-- Ödeme Paneli -->
                    <div id="paymentPanel" class="mb-4">
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <div class="border border-gray-300 rounded-md p-4 text-right">
                                <div class="text-2xl font-bold mb-1">65.657,79</div>
                                <div class="text-sm text-gray-500">ALINAN</div>
                            </div>
                            <div class="border border-gray-300 rounded-md p-4 text-right">
                                <div class="text-2xl font-bold mb-1">65.657,79</div>
                                <div class="text-sm text-gray-500">VERİNİZ/ALINIZ</div>
                            </div>
                        </div>

                        <!-- Hızlı Tutar Butonları -->
                        <div class="grid grid-cols-5 gap-2 mb-4">
                            <button class="bg-gray-400 font-bold py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-500 focus:outline-none" aria-label="5 TL" tabindex="0" onclick="handleQuickAmount(5)" onkeydown="handleKeyDown(event, function() { handleQuickAmount(5); })">
                                5
                            </button>
                            <button class="bg-gray-400 font-bold py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-500 focus:outline-none" aria-label="10 TL" tabindex="0" onclick="handleQuickAmount(10)" onkeydown="handleKeyDown(event, function() { handleQuickAmount(10); })">
                                10
                            </button>
                            <button class="bg-gray-400 font-bold py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-500 focus:outline-none" aria-label="20 TL" tabindex="0" onclick="handleQuickAmount(20)" onkeydown="handleKeyDown(event, function() { handleQuickAmount(20); })">
                                20
                            </button>
                            <button class="bg-gray-400 font-bold py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-500 focus:outline-none" aria-label="50 TL" tabindex="0" onclick="handleQuickAmount(50)" onkeydown="handleKeyDown(event, function() { handleQuickAmount(50); })">
                                50
                            </button>
                            <button class="bg-gray-400 font-bold py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-500 focus:outline-none" aria-label="100 TL" tabindex="0" onclick="handleQuickAmount(100)" onkeydown="handleKeyDown(event, function() { handleQuickAmount(100); })">
                                100
                            </button>
                        </div>
                        <div class="grid grid-cols-5 gap-2">
                            <button class="bg-gray-400 font-bold py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-500 focus:outline-none" aria-label="200 TL" tabindex="0" onclick="handleQuickAmount(200)" onkeydown="handleKeyDown(event, function() { handleQuickAmount(200); })">
                                200
                            </button>
                            <button class="bg-gray-400 font-bold py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-500 focus:outline-none" aria-label="500 TL" tabindex="0" onclick="handleQuickAmount(500)" onkeydown="handleKeyDown(event, function() { handleQuickAmount(500); })">
                                500
                            </button>
                            <button class="bg-gray-400 font-bold py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-500 focus:outline-none" aria-label="1000 TL" tabindex="0" onclick="handleQuickAmount(1000)" onkeydown="handleKeyDown(event, function() { handleQuickAmount(1000); })">
                                1000
                            </button>
                            <button class="bg-gray-400 font-bold py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-500 focus:outline-none" aria-label="5000 TL" tabindex="0" onclick="handleQuickAmount(5000)" onkeydown="handleKeyDown(event, function() { handleQuickAmount(5000); })">
                                5.000
                            </button>
                            <button class="bg-gray-400 font-bold py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-500 focus:outline-none" aria-label="10000 TL" tabindex="0" onclick="handleQuickAmount(10000)" onkeydown="handleKeyDown(event, function() { handleQuickAmount(10000); })">
                                10.000
                            </button>
                        </div>
                    </div>

                    <!-- Sıfırla Butonu -->
                    <button class="w-full bg-gray-800 font-semibold text-white py-3 px-4 rounded-md hover:bg-gray-900 focus:outline-none" aria-label="Sıfırla" tabindex="0" onclick="handleReset()" onkeydown="handleKeyDown(event, handleReset)">
                        SIFIRLA
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="pages/manager/pos/script.js"></script>