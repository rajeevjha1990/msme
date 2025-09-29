<?php
session_start();
include 'dbconfigf/dbconst2025.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_id = $_POST['transaction_id'] ?? '';
    $payment_id = $_POST['payment_id'] ?? '';
    $status = $_POST['status'] ?? '';
    $method = $_POST['method'] ?? '';

    if (empty($transaction_id) || empty($payment_id) || empty($status)) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required parameters']);
        exit();
    }

    try {
        // Update transaction status
        $stmt = $conn->prepare("UPDATE transactions SET payment_id = ?, status = ?, payment_method = ?, updated_at = NOW() WHERE transaction_id = ?");
        $stmt->bind_param("ssss", $payment_id, $status, $method, $transaction_id);
        
        if ($stmt->execute()) {
            if ($status === 'success') {
                // Get transaction details
                $stmt2 = $conn->prepare("SELECT * FROM transactions WHERE transaction_id = ?");
                $stmt2->bind_param("s", $transaction_id);
                $stmt2->execute();
                $transaction = $stmt2->get_result()->fetch_assoc();
                
                if ($transaction) {
                    // Update user status to active
                    $stmt3 = $conn->prepare("UPDATE users SET status = 'active', payment_status = 'paid', activated_at = NOW() WHERE id = ?");
                    $stmt3->bind_param("i", $transaction['user_id']);
                    $stmt3->execute();
                    
                    // Send confirmation email (optional)
                    sendConfirmationEmail($transaction['user_id']);
                    
                    echo json_encode(['success' => true, 'message' => 'Payment successful']);
                } else {
                    echo json_encode(['error' => 'Transaction not found']);
                }
            } else {
                echo json_encode(['success' => true, 'message' => 'Payment status updated']);
            }
        } else {
            echo json_encode(['error' => 'Database error']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

function sendConfirmationEmail($user_id) {
    global $conn;
    
    // Get user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    if ($user) {
        $to = $user['email'];
        $subject = "Registration Successful - Welcome to Business Directory";
        
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #f26522; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { text-align: center; padding: 20px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Registration Successful!</h2>
                </div>
                <div class='content'>
                    <p>Dear " . htmlspecialchars($user['name']) . ",</p>
                    <p>Congratulations! Your business registration has been successfully completed.</p>
                    <p><strong>Plan:</strong> " . ucfirst($user['plan']) . " Plan</p>
                    <p><strong>Amount Paid:</strong> ₹" . number_format($user['amount'], 2) . "</p>
                    <p>Your business profile is now active and will be visible to customers.</p>
                    <p>You can login to your dashboard using your registered email and password.</p>
                </div>
                <div class='footer'>
                    <p>Thank you for choosing our Business Directory!</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: Business Directory <noreply@businessdirectory.com>' . "\r\n";
        
        // Uncomment the line below to send actual emails
        // mail($to, $subject, $message, $headers);
    }
}
?>