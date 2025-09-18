<?php
// Tüm ürünlerin sayısını alacak sorgu
$countSql = "SELECT COUNT(*) AS total FROM products_category";
$countResult = $conn->query($countSql);
$totalItems = $countResult->fetch_assoc()['total'];

// Sayfa numarasını ve sayfa başına ürün sayısını alın
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 12; // Her sayfa için gösterilecek ürün sayısı

// Limit ve offset hesaplaması
$offset = ($page - 1) * $itemsPerPage;
$sql = "SELECT id,type, categoryName, createDate, updateDate FROM products_category  WHERE type='sub'";
$result = $conn->query($sql);
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <h1 style="font-size:24px;margin-right:20px">Stok Hareketleri</h1><br>
    <div x-data="products">
        <div class="relative flex h-full gap-5 sm:min-h-0 xl:h-[calc(100vh_-_150px)]"
            :class="{'min-h-[999px]' : isShowChatMenu}">

            <div class="panel flex-1 overflow-y-auto">
                <div class="mb-5 sm:absolute sm:top-5 ltr:sm:left-5 rtl:sm:right-5">
                    <div class="relative flex items-center justify-between gap-4">
                        <a href="apps-ecommerce-add-product.html" class="btn btn-primary gap-2 px-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="h-5 w-5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Add New
                        </a>
                    </div>
                </div>
                                <div class="product-table">
                    <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                        <div class="dataTable-top">
                            <div class="dataTable-search">
                                <input id="dataTable-search-input" class="dataTable-input" placeholder="Search..."
                                    type="text">
                            </div>
                        </div>
               <?php if ($result->num_rows > 0) {
  echo '<div class="dataTable-container">
        <table id="myTable" class="whitespace-nowrap dataTable-table">
          <thead>
            <tr>
              <th data-sortable="false" style="width: 6.36238%;">
                <input type="checkbox" class="form-checkbox" id="checkAllCheckbox" @change="checkAll($event.target.checked)">
              </th>
              <th data-sortable="" style="width: 34.7856%;"><a href="#" class="dataTable-sorter">Kategori Adı</a></th>
                <th data-sortable="" style="width: 10.0968%;"><a href="#" class="dataTable-sorter">Stok</a></th>
              <th data-sortable="" style="width: 10.5118%;"><a href="#" class="dataTable-sorter">Oluşturulma Tarihi</a></th>
            
              <th data-sortable="" style="width: 11.2725%;"><a href="#" class="dataTable-sorter">Son Güncelleme</a></th>
              <th data-sortable="false" style="width: 13.3472%;">Actions</th>
            </tr>
          </thead>
          <tbody>';

  // Verileri döngü ile alıp tabloya eklemek
  while ($row = $result->fetch_assoc()) {
    include('sub/stockActivities.php');
  }

  echo '</tbody></table></div>';

} else {
  echo "0 sonuç";
}
                ?>
                        <div class="dataTable-bottom">
                            <div class="dataTable-info">Showing 1 to 9 of 12 entries</div>
                            <div class="dataTable-dropdown"><label><span class="ml-2"><select
                                            class="dataTable-selector">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                        </select></span></label></div>
                             <nav class="dataTable-pagination">
                                <ul class="dataTable-pagination-list">
                                    <?php if ($page > 1): ?>
                                        <li class="pager"><a href="?page=1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"><path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></li>
                                        <li class="pager"><a href="?page=<?= $page - 1 ?>"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"><path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= ceil($totalItems / $itemsPerPage); $i++): ?>
                                        <li class="<?= $i == $page ? 'active' : '' ?>"><a href="?page=<?= $i ?>"><?= $i ?></a></li>
                                    <?php endfor; ?>

                                    <?php if ($page < ceil($totalItems / $itemsPerPage)): ?>
                                        <li class="pager"><a href="?page=<?= $page + 1 ?>"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"><path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></li>
                                        <li class="pager"><a href="?page=<?= ceil($totalItems / $itemsPerPage) ?>"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"><path d="M22 12L2 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13 5L19 12L13 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end main content section -->

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('dataTable-search-input');
        const rows = document.querySelectorAll('#myTable tbody tr');

        searchInput.addEventListener('keyup', function (event) {
            const searchText = event.target.value.toLowerCase();

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let found = false;

                cells.forEach(cell => {
                    const text = cell.textContent.toLowerCase();
                    if (text.includes(searchText)) {
                        found = true;
                    }
                });

                if (found) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
