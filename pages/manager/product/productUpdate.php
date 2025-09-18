<script type="text/javascript">
    function Degistir() {
        var nameget = document.getElementById("product-select").value;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "pages/manager/product/modules/getProduct.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var nameData = JSON.parse(xhr.responseText);
                updateProductInfo(nameData);
            }
        };
        xhr.send("name=" + encodeURIComponent(nameget)); // encodeURIComponent to properly encode URL component
    }

    function updateProductInfo(nameData) {
        document.getElementById("status").value = nameData.status;
        document.getElementById("visibility").value = nameData.visibility;
        document.getElementById("main_category").value = nameData.main_category;
        document.getElementById("sub_category").value = nameData.category;
        document.getElementById("description").value = nameData.description;
        document.getElementsByName("supplier")[0].value = nameData.provider;
        document.getElementsByName("brand")[0].value = nameData.brand;
        document.getElementsByName("barcode")[0].value = nameData.barcode;
        document.getElementsByName("stock_code")[0].value = nameData.stockcode;
        document.getElementsByName("purchase_price")[0].value = nameData.buyPrice;
        document.getElementsByName("purchase_tax")[0].value = nameData.buyTax;
        document.getElementsByName("sale_price")[0].value = nameData.price;
        document.getElementsByName("sale_tax")[0].value = nameData.tax;

        document.getElementsByName("packageQuantity")[0].value = nameData.packageQuantity;
        document.getElementsByName("packagePiece")[0].value = nameData.packagePiece;
        document.getElementsByName("stock")[0].value = nameData.stock;
        document.getElementsByName("piece")[0].value = nameData.piece;
    }


</script>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div>
        <div class="gap-5 lg:flex">
            <div class="grow space-y-5">
                <div class="panel">
                    <form action="productUpdate.php" method="post" enctype="multipart/form-data">
                        <div>
                            <label>Ürün Adı</label>
                            <select class="form-select" name="name" id="product-select" onchange="Degistir()">


                                <?php
                                $status_options = [
                                    'published' => 'Yayınlandı',
                                    'draft' => 'Taslak'
                                ];

                                $visibility_options = [
                                    'public' => 'Herkese Açık',
                                    'hiddenUser' => 'Sadece Üye',
                                    'hiddenComp' => 'Sadece Şirket İçi',
                                    'hiddenMngr' => 'Sadece Yönetici'
                                ];



                                $sorgu = "SELECT * FROM products";
                                $result = $conn->query($sorgu);
                                $selected_id = isset($_GET['id']) ? intval($_GET['id']) : 1;
                                $sor = "SELECT * FROM products WHERE id=$selected_id";
                                $selected_result = $conn->query($sor);
                                $selected_product = $selected_result->fetch_assoc();
                                if ($selected_result->num_rows > 0) {
                                    echo "<option value='{$selected_product['name']}' selected>{$selected_product['name']}</option>";
                                } else {
                                    echo "<option selected>Lütfen Ürün Seçiniz</option>";
                                }


                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $nameSelect = $row['name'];
                                        echo "<option value=\"$nameSelect\">$nameSelect</option>";
                                    }
                                } else {
                                    echo "<option disabled selected>Veri bulunamadı</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="panel my-5">

                            <div class="border-b mx-4 text-base font-semibold dark:border-[#191e3a] dark:text-white">
                                Ürün Bilgileri
                            </div>

                            <div class="mt-5">
                                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                                    <div>
                                        <label>Yayın Durumu</label>
                                        <select required class="form-select" name="status" id="status">
                                            <?php
                                            foreach ($status_options as $value => $label) {
                                                $selected = ($value == $status) ? 'selected' : '';
                                                echo "<option value=\"$value\" $selected>$label</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Gizlilik</label>
                                        <select required class="form-select" name="visibility" id="visibility">
                                            <?php
                                            foreach ($visibility_options as $value => $label) {
                                                $selected = ($value == $visibility) ? 'selected' : '';
                                                echo "<option value=\"$value\" $selected>$label</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Ana Kategori</label>
                                        <select class="form-select" name="main_category" id="main_category">
                                            <?php


                                            /*$sorgu ="SELECT categoryName 
                                                              FROM products_category 
                                                              WHERE id = (
                                                                  SELECT pc.mainID 
                                                                  FROM products_category pc 
                                                                  JOIN products p ON pc.id = p.categoryID 
                                                                  WHERE pc.categoryName = $selected_product_category_name
                                                              )";*/

                                            $sorgu = "SELECT categoryName FROM products_category WHERE type='main' ";
                                            $result = $conn->query($sorgu);



                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $category = $row['categoryName'];
                                                    echo "<option value=\"$category\">$category</option>";
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
                                <label>Ürün Açıklaması</label>
                                <div id="editor">
                                    <textarea required name="description" placeholder="Ürün Açıklaması" rows="4"
                                        class="form-textarea" id="description">
                                      <?php
                                      // Seçili ürünün açıklamasını textarea içinde göster
                                      if ($selected_product) {
                                          echo htmlspecialchars(trim(preg_replace('/\s+/', ' ', $selected_product['description'])));
                                      }
                                      ?></textarea>
                                    </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="panel my-5">
                            <div class="border-b mx-4 text-base font-semibold dark:border-[#191e3a] dark:text-white">
                                Ürün Fotoğrafları
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
                                        <label>Barkod</label>
                                        <input required type="text" name="barcode" placeholder="Barkod"
                                            class="form-input"
                                            value="<?php echo isset($selected_product['barcode']) ? htmlspecialchars(trim($selected_product['barcode'])) : ''; ?>" />
                                    </div>
                                    <div>
                                        <label>Stok Kodu</label>
                                        <input required type="text" name="stock_code" placeholder="Stok Kodu"
                                            class="form-input"
                                            value="<?php echo isset($selected_product['stockcode']) ? htmlspecialchars(trim($selected_product['stockcode'])) : ''; ?>" />
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-5 p-4">
                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label>Stok Adet</label>
                                        <input required type="text" name="stock" placeholder="Stok Adeti"
                                            class="form-input"
                                            value="<?php echo isset($selected_product['stock']) ? htmlspecialchars(trim($selected_product['stock'])) : ''; ?>" />
                                    </div>
                                    <div>
                                        <label>Stok Birimi</label>
                                        <input required type="text" name="piece" placeholder="Stok birimi"
                                            class="form-input"
                                            value="<?php echo isset($selected_product['piece']) ? htmlspecialchars(trim($selected_product['piece'])) : ''; ?>" />
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-5 p-4">
                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label>Paket İçindeki Adet</label>
                                        <input required type="text" name="packageQuantity" placeholder="Adet"
                                            class="form-input"
                                            value="<?php echo isset($selected_product['packageQuantity']) ? htmlspecialchars(trim($selected_product['packageQuantity'])) : ''; ?>" />
                                    </div>
                                    <div>
                                        <label>Paket Birimi</label>
                                        <input required type="text" name="packagePiece" placeholder="Birim"
                                            class="form-input"
                                            value="<?php echo isset($selected_product['packagePiece']) ? htmlspecialchars(trim($selected_product['packagePiece'])) : ''; ?>" />
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
                                        <label>Tedarikçi</label>
                                        <input required type="text" name="supplier" placeholder="Tedarikçi"
                                            class="form-input"
                                            value="<?php echo isset($selected_product['provider']) ? htmlspecialchars(trim($selected_product['provider'])) : ''; ?>" />

                                    </div>
                                    <div>
                                        <label>Marka</label>
                                        <input required type="text" name="brand" placeholder="Marka" class="form-input"
                                            value="<?php echo isset($selected_product['brand']) ? htmlspecialchars(trim($selected_product['brand'])) : ''; ?>" />
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                                    <div>
                                        <label>Alış</label>
                                        <div class="flex">
                                            <input required type="text" name="purchase_price" placeholder="Enter Price"
                                                class="form-input ltr:rounded rtl:rounded"
                                                value="<?php echo isset($selected_product['buyPrice']) ? htmlspecialchars(trim($selected_product['buyPrice'])) : ''; ?>" />
                                        </div>
                                    </div>
                                    <!--<div>
                                        <label>Alış Para Birimi</label>
                                        <div class="flex">
                                            <input required type="text" name="purchase_tax" placeholder="Enter Tax"
                                                class="form-input ltr:rounded rtl:rounded"
                                                value="<?php echo isset($selected_product['buyTax']) ? htmlspecialchars(trim($selected_product['buyTax'])) : ''; ?>" />
                                        </div>
                                    </div>-->
                                    <div>
                                        <label>Alış Vergi</label>
                                        <div class="flex">
                                            <div
                                                class="flex items-center justify-center border border-[#e0e6ed] bg-[#eee] px-3 font-bold ltr:rounded-l-md ltr:border-r-0 rtl:rounded-r-md rtl:border-l-0 dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                                %
                                            </div>
                                            <input required type="text" name="purchase_tax" placeholder="Enter Tax"
                                                class="form-input ltr:rounded-l-none rtl:rounded-r-none"
                                                value="<?php echo isset($selected_product['buyTax']) ? htmlspecialchars(trim($selected_product['buyTax'])) : ''; ?>" />
                                        </div>
                                    </div>
                                    <div>
                                        <label>Satış</label>
                                        <div class="flex">
                                            <input required type="text" name="sale_price" placeholder="Enter Price"
                                                class="form-input ltr:rounded rtl:rounded"
                                                value="<?php echo isset($selected_product['price']) ? htmlspecialchars(trim($selected_product['price'])) : ''; ?>" />
                                        </div>
                                    </div>
                                    <!--
                                    <div>
                                        <label>Satış Para Birimi</label>
                                        <div class="flex">
                                            <input required type="text" name="purchase_tax" placeholder="Enter Tax"
                                                class="form-input ltr:rounded rtl:rounded"
                                                value="<?php echo isset($selected_product['buyTax']) ? htmlspecialchars(trim($selected_product['buyTax'])) : ''; ?>" />
                                        </div>
                                    </div>-->
                                    <div>
                                        <label>Satış Vergi</label>
                                        <div class="flex">
                                            <div
                                                class="flex items-center justify-center border border-[#e0e6ed] bg-[#eee] px-3 font-bold ltr:rounded-l-md ltr:border-r-0 rtl:rounded-r-md rtl:border-l-0 dark:border-[#17263c] dark:bg-[#1b2e4b]">
                                                %
                                            </div>
                                            <input required type="text" name="sale_tax" placeholder="Enter Tax"
                                                class="form-input ltr:rounded-l-none rtl:rounded-r-none"
                                                value="<?php echo isset($selected_product['tax']) ? htmlspecialchars(trim($selected_product['tax'])) : ''; ?>" />
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
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            let descriptionField = document.getElementById('description');
            descriptionField.value = descriptionField.value.trim().replace(/\s+/g, ' ');
        });
    </script>

    <?php
    if (
        isset($_POST['name']) && trim($_POST['name']) !== '' &&
        isset($_POST['description']) && trim($_POST['description']) !== '' &&
        isset($_POST['supplier']) && trim($_POST['supplier']) !== '' &&
        isset($_POST['brand']) && trim($_POST['brand']) !== '' &&
        isset($_POST['barcode']) && trim($_POST['barcode']) !== '' &&
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
        $description = $_POST['description'];
        $supplier = $_POST['supplier'];
        $brand = $_POST['brand'];
        $barcode = $_POST['barcode'];
        $stock_code = $_POST['stock_code'];
        $purchase_price = $_POST['purchase_price'];
        $purchase_tax = $_POST['purchase_tax'];
        $sale_price = $_POST['sale_price'];
        $sale_tax = $_POST['sale_tax'];
        $status = $_POST['status'];

        $stock = $_POST['stock'];
        $packageQuantity = $_POST['packageQuantity'];
        $packagePiece = $_POST['packagePiece'];
        $piece = $_POST['piece'];

        $visibility = $_POST['visibility'];
        $main_category = $_POST['main_category'];
        $sub_category = $_POST['sub_category'];

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
        $sql = "UPDATE products 
        SET name = '$name', 
            description = '$description', 
            provider = '$supplier', 
            brand = '$brand', 
            barcode = '$barcode', 
            stockcode = '$stock_code', 
            buyPrice = '$purchase_price', 
            buyTax = '$purchase_tax', 
            price = '$sale_price', 
            tax = '$sale_tax', 
            status = '$status', 
            photos = '$product_images', 
            category = '$sub_category', 
            visibility = '$visibility',
            stock='$stock',
            piece='$piece',
            packageQuantity='$packageQuantity',
            packagePiece='$packagePiece'
        WHERE name = '$name'";

        if ($conn->query($sql) === TRUE) {
            echo "Ürün başarıyla Güncellendi";
        } else {
            echo "Hata: " . $sql . "<br>" . $conn->error;
        }
    } else {
        
    }

    ?>