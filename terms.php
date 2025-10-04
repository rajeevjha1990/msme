<?php
// Include your common header file
include 'common/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions</title>
    <style>

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
        /* Page-specific CSS */
        .terms-page {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #0a0a2a 0%, #381e33 50%, #73391f 100%);
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .terms-header {
            background-color: #c85c31;
            padding: 20px;
            text-align: center;
             margin-top: 80px;
        }

        .terms-header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .terms-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            line-height: 1.6;
        }

        .terms-container h2 {
            font-size: 1.2rem;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .terms-container p {
            margin-bottom: 10px;
        }

        .terms-container ul {
            margin: 10px 0 20px 20px;
        }

        .terms-container li {
            margin-bottom: 8px;
        }

        /* Optional decorative circles */
        .terms-page::before, .terms-page::after {
            content: "";
            position: absolute;
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .terms-page::before {
            width: 200px;
            height: 200px;
            top: 200px;
            left: -100px;
        }

        .terms-page::after {
            width: 150px;
            height: 150px;
            bottom: 100px;
            right: -75px;
        }
    </style>
</head>
<body class="terms-page">

    <div class="terms-header">
        <h1>Terms & Conditions</h1>
    </div>

    <div class="terms-container">
        <p>Welcome to MSME GLOBAL, a business directory platform designed for business owners to list and promote their ventures. By accessing or using our website, you agree to abide by the terms and conditions set forth below.</p>

        <h2>1. Service Provided</h2>
        <p>MSME GLOBAL offers paid and free listings on our online directory for businesses. No physical goods are sold or shipped. Our services are strictly digital and include directory listings, promotional exposure, and business visibility.</p>

        <h2>2. User Responsibilities</h2>
        <p>Users are responsible for the accuracy of the information submitted. No offensive, unlawful, or misleading content is allowed. Users must not impersonate other businesses or individuals. It is purely for personal business promotion; no forum or other platform promotion is allowed.</p>
        <p>In any case except for personal business of any nature allowed, charity, events, tradefair/shows—all rights reserved only for MSME GLOBAL and its affiliates or related concern.</p>

        <h2>3. Payments</h2>
        <p>All payments made are for directory listing and promotional services. Fees are non-transferable. Payment gateways are secured and managed through authorized third-party providers.</p>

        <h2>4. Intellectual Property</h2>
        <p>All content on MSME GLOBAL, including logos, design, and content, is protected and owned by MSME GLOBAL and may not be copied or reused without permission.</p>

        <h2>5. Account Termination</h2>
        <p>We reserve the right to suspend or delete accounts found in violation of our terms without prior notice.</p>

        <h2>6. Changes to Terms</h2>
        <p>These terms may be updated at any time and are binding on all.</p>
    </div>

</body>
</html>
