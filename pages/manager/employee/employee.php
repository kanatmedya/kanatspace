<?php

echo '<script>
    document.title = "Personel Puantaj";
</script>';

// GET ile gelen employeeID'yi al
$employeeID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Toplam sonuç sayısını belirlemek için SQL sorgusu
$queryEmployee = "SELECT COUNT(DISTINCT DATE_FORMAT(date, '%Y-%m')) AS total FROM emp_worklogs WHERE employeeID = ?";
$stmtEmployee = $conn->prepare($queryEmployee);
$stmtEmployee->bind_param('i', $employeeID);
$stmtEmployee->execute();
$resultEmployee = $stmtEmployee->get_result();

$totalResults = 0;
if ($row = $resultEmployee->fetch_assoc()) {
    $totalResults = isset($row['total']) ? (int)$row['total'] : 0;
}

$stmtEmployee->close();

// Sayfa numarasını ve sayfa başına veri sayısını belirleyin
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['queue']) ? (int)$_GET['queue'] : 25;

$offset = ($page - 1) * $itemsPerPage;

// Verileri ay bazında toplamak için SQL sorgusu
$queryWorkLogs = "SELECT DATE_FORMAT(date, '%Y-%m') AS period, 
            SUM(CASE WHEN workStatus = 'worked' THEN 1 ELSE 0 END) AS worked_days,
            SUM(CASE WHEN workStatus = 'notWorked' THEN 1 ELSE 0 END) AS not_worked_days,
            SUM(CASE WHEN workStatus = 'offDuty' THEN 1 ELSE 0 END) AS off_duty_days,
            SUM(CASE WHEN workStatus = 'paidVacation' THEN 1 ELSE 0 END) AS paid_leave_days,
            SUM(CASE WHEN workStatus = 'reported' THEN 1 ELSE 0 END) AS reported_days
        FROM emp_worklogs
        WHERE employeeID = ?
        GROUP BY period
        ORDER BY period DESC
        LIMIT ? OFFSET ?";

$stmtWorkLogs = $conn->prepare($queryWorkLogs);
if (!$stmtWorkLogs) {
    die("Sorgu hazırlama hatası: " . $conn->error);
}

$stmtWorkLogs->bind_param('iii', $employeeID, $itemsPerPage, $offset);
$stmtWorkLogs->execute();
$resultWorkLogs = $stmtWorkLogs->get_result();

$dataWorkLogs = [];
while ($rowWorkLogs = $resultWorkLogs->fetch_assoc()) {
    $dataWorkLogs[] = $rowWorkLogs;
}
$stmtWorkLogs->close();

$totalPages = ceil($totalResults / $itemsPerPage);

setlocale(LC_TIME, 'tr_TR.UTF-8');

?>

<style>
    .dataTable {
        width: 100%;
        border-collapse: collapse;
    }

    .dataTable th,
    .dataTable td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    .dataTable th {
        background-color: #f2f2f2;
        cursor: pointer;
    }

    .dataTable th.sortable:hover {
        background-color: #e2e2e2;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        list-style: none;
        padding: 0;
    }

    .pagination li {
        margin: 0 5px;
    }

    .pagination a {
        text-decoration: none;
        padding: 8px 12px;
        border: 1px solid #ddd;
        color: #333;
        border-radius: 5px;
    }

    .pagination a.active {
        background-color: #007bff;
        color: white;
    }

    .pagination a:hover {
        background-color: #ddd;
    }

    .search-container {
        margin-bottom: 20px;
        text-align: center;
    }

    .search-container input {
        width: 50%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('dataTable-search-input');
    const tableRows = document.querySelectorAll('tbody tr');

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.toLowerCase();
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchTerm) ? '' : 'none';
        });
    });

            const headers = document.querySelectorAll('th.sortable');
    headers.forEach(header => {
        header.addEventListener('click', function () {
            const tableBody = document.querySelector('tbody');
            const rows = Array.from(tableBody.querySelectorAll('tr'));
            const index = Array.from(header.parentNode.children).indexOf(header);
            const ascending = header.dataset.sort === 'asc';

            rows.sort((a, b) => {
                let aSort, bSort;

                if (index === 0) { // Eğer dönem sütunuysa
                    aSort = a.getAttribute('data-sort-period');
                    bSort = b.getAttribute('data-sort-period');
                } else {
                    aSort = a.children[index].getAttribute('data-sort') || '';
                    bSort = b.children[index].getAttribute('data-sort') || '';
                }

                return ascending
                    ? aSort.localeCompare(bSort)
                    : bSort.localeCompare(aSort);
            });

            header.dataset.sort = ascending ? 'desc' : 'asc';
            rows.forEach(row => tableBody.appendChild(row));
        });
    });
});

</script>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div x-data="">
        <div class="relative flex h-full gap-5 sm:min-h-0 xl:h-[calc(100vh-_150px)]">
            <div class="panel flex-1 overflow-y-auto">
                <div class="mb-5 sm:absolute sm:top-5 ltr:sm:left-5 rtl:sm:right-5">
                    <div class="relative flex items-center justify-between gap-4">
                        <h3>Puantaj</h3>
                    </div>
                </div>
                <div class="product-table">
                    <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                        <div class="dataTable-top">
                            <div class="dataTable-search">
                                <input id="dataTable-search-input" class="dataTable-input" placeholder="Arama Yap" type="text">
                            </div>
                        </div>
                        <div class="dataTable-container">
                            <table id="myTable" class="whitespace-nowrap dataTable-table">
                                <thead>
                                    <tr>
                                        <th class="sortable" data-sort="asc">Dönem</th>
                                        <th class="sortable" data-sort="asc">Çalıştı</th>
                                        <th class="sortable" data-sort="asc">Çalışmadı</th>
                                        <th class="sortable" data-sort="asc">İzinli</th>
                                        <th class="sortable" data-sort="asc">Ücretli İzinli</th>
                                        <th class="sortable" data-sort="asc">Raporlu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($dataWorkLogs) > 0): ?>
                                        <?php foreach ($dataWorkLogs as $log): ?>
                                            <tr data-sort-period="<?php echo $log['period']; ?>">
                                                <td>
                                                    <?php 
                                                        $formattedPeriod = strftime('%B %Y', strtotime($log['period'] . '-01'));
                                                        echo htmlspecialchars($formattedPeriod);
                                                    ?>
                                                </td>
                                                <td data-sort="<?php echo htmlspecialchars($log['worked_days']); ?>">
                                                    <?php echo htmlspecialchars($log['worked_days']); ?> Gün
                                                </td>
                                                <td data-sort="<?php echo htmlspecialchars($log['not_worked_days']); ?>">
                                                    <?php echo htmlspecialchars($log['not_worked_days']); ?> Gün
                                                </td>
                                                <td data-sort="<?php echo htmlspecialchars($log['off_duty_days']); ?>">
                                                    <?php echo htmlspecialchars($log['off_duty_days']); ?> Gün
                                                </td>
                                                <td data-sort="<?php echo htmlspecialchars($log['paid_leave_days']); ?>">
                                                    <?php echo htmlspecialchars($log['paid_leave_days']); ?> Gün
                                                </td>
                                                <td data-sort="<?php echo htmlspecialchars($log['reported_days']); ?>">
                                                    <?php echo htmlspecialchars($log['reported_days']); ?> Gün
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" style="text-align:center;">Veri bulunamadı.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        
                <div class="dataTable-bottom">
                    <div class="dataTable-info">
                        <?php
                        $last = $offset + $itemsPerPage;
                        if ($last > $totalResults) {
                            $last = $totalResults;
                        }
                        $offset1 = $totalResults > 0 ? $offset + 1 : 0;
                        echo 'Toplam ' . $totalResults . ' sonuç içinden ' . $offset1 . ' - ' . $last . ' arası gösteriliyor.';
                        ?>
                    </div>
                    <div class="dataTable-dropdown">
                        <label>
                            <select class="dataTable-selector" onchange="degis()">
                                <option value="25" <?php if ($itemsPerPage == 25) echo 'selected'; ?>>25</option>
                                <option value="50" <?php if ($itemsPerPage == 50) echo 'selected'; ?>>50</option>
                                <option value="100" <?php if ($itemsPerPage == 100) echo 'selected'; ?>>100</option>
                                <option value="1000" <?php if ($itemsPerPage == 1000) echo 'selected'; ?>>1000</option>
                            </select>
                        </label>
                    </div>
                    <nav class="dataTable-pagination">
                        <ul class="dataTable-pagination-list">
                            <?php if ($page > 1): ?>
                                <li class="pager"><a href="?page=1&queue=<?= $itemsPerPage ?>&id=<?= $employeeID ?>"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180">
                                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg></a></li>
                                <li class="pager"><a href="?page=<?= $page - 1 ?>&queue=<?= $itemsPerPage ?>&id=<?= $employeeID ?>"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180">
                                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg></a></li>
                            <?php endif; ?>
                
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="<?= $i == $page ? 'active' : '' ?>"><a href="?page=<?= $i ?>&queue=<?= $itemsPerPage ?>&id=<?= $employeeID ?>"><?= $i ?></a></li>
                            <?php endfor; ?>
                
                            <?php if ($page < $totalPages): ?>
                                <li class="pager"><a href="?page=<?= $page + 1 ?>&queue=<?= $itemsPerPage ?>&id=<?= $employeeID ?>"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg></a></li>
                                <li class="pager"><a href="?page=<?= $totalPages ?>&queue=<?= $itemsPerPage ?>&id=<?= $employeeID ?>"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180">
                                        <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg></a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>