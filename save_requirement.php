<?php 
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=Please login first");
    exit();
}

include 'dbconfigf/dbconst2025.php';  // ✅ include DB connection

define('REQ_ARR', [
    '0' => 'Active',
    '1' => 'Got Product',
    '2' => 'Will Report Later',
    '3' => 'Work Done'
]);

// Get logged-in user email & whatsapp from session
$email    = $_SESSION['email']; 
$whatsapp = $_SESSION['whatsapp']; // Make sure whatsapp is stored in session at login

// Fetch name and city from users table using whatsapp
$name = '';
$city = '';
$userQuery = "SELECT name, city,state FROM users WHERE whatsapp = '$whatsapp' LIMIT 1";
$userResult = mysqli_query($conn, $userQuery);
if ($userResult && mysqli_num_rows($userResult) > 0) {
    $row  = mysqli_fetch_assoc($userResult);
    $name = $row['name'];
    $city = $row['city'];
    $state = $row['state'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize form inputs
    $requirement      = mysqli_real_escape_string($conn, $_POST['requirement']);
    $priority         = mysqli_real_escape_string($conn, $_POST['priority']);
    $budget_min       = (int) $_POST['budget_min'];
    $budget_max       = (int) $_POST['budget_max'];
    $quantity         = mysqli_real_escape_string($conn, $_POST['quantity']);
    $requirement_type = mysqli_real_escape_string($conn, $_POST['requirement_type']);
    $brand            = mysqli_real_escape_string($conn, $_POST['brand']);
    $message          = mysqli_real_escape_string($conn, $_POST['message']);
    $category          = mysqli_real_escape_string($conn, $_POST['category']);

    // Default req_status = 0 (Active)
    $req_status = 0;

    // Insert into user_setails table
  $sql = "INSERT INTO user_details 
        (category, name, email, whatsapp,state, city, priority, min_budget, max_budget, quantity, requirement_type, description, message, req_status, created_at, time) 
        VALUES 
        ('$category', '$name', '$email', '$whatsapp','$state', '$city', '$priority', '$budget_min', '$budget_max', '$quantity', '$requirement_type', '$description', '$message', '$req_status', NOW(), NOW())";

    if (mysqli_query($conn, $sql)) {
        header("Location: requirements-list.php?success=Requirement added successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: myrequirements.php");
    exit();
}
?>
