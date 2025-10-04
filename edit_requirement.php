<?php 
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=Please login first");
    exit();
}

include 'dbconfigf/dbconst2025.php';

$id = intval($_GET['id'] ?? 0);
$user_email = $_SESSION['whatsapp'] ?? '';

// Fetch record
$stmt = $conn->prepare("SELECT * FROM user_details WHERE id = ? AND whatsapp = ?");
$stmt->bind_param("is", $id, $user_email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Requirement not found or you don’t have permission.");
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requirement_type = $_POST['requirement_type'];
    $priority = $_POST['priority'];
    $quantity = $_POST['quantity'];
    $budget_min = $_POST['budget_min'];
    $budget_max = $_POST['budget_max'];
    $status = $_POST['status'];
    $description = $_POST['description'];

    $update = $conn->prepare("UPDATE user_details 
        SET requirement_type=?, priority=?, quantity=?, min_budget=?, max_budget=?, status=?, message=? 
        WHERE id=? AND whatsapp=?");
    $update->bind_param(
        "ssiddssis", 
        $requirement_type, 
        $priority, 
        $quantity, 
        $budget_min,   // decimal
        $budget_max,   // decimal
        $status, 
        $description, 
        $id, 
        $user_email
    );
    
    if ($update->execute()) {
        header("Location: view_requirement.php?id=".$id."&success=Updated successfully");
        exit();
    } else {
        $error = "Error updating record.";
    }
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
        
.edit-container {
    max-width: 800px;
    margin: 50px auto;
    background: #fff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0px 6px 18px rgba(0,0,0,0.08);
}

.edit-container h2 {
    text-align: center;
    color: #6a4c93;
    margin-bottom: 25px;
    font-size: 26px;
    font-weight: 700;
}

.edit-form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
}

.edit-form input,
.edit-form select,
.edit-form textarea {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 18px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    transition: border 0.3s, box-shadow 0.3s;
}

.edit-form input:focus,
.edit-form select:focus,
.edit-form textarea:focus {
    border-color: #6a4c93;
    outline: none;
    box-shadow: 0px 0px 6px rgba(106, 76, 147, 0.3);
}

.edit-form textarea {
    resize: vertical;
    min-height: 100px;
}

.edit-actions {
    text-align: center;
    margin-top: 20px;
}

.edit-actions button,
.edit-actions a {
    display: inline-block;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.3s, transform 0.2s;
}

.edit-actions button {
    background: #6a4c93;
    color: #fff;
    border: none;
}

.edit-actions button:hover {
    background: #573a7b;
    transform: translateY(-2px);
}

.edit-actions a {
    background: #e0e0e0;
    color: #333;
    margin-left: 10px;
}

.edit-actions a:hover {
    background: #ccc;
    transform: translateY(-2px);
}

/* Responsive for mobile */
@media (max-width: 600px) {
    .edit-container {
        padding: 20px;
        margin: 20px;
    }
    .edit-container h2 {
        font-size: 22px;
    }
    .edit-actions button,
    .edit-actions a {
        display: block;
        width: 100%;
        margin: 10px 0;
    }
}
</style>

<div class="edit-container">
    <h2>Edit Requirement</h2>

    <?php if (!empty($error)) echo "<p style='color:red;text-align:center;'>$error</p>"; ?>

    <form method="POST" class="edit-form">
        <label>Requirement Type</label>
        <input type="text" name="requirement_type" value="<?php echo htmlspecialchars($row['requirement_type']); ?>" required>

        <label>Priority</label>
        <select name="priority" required>
            <option <?php if($row['priority']=="Low") echo "selected"; ?>>Low</option>
            <option <?php if($row['priority']=="Medium") echo "selected"; ?>>Medium</option>
            <option <?php if($row['priority']=="High") echo "selected"; ?>>High</option>
        </select>

        <label>Quantity</label>
        <input type="number" name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>" required>

        <label>Budget Min</label>
        <input type="number" step="0.01" name="budget_min" value="<?php echo htmlspecialchars($row['min_budget']); ?>" required>

        <label>Budget Max</label>
        <input type="number" step="0.01" name="budget_max" value="<?php echo htmlspecialchars($row['max_budget']); ?>" required>

        <label>Status</label>
<select name="status" required>
    <?php
    define('REQ_ARR', [
        '0' => 'Active',
        '1' => 'Got Product',
        '2' => 'Will Report Later',
        '3' => 'Work Done'
    ]);

    foreach (REQ_ARR as $key => $value) {
        $selected = ($row['status'] == $key) ? "selected" : "";
        echo "<option value='{$key}' {$selected}>{$value}</option>";
    }
    ?>
</select>

        <label>Description</label>
        <textarea name="description"><?php echo htmlspecialchars($row['message']); ?></textarea>

        <div class="edit-actions">
            <button type="submit">Update</button>
            <a href="view_requirement.php?id=<?php echo $id; ?>">Cancel</a>
        </div>
    </form>
</div>

<?php include 'common/footer.php'; ?>
