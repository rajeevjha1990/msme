<?php
session_start();
include 'dbconfigf/dbconst2025.php'; // DB connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email     = trim($_POST['email']);
    $password  = trim($_POST['password']);
    $user_type = trim($_POST['user_type']); // 'business' or 'non-business'

    if (empty($email) || empty($password) || empty($user_type)) {
        header("Location: login.php?error=All fields are required");
        exit();
    }

    // Validate user type
    if (!in_array($user_type, ['business', 'non-business'])) {
        header("Location: login.php?error=Invalid account type selected");
        exit();
    }

    // Determine which table to query based on user type
    if ($user_type === 'business') {
        $table = 'users'; // Your existing business users table
        $dashboard = 'dashboard.php'; // Business dashboard
    } else {
        $table = 'non_business_users'; // New non-business users table
        $dashboard = 'non-business-dashboard.php'; // Non-business dashboard
    }

    // Check if user exists by email in the appropriate table
    $stmt = $conn->prepare("SELECT user_id, name, email, password_hash, whatsapp, type FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify password against password_hash column
        if (password_verify($password, $row['password_hash'])) {
            // Start session with user type information
            $_SESSION['user_id']   = $row['user_id'];
            $_SESSION['name']      = $row['name'];
            $_SESSION['email']     = $row['email'];
            $_SESSION['user_type'] = $user_type; // Store user type in session
            $_SESSION['type'] = $row['type'];
            $_SESSION['whatsapp'] = $row['whatsapp'];
            $_SESSION['logged_in'] = true;

            // Handle remember me functionality
            if (isset($_POST['remember']) && $_POST['remember']) {
                // Set remember me cookies (optional)
                $cookie_name = ($user_type === 'business') ? 'remember_business_user' : 'remember_non_business_user';
                setcookie($cookie_name, $row['user_id'], time() + (86400 * 30), "/"); // 30 days
            }

            // Redirect to appropriate dashboard
            header("Location: $dashboard?success=Login successful");
            exit();
        } else {
            header("Location: login.php?error=Invalid password");
            exit();
        }
    } else {
        $account_type_text = ($user_type === 'business') ? 'business' : 'non-business';
        header("Location: login.php?error=No $account_type_text account found with that email");
        exit();
    }
} else {
    header("Location: login.php?error=Invalid request");
    exit();
}
?>