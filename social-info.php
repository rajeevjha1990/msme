<?php
session_start();
include 'dbconfigf/dbconst2025.php';
include 'common/header.php';

// Ensure user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=Please login first");
    exit();
}

$user_email = $_SESSION['email'];

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    $google_business = $_POST['google_business'];
    $youtube = $_POST['youtube'];
    $linkedin = $_POST['linkedin'];
    $twitter = $_POST['twitter'];
    $interest_area = $_POST['interest_area'];
    $hobbies = $_POST['hobbies'];

    $update = $conn->prepare("UPDATE users SET 
        facebook=?, instagram=?, youtube=?, linkedin=?,
        interest_area=?, hobbies=? 
        WHERE email=?");
      
    $update->bind_param("sssssss",
        $facebook, $instagram, $youtube, $linkedin,
        $interest_area, $hobbies, $user_email
    );

    if ($update->execute()) {
        echo "<script>
            alert('Social information updated successfully!');
            window.location.href = 'dashboard.php';
        </script>";
        exit;
    } else {
        $error = "Error updating social information.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Social Information</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f5f5f5; padding-top: 70px; }
    .navbar { background: #0a1229; }
    .navbar-brand, .nav-link { color: white !important; }
    .profile-card { max-width: 950px; margin: 30px auto; background: #fff; border-radius: 12px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); padding: 25px; }
    .footer { background: #0a1229; color: white; text-align: center; padding: 15px; margin-top: 40px; }
    .btn-primary { background: #0a1229; border: none; }
    .btn-primary:hover { background: #1b2360; }
    input[readonly], input[disabled] { background-color: #e9ecef !important; color: #6c757d; }
    
    /* Progress Indicator */
    .progress-steps {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      position: relative;
    }
    
    .progress-steps::before {
      content: '';
      position: absolute;
      top: 20px;
      left: 0;
      right: 0;
      height: 2px;
      background: #e9ecef;
      z-index: 1;
    }
    
    .progress-steps::after {
      content: '';
      position: absolute;
      top: 20px;
      left: 0;
      width: 100%;
      height: 2px;
      background: #007bff;
      z-index: 2;
    }
    
    .step {
      background: #007bff;
      color: white;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      position: relative;
      z-index: 3;
    }
    
    .step.incomplete {
      background: #e9ecef;
      color: #6c757d;
      border: 2px solid #007bff;
    }
    
    .step-label {
      margin-top: 8px;
      font-size: 12px;
      text-align: center;
      color: #6c757d;
    }
    
    .step-container {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
  </style>
</head>
<body>

<div class="profile-card">
  <!-- Progress Steps -->
 
  
  <h4 class="mb-3">Social Information</h4>

  <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

  <div class="progress mb-4">
    <div class="progress-bar bg-success" role="progressbar" style="width: 100%">100% Complete</div>
  </div>

  <form method="POST" class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Facebook</label>
      <input type="url" class="form-control" name="facebook" value="<?= htmlspecialchars($user['facebook'] ?? '') ?>" placeholder="https://facebook.com/yourprofile">
    </div>
    <div class="col-md-6">
      <label class="form-label">Instagram</label>
      <input type="url" class="form-control" name="instagram" value="<?= htmlspecialchars($user['instagram'] ?? '') ?>" placeholder="https://instagram.com/yourprofile">
    </div>

    <div class="col-md-6">
      <label class="form-label">Google My Business</label>
      <input type="url" class="form-control" name="google_business" value="<?= htmlspecialchars($user['google_business'] ?? '') ?>" placeholder="https://goo.gl/maps/yourbusiness">
    </div>
    <div class="col-md-6">
      <label class="form-label">Youtube</label>
      <input type="url" class="form-control" name="youtube" value="<?= htmlspecialchars($user['youtube'] ?? '') ?>" placeholder="https://youtube.com/yourchannel">
    </div>

    <div class="col-md-6">
      <label class="form-label">LinkedIn</label>
      <input type="url" class="form-control" name="linkedin" value="<?= htmlspecialchars($user['linkedin'] ?? '') ?>" placeholder="https://linkedin.com/in/yourprofile">
    </div>
    <div class="col-md-6">
      <label class="form-label">Twitter/X</label>
      <input type="url" class="form-control" name="twitter" value="<?= htmlspecialchars($user['twitter'] ?? '') ?>" placeholder="https://twitter.com/yourhandle">
    </div>

    <div class="col-md-6">
      <label class="form-label">Interest Area</label>
      <select class="form-select" name="interest_area">
        <option value="">Please select</option>
        <option value="Technology" <?= ($user['interest_area'] ?? '')=='Technology'?'selected':''; ?>>Technology</option>
        <option value="Finance" <?= ($user['interest_area'] ?? '')=='Finance'?'selected':''; ?>>Finance</option>
        <option value="Healthcare" <?= ($user['interest_area'] ?? '')=='Healthcare'?'selected':''; ?>>Healthcare</option>
        <option value="Education" <?= ($user['interest_area'] ?? '')=='Education'?'selected':''; ?>>Education</option>
        <option value="Real Estate" <?= ($user['interest_area'] ?? '')=='Real Estate'?'selected':''; ?>>Real Estate</option>
        <option value="Manufacturing" <?= ($user['interest_area'] ?? '')=='Manufacturing'?'selected':''; ?>>Manufacturing</option>
        <option value="Retail" <?= ($user['interest_area'] ?? '')=='Retail'?'selected':''; ?>>Retail</option>
        <option value="Food & Beverage" <?= ($user['interest_area'] ?? '')=='Food & Beverage'?'selected':''; ?>>Food & Beverage</option>
        <option value="Travel & Tourism" <?= ($user['interest_area'] ?? '')=='Travel & Tourism'?'selected':''; ?>>Travel & Tourism</option>
        <option value="Entertainment" <?= ($user['interest_area'] ?? '')=='Entertainment'?'selected':''; ?>>Entertainment</option>
        <option value="Sports" <?= ($user['interest_area'] ?? '')=='Sports'?'selected':''; ?>>Sports</option>
        <option value="Arts & Culture" <?= ($user['interest_area'] ?? '')=='Arts & Culture'?'selected':''; ?>>Arts & Culture</option>
        <option value="Environment" <?= ($user['interest_area'] ?? '')=='Environment'?'selected':''; ?>>Environment</option>
        <option value="Social Impact" <?= ($user['interest_area'] ?? '')=='Social Impact'?'selected':''; ?>>Social Impact</option>
        <option value="Other" <?= ($user['interest_area'] ?? '')=='Other'?'selected':''; ?>>Other</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Hobbies (Max. 50 Characters)</label>
      <textarea class="form-control" name="hobbies" maxlength="50" rows="2" placeholder="Max. 50 characters with space"><?= htmlspecialchars($user['hobbies'] ?? '') ?></textarea>
      <div class="form-text">Characters remaining: <span id="hobbies-count">50</span></div>
    </div>

    <div class="col-12 d-flex justify-content-between mt-4">
      <a href="booster-survey.php" class="btn btn-secondary">Previous</a>
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
</div>

<script>
// Character counter for hobbies
document.addEventListener('DOMContentLoaded', function() {
    const hobbiesTextarea = document.querySelector('textarea[name="hobbies"]');
    const hobbiesCount = document.getElementById('hobbies-count');
    
    function updateCount() {
        const remaining = 50 - hobbiesTextarea.value.length;
        hobbiesCount.textContent = remaining;
        hobbiesCount.style.color = remaining < 10 ? '#dc3545' : '#6c757d';
    }
    
    hobbiesTextarea.addEventListener('input', updateCount);
    updateCount(); // Initial count
});
</script>

<?php include 'common/footer.php'; ?>
</body>
</html>