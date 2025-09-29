<?php
include 'dbconfigf/dbconst2025.php';

if (isset($_POST['referral'])) {
    $referral = $_POST['referral'];

    // Check if referral_id exists
    $sql = "SELECT * FROM users WHERE reference_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $referral);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "valid";  // referral exists ✅
    } else {
        echo "invalid"; // referral does not exist ❌
    }
}
?>
