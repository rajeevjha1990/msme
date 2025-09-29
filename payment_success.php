<?php
session_start();
include 'dbconfigf/dbconst2025.php';

$transaction_id = $_GET['transaction_id'] ?? '';

if (empty($transaction_id)) {
    header("Location: register-business.php");
    exit();
}

// Get transaction and user details
$stmt = $conn->prepare("
    SELECT t.*, u.name, u.email, u.plan, u.amount 
    FROM transactions t 
    JOIN users u ON t.user_id = u.id 
    WHERE t.transaction_id = ?
");
$stmt->bind_param("s", $transaction_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result || $result['status'] !== 'success') {
    header("Location: payment_failed.php?transaction_id=" . $transaction_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .success-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
            text-align: center;
            position: relative;
        }

        .success-animation {
            padding: 40px 30px;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .success-animation::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
            position: relative;
            animation: bounce 2s ease-in-out;
        }

        @keyframes bounce {
            0%, 60%, 75%, 90%, 100% {
                transform: translateY(0);
            }
            15%, 45% {
                transform: translateY(-20px);
            }
            30% {
                transform: translateY(-10px);
            }
        }

        .success-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            position: relative;
        }

        .success-subtitle {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
        }

        .payment-details {
            padding: 40px 30px;
        }

        .detail-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 2px solid #e9ecef;
        }

        .detail-card h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-value {
            font-weight: bold;
            color: #28a745;
        }

        .transaction-id {
            background: #fff3cd;
            border: 2px solid #ffeaa7;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
            font-family: monospace;
            font-size: 16px;
            font-weight: bold;
            color: #856404;
            word-break: break-all;
        }

        .next-steps {
            background: #e8f5e8;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 2px solid #c3e6c3;
        }

        .next-steps h4 {
            color: #155724;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .next-steps ul {
            text-align: left;
            color: #155724;
            margin-left: 20px;
        }

        .next-steps li {
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .btn {
            padding: 15px 25px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f26522, #e55a1f);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(242, 101, 34, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #333;
            border: 2px solid #dee2e6;
        }

        .btn-secondary:hover {
            background: #f8f9fa;
            border-color: #f26522;
            color: #f26522;
        }

        .footer-note {
            background: #f8f9fa;
            padding: 20px;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #dee2e6;
        }

        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background: #f26522;
            animation: confetti-fall 3s linear forwards;
        }

        @keyframes confetti-fall {
            0% {
                opacity: 1;
                transform: translateY(-100vh) rotate(0deg);
            }
            100% {
                opacity: 0;
                transform: translateY(100vh) rotate(720deg);
            }
        }

        @media (max-width: 768px) {
            .success-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .success-icon {
                font-size: 60px;
            }
            
            .success-title {
                font-size: 24px;
            }
            
            .payment-details {
                padding: 30px 20px;
            }
            
            .action-buttons {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-animation">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">Payment Successful!</h1>
            <p class="success-subtitle">Your registration has been completed successfully</p>
        </div>

        <div class="payment-details">
            <!-- Transaction ID -->
            <div class="transaction-id">
                <i class="fas fa-receipt"></i> Transaction ID: <?php echo htmlspecialchars($transaction_id); ?>
            </div>

            <!-- Payment Details -->
            <div class="detail-card">
                <h3><i class="fas fa-credit-card"></i> Payment Details</h3>
                <div class="detail-row">
                    <span>Customer Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($result['name']); ?></span>
                </div>
                <div class="detail-row">
                    <span>Plan Selected:</span>
                    <span class="detail-value"><?php echo ucfirst($result['plan']); ?> Plan</span>
                </div>
                <div class="detail-row">
                    <span>Amount Paid:</span>
                    <span class="detail-value">₹<?php echo number_format($result['amount'], 2); ?></span>
                </div>
                <div class="detail-row">
                    <span>Payment Method:</span>
                    <span class="detail-value"><?php echo ucfirst($result['payment_method'] ?? 'Online'); ?></span>
                </div>
                <div class="detail-row">
                    <span>Payment Date:</span>
                    <span class="detail-value"><?php echo date('d M Y, h:i A', strtotime($result['updated_at'])); ?></span>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h4><i class="fas fa-list-check"></i> What's Next?</h4>
                <ul>
                    <li>✅ Your business profile has been activated</li>
                    <li>✅ Confirmation email sent to <?php echo htmlspecialchars($result['email']); ?></li>
                    <li>📱 You can now login to your dashboard</li>
                    <li>🔍 Your business will be visible to customers within 24 hours</li>
                    <li>📞 Our team will contact you for profile optimization</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="dashboard.php" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt"></i>
                    Go to Dashboard
                </a>
                <a href="download_receipt.php?transaction_id=<?php echo $transaction_id; ?>" class="btn btn-secondary">
                    <i class="fas fa-download"></i>
                    Download Receipt
                </a>
            </div>

            <div class="action-buttons">
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i>
                    Back to Home
                </a>
                <a href="register-business.php" class="btn btn-secondary">
                    <i class="fas fa-plus"></i>
                    Add Another Business
                </a>
            </div>
        </div>

        <div class="footer-note">
            <i class="fas fa-info-circle"></i>
            For any queries, contact us at support@businessdirectory.com or call +91 9876543210
        </div>
    </div>

    <script>
        // Create confetti effect
        function createConfetti() {
            const colors = ['#f26522', '#28a745', '#ffc107', '#dc3545', '#17a2b8'];
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDelay = Math.random() * 2 + 's';
                    
                    document.body.appendChild(confetti);
                    
                    // Remove confetti after animation
                    setTimeout(() => {
                        if (confetti.parentNode) {
                            confetti.parentNode.removeChild(confetti);
                        }
                    }, 3000);
                }, i * 100);
            }
        }

        // Start confetti on page load
        window.addEventListener('load', createConfetti);

        // Print receipt function
        function printReceipt() {
            window.print();
        }

        // Copy transaction ID
        function copyTransactionId() {
            const transactionId = '<?php echo $transaction_id; ?>';
            navigator.clipboard.writeText(transactionId).then(() => {
                alert('Transaction ID copied to clipboard!');
            });
        }

        // Add click handler to transaction ID
        document.querySelector('.transaction-id').addEventListener('click', copyTransactionId);
        document.querySelector('.transaction-id').style.cursor = 'pointer';
        document.querySelector('.transaction-id').title = 'Click to copy';
    </script>
</body>
</html>