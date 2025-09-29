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

// Fetch existing survey data if any
$stmt = $conn->prepare("SELECT * FROM booster_survey WHERE user_email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$survey = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attend_meets        = $_POST['attend_meets'];
    $whatsapp_groups     = $_POST['whatsapp_groups'];
    $sponsor_events      = $_POST['sponsor_events'];
    $display_expo        = $_POST['display_expo'];
    $mentorship          = $_POST['mentorship'];
    $blog_write          = $_POST['blog_write'];
    $communication_mode  = $_POST['communication_mode'];
    $collaborate         = $_POST['collaborate'];
    $best_time_online    = $_POST['best_time_online'];
    $receive_referrals   = $_POST['receive_referrals'];
    $featured_website    = $_POST['featured_website'];
    $paid_promotions     = $_POST['paid_promotions'];
    $training_interest   = $_POST['training_interest'];
    $vip_invites         = $_POST['vip_invites'];

    // Check if survey already exists for this user
    if ($survey) {
        // Update existing record
        $update = $conn->prepare("UPDATE booster_survey SET 
            attend_meets=?, whatsapp_groups=?, sponsor_events=?, display_expo=?, 
            mentorship=?, blog_write=?, communication_mode=?, collaborate=?, 
            best_time_online=?, receive_referrals=?, featured_website=?, 
            paid_promotions=?, training_interest=?, vip_invites=? 
            WHERE user_email=?");
        $update->bind_param("sssssssssssssss", 
            $attend_meets, $whatsapp_groups, $sponsor_events, $display_expo, 
            $mentorship, $blog_write, $communication_mode, $collaborate, 
            $best_time_online, $receive_referrals, $featured_website, 
            $paid_promotions, $training_interest, $vip_invites, $user_email
        );
        $stmt_to_execute = $update;
    } else {
        // Insert new record
        $insert = $conn->prepare("INSERT INTO booster_survey 
            (user_email, attend_meets, whatsapp_groups, sponsor_events, display_expo, 
            mentorship, blog_write, communication_mode, collaborate, best_time_online, 
            receive_referrals, featured_website, paid_promotions, training_interest, vip_invites) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("sssssssssssssss", 
            $user_email, $attend_meets, $whatsapp_groups, $sponsor_events, $display_expo, 
            $mentorship, $blog_write, $communication_mode, $collaborate, 
            $best_time_online, $receive_referrals, $featured_website, 
            $paid_promotions, $training_interest, $vip_invites
        );
        $stmt_to_execute = $insert;
    }

    if ($stmt_to_execute->execute()) {
        echo "<script>
            alert('Booster survey updated successfully!');
            window.location.href = 'social-info.php';
        </script>";
        exit;
    } else {
        $error = "Error updating booster survey.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booster Survey</title>
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
  <h4 class="mb-3">Booster Survey</h4>

  <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

  <div class="progress mb-4">
    <div class="progress-bar bg-warning" role="progressbar" style="width: 56%">56% Complete</div>
  </div>

  <form method="POST" class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Are you interested in attending physical business meets if organized? *</label>
      <select class="form-select" name="attend_meets" required>
        <option value="">Please select</option>
        <option value="Yes" <?= ($survey['attend_meets'] ?? '')=='Yes'?'selected':''; ?>>Yes</option>
        <option value="No" <?= ($survey['attend_meets'] ?? '')=='No'?'selected':''; ?>>No</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Are you open to collaborating with other businesses?</label>
      <select class="form-select" name="collaborate">
        <option value="">Please select</option>
        <option value="Yes" <?= ($survey['collaborate'] ?? '')=='Yes'?'selected':''; ?>>Yes</option>
        <option value="No" <?= ($survey['collaborate'] ?? '')=='No'?'selected':''; ?>>No</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Are you interested in city-wise WhatsApp business groups? *</label>
      <select class="form-select" name="whatsapp_groups" required>
        <option value="">Please select</option>
        <option value="Yes" <?= ($survey['whatsapp_groups'] ?? '')=='Yes'?'selected':''; ?>>Yes</option>
        <option value="No" <?= ($survey['whatsapp_groups'] ?? '')=='No'?'selected':''; ?>>No</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">What time slots suit you best for online meets?</label>
      <select class="form-select" name="best_time_online">
        <option value="">Please select</option>
        <option value="Morning" <?= ($survey['best_time_online'] ?? '')=='Morning'?'selected':''; ?>>Morning</option>
        <option value="Afternoon" <?= ($survey['best_time_online'] ?? '')=='Afternoon'?'selected':''; ?>>Afternoon</option>
        <option value="Evening" <?= ($survey['best_time_online'] ?? '')=='Evening'?'selected':''; ?>>Evening</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Are you open to sponsoring events or activities?</label>
      <select class="form-select" name="sponsor_events">
        <option value="">Please select</option>
        <option value="Yes" <?= ($survey['sponsor_events'] ?? '')=='Yes'?'selected':''; ?>>Yes</option>
        <option value="No" <?= ($survey['sponsor_events'] ?? '')=='No'?'selected':''; ?>>No</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Would you like to receive business referrals from other members?</label>
      <select class="form-select" name="receive_referrals">
        <option value="">Please select</option>
        <option value="Yes" <?= ($survey['receive_referrals'] ?? '')=='Yes'?'selected':''; ?>>Yes</option>
        <option value="No" <?= ($survey['receive_referrals'] ?? '')=='No'?'selected':''; ?>>No</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Are you interested in displaying your business at expos or trade fairs?</label>
      <select class="form-select" name="display_expo">
        <option value="">Please select</option>
        <option value="Yes" <?= ($survey['display_expo'] ?? '')=='Yes'?'selected':''; ?>>Yes</option>
        <option value="No" <?= ($survey['display_expo'] ?? '')=='No'?'selected':''; ?>>No</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Are you willing to be interviewed or featured on our website? *</label>
      <select class="form-select" name="featured_website" required>
        <option value="">Please select</option>
        <option value="Yes" <?= ($survey['featured_website'] ?? '')=='Yes'?'selected':''; ?>>Yes</option>
        <option value="No" <?= ($survey['featured_website'] ?? '')=='No'?'selected':''; ?>>No</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Are you interested in mentorship (either as mentor or mentee)?</label>
      <select class="form-select" name="mentorship">
        <option value="">Please select</option>
        <option value="Yes" <?= ($survey['mentorship'] ?? '')=='Yes'?'selected':''; ?>>Yes</option>
        <option value="No" <?= ($survey['mentorship'] ?? '')=='No'?'selected':''; ?>>No</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Are you interested in exclusive paid promotions or advertisements?</label>
      <select class="form-select" name="paid_promotions">
        <option value="">Please select</option>
        <option value="Yes" <?= ($survey['paid_promotions'] ?? '')=='Yes'?'selected':''; ?>>Yes</option>
        <option value="No" <?= ($survey['paid_promotions'] ?? '')=='No'?'selected':''; ?>>No</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Are you willing to write a blog for MSME GLOBAL, published with your name? *</label>
      <select class="form-select" name="blog_write" required>
        <option value="">Please select</option>
        <option value="Yes" <?= ($survey['blog_write'] ?? '')=='Yes'?'selected':''; ?>>Yes</option>
        <option value="No" <?= ($survey['blog_write'] ?? '')=='No'?'selected':''; ?>>No</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Do you have an interest in attending training sessions (business skills, branding, networking)? *</label>
      <select class="form-select" name="training_interest" required>
        <option value="">Please select</option>
        <option value="Yes" <?= ($survey['training_interest'] ?? '')=='Yes'?'selected':''; ?>>Yes</option>
        <option value="No" <?= ($survey['training_interest'] ?? '')=='No'?'selected':''; ?>>No</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">What is your preferred mode of communication? *</label>
      <select class="form-select" name="communication_mode" required>
        <option value="">Please select</option>
        <option value="Email" <?= ($survey['communication_mode'] ?? '')=='Email'?'selected':''; ?>>Email</option>
        <option value="Phone" <?= ($survey['communication_mode'] ?? '')=='Phone'?'selected':''; ?>>Phone</option>
        <option value="WhatsApp" <?= ($survey['communication_mode'] ?? '')=='WhatsApp'?'selected':''; ?>>WhatsApp</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Would you prefer personal invitations for select VIP networking events?</label>
      <select class="form-select" name="vip_invites">
        <option value="">Please select</option>
        <option value="Yes" <?= ($survey['vip_invites'] ?? '')=='Yes'?'selected':''; ?>>Yes</option>
        <option value="No" <?= ($survey['vip_invites'] ?? '')=='No'?'selected':''; ?>>No</option>
      </select>
    </div>

    <div class="col-12 d-flex justify-content-between mt-4">
      <a href="business-info.php" class="btn btn-secondary">Previous</a>
      <button type="submit" class="btn btn-primary">Save & Next</button>
    </div>
  </form>
</div>

<?php include 'common/footer.php'; ?>
</body>
</html>