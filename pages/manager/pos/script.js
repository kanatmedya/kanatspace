// Uygulamanın durumunu yönetmek için veri objeleri
const cartState = {
    activeCart: 1,
    cartCount: 1, // Varsayılan olarak 1 sepet
    carts: {
        1: {
            products: [],
            total: 0,
            tax: 0
        }
    }
};

// Demo ürünler (backend'den gelecek)
const products = {
    "kirtasiye": [
        { id: 1, name: "Kurşun Kalem", price: 15.50, taxRate: 18 },
        { id: 2, name: "A4 Kağıt (500 Sayfa)", price: 120.75, taxRate: 18 },
        { id: 3, name: "Tükenmez Kalem", price: 25.00, taxRate: 18 }
    ],
    "elektrik": [
        { id: 4, name: "Priz", price: 45.30, taxRate: 18 },
        { id: 5, name: "LED Ampul", price: 30.90, taxRate: 18 },
        { id: 6, name: "Uzatma Kablosu", price: 85.60, taxRate: 18 }
    ],
    "bilgisayar": [
        { id: 7, name: "USB Bellek 32GB", price: 185.00, taxRate: 18 },
        { id: 8, name: "Klavye", price: 350.00, taxRate: 18 },
        { id: 9, name: "Mouse", price: 175.50, taxRate: 18 }
    ],
    "gida": [
        { id: 10, name: "Makarna 500g", price: 22.50, taxRate: 10 },
        { id: 11, name: "Pirinç 1kg", price: 45.75, taxRate: 10 },
        { id: 12, name: "Zeytinyağı 1L", price: 210.00, taxRate: 10 }
    ],
    "oyuncak": [
        { id: 13, name: "Oyuncak Araba", price: 78.90, taxRate: 18 },
        { id: 14, name: "Lego Set", price: 349.50, taxRate: 18 },
        { id: 15, name: "Peluş Ayı", price: 120.00, taxRate: 18 }
    ],
    "kitap": [
        { id: 16, name: "Roman", price: 85.50, taxRate: 0 },
        { id: 17, name: "Ders Kitabı", price: 120.00, taxRate: 0 },
        { id: 18, name: "Çocuk Kitabı", price: 65.00, taxRate: 0 }
    ],
    "manav": [
        { id: 19, name: "Elma 1kg", price: 18.50, taxRate: 1 },
        { id: 20, name: "Domates 1kg", price: 15.75, taxRate: 1 },
        { id: 21, name: "Patates 1kg", price: 12.90, taxRate: 1 }
    ],
    "bakim": [
        { id: 22, name: "Şampuan", price: 85.00, taxRate: 18 },
        { id: 23, name: "Diş Macunu", price: 45.50, taxRate: 18 },
        { id: 24, name: "El Kremi", price: 55.25, taxRate: 18 }
    ],
    "sarkuteri": [
        { id: 25, name: "Beyaz Peynir 500g", price: 120.00, taxRate: 10 },
        { id: 26, name: "Sosis 250g", price: 85.50, taxRate: 10 },
        { id: 27, name: "Kaşar Peynir 350g", price: 145.75, taxRate: 10 }
    ],
    "hijyen": [
        { id: 28, name: "Tuvalet Kağıdı 8'li", price: 120.00, taxRate: 18 },
        { id: 29, name: "Sabun 4'lü", price: 60.50, taxRate: 18 },
        { id: 30, name: "Havlu Kağıt", price: 45.25, taxRate: 18 }
    ],
    "boya": [
        { id: 31, name: "Oje", price: 45.00, taxRate: 18 },
        { id: 32, name: "Ruj", price: 120.50, taxRate: 18 },
        { id: 33, name: "Göz Kalemi", price: 75.25, taxRate: 18 }
    ]
};

// Aktif ödeme seçeneği
let activePaymentOption = 'odeme';

// DOM yüklendikten sonra
document.addEventListener('DOMContentLoaded', () => {
    // Barkod inputuna focus
    document.getElementById('barcodeInput').focus();
    
    // Varsayılan kategoriyi göster
    handleCategorySelect('kirtasiye');
    
    // Sepet butonlarını render et
    renderCartButtons();
    
    // Sepet bilgilerini güncelle
    updateCartInfo();
    
    // Fare ile kaydırma işlevini ekle
    setupDragToScroll();
    
    // Cari hesap seçim kutusunu başlat
    handleAccountSelection();
});

// Fare ile kaydırma işlevi kurulumu
const setupDragToScroll = () => {
    const slider = document.getElementById('cartButtonsScroll');
    let isDown = false;
    let startX;
    let scrollLeft;

    // Fare (mouse) olayları
    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('active');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });

    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('active');
    });

    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('active');
    });

    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 1.5; // Kaydırma hızı
        slider.scrollLeft = scrollLeft - walk;
    });

    // Dokunmatik ekran olayları
    slider.addEventListener('touchstart', (e) => {
        isDown = true;
        slider.classList.add('active');
        startX = e.touches[0].pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });

    slider.addEventListener('touchend', () => {
        isDown = false;
        slider.classList.remove('active');
    });

    slider.addEventListener('touchcancel', () => {
        isDown = false;
        slider.classList.remove('active');
    });

    slider.addEventListener('touchmove', (e) => {
        if (!isDown) return;
        const x = e.touches[0].pageX - slider.offsetLeft;
        const walk = (x - startX) * 1.5; // Kaydırma hızı
        slider.scrollLeft = scrollLeft - walk;
    });
};

// Enter tuşu ile barkod araması
document.getElementById('barcodeInput').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        handleSearch();
    }
});

// Barkod alanı değişikliğinde arama işlemi
document.getElementById('barcodeInput').addEventListener('keyup', (e) => {
    if (e.key === 'Enter') {
        // Enter tuşu için handleSearch zaten keydown ile çağrılıyor
        return;
    } else if (e.target.value.trim() !== '') {
        // Kullanıcı bir şey yazdığında otomatik arama yap (gecikmeli)
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(() => {
            handleSearch();
        }, 300); // 300ms gecikme ile arama yap
    } else if (e.target.value.trim() === '') {
        // Arama alanı boşaltıldığında kategorileri tekrar göster
        clearSearch();
    }
});

// Arama işlemi
const handleSearch = () => {
    const barcodeInput = document.getElementById('barcodeInput');
    const searchQuery = barcodeInput.value.trim().toLowerCase();
    const categoryContainer = document.getElementById('categoryContainer'); // Kategori listesi container
    const productsContainer = document.querySelector('.col-span-8'); // Ürün grid container
    
    // Arama alanı boşsa, normal kategori görünümünü göster
    if (searchQuery === '') {
        // Kategorileri göster, varsayılan kategoriyi seç
        categoryContainer.classList.remove('hidden');
        categoryContainer.classList.remove('col-span-0');
        categoryContainer.classList.add('col-span-4');
        
        // Ürün grid'i normal boyuta getir
        productsContainer.classList.remove('col-span-12');
        productsContainer.classList.add('col-span-8');
        productsContainer.classList.remove('rounded-l-md');
        productsContainer.classList.add('rounded-r-md');
        
        handleCategorySelect('kirtasiye');
        return;
    }
    
    // Tüm ürünleri düz bir dizide topla
    const allProducts = [];
    for (const category in products) {
        products[category].forEach(product => {
            allProducts.push({...product, category});
        });
    }
    
    // Arama sorgusuyla eşleşen ürünleri filtrele
    const searchResults = allProducts.filter(product => {
        // Hem ürün adında hem de barkod numarasında arama yap
        const nameMatch = product.name.toLowerCase().includes(searchQuery);
        const barcodeMatch = product.id.toString() === searchQuery; // Şimdilik ID'yi barkod olarak kullanıyoruz
        return nameMatch || barcodeMatch;
    });
    
    // Kategori bölümünü gizle
    categoryContainer.classList.add('hidden');
    categoryContainer.classList.remove('col-span-4');
    categoryContainer.classList.add('col-span-0');
    
    // Ürün grid'i tüm genişliği kaplasın
    productsContainer.classList.remove('col-span-8');
    productsContainer.classList.add('col-span-12');
    productsContainer.classList.add('rounded-l-md');
    
    // Arama sonuçlarını göster
    displaySearchResults(searchResults);
    
    // Eğer tam barkod eşleşmesi varsa ve tekse, ürünü sepete ekle
    const exactBarcodeMatch = searchResults.filter(product => product.id.toString() === searchQuery);
    if (exactBarcodeMatch.length === 1) {
        addProductToCart(exactBarcodeMatch[0]);
        // Barkod input'unu temizle
        barcodeInput.value = '';
        barcodeInput.focus();
        // Kategori görünümünü geri getir
        categoryContainer.classList.remove('hidden');
        categoryContainer.classList.remove('col-span-0');
        categoryContainer.classList.add('col-span-4');
        
        // Ürün grid'i normal boyuta getir
        productsContainer.classList.remove('col-span-12');
        productsContainer.classList.add('col-span-8');
        productsContainer.classList.remove('rounded-l-md');
        
        handleCategorySelect('kirtasiye');
    }
};

// Arama sonuçlarını gösterme
const displaySearchResults = (results) => {
    const productsGridEl = document.getElementById('productsGrid');
    
    // Grid'i temizle
    productsGridEl.innerHTML = '';
    
    // Sonuç yoksa bilgi mesajı göster
    if (results.length === 0) {
        productsGridEl.innerHTML = `
            <div class="col-span-2 p-4 text-center text-gray-500">
                Arama sonucu bulunamadı.
                <div class="mt-2">
                    <button class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600" 
                            onclick="clearSearch()">
                        Aramayı Temizle
                    </button>
                </div>
            </div>
        `;
        return;
    }
    
    // Arama sonuçları başlığı ve temizleme butonu ekle
    const searchHeader = document.createElement('div');
    searchHeader.className = 'col-span-2 mb-2 flex justify-between items-center bg-gray-100 p-2 w-full';
    searchHeader.innerHTML = `
        <div class="font-bold text-blue-600">Ürünler (${results.length})</div>
        <button class="bg-gray-200 text-gray-700 py-1 px-2 rounded text-xs hover:bg-gray-300" 
                onclick="clearSearch()">
            Aramayı Temizle
        </button>
    `;
    productsGridEl.appendChild(searchHeader);
    
    // Arama sonuçlarını listele
    results.forEach(product => {
        const productCard = document.createElement('div');
        productCard.className = 'border border-gray-300 rounded-md p-2 cursor-pointer hover:bg-blue-50';
        productCard.setAttribute('tabindex', '0');
        productCard.setAttribute('aria-label', product.name);
        productCard.onclick = () => handleProductSelect(product.id);
        productCard.onkeydown = (e) => handleKeyDown(e, () => handleProductSelect(product.id));
        
        productCard.innerHTML = `
            <div class="font-bold">${product.name}</div>
            <div class="text-gray-500 text-sm">${formatCurrency(product.price)}</div>
            <div class="text-xs text-gray-400 mt-1">Kategori: ${getCategoryName(product.category)}</div>
        `;
        
        productsGridEl.appendChild(productCard);
    });
};

// Kategori adını alma
const getCategoryName = (categoryId) => {
    const categoryNames = {
        "kirtasiye": "Kırtasiye Ürünleri",
        "elektrik": "Elektrik ve Elektronik",
        "bilgisayar": "Bilgisayar Malzemeleri",
        "gida": "Gıda",
        "oyuncak": "Seks Oyuncakları",
        "kitap": "Kitap",
        "manav": "Manav",
        "bakim": "Kişisel Bakım",
        "sarkuteri": "Şarküteri",
        "hijyen": "Hijyen",
        "boya": "Makyaj Ürünleri"
    };
    
    return categoryNames[categoryId] || categoryId;
};

// Aramayı temizleme
const clearSearch = () => {
    const barcodeInput = document.getElementById('barcodeInput');
    barcodeInput.value = '';
    barcodeInput.focus();
    
    const categoryContainer = document.getElementById('categoryContainer');
    const productsContainer = document.querySelector('.col-span-12, .col-span-8'); // Ürün grid container
    
    // Kategorileri göster, varsayılan kategoriyi seç
    categoryContainer.classList.remove('hidden');
    categoryContainer.classList.remove('col-span-0');
    categoryContainer.classList.add('col-span-4');
    
    // Ürün grid'i normal boyuta getir
    productsContainer.classList.remove('col-span-12');
    productsContainer.classList.add('col-span-8');
    productsContainer.classList.remove('rounded-l-md');
    
    handleCategorySelect('kirtasiye');
};

// Sepete ürün ekleme
const addProductToCart = (product) => {
    const activeCart = cartState.activeCart;
    const cart = cartState.carts[activeCart];
    
    // Sepette aynı ürün var mı kontrol et
    const productInCart = cart.products.find(item => item.id === product.id);
    
    if (productInCart) {
        // Varsa miktarını artır
        productInCart.quantity += 1;
        productInCart.total = productInCart.quantity * productInCart.price;
    } else {
        // Yoksa yeni ekle
        cart.products.push({
            id: product.id,
            name: product.name,
            price: product.price,
            taxRate: product.taxRate || 18, // Eğer taxRate belirtilmemişse varsayılan 18
            quantity: 1,
            total: product.price
        });
    }
    
    // Toplam ve vergi hesapla
    calculateCartTotal(activeCart);
    
    // Sepet içeriğini güncelle
    updateCartContents();
    updateCartInfo();
};

// Sepetten ürün çıkarma
const removeProductFromCart = (productId) => {
    const activeCart = cartState.activeCart;
    const cart = cartState.carts[activeCart];
    
    // Ürünü sepetten çıkar
    cart.products = cart.products.filter(product => product.id !== productId);
    
    // Toplam ve vergi hesapla
    calculateCartTotal(activeCart);
    
    // Sepet içeriğini güncelle
    updateCartContents();
    updateCartInfo();
};

// Ürün miktarını değiştirme
const changeProductQuantity = (productId, newQuantity) => {
    if (newQuantity <= 0) {
        removeProductFromCart(productId);
        return;
    }
    
    const activeCart = cartState.activeCart;
    const cart = cartState.carts[activeCart];
    
    // Ürünü bul ve miktarını güncelle
    const product = cart.products.find(item => item.id === productId);
    
    if (product) {
        product.quantity = newQuantity;
        product.total = product.quantity * product.price;
        
        // Toplam ve vergi hesapla
        calculateCartTotal(activeCart);
        
        // Sepet içeriğini güncelle
        updateCartContents();
        updateCartInfo();
    }
};

// Sepet toplamını hesapla
const calculateCartTotal = (cartNo) => {
    const cart = cartState.carts[cartNo];
    
    // Toplam hesapla
    cart.total = cart.products.reduce((acc, product) => acc + product.total, 0);
    
    // Her ürün için kendi vergi oranına göre vergi hesapla
    cart.tax = cart.products.reduce((acc, product) => {
        // Vergi oranı product.taxRate'te bulunur (yüzde olarak)
        const productTax = (product.total * product.taxRate) / 100;
        return acc + productTax;
    }, 0);
};

// Sepet içeriğini güncelleme
const updateCartContents = () => {
    const cartProductsEl = document.getElementById('cartProducts');
    const activeCart = cartState.activeCart;
    const cart = cartState.carts[activeCart];
    
    // Sepet içeriğini temizle
    cartProductsEl.innerHTML = '';
    
    // Sepetteki ürünleri listele
    cart.products.forEach(product => {
        const tr = document.createElement('tr');
        
        // KDV seçenekleri için dropdown oluştur
        const taxRateSelectOptions = `
            <option value="0" ${product.taxRate === 0 ? 'selected' : ''}>%0</option>
            <option value="1" ${product.taxRate === 1 ? 'selected' : ''}>%1</option>
            <option value="10" ${product.taxRate === 10 ? 'selected' : ''}>%10</option>
            <option value="20" ${product.taxRate === 20 ? 'selected' : ''}>%20</option>
            <option value="manual" ${![0, 1, 10, 20].includes(product.taxRate) ? 'selected' : ''}>Manuel</option>
        `;
        
        tr.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${product.name}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <button class="w-8 h-8 border border-gray-300 rounded-l-md bg-gray-100 flex items-center justify-center focus:outline-none" aria-label="Azalt" tabindex="0" onclick="handleQuantityChange(${product.id}, ${product.quantity - 1})" onkeydown="handleKeyDown(event, function() { handleQuantityChange(${product.id}, ${product.quantity - 1}); })">
                        -
                    </button>
                    <input type="text" value="${product.quantity}" class="w-12 h-8 border-t border-b border-gray-300 text-center focus:outline-none" aria-label="Miktar" onchange="handleQuantityInputChange(event, ${product.id})">
                    <button class="w-8 h-8 border border-gray-300 rounded-r-md bg-gray-100 flex items-center justify-center focus:outline-none" aria-label="Artır" tabindex="0" onclick="handleQuantityChange(${product.id}, ${product.quantity + 1})" onkeydown="handleKeyDown(event, function() { handleQuantityChange(${product.id}, ${product.quantity + 1}); })">
                        +
                    </button>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <select class="border border-gray-300 rounded-md p-1 text-sm w-20" aria-label="KDV Oranı" onchange="handleTaxRateChange(${product.id}, this.value)">
                        ${taxRateSelectOptions}
                    </select>
                    <div class="ml-2 ${![0, 1, 10, 20].includes(product.taxRate) ? '' : 'hidden'}" id="manualTaxInput-${product.id}">
                        <input type="number" min="0" max="100" value="${![0, 1, 10, 20].includes(product.taxRate) ? product.taxRate : ''}" class="border border-gray-300 rounded-md p-1 text-sm w-16" aria-label="Manuel KDV" oninput="handleManualTaxRateChange(${product.id}, this.value)">
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${formatCurrency(product.price)}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${formatCurrency(product.total)}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button class="text-red-600 hover:text-red-900 focus:outline-none" aria-label="Sil" tabindex="0" onclick="handleDeleteProduct(${product.id})" onkeydown="handleKeyDown(event, function() { handleDeleteProduct(${product.id}); })">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        cartProductsEl.appendChild(tr);
    });
    
    // Eğer sepet boşsa, bilgi mesajı göster
    if (cart.products.length === 0) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                Sepetinizde ürün bulunmamaktadır.
            </td>
        `;
        cartProductsEl.appendChild(tr);
    }
};

// Sepet bilgilerini güncelle
const updateCartInfo = () => {
    const taxTotalEl = document.getElementById('taxTotal');
    const grandTotalEl = document.getElementById('grandTotal');
    const deleteCartBtn = document.querySelector('button[aria-label="Sepeti sil"]');
    
    const activeCart = cartState.activeCart;
    const cart = cartState.carts[activeCart];
    
    // Vergi ve toplam bilgisini güncelle
    taxTotalEl.textContent = formatCurrency(cart.tax);
    grandTotalEl.textContent = formatCurrency(cart.total + cart.tax);
    
    // Sepet butonlarını güncelle
    for (let i = 1; i <= cartState.cartCount; i++) {
        const cartButton = document.querySelector(`button[aria-label="Sepet ${i}"]`);
        // Eğer buton yoksa, bu sepet için buton oluşturulmamış demektir
        if (!cartButton) continue;
        
        const productCount = cartState.carts[i].products.length;
        
        // Butonun yazısını ve stilini güncelle
        const spanEl = cartButton.querySelector('span');
        spanEl.textContent = `${productCount} ürün`;
        
        // Aktif sepet vurgusunu değiştir
        if (i === activeCart) {
            cartButton.classList.remove('bg-white', 'text-gray-700');
            cartButton.classList.add('bg-green-500', 'text-white');
        } else {
            cartButton.classList.remove('bg-green-500', 'text-white');
            cartButton.classList.add('bg-white', 'text-gray-700');
        }
    }
    
    // Tek sepet varsa SİL butonunu devre dışı bırak
    if (cartState.cartCount <= 1) {
        deleteCartBtn.disabled = true;
        deleteCartBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        deleteCartBtn.disabled = false;
        deleteCartBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
};

// Kategori seçimi
const handleCategorySelect = (categoryId) => {
    // Tüm kategori öğelerinin active sınıfını kaldır
    document.querySelectorAll('.category-item').forEach(item => {
        item.classList.remove('bg-blue-100');
    });
    
    // Seçilen kategoriye active sınıfını ekle
    const selectedCategory = document.querySelector(`[data-kategori="${categoryId}"]`);
    if (selectedCategory) {
        selectedCategory.classList.add('bg-blue-100');
    }
    
    // Kategori ürünlerini göster
    showCategoryProducts(categoryId);
};

// Kategori ürünlerini gösterme
const showCategoryProducts = (categoryId) => {
    const productsGridEl = document.getElementById('productsGrid');
    
    // Grid'i temizle
    productsGridEl.innerHTML = '';
    
    // Kategoriye ait ürünleri göster
    if (products[categoryId]) {
        products[categoryId].forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'border border-gray-300 rounded-md p-2 cursor-pointer hover:bg-blue-50';
            productCard.setAttribute('tabindex', '0');
            productCard.setAttribute('aria-label', product.name);
            productCard.onclick = () => handleProductSelect(product.id);
            productCard.onkeydown = (e) => handleKeyDown(e, () => handleProductSelect(product.id));
            
            productCard.innerHTML = `
                <div class="font-bold">${product.name}</div>
                <div class="text-gray-500 text-sm">${formatCurrency(product.price)}</div>
            `;
            
            productsGridEl.appendChild(productCard);
        });
    }
};

// Ürün seçimi
const handleProductSelect = (productId) => {
    // Tüm kategorilerde ürünü ara
    let selectedProduct = null;
    for (const category in products) {
        const foundProduct = products[category].find(product => product.id === productId);
        if (foundProduct) {
            selectedProduct = foundProduct;
            break;
        }
    }
    
    if (selectedProduct) {
        addProductToCart(selectedProduct);
    }
};

// Miktar değiştirme
const handleQuantityChange = (productId, newQuantity) => {
    changeProductQuantity(productId, newQuantity);
};

// Miktar input değişimi
const handleQuantityInputChange = (event, productId) => {
    const newQuantity = parseInt(event.target.value);
    if (!isNaN(newQuantity)) {
        changeProductQuantity(productId, newQuantity);
    }
};

// Ürün silme
const handleDeleteProduct = (productId) => {
    removeProductFromCart(productId);
};

// Sepet seçimi
const handleCartSelect = (cartNo) => {
    cartState.activeCart = cartNo;
    
    // Sepet içeriğini ve bilgilerini güncelle
    updateCartContents();
    updateCartInfo();
};

// Sepeti sil
const handleDeleteCart = () => {
    // Eğer tek sepet kaldıysa, silme işlemi yapma
    if (cartState.cartCount <= 1) {
        return;
    }
    
    // Aktif sepeti kaldır ve önceki sepete geç
    const activeCart = cartState.activeCart;
    
    // Eğer son sepet siliniyorsa, önceki sepeti aktif yap
    if (activeCart === cartState.cartCount) {
        cartState.activeCart = activeCart - 1;
    }
    
    // Sepet numaralarını yeniden düzenle (silinen sepetten sonrakileri bir önceki numaraya taşı)
    for (let i = activeCart; i < cartState.cartCount; i++) {
        cartState.carts[i] = cartState.carts[i + 1];
    }
    
    // Son sepeti kaldır
    delete cartState.carts[cartState.cartCount];
    
    // Sepet sayısını güncelle
    cartState.cartCount--;
    
    // Sepet içeriğini ve butonları güncelle
    updateCartContents();
    updateCartInfo();
    renderCartButtons();
};

// Sepeti beklet (yeni sepet ekle)
const handlePutCartOnHold = () => {
    // Yeni sepet numarası
    const newCartNumber = cartState.cartCount + 1;
    
    // Yeni sepeti state'e ekle
    cartState.carts[newCartNumber] = {
        products: [],
        total: 0,
        tax: 0
    };
    
    // Sepet sayısını güncelle
    cartState.cartCount = newCartNumber;
    
    // Yeni eklenen sepeti aktif yap
    cartState.activeCart = newCartNumber;
    
    // Sepet içeriğini ve butonları güncelle
    updateCartContents();
    updateCartInfo();
    renderCartButtons();
};

// Ödeme seçimi
const handlePaymentSelection = (selection) => {
    // Aktif seçeneği güncelle
    activePaymentOption = selection;
    
    // Butonların stillerini güncelle
    document.querySelectorAll('#paymentBtn, #changeBtn, #discountBtn, #taxBtn').forEach(btn => {
        btn.classList.remove('bg-blue-500', 'text-white', 'bg-white', 'text-gray-700');
        btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
    });
    
    // Seçilen butonu vurgula
    const selectedBtn = document.getElementById(`${selection}Btn`);
    if (selectedBtn) {
        selectedBtn.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
        selectedBtn.classList.add('bg-blue-500', 'text-white');
    }
    
    // Panel içeriğini güncelle - şimdilik sadece ödeme panelini gösteriyoruz
    // Diğer paneller için içerik eklenebilir
    document.getElementById('paymentPanel').style.display = selection === 'odeme' ? 'block' : 'none';
};

// Hızlı tutar ekleme
const handleQuickAmount = (amount) => {
    // Burada ödeme işlemi yapılacak (backend)
    console.log(`${amount} TL ödeme yapıldı.`);
};

// Sıfırlama
const handleReset = () => {
    if (confirm('Tüm sepetleri sıfırlamak istediğinize emin misiniz?')) {
        // Tüm sepetleri sıfırla
        for (let i = 1; i <= 4; i++) {
            cartState.carts[i].products = [];
            cartState.carts[i].total = 0;
            cartState.carts[i].tax = 0;
        }
        
        // Sepet içeriğini ve bilgilerini güncelle
        updateCartContents();
        updateCartInfo();
    }
};

// Cari Hesap Seçimi
const handleAccountSelection = () => {
    // Select2'yi jQuery kullanarak başlat
    $('#accountSelection').select2({
        placeholder: "CARİ HESAP SEÇİMİ",
        allowClear: true,
        width: 'resolve', // Dropdown genişliği otomatik ayarlanır
        language: {
            inputTooShort: function(args) {
                return "Lütfen aramak için yazın...";
            },
            searching: function() {
                return "Aranıyor...";
            },
            noResults: function() {
                return "Sonuç bulunamadı";
            },
            errorLoading: function() {
                return "Sonuçlar yüklenemedi";
            }
        },
        minimumInputLength: 0, // Hiçbir karakter girmeden de sonuçları göster
        minimumResultsForSearch: 0, // Her zaman arama kutusunu göster
        dropdownCssClass: "select2-dropdown-open", // Özel CSS sınıfı
        templateResult: formatOption,
        templateSelection: formatOption,
        matcher: matchCustom, // Özel eşleştirme fonksiyonu
        selectOnClose: false, // Kapandığında otomatik seçim yapma
        tags: false, // Yeni etiket eklemeyi kapat
        tokenSeparators: [], // Token ayırıcı yok
        escapeMarkup: function (markup) { return markup; }, // HTML işaretlemelerine izin ver
        dropdownAutoWidth: true, // Dropdown genişliğini otomatik ayarla
    });
    
    // Select2 CSS ayarlarını düzelt
    addSelect2CustomStyles();
    
    // Select2'nin başlatıldığından emin ol
    console.log("Select2 initialized for accountSelection");
    
    // Dropdown açıldığında arama alanına focus yap
    $('#accountSelection').on('select2:open', function() {
        setTimeout(function() {
            $('.select2-search__field').focus();
        }, 50);
    });
    
    // Select2 arama alanı tıklandığında tüm içeriği göster
    $(document).on('click', '.select2-search__field', function() {
        if ($(this).val() === '') {
            // Tüm seçenekleri göster
            const searchField = $(this);
            searchField.val(' '); // Boşluk ekleyerek tüm sonuçları göster
            searchField.trigger('input'); // Input event tetikle
            setTimeout(function() {
                searchField.val(''); // Sonra boşluğu temizle
                searchField.focus(); // Focus'u koru
            }, 10);
        }
    });
};

// Select2 için özel bir eşleştirme fonksiyonu
function matchCustom(params, data) {
    // Arama terimi yoksa veya boşluksa tüm seçenekleri göster
    if ($.trim(params.term) === '' || $.trim(params.term) === ' ') {
        return data;
    }

    // Yoksa normal eşleştirme yapmaya devam et
    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
        return data;
    }
    
    // Veri bilgisi içinde de arama yap (data-info attribute)
    if (data.element && data.element.dataset && data.element.dataset.info) {
        if (data.element.dataset.info.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
            return data;
        }
    }

    // Eşleşme bulunamadıysa null döndür
    return null;
}

// Select2 için özel stil düzenlemeleri
const addSelect2CustomStyles = () => {
    // Eğer önceden eklenmiş bir stil varsa kaldır
    const existingStyle = document.getElementById('select2-custom-styles');
    if (existingStyle) {
        existingStyle.remove();
    }
    
    const styleEl = document.createElement('style');
    styleEl.id = 'select2-custom-styles';
    styleEl.textContent = `
        .select2-container--default .select2-selection--single {
            height: 3rem !important;
            padding: 0.5rem !important;
            display: flex !important;
            align-items: center !important;
            border: 2px solid #d1d5db !important;
            border-radius: 0.375rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 3rem !important;
        }
        .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }
        .select2-search--dropdown .select2-search__field {
            padding: 0.5rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.25rem !important;
            margin-bottom: 0.5rem !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6 !important;
        }
        .select2-container--default .select2-selection__placeholder {
            color: #6b7280 !important;
        }
        .select2-search--dropdown {
            padding: 0.5rem !important;
        }
    `;
    document.head.appendChild(styleEl);
};

// Select2 için opsiyonları formatla
const formatOption = (option) => {
    if (!option.id) {
        return option.text;
    }
    
    return $('<span>', {
        html: `<div>${option.text}</div>` +
            (option.element && option.element.dataset.info ? 
             `<small class="text-gray-500">${option.element.dataset.info}</small>` : '')
    });
};

// Tam ekran
const handleFullScreen = () => {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {
            console.log(`Tam ekran hatası: ${err.message}`);
        });
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }
};

// Para birimi formatı
const formatCurrency = (value) => {
    return value.toFixed(2).replace('.', ',') + '₺';
};

// Klavye erişilebilirliği için
const handleKeyDown = (event, callback) => {
    if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        callback();
    }
};

// Sepet butonlarını render etme
const renderCartButtons = () => {
    const cartButtonsContainer = document.getElementById('cartButtonsContainer');
    const cartButtonsScroll = document.getElementById('cartButtonsScroll');
    
    // Eğer tek sepet varsa sepet butonlarını gösterme
    if (cartState.cartCount <= 1) {
        cartButtonsContainer.innerHTML = '';
        cartButtonsScroll.classList.add('hidden');
        return;
    }
    
    // Birden fazla sepet varsa sepet butonlarını göster
    cartButtonsScroll.classList.remove('hidden');
    
    // Mevcut sepet butonlarını temizle
    cartButtonsContainer.innerHTML = '';
    
    // Her sepet için buton oluştur
    for (let i = 1; i <= cartState.cartCount; i++) {
        const button = document.createElement('button');
        const isActive = i === cartState.activeCart;
        
        button.className = isActive 
            ? 'bg-green-500 text-white py-1.5 px-3 rounded-md hover:bg-green-600 focus:outline-none flex-shrink-0 text-sm' 
            : 'bg-white text-gray-700 py-1.5 px-3 rounded-md hover:bg-gray-100 focus:outline-none flex-shrink-0 text-sm';
        
        button.setAttribute('aria-label', `Sepet ${i}`);
        button.setAttribute('tabindex', '0');
        button.onclick = () => handleCartSelect(i);
        button.onkeydown = (e) => handleKeyDown(e, function() { handleCartSelect(i); });
        
        // Ürün sayısını göster
        const productCount = cartState.carts[i].products.length;
        
        button.innerHTML = `
            SEPET ${i}
            <span class="block text-xs">${productCount} ürün</span>
        `;
        
        cartButtonsContainer.appendChild(button);
    }
    
    // 5'ten fazla sepet varsa otomatik olarak kaydırılabilir hale getir
    if (cartState.cartCount > 5) {
        // Son sepeti görünür hale getirmek için scroll pozisyonunu ayarla
        setTimeout(() => {
            cartButtonsScroll.scrollLeft = cartButtonsScroll.scrollWidth;
        }, 100);
    }
};

// KDV oranını değiştirme
const handleTaxRateChange = (productId, taxRateValue) => {
    const activeCart = cartState.activeCart;
    const cart = cartState.carts[activeCart];
    
    // Ürünü bul
    const product = cart.products.find(item => item.id === productId);
    
    if (product) {
        if (taxRateValue === 'manual') {
            // Manuel giriş alanını göster
            const manualInputDiv = document.getElementById(`manualTaxInput-${productId}`);
            manualInputDiv.classList.remove('hidden');
            
            // Mevcut değeri koru (eğer zaten manuel değerse)
            if ([0, 1, 10, 20].includes(product.taxRate)) {
                // Eğer standart değerlerden biri ise, 18 (varsayılan) olarak ayarla
                product.taxRate = 18;
            }
        } else {
            // Manuel giriş alanını gizle
            const manualInputDiv = document.getElementById(`manualTaxInput-${productId}`);
            if (manualInputDiv) {
                manualInputDiv.classList.add('hidden');
            }
            
            // Seçilen değeri ayarla
            product.taxRate = parseInt(taxRateValue);
        }
        
        // Toplam ve vergi hesapla
        calculateCartTotal(activeCart);
        
        // Sepet bilgilerini güncelle
        updateCartInfo();
    }
};

// Manuel KDV oranını değiştirme
const handleManualTaxRateChange = (productId, taxRateValue) => {
    const activeCart = cartState.activeCart;
    const cart = cartState.carts[activeCart];
    
    // Ürünü bul
    const product = cart.products.find(item => item.id === productId);
    
    if (product) {
        // Sayısal değere dönüştür ve geçerlilik kontrolü yap
        let newTaxRate = parseInt(taxRateValue);
        
        // Eğer sayı değilse veya negatifse 0 olarak ayarla
        if (isNaN(newTaxRate) || newTaxRate < 0) {
            newTaxRate = 0;
        }
        
        // Eğer 100'den büyükse 100 olarak ayarla
        if (newTaxRate > 100) {
            newTaxRate = 100;
        }
        
        // KDV oranını güncelle
        product.taxRate = newTaxRate;
        
        // Toplam ve vergi hesapla
        calculateCartTotal(activeCart);
        
        // Sepet bilgilerini güncelle
        updateCartInfo();
    }
}; 