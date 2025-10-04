<?php
// Include your common header file
include 'common/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancellation & Refund Policy</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        html, body { overflow-x: hidden; }

        .refund-page {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #fff6ff 0%, #d4a3f5 50%, #b284f5 100%);
            color: #000;
            margin: 0;
            padding: 0;
        }

        .refund-header {
            background-color: #f78b3f;
            padding: 20px;
            text-align: center;
              margin-top: 80px;
        }

        .refund-header h1 {
            margin: 0;
            font-size: 2rem;
            color: white;
        }

        .refund-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            line-height: 1.6;
        }

        .refund-container p {
            margin-bottom: 10px;
        }

        .refund-container h2 {
            font-size: 1.2rem;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .refund-container em {
            display: block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="refund-page">

    <div class="refund-header">
        <h1>Cancellation & Refund Policy</h1>
    </div>

    <div class="refund-container">
        <p><em>MSME GLOBAL offers digital directory services; hence, our refund and cancellation policy is outlined below.</em></p>

        <h2>Cancellations :</h2>
        <p>Cancellations must be requested via email at <strong>support@msmeglobal.com</strong> within 6 hours of payment.<br>
        Once listing work has commenced, cancellations may not be processed.</p>

        <h2>Refunds :</h2>
        <p>Refunds are not applicable once services (listing creation, promotion, or directory exposure) have been initiated.<br>
        In rare technical or billing errors (e.g., duplicate payments), refunds will be issued within 7 business days after proper verification.</p>

        <h2>Contact For Support :</h2>
        <p>For refund requests or queries, email us at: <strong>support@msmeglobal.com</strong><br>
        Include: Transaction ID, Payment Date, Registered Name, Mobile Number, and Reason for Refund.</p>
    </div>
        <?php
// Include your common header file
include 'common/footer.php';
?>
</body>
</html>
