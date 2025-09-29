<?php
// Include your common header file
include 'common/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        html, body { overflow-x: hidden; }

        .privacy-page {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #fff6f0 0%, #f9b17a 50%, #f78b3f 100%);
            color: #000;
            margin: 0;
            padding: 0;
            
        }

        .privacy-header {
            background-color: #f78b3f;
            padding: 20px;
            text-align: center;
             margin-top: 80px;
        }

        .privacy-header h1 {
            margin: 0;
            font-size: 2rem;
            color: white;
        }

        .privacy-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            line-height: 1.6;
        }

        .privacy-container h2 {
            font-size: 1.2rem;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .privacy-container p {
            margin-bottom: 10px;
        }

        /* Decorative circles like your image */
        .privacy-page::before, .privacy-page::after {
            content: "";
            position: absolute;
            border: 2px solid rgba(0,0,0,0.05);
            border-radius: 50%;
        }

        .privacy-page::before {
            width: 200px;
            height: 200px;
            top: 150px;
            left: -100px;
        }

        .privacy-page::after {
            width: 150px;
            height: 150px;
            bottom: 100px;
            right: -75px;
        }
    </style>
</head>
<body class="privacy-page">

    <div class="privacy-header">
        <h1>Privacy Policy</h1>
    </div>

    <div class="privacy-container">
        <p><em>At MSME GLOBAL, we respect your privacy and are committed to protecting your personal data.</em></p>

        <h2>1. Information We Collect</h2>
        <p>Name, business name, contact details (e-mail, phone, address), and business-related information (industry, service, etc.).</p>

        <h2>2. How We Use Your Data</h2>
        <p>To display your business in our directory, to contact you regarding updates, promotions, or service enhancements.  
        To improve our services and website experience.</p>

        <h2>3. Data Sharing</h2>
        <p>We do not sell, rent, or share your data with third parties for marketing purposes.  
        Data may be shared only for legal, technical, or security reasons with trusted partners (like payment gateway providers).</p>

        <h2>4. Cookies & Tracking</h2>
        <p>Cookies may be used to enhance site functionality and user experience. You can control cookie settings in your browser.</p>

        <h2>5. Data Security</h2>
        <p>We implement the latest security measures to safeguard your data, including encrypted payment gateways and secure server storage.</p>
    </div>
    <?php include 'common/footer.php'; ?>

</body>
</html>
