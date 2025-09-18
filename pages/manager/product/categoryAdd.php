<div class="animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div>
        <div class="gap-5 lg:flex">
            <div class="grow space-y-5">
                <form action="categoryAdd.php" method="post" enctype="multipart/form-data">
                    <div class="panel mb-5">

                        <div class="border-b mx-4 text-base font-semibold dark:border-[#191e3a] dark:text-white">
                            Kategori Ekle
                        </div>

                        <div class="space-y-5 p-4">

                            <div>
                                <label>Kategori Adı</label>
                                <input type="text" name="name" placeholder="Kategori Adı" class="form-input" />
                            </div>

                            <div>
                                <label>Sıra No</label>
                                <input type="text" name="displayOrder"
                                    placeholder="Sıra No (Boş Bırakılırsa en son sıraya eklenir)" class="form-input" />
                            </div>

                            <div>
                                <label>Kategori Türü</label>
                                <select required class="form-select" name="type" id="categoryType" >
                                    <option>Kategori Türü</option>
                                    <option value="main">Ana Kategori</option>
                                    <option value="sub">Alt Kategori</option>
                                </select>
                            </div>


                            <div  id="upperCategoryDiv" style="display: none;">
                                <label>Üst Kategori</label>
                                <select required class="form-select" name="sub_category" id="sub_categoryid">
                                    <option>Kategori Türü</option>
                                    <?php 
                                      $sql = "SELECT mainID, categoryName FROM products_category WHERE type='main'";
                                        $result = $conn->query($sql);
                                        
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo "<option value='" . $row['mainID'] . "'>" . $row['categoryName'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>Ana Kategori Bulunamadı</option>";
                                        }
                                     ?>
                                </select>
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
<script>
        document.getElementById('categoryType').addEventListener('change', function() {
            var upperCategoryDiv = document.getElementById('upperCategoryDiv');
            var statusSelect = document.getElementById('sub_categoryid');
            
            if (this.value === 'sub') {
                upperCategoryDiv.style.display = 'block';
                statusSelect.setAttribute('required', 'required');
            } else {
                upperCategoryDiv.style.display = 'none';
                statusSelect.removeAttribute('required');
            }
        });
    </script>




<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verileri al
    $name = $_POST['name'];
    $displayOrder = $_POST['displayOrder'];
    $type = $_POST['type'];
    $mainID = isset($_POST['sub_category']) ? $_POST['sub_category'] : null;

    // Verileri doğrula
    if (empty($name) || empty($type)) {
        echo "Lütfen gerekli alanları doldurun.";
    } else {
        // displayOrder boşsa, uygun değeri bul
        if (empty($displayOrder)) {
            if ($type === "main") {
                // Mevcut ana kategoriler arasındaki en yüksek displayOrder değerini bul
                $sql = "SELECT MAX(displayOrder) AS maxDisplayOrder FROM products_category WHERE type='main'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $displayOrder = $row['maxDisplayOrder'] + 1;
                } else {
                    $displayOrder = 1; // Eğer hiç ana kategori yoksa, ilk displayOrder 1 olmalıdır
                }
            } else {
                // Mevcut alt kategoriler arasında, belirli mainID içindeki en yüksek displayOrder değerini bul
                $sql = "SELECT MAX(displayOrder) AS maxDisplayOrder FROM products_category WHERE type='sub' AND mainID=$mainID";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $displayOrder = $row['maxDisplayOrder'] + 1;
                } else {
                    $displayOrder = 1; // Eğer hiç alt kategori yoksa, ilk displayOrder 1 olmalıdır
                }
            }
        }

        // SQL sorgusu
        if ($type === "main") {
            // Mevcut en yüksek mainID'yi bul
            $sql = "SELECT MAX(mainID) AS maxMainID FROM products_category WHERE type='main'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $newMainID = $row['maxMainID'] + 1;
            } else {
                $newMainID = 1; // Eğer hiç ana kategori yoksa, ilk mainID 1 olmalıdır
            }

            // Ana kategori ekle
            $sql = "INSERT INTO products_category (type, mainID, displayOrder, categoryName) VALUES ('$type', $newMainID, $displayOrder, '$name')";
        } else {
            // Alt kategori için mainID gerekli
            $sql = "INSERT INTO products_category (type, mainID, displayOrder, categoryName) VALUES ('$type', $mainID, $displayOrder, '$name')";
        }

        // Sorguyu çalıştır
        if ($conn->query($sql) === TRUE) {
            echo' <script> location.replace("categoryAdd"); </script>';
        } else {
            echo "Hata: " . $sql . "<br>" . $conn->error;
        }
    }
}

?>