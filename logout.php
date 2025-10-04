<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to home page with a success message
header("Location: home.php?success=You have logged out successfully");
exit();
