<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=Please login first");
    exit();
}

include 'dbconfigf/dbconst2025.php';

// Map requirement status
define('REQ_ARR', [
    '0' => 'Active',
    '1' => 'Got Product',
    '2' => 'Will Report Later',
    '3' => 'Work Done'
]);

$id = intval($_GET['id'] ?? 0);
$user_email = $_SESSION['email'] ?? '';

// ✅ Get whatsapp of logged-in user
$whatsapp = '';
$stmt = $conn->prepare("SELECT whatsapp FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->bind_result($whatsapp);
$stmt->fetch();
$stmt->close();

// ✅ Fetch requirement from user_details table using whatsapp + id
$stmt = $conn->prepare("SELECT * FROM user_details WHERE id = ? AND whatsapp = ?");
$stmt->bind_param("is", $id, $whatsapp);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    die("Requirement not found or you don’t have permission.");
}

include 'common/header.php';
?>

<style>
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            min-height: 100vh;
            padding-top: 120px; /* Space for header */
        }
        
.requirement-container {
    max-width: 900px;
    margin: 60px auto;
    background: #fff;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0px 8px 25px rgba(0,0,0,0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    animation: fadeIn 0.6s ease-in-out;
}
.requirement-container h2 {
    text-align: center;
    color: #4a148c;
    font-size: 26px;
    margin-bottom: 25px;
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
}
.requirement-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
.requirement-table th {
    text-align: left;
    background: #f3e5f5;
    padding: 12px;
    color: #4a148c;
    font-weight: 600;
    width: 30%;
    border-bottom: 1px solid #ddd;
}
.requirement-table td {
    padding: 12px;
    color: #333;
    border-bottom: 1px solid #eee;
}
.requirement-table tr:hover td {
    background: #fafafa;
}
.action-buttons {
    margin-top: 25px;
    text-align: center;
}
.action-buttons a {
    display: inline-block;
    margin: 0 10px;
    padding: 12px 25px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    transition: all 0.3s ease;
}
.action-buttons a.edit-btn {
    background: #6a1b9a;
    color: #fff;
}
.action-buttons a.edit-btn:hover {
    background: #4a148c;
}
.action-buttons a.back-btn {
    background: #e0e0e0;
    color: #333;
}
.action-buttons a.back-btn:hover {
    background: #bdbdbd;
}

/* Small animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<div class="requirement-container">
    <h2>Requirement Details</h2>
    <table class="requirement-table">
        <tr><th>Date</th><td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td></tr>
        <tr><th>Requirement Type</th><td><?php echo htmlspecialchars($row['requirement_type']); ?></td></tr>
        <tr><th>Priority</th><td><?php echo htmlspecialchars($row['priority']); ?></td></tr>
        <tr><th>Quantity</th><td><?php echo htmlspecialchars($row['quantity']); ?></td></tr>
        <tr><th>Budget</th><td><?php echo htmlspecialchars($row['min_budget'])." - ".htmlspecialchars($row['max_budget']); ?></td></tr>
        <tr><th>Status</th><td><?php echo REQ_ARR[$row['req_status']] ?? 'Unknown'; ?></td></tr>
        <tr><th>Description</th><td><?php echo nl2br(htmlspecialchars($row['description'] ?? '')); ?></td></tr>
    </table>

    <div class="action-buttons">
        <a href="edit_requirement.php?id=<?php echo $row['id']; ?>" class="edit-btn">✏️ Edit</a>
        <a href="requirements-list.php" class="back-btn">⬅ Back</a>
    </div>
</div>

<?php include 'common/footer.php'; ?>
