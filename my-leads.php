<?php
session_start();
include 'common/header.php';
include 'dbconfigf/dbconst2025.php';

// Status map
define('REQ_ARR', [
    '0' => 'Active',
    '1' => 'Got Product',
    '2' => 'Will Report Later',
    '3' => 'Work Done'
]);

// Check login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=Please login first");
    exit();
}

$user_email = $_SESSION['email'];

// Find user state
$state = null;
$stmt = $conn->prepare("
    SELECT state FROM users WHERE email = ?
    UNION
    SELECT state FROM non_business_users WHERE email = ? LIMIT 1
");
$stmt->bind_param("ss", $user_email, $user_email);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $state = $row['state'];
}
$stmt->close();

// Fetch requirements from user_setails
$query = "
    SELECT * FROM user_details 
    WHERE email IN (
        SELECT email FROM users WHERE state = ?
        UNION
        SELECT email FROM non_business_users WHERE state = ?
    )
    ORDER BY created_at DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $state, $state);
$stmt->execute();
$requirements = $stmt->get_result();
?>

<!-- Add Bootstrap CSS if not already included -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #c9b6ff, #f2d6d6);
    min-height: 100vh;
}

.container {
 
    max-width: 1100px;
    margin: 100px auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    text-align: center;
    margin-bottom: 8px;
    color: #333;
}

.page-subtitle {
    text-align: center;
    font-size: 14px;
    color: #777;
    margin-bottom: 20px;
}

.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 15px;
    text-align: center;
}

.styled-table thead tr {
    background-color: #6a1b9a;
    color: #ffffff;
    text-align: center;
}

.styled-table th, 
.styled-table td {
    padding: 12px 15px;
    border: 1px solid #e0e0e0;
}

.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f9f9f9;
}

.styled-table tbody tr:hover {
    background-color: #f1f1f1;
    transition: 0.3s;
}

.status-active {
    color: green;
    font-weight: 600;
}

.status-pending {
    color: orange;
    font-weight: 600;
}

.status-closed {
    color: red;
    font-weight: 600;
}

.view-btn {
    padding: 6px 12px;
    background: #6a1b9a;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    text-decoration: none;
}

.view-btn:hover {
    background: #4a0072;
}

/* Modal improvements */
.modal-header {
    background: #6a1b9a !important;
    color: #fff !important;
}

.modal-header .btn-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #fff;
    opacity: 0.8;
}

.modal-header .btn-close:hover {
    opacity: 1;
}
</style>

<div class="container">
    <h2 class="page-title">My Leads </h2>
    <p class="page-subtitle">Here you can see all the requirements available in your state.</p>

    <table class="styled-table">
        <thead>
            <tr>
                <th>S. No</th>
                <th>Date</th>
                <th>Requirement Type</th>
                <th>Priority</th>
                <th>Quantity</th>
                <th>Budget</th>
                <th>Status</th>
                <th>View / Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($requirements->num_rows > 0): ?>
                <?php $i = 1; while ($row = $requirements->fetch_assoc()): ?>
                    <?php 
                        $statusCode = $row['req_status'];
                        $statusText = REQ_ARR[$statusCode] ?? 'Unknown';
                        $statusClass = '';
                        if ($statusCode == 0) $statusClass = 'status-active';
                        elseif ($statusCode == 1) $statusClass = 'status-pending';
                        elseif ($statusCode == 2) $statusClass = 'status-closed';
                        elseif ($statusCode == 3) $statusClass = 'status-closed';
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($row['created_at']))); ?></td>
                        <td><?php echo htmlspecialchars($row['requirement_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['priority']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['min_budget']) . " - " . htmlspecialchars($row['max_budget']); ?></td>
                        <td class="<?php echo $statusClass; ?>">
                            <?php echo htmlspecialchars($statusText); ?>
                        </td>
                        <td>
                            <button class="view-btn" 
                                    data-id="<?php echo $row['id']; ?>" 
                                    data-email="<?php echo htmlspecialchars($row['email']); ?>">
                                View
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align:center; color:gray;">No requirements available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="requirementModal" tabindex="-1" aria-labelledby="requirementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requirementModalLabel">Requirement Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="requirementDetails">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Bootstrap JS if not already included -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function viewRequirement(id, email) {
    // Show modal
    var myModal = new bootstrap.Modal(document.getElementById('requirementModal'));
    myModal.show();

    // Show loading spinner
    document.getElementById('requirementDetails').innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading requirement details...</p>
        </div>
    `;

    // Fetch details via AJAX
    fetch("get-requirement.php?id=" + id + "&email=" + encodeURIComponent(email))
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        document.getElementById('requirementDetails').innerHTML = data;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('requirementDetails').innerHTML = `
            <div class="alert alert-danger" role="alert">
                <strong>Error!</strong> Failed to load requirement details. Please try again.
            </div>
        `;
    });
}

// Add event listeners to view buttons
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let email = this.getAttribute('data-email');
            viewRequirement(id, email);
        });
    });
});
</script>

<?php include 'common/footer.php'; ?>