<?php
include 'dbconfigf/dbconst2025.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate token
    $stmt = $conn->prepare("SELECT user_id, reset_expiry FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if (strtotime($row['reset_expiry']) > time()) {
            // Handle form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

                $update = $conn->prepare("UPDATE users SET password_hash=?, reset_token=NULL, reset_expiry=NULL WHERE reset_token=?");
                $update->bind_param("ss", $new_password, $token);
                $update->execute();

                echo "<div style='text-align:center; margin-top:50px;'>Password updated successfully. <a href='login.php'>Login</a></div>";
                exit();
            }
        } else {
            echo "<div style='text-align:center; margin-top:50px;'>Reset link has expired.</div>";
            exit();
        }
    } else {
        echo "<div style='text-align:center; margin-top:50px;'>Invalid reset link.</div>";
        exit();
    }
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
      min-height: calc(100vh - 200px); /* leaves space for header/footer */
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
      font-family: Arial, sans-serif;
    }

    .reset-container h2 {
      margin-bottom: 20px;
      font-size: 22px;
      color: #333;
    }

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

    .reset-container button:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <?php include 'common/header.php'; ?>

  <div class="reset-wrapper">
    <div class="reset-container">
      <h2>Reset Password</h2>
      <form method="POST">
        <input type="password" name="password" placeholder="Enter new password" required />
        <button type="submit">Submit</button>
      </form>
    </div>
  </div>

  <?php include 'common/footer.php'; ?>
</body>
</html>
