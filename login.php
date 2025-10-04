<?php 
$page_title = "Login - MSME Global";
include 'common/header.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $page_title; ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Login Page Specific CSS - Following the working pattern */
* {
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #e8d5e8, #f2d6d6);
    min-height: 100vh;
    padding-top: 120px; /* Account for fixed header */
}

.login-main-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 120px);
}

.login-main-container {
    background: rgba(255, 255, 255, 0.9);
    padding: 50px 40px;
    border-radius: 30px;
    box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 450px;
    text-align: center;
    backdrop-filter: blur(10px);
}

.login-welcome-title {
    font-size: 36px;
    font-weight: bold;
    color: #333;
    margin-bottom: 30px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

/* User Type Selection Styles */
.user-type-selection {
    margin-bottom: 40px;
    padding: 20px 0;
}

.user-type-title {
    font-size: 20px;
    color: #333;
    margin-bottom: 20px;
    font-weight: 600;
}

.user-type-options {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 30px;
    flex-wrap: nowrap;
}

.user-type-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    padding: 15px 25px;
    border-radius: 15px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    background: rgba(255, 255, 255, 0.5);
    min-width: 140px;
    flex-shrink: 0;
}

.user-type-option:hover {
    background: rgba(255, 255, 255, 0.8);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.user-type-option.selected {
    border-color: #6a4c93;
    background: rgba(106, 76, 147, 0.1);
}

.user-type-option input[type="radio"] {
    display: none;
}

.user-type-icon {
    font-size: 28px;
    margin-bottom: 8px;
    color: #6a4c93;
    transition: all 0.3s ease;
}

.user-type-option.selected .user-type-icon {
    color: #ff6b35;
    transform: scale(1.1);
}

.user-type-label {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    text-align: center;
    line-height: 1.2;
}

.user-type-option.selected .user-type-label {
    color: #6a4c93;
}

.login-form-group {
    margin-bottom: 30px;
    text-align: left;
}

.login-form-group label {
    display: block;
    font-size: 18px;
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
}

.login-form-group input[type="email"],
.login-form-group input[type="password"] {
    width: 100%;
    padding: 15px 20px;
    border: 2px solid #ddd;
    border-radius: 25px;
    font-size: 16px;
    background: white;
    outline: none;
    box-sizing: border-box;
    transition: all 0.3s ease;
}

.login-form-group input[type="email"]:focus,
.login-form-group input[type="password"]:focus {
    border-color: #6a4c93;
    box-shadow: 0 0 10px rgba(106, 76, 147, 0.3);
    transform: translateY(-2px);
}

.login-form-group input::placeholder {
    color: #aaa;
    font-size: 15px;
}

.login-form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 25px 0 40px 0;
}

.login-remember-me {
    display: flex;
    align-items: center;
    gap: 8px;
}

.login-remember-me input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #ff6b35;
}

.login-remember-me label {
    font-size: 16px;
    color: #666;
    cursor: pointer;
    margin-bottom: 0;
}

.login-forgot-password {
    color: #e74c3c;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    transition: color 0.3s ease;
}

.login-forgot-password:hover {
    color: #c0392b;
    text-decoration: underline;
}

.login-submit-btn {
    width: 60%;
    padding: 15px 0;
    background: linear-gradient(135deg, #6a4c93, #ff6b35);
    color: white;
    border: none;
    border-radius: 25px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.login-submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(106, 76, 147, 0.4);
}

.login-submit-btn:active {
    transform: translateY(0);
}

.login-register-section {
    margin-top: 20px;
}

.login-register-text {
    color: #666;
    font-size: 16px;
    margin-bottom: 15px;
}

.login-register-btn {
    padding: 12px 30px;
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.login-register-btn:hover {
    background: linear-gradient(135deg, #c0392b, #a93226);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(231, 76, 60, 0.4);
}

.login-message {
    padding: 12px 20px;
    border-radius: 15px;
    margin-bottom: 20px;
    font-size: 14px;
    font-weight: 500;
}

.login-message.error {
    background: #ffe6e6;
    color: #c0392b;
    border: 1px solid #e74c3c;
}

.login-message.success {
    background: #e8f5e8;
    color: #27ae60;
    border: 1px solid #2ecc71;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    body {
        padding-top: 140px;
    }
    .login-main-content {
        padding: 15px;
    }
    .login-main-container {
        padding: 40px 30px;
    }
    .login-welcome-title {
        font-size: 28px;
        margin-bottom: 25px;
    }
    .user-type-options {
        gap: 20px;
        flex-wrap: wrap;
    }
    .user-type-option {
        padding: 12px 20px;
        min-width: 120px;
    }
    .user-type-icon {
        font-size: 24px;
    }
    .user-type-label {
        font-size: 14px;
    }
    .login-form-options {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    .login-submit-btn {
        width: 80%;
    }
}

@media (max-width: 480px) {
    body {
        padding-top: 160px;
    }
    .login-main-container {
        padding: 30px 20px;
    }
    .login-welcome-title {
        font-size: 24px;
    }
    .user-type-options {
        gap: 15px;
        flex-wrap: wrap;
    }
    .user-type-option {
        min-width: 100px;
        flex: 1;
        max-width: 45%;
    }
    .login-submit-btn {
        width: 90%;
    }
}

/* Ensure no horizontal scroll */
html, body {
    overflow-x: hidden;
}

</style>
</head>
<body>

<div class="login-main-content">
    <div class="login-main-container">
        <h1 class="login-welcome-title">Welcome!</h1>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="login-message error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="login-message success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        
        <!-- User Type Selection -->
        <div class="user-type-selection">
            <h3 class="user-type-title">Select Account Type</h3>
            <div class="user-type-options">
                <div class="user-type-option selected" data-type="business">
                    <input type="radio" id="business" name="user_type" value="business" checked>
                    <div class="user-type-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <label for="business" class="user-type-label">Business User</label>
                </div>
                
                <div class="user-type-option" data-type="non-business">
                    <input type="radio" id="non-business" name="user_type" value="non-business">
                    <div class="user-type-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <label for="non-business" class="user-type-label">Non-Business User</label>
                </div>
            </div>
        </div>
        
        <form action="process-login.php" method="POST" id="loginForm">
            <!-- Hidden input to store selected user type -->
            <input type="hidden" name="user_type" id="selected_user_type" value="business">
            
            <div class="login-form-group">
                <label for="email">Email-ID / Mobile:</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            
            <div class="login-form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            
            <div class="login-form-options">
                <div class="login-remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember Me</label>
                </div>
                <a href="forgot-password.php" class="login-forgot-password">Forgot password?</a>
            </div>
            
            <button type="submit" class="login-submit-btn" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="login-register-section">
            <p class="login-register-text" id="registerText">Don't have a business account?</p>
            <a href="register-business.php" class="login-register-btn" id="registerLink">
                <i class="fas fa-user-plus"></i> Register
            </a>
        </div>
    </div>
</div>

<script>
// User type selection handling
document.querySelectorAll('.user-type-option').forEach(option => {
    option.addEventListener('click', function() {
        // Remove selected class from all options
        document.querySelectorAll('.user-type-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        
        // Add selected class to clicked option
        this.classList.add('selected');
        
        // Update radio button
        const radio = this.querySelector('input[type="radio"]');
        radio.checked = true;
        
        // Update hidden input
        document.getElementById('selected_user_type').value = radio.value;
        
        // Update register section text and link based on selection
        const registerText = document.getElementById('registerText');
        const registerLink = document.getElementById('registerLink');
        
        if (radio.value === 'business') {
            registerText.textContent = "Don't have a business account?";
            registerLink.href = "register-business.php";
            registerLink.innerHTML = '<i class="fas fa-building"></i> Register Business';
        } else {
            registerText.textContent = "Don't have a non-business account?";
            registerLink.href = "register-nonbusiness.php";
            registerLink.innerHTML = '<i class="fas fa-user-plus"></i> Register Non-Business';
        }
    });
});

// Login form enhancement
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('loginBtn');
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const userType = document.getElementById('selected_user_type').value;
    
    // Basic validation
    if (!email || !password) {
        e.preventDefault();
        alert('Please fill in all fields');
        return;
    }
    
    // Validate user type selection
    if (!userType) {
        e.preventDefault();
        alert('Please select an account type');
        return;
    }
    
    // Add loading state
    btn.classList.add('loading');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
    btn.disabled = true;
});

// Auto-hide messages after 5 seconds
setTimeout(function() {
    const messages = document.querySelectorAll('.login-message');
    messages.forEach(function(message) {
        message.style.opacity = '0';
        message.style.transform = 'translateY(-10px)';
        setTimeout(function() {
            message.style.display = 'none';
        }, 300);
    });
}, 5000);
</script>

<?php include 'common/footer.php'; ?>

</body>
</html>