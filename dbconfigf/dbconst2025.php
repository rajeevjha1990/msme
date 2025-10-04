<?php
// Database configuration
$servername = "localhost";   // or your DB host
$username   = "root";        // your DB username
$password   = "";            // your DB password
$dbname     = "msmeglobal";  // replace with your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset to UTF-8
$conn->set_charset("utf8");
?>
