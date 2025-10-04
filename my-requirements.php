<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=Please login first");
    exit();
}


// user session data
$user_name = $_SESSION['name'];
?>
<?php 

include 'dbconfigf/dbconst2025.php';

include 'common/header.php'; ?>

<style>

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
    
.myreq-container {
    background-color: #f3f6fa;
    padding: 40px 20px;
}

.myreq-box {
    background: white;
    padding: 30px;
    border-radius: 8px;
    max-width: 900px;
    margin: auto;
    box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
}

.myreq-box h2 {
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
}

.myreq-box label {
    font-weight: 600;
    display: block;
    margin-bottom: 6px;
}

.myreq-box input, 
.myreq-box select, 
.myreq-box textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 18px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.post-btn {
    background: #0dcaf0;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
    display: block;
    margin: 20px auto 0 auto;
}
</style>

<div class="myreq-container">
    <div class="myreq-box">
        <h2>My Requirements</h2>

        <form method="post" action="save_requirement.php">
            <label>Requirements* (Please select your requirements)</label>
            <select name="requirement" required>
                <option value="">Please Select</option>
                <option value="product">Product</option>
                <option value="service">Service</option>
            </select>

            <label>Priority*</label>
            <select name="priority" required>
                <option value="">Please select</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>

            <label>Budget*</label>
            <div style="display:flex; gap:10px;">
                <input type="number" name="budget_min" placeholder="Min" required>
                <input type="number" name="budget_max" placeholder="Max" required>
            </div>
             <label>Category*</label>
            <select name="category">
                <option value="">Select Category</option>
                <?php
                $catResult = $conn->query("SELECT DISTINCT category FROM users where category is NOT NULL ORDER BY category ASC");
                while ($catRow = $catResult->fetch_assoc()) {
                    $cat = htmlspecialchars($catRow['category']);
                    $selected = ($selectedCategory == $cat) ? "selected" : "";
                    echo "<option value='$cat' $selected>$cat</option>";
                }
                ?>
            </select>
            <label>Quantity*</label>
            <input type="text" name="quantity" required>

            <label>Requirement Type</label>
            <select name="requirement_type">
                <option value="">Please select</option>
                <option value="new">New</option>
                <option value="replacement">Replacement</option>
            </select>

            <label>Brand/Make Preferences</label>
            <input type="text" name="brand">

            <label>Message</label>
            <textarea name="message" rows="4"></textarea>

            <button type="submit" class="post-btn">Post Your Requirement</button>
        </form>
    </div>
</div>

<?php include 'common/footer.php'; ?>
