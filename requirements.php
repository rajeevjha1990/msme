<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
define('REQ_ARR', [
    '0' => 'Active',
    '1' => 'Got Product',
    '2' => 'Will Report Later',
    '3' => 'Work Done'
]);
include 'common/header.php';
include 'dbconfigf/dbconst2025.php'; // DB connection

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']); // adjust according to your session variable

// Fetch all requirements from user_details with city and state names
$requirements = [];
$sql = "
    SELECT ud.*, 
           cm.city AS city_name, 
           sm.state AS state_name
    FROM user_details ud
    LEFT JOIN city_master cm ON ud.city = cm.cityid
    LEFT JOIN state_master sm ON ud.state = sm.stateid
    ORDER BY ud.created_at DESC
";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $requirements[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requirements Listings</title>
    <style>
        /* Keep your existing CSS */
        * { box-sizing: border-box; }
        body { font-family: Arial,sans-serif; margin:0; padding:0; background:#f5f5f5; min-height:100vh; padding-top:120px; }
        .main-content { max-width:1200px; margin:0 auto; padding:0; }
        .requirements-page { padding:20px; background-color:#f5f5f5; min-height:calc(100vh - 200px); }
        .requirements-title { text-align:center; margin-bottom:30px; font-size:28px; font-weight:bold; color:#333; text-shadow:1px 1px 2px rgba(255,255,255,0.8);}
        .requirements-filters { background:#fff; padding:20px; border-radius:15px; box-shadow:0 4px 12px rgba(0,0,0,0.1); margin-bottom:25px; display:flex; flex-wrap:wrap; gap:20px; align-items:center; }
        .requirements-filters label { font-weight:600; color:#333; display:flex; align-items:center; gap:8px; }
        .requirements-filters input[type="number"], .requirements-filters input[type="text"] { padding:8px 12px; border:2px solid #e0e0e0; border-radius:8px; font-size:14px; transition:all 0.3s ease; }
        .requirements-filters input[type="number"]:focus, .requirements-filters input[type="text"]:focus { outline:none; border-color:#6b4c93; box-shadow:0 0 0 3px rgba(107,76,147,0.1);}
        .requirements-table-container { background:#fff; border-radius:15px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1);}
        .requirements-table { border-collapse:collapse; width:100%; background:#fff;}
        .requirements-table th, .requirements-table td { border:1px solid #e0e0e0; padding:15px 12px; text-align:center; }
        .requirements-table th { font-weight:bold; cursor:pointer; transition:background 0.3s ease; font-size:14px;}
        .requirements-table th:hover { background: linear-gradient(135deg, #5a3f7a 0%, #d16b3a 100%);}
        .requirements-table tbody tr { transition: background-color 0.3s ease; }
        .requirements-table tbody tr:nth-child(even) { background-color: #f9f9f9; }
        .requirements-table tbody tr:hover { background-color: #f0f0f0; }
        .requirements-table td { color:#555; font-size:14px; }
        .status-badge { padding:6px 12px; border-radius:20px; font-size:12px; font-weight:bold; text-transform:uppercase; }
        .status-active { background:#e8f5e8; color:#4caf50; }
        .status-pending { background:#fff3cd; color:#856404; }
        .status-closed { background:#f8d7da; color:#721c24; }
        .priority-badge { padding:4px 8px; border-radius:15px; font-size:11px; font-weight:bold; text-transform:uppercase; }
        .priority-high { background:#ffebee; color:#c62828; }
        .priority-medium { background:#fff8e1; color:#f57c00; }
        .priority-low { background:#e8f5e8; color:#388e3c; }
        .view-btn { background: linear-gradient(135deg, #6b4c93, #e67e49); color:white; border:none; padding:8px 16px; border-radius:20px; cursor:pointer; font-size:12px; font-weight:bold; transition:all 0.3s ease; text-transform:uppercase;}
        .view-btn:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(107,76,147,0.3); }
        .sample-data { font-style:italic; color:#666; }
        html,body { overflow-x:hidden; }
        .pagination { margin-top:20px; text-align:center; }
        .pagination span { display:inline-block; padding:8px 12px; margin:0 2px; background:#fff; border:1px solid #e0e0e0; border-radius:5px; cursor:pointer; transition:all 0.3s ease; }
        .pagination span:hover, .pagination span.active { background: linear-gradient(135deg, #6b4c93, #e67e49); color:white; border-color:transparent; }

        /* Modal styles */
        .modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; overflow:auto; background:rgba(0,0,0,0.5); }
        .modal-content { background:#fff; margin:10% auto; padding:20px; border-radius:10px; max-width:500px; position:relative; }
        .close { position:absolute; top:10px; right:15px; font-size:24px; font-weight:bold; cursor:pointer; color:#333; }
    </style>
</head>
<body>

<div class="main-content">
    <div class="requirements-page">
        <h2 class="requirements-title">Leads Listings</h2>

        <div class="requirements-filters">
            <label>
                Show 
                <input type="number" min="1" value="10" style="width:70px;" id="entriesSelect">
                entries
            </label>
            <label>
                Search: 
                <input type="text" placeholder="Search requirements..." id="searchInput">
            </label>
        </div>

        <div class="requirements-table-container">
            <table class="requirements-table">
                <thead>
                    <tr>
                        <th>Serial No</th>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>City / State</th>
                        <th>Budget</th>
                        <th>Status</th>
                        <th>View Details</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
<?php if(count($requirements) > 0): ?>
    <?php foreach($requirements as $index => $req): ?>
        <tr data-category="<?= strtolower($req['category']) ?>" 
            data-status="<?= strtolower($req['req_status']) ?>" 
            data-priority="<?= strtolower($req['priority']) ?>" 
            data-city="<?= strtolower($req['city_name'].' / '.$req['state_name']) ?>"
            data-budget="<?= number_format($req['min_budget']) ?> - <?= number_format($req['max_budget']) ?>"
        >
            <td><?= $index + 1 ?></td>
            <td><?= date('d-m-Y', strtotime($req['created_at'])) ?></td>
            <td><?= htmlspecialchars($req['category']) ?></td>
            <td><span class="priority-badge priority-<?= strtolower($req['priority']) ?>"><?= ucfirst($req['priority']) ?></span></td>
            <td><?= htmlspecialchars($req['city_name'].' / '.$req['state_name']) ?></td>
            <td>₹<?= number_format($req['min_budget']) ?> - ₹<?= number_format($req['max_budget']) ?></td>
            <td data-status="<?= strtolower(REQ_ARR[$req['req_status']] ?? ''); ?>">
    <?= REQ_ARR[$req['req_status']] ?? ''; ?>
</td>
            <td>
                <?php if($isLoggedIn): ?>
                    <button class="view-btn" onclick="openModal(<?= $index ?>)">View</button>
                <?php else: ?>
                    <button class="view-btn" onclick="redirectToLogin()">View</button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="8" style="text-align:center; padding:40px; color:#999; font-style:italic;">No requirements available.</td>
    </tr>
<?php endif; ?>
</tbody>
            </table>
        </div>

        <div class="pagination" id="pagination"></div>
    </div>
</div>

<!-- Modal Structure -->
<div id="detailsModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalBody"></div>
    </div>
</div>

<script>
    // Pagination & search logic (unchanged)
    const allRows = Array.from(document.querySelectorAll('#tableBody tr'));
    let filteredRows = [...allRows];
    let currentPage = 1;
    let rowsPerPage = 10;

    const searchInput = document.getElementById('searchInput');
    const entriesSelect = document.getElementById('entriesSelect');
    const tableBody = document.getElementById('tableBody');

    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        if(searchTerm === '') filteredRows = [...allRows];
        else {
            filteredRows = allRows.filter(row => {
                const text = row.textContent.toLowerCase();
                return text.includes(searchTerm) || row.dataset.category.includes(searchTerm) || row.dataset.city.includes(searchTerm) || row.dataset.status.includes(searchTerm) || row.dataset.priority.includes(searchTerm);
            });
        }
        currentPage = 1;
        updateTable();
        updatePagination();
    }

    function updateEntriesPerPage() {
        rowsPerPage = parseInt(entriesSelect.value);
        currentPage = 1;
        updateTable();
        updatePagination();
    }

    function updateTable() {
        allRows.forEach(r=>r.style.display='none');
        const start = (currentPage-1)*rowsPerPage;
        const end = start+rowsPerPage;
        const rowsToShow = filteredRows.slice(start,end);
        rowsToShow.forEach((row,i)=>{ row.style.display=''; row.querySelector('td:first-child').textContent=start+i+1; });
        if(filteredRows.length===0) showNoResultsMessage(); else hideNoResultsMessage();
    }

    function showNoResultsMessage() {
        hideNoResultsMessage();
        const noResultsRow = document.createElement('tr');
        noResultsRow.id = 'noResultsRow';
        noResultsRow.innerHTML = `<td colspan="8" style="text-align:center; padding:40px; color:#999; font-style:italic;">No requirements found matching your search criteria.</td>`;
        tableBody.appendChild(noResultsRow);
    }

    function hideNoResultsMessage() {
        const row=document.getElementById('noResultsRow'); if(row) row.remove();
    }

    function updatePagination() {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        const prevBtn = document.createElement('span');
        prevBtn.textContent='Previous';
        prevBtn.onclick=()=>changePage('prev');
        prevBtn.style.opacity = currentPage===1 ? '0.5':'1';
        prevBtn.style.cursor = currentPage===1 ? 'not-allowed':'pointer';
        pagination.appendChild(prevBtn);

        for(let i=1;i<=Math.min(totalPages,5);i++){
            const pageBtn=document.createElement('span');
            pageBtn.textContent=i;
            pageBtn.onclick=()=>changePage(i);
            if(i===currentPage) pageBtn.classList.add('active');
            pagination.appendChild(pageBtn);
        }

        const nextBtn = document.createElement('span');
        nextBtn.textContent='Next';
        nextBtn.onclick=()=>changePage('next');
        nextBtn.style.opacity = currentPage===totalPages ? '0.5':'1';
        nextBtn.style.cursor = currentPage===totalPages ? 'not-allowed':'pointer';
        pagination.appendChild(nextBtn);
    }

    function changePage(page) {
        const totalPages = Math.ceil(filteredRows.length/rowsPerPage);
        if(page==='prev' && currentPage>1) currentPage--;
        else if(page==='next' && currentPage<totalPages) currentPage++;
        else if(typeof page==='number' && page>=1 && page<=totalPages) currentPage=page;
        updateTable(); updatePagination();
    }

    searchInput.addEventListener('input', performSearch);
    entriesSelect.addEventListener('change', updateEntriesPerPage);

    updateTable(); updatePagination();

    function redirectToLogin() { window.location.href='login.php'; }

    function openModal(index) {
        const row = filteredRows[index];
        const details = `
            <strong>Category:</strong> ${row.dataset.category}<br>
            <strong>Priority:</strong> ${row.dataset.priority}<br>
            <strong>Status:</strong>${row.dataset.status}<br>
            <strong>Budget:</strong> ${row.dataset.budget}<br>
            <strong>City / State:</strong> ${row.dataset.city}
        `;
        document.getElementById('modalBody').innerHTML = details;
        document.getElementById('detailsModal').style.display='block';
    }

    function closeModal() { document.getElementById('detailsModal').style.display='none'; }
    window.onclick = function(event) { if(event.target===document.getElementById('detailsModal')) closeModal(); }
</script>

</body>
</html>

<?php include 'common/footer.php'; ?>
