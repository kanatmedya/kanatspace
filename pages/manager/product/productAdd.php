<div class="animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div>
        <div class="gap-5 lg:flex">
            <div class="grow space-y-5">
                <form action="productAdd.php" method="post" enctype="multipart/form-data">
                    <div class="panel mb-5">

                        <div class="border-b mx-4 text-base font-semibold dark:border-[#191e3a] dark:text-white">
                            Ürün Bilgileri
                        </div>

                        <div class="space-y-5 p-4">

                            <div>
                                <label>Ürün Adı</label>
                                <input type="text" name="name" placeholder="Ürün Adı" class="form-input" />
                            </div>

                            <div class="mt-5">
                                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">

                                    <div>
                                        <label>Yayın Durumu</label>
                                        <select required class="form-select" name="status" id="status">
                                            <option>Yayın Durumu</option>
                                            <option value="published">Yayınlandı</option>
                                            <option value="draft">Taslak</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label>Gizlilik</label>
                                        <select required class="form-select" name="visibility" id="visibility">
                                            <option>Gizlilik Durumu</option>
                                            <option value="public">Herkese Açık</option>
                                            <option value="hiddenUser">Sadece Üye</option>
                                            <option value="hiddenComp">Sadece Şirket İçi</option>
                                            <option value="hiddenMngr">Sadece Yönetici</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label>Ana Kategori</label>
                                        <select required class="form-select" name="main_category" id="main_category">
                                            <option>Kategori Seçin</option>
                                            <?php
                                            $sorgu = "SELECT categoryName FROM products_category WHERE type='main'";
                                            $result = $conn->query($sorgu);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $category = $row['categoryName'];
                                                    echo "<option>$category</option>";
                                                }
                                            } else {
                                                echo "<option disabled selected>Veri bulunamadı</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Alt Kategori</label>
                                        <select required class="form-select" name="sub_category" id="sub_category">
                                            <option>Kategori Seçin</option>
                                            <?php
                                            $sorgu = "SELECT categoryName FROM products_category WHERE type='sub'";
                                            $result = $conn->query($sorgu);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $category = $row['categoryName'];
                                                    echo "<option>$category</option>";
                                                }
                                            } else {
                                                echo "<option disabled selected>Veri bulunamadı</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <label>Ürün Açıklaması(opsiyonel)</label>
                                    <div id="editor">
                                        <textarea name="description" placeholder="Ürün Açıklaması" class="form-textarea" id="description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel my-5">
                        <div class="border-b mx-4 text-base font-semibold dark:border-[#191e3a] dark:text-white">
                            Ürün Fotoğrafları (opsiyonel)
                        </div>
                        <div class="space-y-5 p-4">
                            <div class="grid gap-4 sm:grid-cols-1">
                                <label
                                    class="relative flex flex-col items-center justify-center gap-4 border-2 border-dashed border-primary/50 bg-primary/5 p-5">
                                    <span
                                        class="flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3 14.25C3.41421 14.25 3.75 14.5858 3.75 15C3.75 16.4354 3.75159 17.4365 3.85315 18.1919C3.9518 18.9257 4.13225 19.3142 4.40901 19.591C4.68577 19.8678 5.07435 20.0482 5.80812 20.1469C6.56347 20.2484 7.56459 20.25 9 20.25H15C16.4354 20.25 17.4365 20.2484 18.1919 20.1469C18.9257 20.0482 19.3142 19.8678 19.591 19.591C19.8678 19.3142 20.0482 18.9257 20.1469 18.1919C20.2484 17.4365 20.25 16.4354 20.25 15C20.25 14.5858 20.5858 14.25 21 14.25C21.4142 14.25 21.75 14.5858 21.75 15V15.0549C21.75 16.4225 21.75 17.5248 21.6335 18.3918C21.5125 19.2919 21.2536 20.0497 20.6517 20.6516C20.0497 21.2536 19.2919 21.5125 18.3918 21.6335C17.5248 21.75 16.4225 21.75 15.0549 21.75H8.94513C7.57754 21.75 6.47522 21.75 5.60825 21.6335C4.70814 21.5125 3.95027 21.2536 3.34835 20.6517C2.74643 20.0497 2.48754 19.2919 2.36652 18.3918C2.24996 17.5248 2.24998 16.4225 2.25 15.0549C2.25 15.0366 2.25 15.0183 2.25 15C2.25 14.5858 2.58579 14.25 3 14.25Z"
                                                fill="currentColor" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M12 2.25C12.2106 2.25 12.4114 2.33852 12.5535 2.49392L16.5535 6.86892C16.833 7.17462 16.8118 7.64902 16.5061 7.92852C16.2004 8.20802 15.726 8.18678 15.4465 7.88108L12.75 4.9318V16C12.75 16.4142 12.4142 16.75 12 16.75C11.5858 16.75 11.25 16.4142 11.25 16V4.9318L8.55353 7.88108C8.27403 8.18678 7.79963 8.20802 7.49393 7.92852C7.18823 7.64902 7.16698 7.17462 7.44648 6.86892L11.4465 2.49392C11.5886 2.33852 11.7894 2.25 12 2.25Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    <h5 class="text-center text-lg font-bold">Fotoğraflarını Değiştir</h5>
                                    <input type="file" name="product_images[]" multiple
                                        class="absolute z-[1] cursor-pointer opacity-0" />
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="panel my-5">

                        <div class="border-b mx-4 text-base font-semibold dark:border-[#191e3a] dark:text-white">
                            Stok Bilgileri
                        </div>

                        <div class="space-y-5 p-4">
                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label>Barkod (opsiyonel)</label>
                                    <input type="text" name="barcode" placeholder="Barkod" class="form-input" />
                                </div>
                                <div>
                                    <label>Stok Kodu</label>
                                    <input required type="text" name="stock_code" placeholder="Stok Kodu"
                                        class="form-input" />
                                </div>
                            </div>

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label>Stok Adet</label>
                                    <input required type="text" name="stock" placeholder="Örnek: 1"
                                        class="form-input" />
                                </div>
                                <div>
                                    <label>Stok Birimi</label>
                                    <input required type="text" name="piece" placeholder="Örnek: Adet"
                                        class="form-input" />
                                </div>
                            </div>

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label>Paket İçindeki Adet(opsiyonel)</label>
                                    <input type="text" name="packageQuantity" placeholder="Örnek: 10"
                                        class="form-input" />
                                </div>
                                <div>
                                    <label>Paket Birimi(opsiyonel)</label>
                                    <input type="text" name="packagePiece" placeholder="Örnek: Kg" class="form-input" />
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="panel my-5">

                        <div class="border-b mx-4 text-base font-semibold dark:border-[#191e3a] dark:text-white">
                            Tedarik Bilgileri
                        </div>
                        <div class="space-y-5 p-4">
                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label>Tedarikçi(opsiyonel)</label>
                                    <input type="text" name="supplier" placeholder="Tedarikçi" class="form-input" />

                                </div>
                                <div>
                                    <label>Marka(opsiyonel)</label>
                                    <input type="text" name="brand" placeholder="Marka" class="form-input" />
                                </div>
                            </div>

                            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                                <div>
                                    <label>Alış</label>
                                    <div class="flex">
                                        <input required type="text" name="purchase_price" placeholder="Enter Price"
                                            class="form-input ltr:rounded rtl:rounded" />
                                    </div>
                                </div>

                                <div>
                                    <label>Alış Vergi</label>
                                    <div class="flex">
                                        <div class="flex items-center justify-center border border-[#e0e6ed] bg-[#eee] px-3 font-bold
                                            ltr:rounded-l-md ltr:border-r-0 rtl:rounded-r-md rtl:border-l-0
                                            dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                            %
                                        </div>
                                        <input required type="text" name="purchase_tax" placeholder="Enter Tax"
                                            class="form-input ltr:rounded-l-none rtl:rounded-r-none" />
                                    </div>
                                </div>
                                <div>
                                    <label>Satış</label>
                                    <div class="flex">
                                        <input required type="text" name="sale_price" placeholder="Enter Price"
                                            class="form-input ltr:rounded rtl:rounded" />
                                    </div>
                                </div>

                                <div>
                                    <label>Satış Vergi</label>
                                    <div class="flex">
                                        <div class="flex items-center justify-center border border-[#e0e6ed] bg-[#eee] px-3 font-bold
                                            ltr:rounded-l-md ltr:border-r-0 rtl:rounded-r-md rtl:border-l-0
                                            dark:border-[#17263c] dark:bg-[#1b2e4b]"> %
                                        </div>
                                        <input required type="text" name="sale_tax" placeholder="Enter Tax"
                                            class="form-input ltr:rounded-l-none rtl:rounded-r-none" />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="submit" class="btn btn-primary" style="width:100%">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end main content section -->
</div>

<?php


if (
    isset($_POST['name']) && trim($_POST['name']) !== '' &&
    isset($_POST['stock_code']) && trim($_POST['stock_code']) !== '' &&
    isset($_POST['purchase_price']) && trim($_POST['purchase_price']) !== '' &&
    isset($_POST['purchase_tax']) && trim($_POST['purchase_tax']) !== '' &&
    isset($_POST['sale_price']) && trim($_POST['sale_price']) !== '' &&
    isset($_POST['sale_tax']) && trim($_POST['sale_tax']) !== '' &&
    isset($_POST['status']) && trim($_POST['status']) !== '' &&
    isset($_POST['visibility']) && trim($_POST['visibility']) !== '' &&
    isset($_POST['main_category']) && trim($_POST['main_category']) !== '' &&
    isset($_POST['sub_category']) && trim($_POST['sub_category']) !== ''
) {

    // Formdan gelen verileri al
    $name = $_POST['name'];
    $description = isset($_POST['description']) ? $_POST['description'] : 'ürün';
    $supplier = isset($_POST['supplier']) ? $_POST['supplier'] : 'Tedarikçi';
    $brand = isset($_POST['brand']) ? $_POST['brand'] : 'marka';
    $barcode = isset($_POST['barcode']) ? $_POST['barcode'] : 'barkod';
    $stock_code = $_POST['stock_code'];
    $purchase_price = $_POST['purchase_price'];
    $purchase_tax = $_POST['purchase_tax'];
    $sale_price = $_POST['sale_price'];
    $sale_tax = $_POST['sale_tax'];
    $status = $_POST['status'];
    $visibility = $_POST['visibility'];
    $main_category = $_POST['main_category'];
    $sub_category = $_POST['sub_category'];
    $stock = $_POST['stock'];
    
    $packageQuantity = isset($_POST['packageQuantity']) ? $_POST['packageQuantity'] : 0;
    $packagePiece = isset($_POST['packagePiece']) ? $_POST['packagePiece'] : 'tür';
    $piece = $_POST['piece'];

    // Resim yükleme
    $target_dir = "uploads/products/";
    $images = [];

    foreach ($_FILES['product_images']['name'] as $key => $yol) {
        if (!empty($yol)) {
            $target_file = $target_dir . basename($yol);
            if (move_uploaded_file($_FILES['product_images']['tmp_name'][$key], $target_file)) {
                $images[] = $target_file;
            }
        }
    }

    $product_images = implode(',', $images);

    // Veritabanına kayıt ekleme name, description, provider, brand, barcode, stockcode, buy_price, buy_tax, sell_price, sell_tax, status, visibility, main_category, sub_category, product_photos
    $sql = "INSERT INTO products (name, description, provider, brand, barcode, stock, stockcode, buyPrice, buyTax, price, tax, status, photos, category, visibility, packageQuantity, packagePiece, piece)
    VALUES ('$name', '$description', '$supplier', '$brand', '$barcode', '$stock', '$stock_code', '$purchase_price', '$purchase_tax', '$sale_price', '$sale_tax', '$status','$product_images','$sub_category','$visibility','$packageQuantity', '$packagePiece','$piece')";

    if ($conn->query($sql) === TRUE) {
        echo "Yeni kayıt başarıyla oluşturuldu";
    } else {
        echo "Hata: " . $sql . "<br>" . $conn->error;
    }
} else {
    if (isset($_POST[""])) {
        echo "Lütfen tüm alanları doldurun";
    }
}

?>