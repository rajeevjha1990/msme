<?php
session_start();
include 'dbconfigf/dbconst2025.php';

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userid = (int) $_SESSION['user_id'];

// Read and clear flash (so it shows only once)
$error_msg = "";
$success_msg = "";
if (!empty($_SESSION['flash'])) {
    $error_msg   = isset($_SESSION['flash']['error']) ? $_SESSION['flash']['error'] : "";
    $success_msg = isset($_SESSION['flash']['success']) ? $_SESSION['flash']['success'] : "";
    unset($_SESSION['flash']);
}

// Fetch current password hash
$stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id=?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    die("User not found.");
}
$row = $result->fetch_assoc();
$current_hash = $row['password_hash'];

// Handle POST (process then redirect with flash)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password     = $_POST['password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
      if (!password_verify($old_password, $current_hash)) {
          $_SESSION['flash']['error'] = "Old password is incorrect.";
          header('Location: ' . $_SERVER['PHP_SELF']);
          exit();
      } elseif ($new_password !== $confirm_password) {
          $_SESSION['flash']['error'] = "New password and confirm password do not match.";
          header('Location: ' . $_SERVER['PHP_SELF']);
          exit();
      }
    // All good — update password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $update = $conn->prepare("UPDATE users SET password_hash=? WHERE user_id=?");
    $update->bind_param("si", $hashed_password, $userid);

    if ($update->execute()) {
        $_SESSION['flash']['success'] = "Password updated successfully.";
        $_SESSION = [];
      session_destroy();
      header("Location: home.php?msg=Password changed successfully, please login again.");
      exit();
    } else {
        $_SESSION['flash']['error'] = "Something went wrong. Try again later.";
    }

    // Redirect to avoid form resubmission and to show flash once
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<style>
* { box-sizing: border-box; }
body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
html, body { overflow-x: hidden; }
.reset-wrapper {
  min-height: calc(100vh - 200px);
  display: flex;
  justify-content: center;
  align-items: center;
}
.reset-container {
  background: #fff;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0px 4px 12px rgba(0,0,0,0.15);
  width: 400px;
  text-align: center;
}
.reset-container h2 { margin-bottom: 20px; font-size: 22px; color: #333; }
.reset-container input[type="password"] {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
}
.reset-container button {
  width: 100%;
  padding: 12px;
  background: #007bff;
  border: none;
  color: #fff;
  font-size: 16px;
  font-weight: bold;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.3s;
}
.reset-container button:hover { background: #0056b3; }
.error { color: red; margin-bottom: 10px; }
.success { color: green; margin-bottom: 10px; }
.debug { background: #f9f9f9; border: 1px solid #ddd; padding: 10px; margin-top: 20px; text-align: left; font-size: 12px; }
</style>
</head>
<body>
<?php include 'common/header.php'; ?>

<div class="reset-wrapper">
  <div class="reset-container">
    <h2>Change Password</h2>

    <?php if ($error_msg) echo "<div class='error'>{$error_msg}</div>"; ?>
    <?php if ($success_msg) echo "<div class='success'>{$success_msg}</div>"; ?>

<form method="POST" action="">
      <input type="password" name="password" placeholder="Old password" required />
      <input type="password" name="new_password" placeholder="Enter new password" required />
      <input type="password" name="confirm_password" placeholder="Confirm new password" required />
      <button type="submit">Submit</button>
    </form>

    <?php if ($debug_info) echo "<div class='debug'>{$debug_info}</div>"; ?>
  </div>
</div>

<?php include 'common/footer.php'; ?>
</body>
</html>
