<div class="container mt-5">
    <?php
    // Mevcut kategori verilerini çek
    $categoryID = $_GET['id']; // Güncellenmek istenen kategori ID'si
    $sql = "SELECT * FROM accounting_category WHERE id = $categoryID";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    } else {
        die("Kategori bulunamadı.");
    }
    ?>

    <form action="incomeCategoryUpdate.php?id=<?php echo $categoryID; ?>" method="post" enctype="multipart/form-data">
        <div class="panel mb-5">
            <div class="border-b mx-4 text-base font-semibold dark:border-[#191e3a] dark:text-white">
                Kategori Güncelle
            </div>
            <div class="space-y-5 p-4">
                <div>
                    <label>Kategori Adı</label>
                    <input type="text" name="category" value="<?php echo $category['category']; ?>" placeholder="Kategori Adı" class="form-input" required />
                </div>
                
                <div>
                    <label>Kategori Türü</label>
                    <select required class="form-select" name="type" id="categoryType">
                        <option value="">Kategori Türü</option>
                        <option value="main" <?php if ($category['type'] == 'main') echo 'selected'; ?>>Ana Kategori</option>
                        <option value="sub" <?php if ($category['type'] == 'sub') echo 'selected'; ?>>Alt Kategori</option>
                    </select>
                </div>
                <div id="upperCategoryDiv" style="<?php if ($category['type'] == 'sub') echo 'display: block;'; else echo 'display: none;'; ?>">
                    <label>Üst Kategori</label>
                    <select <?php echo ($category['type']=='sub') ? 'required' : '' ?> class="form-select" name="categorySub" id="categorySubid">
                        <option value="">Üst Kategori Seçin</option>
                        <?php
                        // Ana kategorileri çek
                        $sql = "SELECT id, category FROM accounting_category WHERE type='main'";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $selected = ($row['id'] == $category['categorySub']) ? 'selected' : '';
                                echo "<option value='" . $row['id'] . "' $selected>" . $row['category'] . "</option>";
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
            <button type="submit" class="btn btn-primary" style="width:100%">Güncelle</button>
        </div>
    </form>
</div>
<script>
    document.getElementById('categoryType').addEventListener('change', function() {
        var upperCategoryDiv = document.getElementById('upperCategoryDiv');
        var subCategorySelect = document.getElementById('categorySubid');
        
        if (this.value === 'sub') {
            upperCategoryDiv.style.display = 'block';
            subCategorySelect.setAttribute('required', 'required');
        } else {
            upperCategoryDiv.style.display = 'none';
            subCategorySelect.removeAttribute('required');
        }
    });
</script>

<?php
// Form gönderildi mi?
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verileri al
    $category = $_POST['category'];
    $type = $_POST['type'];
    if(isset($_POST['categorySub'])){
        $categorySub = $_POST['categorySub'];
    }else{
        $categorySub = NULL;
    }

    // Verileri doğrula
    if (empty($category) || empty($type)) {
        echo "Lütfen gerekli alanları doldurun.";
    } else {
        // SQL sorgusu
        if ($type == "main") {
            // Ana kategori güncelle
            $sql = "UPDATE accounting_category SET category='$category', type='$type', categorySub=NULL WHERE id=$categoryID";
        } else {
            // Alt kategori güncelle
            $sql = "UPDATE accounting_category SET category='$category',  type='$type', categorySub='$categorySub' WHERE id=$categoryID";
        }

        // Sorguyu çalıştır
        if ($conn->query($sql) === TRUE) {
            echo "Kayıt başarıyla güncellendi.";
        } else {
            echo "Hata: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
