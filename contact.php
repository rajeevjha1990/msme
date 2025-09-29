<?php include 'common/header.php'; ?>
<?php
include 'dbconfigf/dbconst2025.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $user_message = trim($_POST['message'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }
    
    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    }
    
    if (empty($subject)) {
        $errors[] = 'Subject is required';
    }
    
    if (empty($user_message)) {
        $errors[] = 'Message is required';
    }
    
    if (strlen($user_message) > 300) {
        $errors[] = 'Message must be 300 characters or less';
    }
    
    // Simple honeypot spam protection (hidden field)
    if (!empty($_POST['website'])) {
        $errors[] = 'Spam detected';
    }
    
    if (!empty($errors)) {
        $message = 'Please fix the following errors: ' . implode(', ', $errors);
        $messageType = 'error';
    } else {
        // Save to database (create table if not exists)
        try {
            $pdo = new PDO('sqlite:contact_messages.db');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create table if it doesn't exist
            $pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                phone TEXT NOT NULL,
                subject TEXT NOT NULL,
                message TEXT NOT NULL,
                ip_address TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
            
            // Insert message
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $subject, $user_message, $_SERVER['REMOTE_ADDR']]);
            
            // Send email notification
            $to = 'support@memeglobal.com';
            $email_subject = 'New Contact Form Submission: ' . $subject;
            $email_body = "New contact form submission:\n\n";
            $email_body .= "Name: $name\n";
            $email_body .= "Email: $email\n";
            $email_body .= "Phone: $phone\n";
            $email_body .= "Subject: $subject\n";
            $email_body .= "Message: $user_message\n";
            $email_body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
            $email_body .= "Submitted: " . date('Y-m-d H:i:s') . "\n";
            
            $headers = "From: noreply@memeglobal.com\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            if (mail($to, $email_subject, $email_body, $headers)) {
                $message = 'Thank you! Your message has been sent successfully. We will get back to you soon.';
                $messageType = 'success';
                
                // Clear form data on success
                $_POST = [];
            } else {
                $message = 'Your message was saved but email notification failed. We will still review your message.';
                $messageType = 'warning';
            }
            
        } catch (PDOException $e) {
            $message = 'Database error occurred. Please try again later.';
            $messageType = 'error';
            error_log('Contact form database error: ' . $e->getMessage());
        }
    }

    try {
    $mail = new PHPMailer(true);

    //Server settings
      $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'msmeglobaltech@gmail.com'; // your Gmail
            $mail->Password   = 'dtme fkgm vicc ixis';   // your Gmail App Password
            $mail->SMTPSecure = 'tls'; 
            $mail->Port       = 587;

    //Recipients
    $mail->setFrom('msmeglobaltech@gmail.com', 'MSMEGlobal');
    $mail->addAddress('sskjha2022@gmail.com', 'Support'); // Destination
    $mail->addReplyTo($email, $name); // User’s email as reply-to

    // Content
    $mail->isHTML(false);
    $mail->Subject = 'New Contact Form Submission: ' . $subject;
    $mail->Body    = "New contact form submission:\n\n"
                   . "Name: $name\n"
                   . "Email: $email\n"
                   . "Phone: $phone\n"
                   . "Subject: $subject\n"
                   . "Message: $user_message\n"
                   . "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n"
                   . "Submitted: " . date('Y-m-d H:i:s') . "\n";

    $mail->send();
    $message = 'Thank you! Your message has been sent successfully. We will get back to you soon.';
    $messageType = 'success';
    $_POST = [];

} catch (Exception $e) {
    $message = 'Your message was saved but email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    $messageType = 'warning';
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        /* Your existing CSS styles (unchanged except captcha section) */
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; padding-top: 1%; }
        html, body { overflow-x: hidden; }
        .contact-hero-section {
            background-image: url('assets/a4.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 400px;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            text-align: center; color: white; position: relative;
        }
        .contact-hero-section::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="buildings" patternUnits="userSpaceOnUse" width="100" height="100"><rect width="100" height="100" fill="%23000000" opacity="0.1"/><rect x="10" y="20" width="15" height="60" fill="%23000000" opacity="0.05"/><rect x="30" y="10" width="20" height="70" fill="%23000000" opacity="0.05"/><rect x="55" y="25" width="12" height="55" fill="%23000000" opacity="0.05"/><rect x="70" y="15" width="18" height="65" fill="%23000000" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23buildings)"/></svg>') center/cover;
            opacity: 0.3;
        }
        .contact-hero-content { position: relative; z-index: 2; max-width: 800px; padding: 0 20px; }
        .contact-hero-title { font-size: 3.5rem; font-weight: bold; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        .contact-hero-subtitle { font-size: 1.1rem; margin-bottom: 10px; opacity: 0.9; }
        .contact-info-cards { display: flex; justify-content: center; gap: 30px; padding: 60px 20px; max-width: 1200px; margin: 0 auto; flex-wrap: wrap; }
        .contact-info-card {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #9b59b6 100%);
            border-radius: 20px; padding: 40px 30px; text-align: center; color: white;
            flex: 1; min-width: 280px; max-width: 350px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .contact-info-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        .contact-info-icon { width: 60px; height: 60px; margin: 0 auto 25px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.2); border-radius: 50%; backdrop-filter: blur(10px); }
        .contact-info-icon svg { width: 30px; height: 30px; fill: white; }
        .contact-info-label { font-size: 1rem; font-weight: 600; margin-bottom: 15px; opacity: 0.9; }
        .contact-info-details { font-size: 0.95rem; line-height: 1.6; }
        .contact-form-section { background: #f8f9fa; padding: 80px 20px; }
        .contact-form-container { max-width: 600px; margin: 0 auto; background: white; padding: 50px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .contact-form-group { margin-bottom: 25px; }
        .contact-form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 0.95rem; }
        .contact-form-input, .contact-form-textarea {
            width: 100%; padding: 15px 20px; border: 2px solid #e9ecef; border-radius: 50px;
            font-size: 1rem; transition: border-color 0.3s ease, box-shadow 0.3s ease; box-sizing: border-box;
        }
        .contact-form-textarea { border-radius: 20px; resize: vertical; min-height: 120px; }
        .contact-form-input:focus, .contact-form-textarea:focus {
            outline: none; border-color: #f7931e; box-shadow: 0 0 0 3px rgba(247, 147, 30, 0.1);
        }
        .contact-submit-btn {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white; border: none; padding: 15px 40px; border-radius: 50px;
            font-size: 1.1rem; font-weight: 600; cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease; float: right;
        }
        .contact-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(247, 147, 30, 0.3); }
        .contact-map-section { padding: 60px 20px; }
        .contact-map-container { max-width: 1200px; margin: 0 auto; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        @media (max-width: 768px) {
            .contact-hero-title { font-size: 2.5rem; }
            .contact-info-cards { flex-direction: column; align-items: center; gap: 20px; }
            .contact-form-container { padding: 30px 25px; margin: 0 10px; }
            .contact-submit-btn { float: none; width: 100%; }
        }
        .contact-required { color: #e74c3c; }
        
        /* Message styles */
        .message {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .message.warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        /* Hide honeypot field */
        .honeypot { display: none !important; }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="contact-hero-section">
        <div class="contact-hero-content">
            <h1 class="contact-hero-title">CONTACT US</h1>
            <p class="contact-hero-subtitle">Need an expert? Leave your contact info and we'll be in touch shortly</p>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="contact-info-cards">
        <div class="contact-info-card">
            <div class="contact-info-icon">
                <svg viewBox="0 0 24 24"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56-.35-.12-.74-.03-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"/></svg>
            </div>
            <div class="contact-info-label">Phone : +919331177595</div>
        </div>
        <div class="contact-info-card">
            <div class="contact-info-icon">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            </div>
            <div class="contact-info-label">E-Mail : support@memeglobal.com</div>
        </div>
        <div class="contact-info-card">
            <div class="contact-info-icon">
                <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            </div>
            <div class="contact-info-details">
                <div class="contact-info-label">Location : Mercantile Building,</div>
                <div>9 Bentick street, 1st Floor,</div>
                <div>Kolkata 700001.</div>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="contact-form-section">
        <div class="contact-form-container">
            <?php if (!empty($message)): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form id="contactForm" method="post" action="">
                <!-- Honeypot field for basic spam protection -->
                <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off">
                
                <div class="contact-form-group">
                    <label class="contact-form-label">Your Name <span class="contact-required">*</span></label>
                    <input type="text" name="name" class="contact-form-input" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                </div>
                <div class="contact-form-group">
                    <label class="contact-form-label">Your Email <span class="contact-required">*</span></label>
                    <input type="email" name="email" class="contact-form-input" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>
                <div class="contact-form-group">
                    <label class="contact-form-label">Your Phone Number <span class="contact-required">*</span></label>
                    <input type="tel" name="phone" class="contact-form-input" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>
                </div>
                <div class="contact-form-group">
                    <label class="contact-form-label">Subject <span class="contact-required">*</span></label>
                    <input type="text" name="subject" class="contact-form-input" value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>" required>
                </div>
                <div class="contact-form-group">
                    <label class="contact-form-label">Message <span class="contact-required">*</span></label>
                    <textarea name="message" class="contact-form-textarea" maxlength="300" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="contact-submit-btn">Submit</button>
                <div style="clear: both;"></div>
            </form>
        </div>
    </section>

    <!-- Map -->
    <section class="contact-map-section">
        <div class="contact-map-container">
            <iframe
                width="100%" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                src="https://www.openstreetmap.org/export/embed.html?bbox=88.3468%2C22.5726%2C88.3500%2C22.5745&amp;layer=mapnik&amp;marker=22.5735%2C88.3484">
            </iframe>
            <small>
                <a href="https://www.openstreetmap.org/?mlat=22.5735&amp;mlon=88.3484#map=18/22.5735/88.3484" target="_blank">
                    View Larger Map
                </a>
            </small>
        </div>
    </section>

    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            // Client-side validation
            const name = document.querySelector('input[name="name"]').value.trim();
            const email = document.querySelector('input[name="email"]').value.trim();
            const phone = document.querySelector('input[name="phone"]').value.trim();
            const subject = document.querySelector('input[name="subject"]').value.trim();
            const message = document.querySelector('textarea[name="message"]').value.trim();
            
            if (!name || !email || !phone || !subject || !message) {
                e.preventDefault();
                alert("Please fill in all required fields.");
                return false;
            }
            
            if (message.length > 300) {
                e.preventDefault();
                alert("Message must be 300 characters or less.");
                return false;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert("Please enter a valid email address.");
                return false;
            }
        });
        
        // Character counter for message field
        const messageField = document.querySelector('textarea[name="message"]');
        const maxLength = 300;
        
        messageField.addEventListener('input', function() {
            const currentLength = this.value.length;
            const remaining = maxLength - currentLength;
            
            // Create or update character counter
            let counter = document.getElementById('char-counter');
            if (!counter) {
                counter = document.createElement('div');
                counter.id = 'char-counter';
                counter.style.cssText = 'font-size: 0.85rem; color: #666; text-align: right; margin-top: 5px;';
                this.parentNode.appendChild(counter);
            }
            
            counter.textContent = `${remaining} characters remaining`;
            counter.style.color = remaining < 50 ? '#e74c3c' : '#666';
        });
    </script>
</body>
</html>
<?php include 'common/footer.php'; ?>