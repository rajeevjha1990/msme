<?php
$plain = "Test@123";
$hash = '$2y$10$WzN2YZG2L9Y5jH7E3I9N2e3nPrP/zUdWKnF4GxW0BqfQ6qAj8sBse';

if (password_verify($plain, $hash)) {
    echo "✅ Password matches hash";
} else {
    echo "❌ Password does not match";
}

// To generate a new correct hash
echo "\nGenerated Hash: " . password_hash($plain, PASSWORD_BCRYPT);
?>
