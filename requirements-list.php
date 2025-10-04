<?php  
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=Please login first");
    exit();
}

// DB Connection
include 'dbconfigf/dbconst2025.php';

// Logged-in user email from session
$user_email = $_SESSION['email'] ?? '';

// ✅ First fetch whatsapp of logged-in user from `users` table
$whatsapp = '';
$stmt = $conn->prepare("SELECT whatsapp FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->bind_result($whatsapp);
$stmt->fetch();
$stmt->close();

// Now fetch requirements from `user_details` table using whatsapp
$stmt = $conn->prepare("SELECT * FROM user_details WHERE whatsapp = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $whatsapp);
$stmt->execute();
$result = $stmt->get_result();
?>
<?php include 'common/header.php'; ?>

<style>
/* Keep your existing CSS unchanged */
* {
    box-sizing: border-box;
}
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #e8d5e8, #f2d6d6);
    min-height: 100vh;
    padding-top: 120px; /* Account for fixed header */
}
.req-container {
    background-color: #f3f6fa;
    padding: 40px 20px;
    min-height: calc(100vh - 200px);
}
.req-box {
    background: white;
    padding: 30px;
    border-radius: 12px;
    max-width: 1100px;
    margin: auto;
    box-shadow: 0px 4px 15px rgba(0,0,0,0.08);
    overflow-x: auto;
}
.req-box h2 {
    text-align: center;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}
.req-box p {
    text-align: center;
    color: #6c757d;
    margin-bottom: 25px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 15px;
}
table th, table td {
    border: 1px solid #dee2e6;
    padding: 12px 14px;
    text-align: center;
    vertical-align: middle;
}
table th {
    background: #6a4c93;
    color: #fff;
    font-weight: 600;
}
table tr:nth-child(even) {
    background: #f9f9f9;
}
table a {
    color: #6a4c93;
    text-decoration: none;
    font-weight: 500;
}
table a:hover {
    text-decoration: underline;
}
.no-data {
    text-align: center;
    color: #888;
    padding: 20px;
    font-size: 16px;
}
</style>

<div class="req-container">
    <div class="req-box">
        <h2>My Requirements List</h2>
        <p>Here you can see all the requirements you have created.</p>

        <table>
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
                <?php
                if ($result->num_rows > 0) {
                    $i = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>".$i++."</td>
                            <td>".date('d-m-Y', strtotime($row['created_at']))."</td>
                            <td>".htmlspecialchars($row['requirement_type'])."</td>
                            <td>".htmlspecialchars($row['priority'])."</td>
                            <td>".htmlspecialchars($row['quantity'])."</td>
                            <td>".htmlspecialchars($row['min_budget'])." - ".htmlspecialchars($row['max_budget'])."</td>
                            <td>".htmlspecialchars($row['req_status'])."</td>
                            <td>
                                <a href='view_requirement.php?id=".$row['id']."'>View</a> |
                                <a href='edit_requirement.php?id=".$row['id']."'>Edit</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='no-data'>No requirements available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'common/footer.php'; ?>
