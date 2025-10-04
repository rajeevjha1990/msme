<?php
session_start();
include 'dbconfigf/dbconst2025.php'; // your DB connection file

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if email exists in DB
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate token + expiry
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Save token in DB
        $update = $conn->prepare("UPDATE users SET reset_token=?, reset_expiry=? WHERE email=?");
        $update->bind_param("sss", $token, $expiry, $email);
        $update->execute();

        // Reset link
        $reset_link = "http://localhost/msme/reset_password.php?token=" . $token;

        // Send email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'msmeglobaltech@gmail.com'; // your Gmail
            $mail->Password   = 'dtme fkgm vicc ixis';   // your Gmail App Password
            $mail->SMTPSecure = 'tls'; 
            $mail->Port       = 587;

            // Sender & recipient
            $mail->setFrom('msmeglobaltech@gmail.com', 'MSMEGLOBAL');
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Password Reset Request";
            $mail->Body    = "Hello,<br><br>Click the link below to reset your password:<br>
                              <a href='$reset_link'>$reset_link</a><br><br>
                              This link is valid for 1 hour.";
            $mail->AltBody = "Copy and paste this URL into your Abrowser: $reset_link";

            $mail->send();
            $message = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $message = "Mailer Error: " . $mail->ErrorInfo;
        }

    } else {
        $message = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
            * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        html, body { overflow-x: hidden; }
.container {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px #ccc;
    width: 400px;

    /* Center it nicely between header & footer */
    margin: 80px auto;   /* pushes it down from header */
}     input[type="email"], button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .msg {
            margin-top: 15px;
            font-weight: bold;
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
        <?php include 'common/header.php' ?>
<div class="container">
    <h2>Forgot Password</h2>
    <form method="POST">
        <label>Enter your email:</label>
        <input type="email" name="email" required placeholder="you@example.com">
        <button type="submit">Send Reset Link</button>
    </form>
    <?php if (!empty($message)) echo "<p class='msg'>$message</p>"; ?>
  
</div>

</body>
   <?php include 'common/footer.php' ?>
</html>
