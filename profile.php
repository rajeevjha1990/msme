<?php
session_start();
include 'dbconfigf/dbconst2025.php';
include 'common/header.php';

// Ensure user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=Please login first");
    exit();
}

$user_email = $_SESSION['email']; // Session email

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Calculate dates
$registration_date = $user['created_at'] ?? date("Y-m-d H:i:s"); // assuming DB column created_at
$next_renewal_date = date("Y-m-d H:i:s", strtotime($registration_date . " +1 year +7 days"));

// Handle form update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name        = $_POST['name'];
    $gender      = $_POST['gender'];
    $alternate   = $_POST['alternate'];
    $blood_group = $_POST['blood_group'];
    $day         = $_POST['day'];
    $month       = $_POST['month'];
    $mday        = $_POST['mday'];
    $mmonth      = $_POST['mmonth'];

    $update = $conn->prepare("UPDATE users SET
        name=?, gender=?, alternate=?, blood_group=?, day=?, month=?, mday=?, mmonth=?
        WHERE email=?");
    $update->bind_param("ssssisiss", $name, $gender, $alternate, $blood_group, $day, $month, $mday, $mmonth, $user_email);

    if ($update->execute()) {
        $success = "Profile updated successfully!";
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
         echo "<script>
            alert('Profile updated successfully!');
            window.location.href = 'buisness-info.php';
          </script>";
    exit();

    } else {
        $error = "Error updating profile.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; padding-top: 70px; }
        .navbar { background: #0a1229; }
        .navbar-brand, .nav-link { color: white !important; }
        .profile-card { max-width: 800px; margin: 30px auto; background: #fff; border-radius: 12px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); padding: 25px; }
        .footer { background: #0a1229; color: white; text-align: center; padding: 15px; margin-top: 40px; }
        .btn-primary { background: #0a1229; border: none; }
        .btn-primary:hover { background: #1b2360; }
        input[readonly], input[disabled] { background-color: #e9ecef !important; color: #6c757d; }
    </style>
</head>
<body>

<div class="profile-card">
    <h4 class="mb-3">Member Profile</h4>

    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <div class="progress mb-4">
        <div class="progress-bar bg-danger" role="progressbar" style="width: 14%">14% Complete</div>
    </div>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Name*</label>
            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Gender*</label>
            <select class="form-select" name="gender" required>
                <option value="">Please select</option>
                <option value="Male"   <?php if (($user['gender'] ?? '') == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if (($user['gender'] ?? '') == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other"  <?php if (($user['gender'] ?? '') == 'Other') echo 'selected'; ?>>Other</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">WhatsApp No.*</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['whatsapp'] ?? ''); ?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label">Alternate Contact No.</label>
            <input type="text" class="form-control" name="alternate" value="<?php echo htmlspecialchars($user['alternate'] ?? ''); ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email ID</label>
            <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label">Blood Group</label>
            <select class="form-select" name="blood_group">
                <option value="">Please select</option>
                <?php
                $groups = ["A+","A-","B+","B-","O+","O-","AB+","AB-"];
                foreach ($groups as $g) {
                    $selected = (($user['blood_group'] ?? '') == $g) ? "selected" : "";
                    echo "<option value='$g' $selected>$g</option>";
                }
                ?>
            </select>
        </div>

        <?php
        // Define months array for VARCHAR storage - stores full month names
        $months = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        ?>

        <!-- Birth Date and Month -->
        <div class="col-md-3">
            <label class="form-label">Birth Date</label>
            <select class="form-select" name="day">
                <option value="">Day</option>
                <?php
                for ($i = 1; $i <= 31; $i++) {
                    $selected = (($user['day'] ?? '') == $i) ? "selected" : "";
                    echo "<option value='$i' $selected>$i</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Birth Month</label>
            <select class="form-select" name="month">
                <option value="">Select Month</option>
                <?php
                foreach ($months as $month_name) {
                    $selected = (($user['month'] ?? '') == $month_name) ? "selected" : "";
                    echo "<option value='$month_name' $selected>$month_name</option>";
                }
                ?>
            </select>
        </div>

        <!-- Marriage Anniversary Date and Month -->
        <div class="col-md-3">
            <label class="form-label">Anniversary Date</label>
            <select class="form-select" name="mday">
                <option value="">Day</option>
                <?php
                for ($i = 1; $i <= 31; $i++) {
                    $selected = (($user['mday'] ?? '') == $i) ? "selected" : "";
                    echo "<option value='$i' $selected>$i</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Anniversary Month</label>
            <select class="form-select" name="mmonth">
                <option value="">Select Month</option>
                <?php
                foreach ($months as $month_name) {
                    $selected = (($user['mmonth'] ?? '') == $month_name) ? "selected" : "";
                    echo "<option value='$month_name' $selected>$month_name</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Registration Date</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($registration_date); ?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label">Next Renewal Date</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($next_renewal_date); ?>" readonly>
        </div>

        <div class="col-12 d-flex justify-content-between mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Previous</a>
            <button type="submit" class="btn btn-primary">Save & Next</button>
        </div>
    </form>
</div>
<?php include 'common/footer.php'; ?>
</body>
</html>
