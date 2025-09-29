<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'common/header.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<style>
* {
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #c9b6ff, #f2d6d6);
    min-height: 100vh;
}

/* Register container */
.register-container {
    background: #ffffff;
    padding: 50px 40px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    text-align: center;
    width: 350px;
    margin: 100px auto 50px auto; /* Top margin from header */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.register-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.3);
}

h2 {
    margin-bottom: 35px;
    color: #4a148c;
    font-size: 28px;
    letter-spacing: 1px;
}

/* Radio buttons styled as cards */
.radio-group {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.radio-group label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
    border: 2px solid #ccc;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #fafafa;
}

.radio-group input[type="radio"] {
    transform: scale(1.2);
    accent-color: #4a148c;
}

.radio-group label:hover {
    background: linear-gradient(135deg, #4a148c, #8e24aa);
    color: #fff;
    border-color: #4a148c;
}

/* Responsive */
@media (max-width: 480px) {
    .register-container {
        width: 90%;
        padding: 40px 20px;
        margin: 80px auto 40px auto;
    }

    h2 {
        font-size: 24px;
        margin-bottom: 25px;
    }

    .radio-group label {
        font-size: 14px;
        padding: 12px 15px;
    }
}
</style>
</head>
<body>

<div class="register-container">
    <h2>Register User</h2>

    <div class="radio-group">
        <label>
            Business User
            <input type="radio" name="user_type" value="business" onclick="redirectUser(this.value)">
        </label>
        <label>
            Non-Buisness User
            <input type="radio" name="user_type" value="personal" onclick="redirectUser(this.value)">
        </label>
    </div>
</div>

<script>
function redirectUser(value) {
    if(value === 'business') {
        window.location.href = 'register-business.php';
    } else if(value === 'personal') {
        window.location.href = 'register-nonbusiness.php';
    }
}
</script>

<?php include 'common/footer.php'; ?>
</body>
</html>
