<?php
// config.php
error_reporting(E_ALL);
ini_set('display_errors', 0); // set to 0 on production

// Database settings
$DB_HOST = 'localhost:3309';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'msmeglobal';



$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    error_log('DB connect error: ' . $conn->connect_error);
    http_response_code(500);
    exit('Database connection error');
}

// Razorpay credentials (server-side only)
define('RAZORPAY_KEY_ID', 'rzp_live_or_test_key_id_here');
define('RAZORPAY_KEY_SECRET', 'rzp_live_or_test_key_secret_here');

// Site settings
define('SITE_NAME', 'Business Directory'); 
