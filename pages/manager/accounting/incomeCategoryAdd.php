<div class="animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div>
        <div class="gap-5 lg:flex">
            <div class="grow space-y-5">
                <form action="incomeCategoryAdd.php" method="post" enctype="multipart/form-data">
                    <div class="panel mb-5">

                        <div class="border-b mx-4 text-base font-semibold dark:border-[#191e3a] dark:text-white">
                            Kategori Ekle
                        </div>

                        <div class="space-y-5 p-4">

                            <div>
                                <label>Kategori Adı</label>
                                <input type="text" name="category" placeholder="Kategori Adı" class="form-input" required />
                            </div>

                            <div>
                                <label>Kategori Türü</label>
                                <select required class="form-select" name="type" id="categoryType">
                                    <option>Kategori Türü</option>
                                    <option value="main">Ana Kategori</option>
                                    <option value="sub">Alt Kategori</option>
                                </select>
                            </div>

                            <div id="upperCategoryDiv" style="display: none;">
                                <label>Alt Kategori</label>
                                <select required class="form-select" name="categorySub" id="categorySubid">
                                    <option>Kategori Türü</option>
                                    <?php 
                                      $sql = "SELECT id, category FROM accounting_category WHERE type='main'";
                                        $result = $conn->query($sql);
                                        
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo "<option value='" . $row['id'] . "'>" . $row['category'] . "</option>";
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
        var statusSelect = document.getElementById('categorySubid');
        
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
    $category = $_POST['category'];
    $type = $_POST['type'];
    $categorySub = isset($_POST['categorySub']) ? $_POST['categorySub'] : null;

    // Verileri doğrula
    if (empty($category) || empty($type)) {
        echo "Lütfen gerekli alanları doldurun.";
    } else {
        // SQL sorgusu
        if ($type === "main") {
            // Ana kategori ekle
            $sql = "INSERT INTO accounting_category (category, type) VALUES ('$category', '$type')";
        } else {
            // Alt kategori ekle
            $sql = "INSERT INTO accounting_category (category, categorySub, type) VALUES ('$category', '$categorySub', '$type')";
        }

        // Sorguyu çalıştır
        if ($conn->query($sql) === TRUE) {
            echo "Yeni kayıt başarıyla oluşturuldu.";
        } else {
            echo "Hata: " . $sql . "<br>" . $conn->error;
        }
    }
}

?>
