<?php
// Include your common header file
include 'common/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping & Delivery Policy</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        html, body { overflow-x: hidden; }

        .shipping-page {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #fff6ff 0%, #d4a3f5 50%, #b284f5 100%);
            color: #000;
            margin: 0;
            padding: 0;
        }

        .shipping-header {
            background-color: #f78b3f;
            padding: 20px;
            text-align: center;
             margin-top: 80px;
        }

        .shipping-header h1 {
            margin: 0;
            font-size: 2rem;
            color: white;
        }

        .shipping-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            line-height: 1.6;
        }

        .shipping-container p {
            margin-bottom: 10px;
        }

        .shipping-container h2 {
            font-size: 1.2rem;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .shipping-container em {
            display: block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="shipping-page">

    <div class="shipping-header">
        <h1>Shipping & Delivery Policy</h1>
    </div>

    <div class="shipping-container">
        <p><em>Please note that MSME GLOBAL is a service-based platform and does not deal in physical goods or delivery.</em></p>

        <h2>Digital Service Only :</h2>
        <p>No shipping or courier services are involved.<br>
        Business listings and services are activated digitally after payment verification.</p>

        <h2>Listing Activation :</h2>
        <p>Free listings are typically reviewed and published within 48-72 hours.<br>
        Paid listings or premium services are activated within 24 working hours upon successful payment.</p>
    </div>

    <?php
// Include your common header file
include 'common/footer.php';
?>

</body>
</html>
