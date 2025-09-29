<?php
include 'dbconfigf/dbconst2025.php';

// Always return JSON
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("This script only accepts POST requests.");
    }

    if (empty($_POST['promo'])) {
        throw new Exception("Promo code is required.");
    }

    if (!isset($_POST['amount']) || !is_numeric($_POST['amount'])) {
        throw new Exception("Amount must be provided and numeric.");
    }

    $promo = trim($_POST['promo']);
    $amount = floatval($_POST['amount']);

    // Prepare query
    $stmt = $conn->prepare("SELECT discount_type, discount_value FROM promocodes WHERE code = ? AND is_active = '1'");
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }

    $stmt->bind_param("s", $promo);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        throw new Exception("Error executing query: " . $stmt->error);
    }

    if ($result->num_rows === 0) {
        throw new Exception("Invalid or expired promo code.");
    }

    $row = $result->fetch_assoc();

    // Apply discount
    if ($row['discount_type'] === "fixed") {
        $discount = $row['discount_value'];
    } elseif ($row['discount_type'] === "percentage") {
        $discount = ($amount * $row['discount_value']) / 100;
    } else {
        throw new Exception("Invalid discount type in database.");
    }

    $discountedAmount = max(0, $amount - $discount);

    // Return success response
    $response = [
        "status" => "success",
        "message" => "Promo code applied successfully",
        "original_amount" => $amount,
        "discount" => $discount,
        "discounted_amount" => $discountedAmount,
        "promo_code" => $promo
    ];

    echo json_encode($response);

} catch (Exception $e) {
    // Return error response
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>