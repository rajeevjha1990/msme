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


// Fetch distinct natures from users table
$natureOptions = [];
$natureQuery = $conn->query("SELECT DISTINCT nature FROM users WHERE nature IS NOT NULL AND nature != ''");
if ($natureQuery) {
    while ($row = $natureQuery->fetch_assoc()) {
        $natureOptions[] = $row['nature'];
    }
}


// Handle form update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $entity_status = trim($_POST['entity_status']);
    $entity_name   = trim($_POST['entity_name']);
    $nature        = trim($_POST['nature']);
    $website       = trim($_POST['website']);
    $industry      = trim($_POST['industry']);
    $category      = trim($_POST['category']);
    $address       = trim($_POST['address']);
    $state         = trim($_POST['state']);
    $city          = trim($_POST['city']);
    $pincode       = trim($_POST['pincode']);
    $products      = trim($_POST['products']);
    $description   = trim($_POST['description']);
    $gives         = trim($_POST['gives']);
    $asks          = trim($_POST['asks']);
    $team_size     = trim($_POST['team_size']);
    $branches      = trim($_POST['branches']);
    $years         = trim($_POST['years_business']);
    $turnover      = trim($_POST['turnover']);

    // Prepare update statement
    $update = $conn->prepare("UPDATE users SET 
        entity_status=?, entity_name=?, nature=?, website=?, industry=?, category=?, 
        address=?, state=?, city=?, pincode=?, products=?, description=?, gives=?, asks=?, 
        team_size=?, branches=?, years_business=?, turnover=? 
        WHERE email=?"
    );

    if (!$update) {
        // Prepare failed
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    if (!$update->bind_param(
        "sssssssssssssssssss", 
        $entity_status, $entity_name, $nature, $website, $industry, $category,
        $address, $state, $city, $pincode, $products, $description, $gives, $asks,
        $team_size, $branches, $years, $turnover, $user_email
    )) {
        die("Bind failed: " . $update->error);
    }

    // Execute statement
    if (!$update->execute()) {
        die("Execute failed: " . $update->error);
    } else {
        echo "<script>
            alert('Business info updated successfully!');
            window.location.href = 'booster-survey.php';
        </script>";
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Business Information</title>
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
  </style>
</head>
<body>

<div class="profile-card">
  <h4 class="mb-3">Business Information</h4>

  <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

  <div class="progress mb-4">
    <div class="progress-bar bg-danger" role="progressbar" style="width: 28%">28% Complete</div>
  </div>

  <form method="POST" class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Business Entity Status*</label>
      <select class="form-select" name="entity_status" required>
        <option value="">Please select</option>
        <option value="Proprietorship" <?= ($user['entity_status'] ?? '')=='Proprietorship'?'selected':''; ?>>Proprietorship</option>
        <option value="Partnership" <?= ($user['entity_status'] ?? '')=='Partnership'?'selected':''; ?>>Partnership</option>
        <option value="Private Limited" <?= ($user['entity_status'] ?? '')=='Private Limited'?'selected':''; ?>>Private Limited</option>
        <option value="LLP" <?= ($user['entity_status'] ?? '')=='LLP'?'selected':''; ?>>LLP</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Business Entity Name</label>
      <input type="text" class="form-control" name="entity_name" value="<?= htmlspecialchars($user['entity_name'] ?? '') ?>">
    </div>

   <div class="col-md-6">
  <label class="form-label">Nature of Business*</label>
  <select class="form-select" name="nature" required>
    <option value="">Please select</option>
    <?php foreach($natureOptions as $option): ?>
      <option value="<?= htmlspecialchars($option) ?>" 
        <?= ($user['nature'] ?? '') == $option ? 'selected' : '' ?>>
        <?= htmlspecialchars($option) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

    

    <div class="col-md-6">
      <label class="form-label">Industry Classification*</label>
      <input type="text" class="form-control" name="industry" value="<?= htmlspecialchars($user['industry'] ?? '') ?>" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Business Category*</label>
      <input type="text" class="form-control" name="category" value="<?= htmlspecialchars($user['category'] ?? '') ?>" required>
    </div>

    <div class="col-md-12">
      <label class="form-label">Business Address*</label>
      <textarea class="form-control" name="address" rows="2" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
    </div>

    <div class="col-md-4">
      <label class="form-label">State*</label>
      <input type="text" class="form-control" name="state" value="<?= htmlspecialchars($user['state'] ?? '') ?>" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">City*</label>
      <input type="text" class="form-control" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">PIN Code*</label>
      <input type="text" class="form-control" name="pincode" value="<?= htmlspecialchars($user['pincode'] ?? '') ?>" required>
    </div>

    <div class="col-md-12">
      <label class="form-label">Your 10 Major Products/Services (Max 150 chars)</label>
      <textarea class="form-control" name="products" maxlength="150" rows="2"><?= htmlspecialchars($user['products'] ?? '') ?></textarea>
    </div>

    <div class="col-md-12">
      <label class="form-label">Business Description (Max 600 chars)</label>
      <textarea class="form-control" name="description" maxlength="600" rows="3"><?= htmlspecialchars($user['description'] ?? '') ?></textarea>
    </div>

    <div class="col-md-6">
      <label class="form-label">Your 5 Gives</label>
      <textarea class="form-control" name="gives" maxlength="150" rows="2"><?= htmlspecialchars($user['gives'] ?? '') ?></textarea>
    </div>
    <div class="col-md-6">
      <label class="form-label">Your 5 Asks</label>
      <textarea class="form-control" name="asks" maxlength="150" rows="2"><?= htmlspecialchars($user['asks'] ?? '') ?></textarea>
    </div>

    <div class="col-md-6">
      <label class="form-label">Team Size</label>
      <select class="form-select" name="team_size">
        <option value="">Please select</option>
        <option value="1-10" <?= ($user['team_size'] ?? '')=='1-10'?'selected':''; ?>>1-10</option>
        <option value="11-50" <?= ($user['team_size'] ?? '')=='11-50'?'selected':''; ?>>11-50</option>
        <option value="51-200" <?= ($user['team_size'] ?? '')=='51-200'?'selected':''; ?>>51-200</option>
        <option value="200+" <?= ($user['team_size'] ?? '')=='200+'?'selected':''; ?>>200+</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">No. of Branches</label>
      <select class="form-select" name="branches">
        <option value="">Please select</option>
        <option value="1" <?= ($user['branches'] ?? '')=='1'?'selected':''; ?>>1</option>
        <option value="2-5" <?= ($user['branches'] ?? '')=='2-5'?'selected':''; ?>>2-5</option>
        <option value="6-10" <?= ($user['branches'] ?? '')=='6-10'?'selected':''; ?>>6-10</option>
        <option value="10+" <?= ($user['branches'] ?? '')=='10+'?'selected':''; ?>>10+</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Years in Current Business*</label>
      <select class="form-select" name="years_business" required>
        <option value="">Please select</option>
        <option value="0-1" <?= ($user['years_business'] ?? '')=='0-1'?'selected':''; ?>>0-1</option>
        <option value="2-5" <?= ($user['years_business'] ?? '')=='2-5'?'selected':''; ?>>2-5</option>
        <option value="6-10" <?= ($user['years_business'] ?? '')=='6-10'?'selected':''; ?>>6-10</option>
        <option value="10+" <?= ($user['years_business'] ?? '')=='10+'?'selected':''; ?>>10+</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Current Turnover (INR)</label>
      <select class="form-select" name="turnover">
        <option value="">Please select</option>
        <option value="0-10L" <?= ($user['turnover'] ?? '')=='0-10L'?'selected':''; ?>>0 - 10 Lakh</option>
        <option value="10L-1Cr" <?= ($user['turnover'] ?? '')=='10L-1Cr'?'selected':''; ?>>10 Lakh - 1 Crore</option>
        <option value="1Cr-10Cr" <?= ($user['turnover'] ?? '')=='1Cr-10Cr'?'selected':''; ?>>1 Crore - 10 Crore</option>
        <option value="10Cr+" <?= ($user['turnover'] ?? '')=='10Cr+'?'selected':''; ?>>10 Crore+</option>
      </select>
    </div>

    <div class="col-12 d-flex justify-content-between mt-4">
      <a href="profile.php" class="btn btn-secondary">Previous</a>
      <button type="submit" class="btn btn-primary">Save & Next</button>
    </div>
  </form>
</div>

<?php include 'common/footer.php'; ?>
</body>
</html>
