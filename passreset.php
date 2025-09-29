<?php
include 'dbconfigf/dbconst2025.php'; // DB connection

// Fetch all users
$sql = "SELECT user_id, whatsapp FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userId = $row['user_id'];
        $whatsapp = $row['whatsapp'];

        // Generate bcrypt hash from WhatsApp number
        $newHash = password_hash($whatsapp, PASSWORD_BCRYPT);

        // Update password in DB
        $update = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        $update->bind_param("si", $newHash, $userId);
        $update->execute();
    }
    echo "✅ All user passwords have been reset to their WhatsApp numbers.";
} else {
    echo "No users found.";
}

$conn->close();
?>
