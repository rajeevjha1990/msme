<?php
session_start();
include 'dbconfigf/dbconst2025.php';

// Check if user_id and amount are provided
if (!isset($_GET['user_id']) || !isset($_GET['amount'])) {
    header("Location: register-business.php");
    exit();
}

$user_id = $_GET['user_id'];
$amount = $_GET['amount'];
$plan = $_GET['plan'] ?? 'basic';

// Fetch user details for payment
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header("Location: register-business.php");
    exit();
}

// Generate unique transaction ID
$transaction_id = 'TXN' . time() . rand(1000, 9999);

// Store transaction details
$stmt = $conn->prepare("INSERT INTO transactions (user_id, transaction_id, amount, plan, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
$stmt->bind_param("isds", $user_id, $transaction_id, $amount, $plan);
$stmt->execute();

// Payment gateway configurations
$merchant_key = "your_merchant_key"; // Replace with actual merchant key
$merchant_id = "your_merchant_id"; // Replace with actual merchant ID
$merchant_salt = "your_merchant_salt"; // Replace with actual salt

// For demo purposes, we'll show multiple payment options
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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

        .payment-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
            min-height: 600px;
        }

        .payment-header {
            background: linear-gradient(135deg, #f26522, #e55a1f);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .payment-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
        }

        .payment-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            position: relative;
        }

        .payment-header p {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
        }

        .payment-content {
            padding: 40px 30px;
        }

        .order-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 2px solid #e9ecef;
        }

        .order-summary h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 18px;
            color: #f26522;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
        }

        .user-details {
            background: #fff5f1;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            border: 2px solid #ffeee7;
        }

        .user-details h4 {
            color: #f26522;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .payment-methods {
            margin-bottom: 30px;
        }

        .payment-methods h3 {
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .payment-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .payment-option {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .payment-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(242, 101, 34, 0.1), transparent);
            transition: left 0.5s;
        }

        .payment-option:hover::before {
            left: 100%;
        }

        .payment-option:hover {
            border-color: #f26522;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(242, 101, 34, 0.1);
        }

        .payment-option.selected {
            border-color: #f26522;
            background: #fff5f1;
        }

        .payment-option i {
            font-size: 30px;
            margin-bottom: 10px;
            color: #f26522;
        }

        .payment-option h4 {
            color: #333;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .payment-option p {
            color: #666;
            font-size: 12px;
        }

        .secure-badges {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin: 25px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .secure-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #28a745;
            font-size: 12px;
            font-weight: bold;
        }

        .pay-button {
            width: 100%;
            background: linear-gradient(135deg, #f26522, #e55a1f);
            color: white;
            border: none;
            padding: 18px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .pay-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            transition: all 0.6s;
            transform: translate(-50%, -50%);
        }

        .pay-button:hover::before {
            width: 300px;
            height: 300px;
        }

        .pay-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(242, 101, 34, 0.3);
        }

        .pay-button:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #666;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            padding: 8px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: #f8f9fa;
            color: #f26522;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading i {
            font-size: 40px;
            color: #f26522;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .timer {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            color: #856404;
        }

        .timer strong {
            color: #f26522;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .payment-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .payment-content {
                padding: 30px 20px;
            }
            
            .payment-options {
                grid-template-columns: 1fr;
            }
            
            .secure-badges {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h1><i class="fas fa-credit-card"></i> Complete Payment</h1>
            <p>Secure payment powered by industry-leading encryption</p>
        </div>

        <div class="payment-content">
            <a href="register-business.php" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to Registration
            </a>

            <!-- Timer -->
            <div class="timer">
                <i class="fas fa-clock"></i> Session expires in: <strong id="timer">15:00</strong>
            </div>

            <!-- User Details -->
            <div class="user-details">
                <h4><i class="fas fa-user"></i> Customer Details</h4>
                <div class="detail-row">
                    <span>Name:</span>
                    <span><strong><?php echo htmlspecialchars($user['name']); ?></strong></span>
                </div>
                <div class="detail-row">
                    <span>Email:</span>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="detail-row">
                    <span>Phone:</span>
                    <span><?php echo htmlspecialchars($user['whatsapp']); ?></span>
                </div>
                <div class="detail-row">
                    <span>Transaction ID:</span>
                    <span><strong><?php echo $transaction_id; ?></strong></span>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h3><i class="fas fa-receipt"></i> Order Summary</h3>
                <div class="summary-row">
                    <span>Plan Selected:</span>
                    <span><strong><?php echo ucfirst($plan); ?> Plan</strong></span>
                </div>
                <div class="summary-row">
                    <span>Base Amount:</span>
                    <span>₹<?php echo number_format($user['base_amount'], 2); ?></span>
                </div>
                <?php if ($user['discount_amount'] > 0): ?>
                <div class="summary-row" style="color: #28a745;">
                    <span>Discount Applied:</span>
                    <span>-₹<?php echo number_format($user['discount_amount'], 2); ?></span>
                </div>
                <?php endif; ?>
                <div class="summary-row">
                    <span>Total Amount:</span>
                    <span>₹<?php echo number_format($amount, 2); ?></span>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="payment-methods">
                <h3><i class="fas fa-wallet"></i> Choose Payment Method</h3>
                
                <div class="payment-options">
                    <div class="payment-option" data-method="razorpay">
                        <i class="fab fa-cc-visa"></i>
                        <h4>Razorpay</h4>
                        <p>Credit/Debit Cards, UPI, Wallets</p>
                    </div>
                    
                    <div class="payment-option" data-method="payu">
                        <i class="fas fa-university"></i>
                        <h4>PayU</h4>
                        <p>Net Banking & Cards</p>
                    </div>
                    
                    <div class="payment-option" data-method="phonepe">
                        <i class="fas fa-mobile-alt"></i>
                        <h4>PhonePe</h4>
                        <p>UPI & Mobile Payments</p>
                    </div>
                    
                    <div class="payment-option" data-method="paytm">
                        <i class="fas fa-wallet"></i>
                        <h4>Paytm</h4>
                        <p>Wallet & UPI</p>
                    </div>
                </div>
            </div>

            <!-- Security Badges -->
            <div class="secure-badges">
                <div class="secure-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>256-bit SSL</span>
                </div>
                <div class="secure-badge">
                    <i class="fas fa-lock"></i>
                    <span>PCI Compliant</span>
                </div>
                <div class="secure-badge">
                    <i class="fas fa-check-circle"></i>
                    <span>Secure Payment</span>
                </div>
            </div>

            <!-- Loading -->
            <div class="loading" id="loading">
                <i class="fas fa-spinner"></i>
                <p>Processing your payment...</p>
            </div>

            <!-- Pay Button -->
            <button class="pay-button" id="payButton" disabled>
                <i class="fas fa-credit-card"></i>
                Select Payment Method
            </button>
        </div>
    </div>

    <script>
        let selectedMethod = null;
        let timerInterval;
        
        // Timer functionality
        function startTimer() {
            let timeLeft = 15 * 60; // 15 minutes in seconds
            
            timerInterval = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                document.getElementById('timer').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    alert('Session expired! Redirecting to registration page.');
                    window.location.href = 'register-business.php';
                }
                
                timeLeft--;
            }, 1000);
        }
        
        // Start timer
        startTimer();
        
        // Payment method selection
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove previous selection
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                
                // Add selection to clicked option
                this.classList.add('selected');
                selectedMethod = this.dataset.method;
                
                // Enable pay button
                const payButton = document.getElementById('payButton');
                payButton.disabled = false;
                payButton.innerHTML = `<i class="fas fa-credit-card"></i> Pay ₹<?php echo number_format($amount, 2); ?> via ${this.querySelector('h4').textContent}`;
            });
        });
        
        // Pay button click handler
        document.getElementById('payButton').addEventListener('click', function() {
            if (!selectedMethod) {
                alert('Please select a payment method');
                return;
            }
            
            // Show loading
            document.getElementById('loading').style.display = 'block';
            this.style.display = 'none';
            
            // Process payment based on selected method
            switch(selectedMethod) {
                case 'razorpay':
                    processRazorpayPayment();
                    break;
                case 'payu':
                    processPayUPayment();
                    break;
                case 'phonepe':
                    processPhonePePayment();
                    break;
                case 'paytm':
                    processPaytmPayment();
                    break;
                default:
                    alert('Payment method not implemented yet');
                    hideLoading();
            }
        });
        
        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('payButton').style.display = 'block';
        }
        
        // Razorpay Integration
        function processRazorpayPayment() {
            const options = {
                "key": "rzp_test_your_key_here", // Replace with your Razorpay key
                "amount": <?php echo $amount * 100; ?>, // Amount in paise
                "currency": "INR",
                "name": "Business Directory",
                "description": "<?php echo ucfirst($plan); ?> Plan Registration",
                "order_id": "<?php echo $transaction_id; ?>",
                "handler": function (response) {
                    // Payment successful
                    updatePaymentStatus(response.razorpay_payment_id, 'success');
                },
                "prefill": {
                    "name": "<?php echo htmlspecialchars($user['name']); ?>",
                    "email": "<?php echo htmlspecialchars($user['email']); ?>",
                    "contact": "<?php echo htmlspecialchars($user['whatsapp']); ?>"
                },
                "notes": {
                    "user_id": "<?php echo $user_id; ?>",
                    "plan": "<?php echo $plan; ?>"
                },
                "theme": {
                    "color": "#f26522"
                },
                "modal": {
                    "ondismiss": function() {
                        hideLoading();
                    }
                }
            };
            
            const rzp = new Razorpay(options);
            rzp.open();
        }
        
        // PayU Integration
        function processPayUPayment() {
            // Create form for PayU
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'process_payu.php';
            
            const formData = {
                'user_id': '<?php echo $user_id; ?>',
                'amount': '<?php echo $amount; ?>',
                'transaction_id': '<?php echo $transaction_id; ?>',
                'plan': '<?php echo $plan; ?>'
            };
            
            for (const key in formData) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = formData[key];
                form.appendChild(input);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
        
        // PhonePe Integration (Mock)
        function processPhonePePayment() {
            setTimeout(() => {
                if (confirm('This would redirect to PhonePe. Simulate success?')) {
                    updatePaymentStatus('phonepe_' + Date.now(), 'success');
                } else {
                    hideLoading();
                }
            }, 2000);
        }
        
        // Paytm Integration (Mock)
        function processPaytmPayment() {
            setTimeout(() => {
                if (confirm('This would redirect to Paytm. Simulate success?')) {
                    updatePaymentStatus('paytm_' + Date.now(), 'success');
                } else {
                    hideLoading();
                }
            }, 2000);
        }
        
        // Update payment status
        function updatePaymentStatus(paymentId, status) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_payment_status.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (status === 'success') {
                        // Clear timer
                        clearInterval(timerInterval);
                        
                        // Redirect to success page
                        window.location.href = 'payment_success.php?transaction_id=<?php echo $transaction_id; ?>';
                    } else {
                        // Redirect to failure page
                        window.location.href = 'payment_failed.php?transaction_id=<?php echo $transaction_id; ?>';
                    }
                }
            };
            
            const params = `transaction_id=<?php echo $transaction_id; ?>&payment_id=${paymentId}&status=${status}&method=${selectedMethod}`;
            xhr.send(params);
        }
        
        // Prevent back button
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
        
        // Auto-refresh prevention
        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '';
        });
    </script>
</body>
</html>