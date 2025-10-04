<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'common/header.php';
include 'dbconfigf/dbconst2025.php'; // DB connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['promo'])) {
    // From form
    $reference_name = $_POST['reference_id']; // user-entered reference name
    $name = $_POST['name'];
    $whatsapp = $_POST['whatsapp'];
    $alternate = $_POST['alternate'];
    $email = $_POST['email'];
    $industry = $_POST['industry'];
    $category = $_POST['category'];
    $stateid = $_POST['state']; 
    $cityid  = $_POST['city'];
    $pincode = $_POST['pincode'];
    $plan = $_POST['plan'];
    $password = $_POST['password'];
    $promocode = isset($_POST['promocode']) ? $_POST['promocode'] : '';
    $final_amount = isset($_POST['final_amount']) ? $_POST['final_amount'] : 0;
    $discount_amount = isset($_POST['discount_amount']) ? $_POST['discount_amount'] : 0;

    // ✅ Check if WhatsApp already exists
    $check = $conn->prepare("SELECT user_id FROM users WHERE whatsapp = ?");
    $check->bind_param("s", $whatsapp);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('WhatsApp number already exists!'); window.history.back();</script>";
        exit();
    }
    $check->close();

    // ✅ Generate reference_id (WhatsApp + 1 at the end)
    $reference_id = $whatsapp . "1";

    // Base plan amount
    $base_amount = ($plan == "trusted") ? 1000 : 250;
    $amount = ($final_amount > 0) ? $final_amount : $base_amount;

    // File upload paths
    $photo_path = null;
    $logo_path = null;
    $upload_dir = "uploads/users/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        if (in_array($photo_ext, $allowed_extensions) && $_FILES['photo']['size'] <= 2 * 1024 * 1024) {
            $photo_name = 'photo_' . time() . '_' . rand(1000, 9999) . '.' . $photo_ext;
            $photo_path = $upload_dir . $photo_name;
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
                die("Error uploading photo. Please try again.");
            }
        } else {
            die("Invalid photo format or size. Please upload JPG/JPEG/PNG file under 2MB.");
        }
    }

    // Logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $logo_ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        if (in_array($logo_ext, $allowed_extensions) && $_FILES['logo']['size'] <= 2 * 1024 * 1024) {
            $logo_name = 'logo_' . time() . '_' . rand(1000, 9999) . '.' . $logo_ext;
            $logo_path = $upload_dir . $logo_name;
            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path)) {
                die("Error uploading logo. Please try again.");
            }
        } else {
            die("Invalid logo format or size. Please upload JPG/JPEG/PNG file under 2MB.");
        }
    }

    // Encrypt password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // ✅ Insert into users with swapped values
    $stmt = $conn->prepare("INSERT INTO users 
        (referencename, reference_id, name, whatsapp, alternate, email, industry,
         category, state, city, pincode, plan, amount, base_amount,
         discount_amount, promocode, photo_path, logo_path, password_hash, created_at) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, NOW())");

    $stmt->bind_param("sssssssssssiissssss", 
        $reference_name, $reference_id, $name, $whatsapp, $alternate, $email, $industry, 
        $category, $stateid, $cityid, $pincode, $plan, $amount, $base_amount, $discount_amount, 
        $promocode, $photo_path, $logo_path, $password_hash
    );

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        $redirectUrl = "payment.php?user_id=" . $user_id . "&amount=" . $amount . "&plan=" . $plan;
        include 'modal-sucess.php';
        echo "<script>showSuccessModal('$redirectUrl');</script>";
        exit();
    } else {
        if ($photo_path && file_exists($photo_path)) unlink($photo_path);
        if ($logo_path && file_exists($logo_path)) unlink($logo_path);
        echo "Error: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<title>Register User</title>
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

form select {
    margin-bottom: 15px;
    padding: 12px 16px;
    border: 2px solid #e5e5e5;
    border-radius: 25px;
    width: 100%;
    box-sizing: border-box;
    font-size: 14px;
    background-color: #fff;
    appearance: none; /* removes default arrow in some browsers */
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;utf8,<svg fill='%23333' height='20' viewBox='0 0 24 24' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 18px 18px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

form select:focus {
    outline: none;
    border-color: #f26522;
    box-shadow: 0 0 0 3px rgba(242, 101, 34, 0.1);
}

    .container {
        background-color: white;
        border-radius: 15px;
        padding: 30px;
        width: 600px;
        max-width: 90%;
        box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
        margin-top: 40px;
    }
    .form-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* takes full height */
    padding: 20px; /* prevents sticking to edges on mobile */
}

    .form-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #f26522;
    }
   form label {
    margin-top: 18px;
    display: block;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

form input[type="text"], 
form input[type="email"], 
form input[type="password"] {
    margin-bottom: 15px;
    padding: 12px 16px;
    border: 2px solid #e5e5e5;
    border-radius: 25px;
    width: 100%;
    box-sizing: border-box;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

form input[type="text"]:focus, 
form input[type="email"]:focus,
form input[type="password"]:focus {
    outline: none;
    border-color: #f26522;
    box-shadow: 0 0 0 3px rgba(242, 101, 34, 0.1);
}
 
/* Plan Selection Styles */
.plan-selection-title {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    margin: 35px 0 25px 0;
    color: #333;
    border-bottom: 2px solid #f26522;
    padding-bottom: 8px;
    display: inline-block;
    width: 100%;
}

.plans-container {
    display: flex;
    gap: 20px;
    margin: 25px 0;
}

.plan-option {
    flex: 1;
    position: relative;
}

.plan-option input[type="radio"] {
    display: none;
}

.plan-label {
    cursor: pointer;
    display: block;
    transition: all 0.3s ease;
}

.plan {
    background-color: white;
    border-radius: 15px;
    padding: 0;
    margin: 0;
    box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: 3px solid transparent;
    position: relative;
    overflow: hidden;
}

.plan-option input[type="radio"]:checked + .plan-label .plan {
    border-color: #f26522;
    transform: translateY(-5px);
    box-shadow: 0px 8px 25px rgba(242, 101, 34, 0.2);
}

.plan-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    margin: 0;
    position: relative;
}

.plan-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: bold;
}

.plan-price {
    font-size: 28px;
    font-weight: bold;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    padding: 8px 12px;
    background: rgba(255,255,255,0.2);
    border-radius: 8px;
    border: 2px solid rgba(255,255,255,0.3);
}

.trusted .plan-header {
    background: linear-gradient(135deg, #f26522, #e55a1f);
    color: white;
    border-radius: 12px 12px 0 0;
}

.trusted .plan-price {
    color: white;
    background: rgba(255,255,255,0.25);
    border-color: rgba(255,255,255,0.4);
}

.basic .plan-header {
    background: linear-gradient(135deg, #6a1b9a, #5a1582);
    color: white;
    border-radius: 12px 12px 0 0;
}

.basic .plan-price {
    color: white;
    background: rgba(255,255,255,0.25);
    border-color: rgba(255,255,255,0.4);
}

.plan-features {
    padding: 20px;
}

.plan-features ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.plan-features li {
    padding: 8px 0;
    font-size: 14px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
}

.plan-features li:last-child {
    border-bottom: none;
}

.plan-badge {
    position: absolute;
    top: -2px;
    left: -2px;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 6px 12px;
    font-size: 10px;
    font-weight: bold;
    border-radius: 0 0 12px 0;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Selected Plan Summary */
.selected-plan-summary {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 2px solid #f26522;
    border-radius: 15px;
    padding: 20px;
    margin: 25px 0;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.summary-content h4 {
    margin: 0 0 15px 0;
    color: #f26522;
    font-size: 18px;
}

.summary-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.amount-row, .discount-row, .total-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.discount-row {
    color: #28a745;
}

.total-row {
    border-top: 2px solid #dee2e6;
    margin-top: 10px;
    padding-top: 15px;
    font-size: 16px;
}

/* Promocode styling */
.promocode-section {
    margin: 25px 0;
    position: relative;
}

.promocode-section input {
    padding-right: 100px !important;
}

.apply-promo-btn {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: #f26522;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 15px;
    cursor: pointer;
    font-size: 12px;
    font-weight: bold;
}

.apply-promo-btn:hover {
    background: #d9531e;
}

.promo-message {
    margin-top: 8px;
    font-size: 12px;
    font-weight: bold;
    display: none;
}

.promo-success {
    color: #28a745;
}

.promo-error {
    color: #dc3545;
}

/* Upload styles */
.photo-logo {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    margin: 25px 0;
}

.upload-box {
    width: 48%;
    display: flex;
    flex-direction: column;
}

.upload-box label {
    font-weight: bold;
    margin-bottom: 10px;
    font-size: 14px;
    color: #333;
}

.upload-box small {
    display: block;
    font-weight: normal;
    font-size: 11px;
    color: #666;
    margin-top: 3px;
    line-height: 1.3;
}

.upload-box input[type="file"] {
    display: none;
}

.upload-area {
    border: 2px dashed #bbb;
    border-radius: 12px;
    min-height: 140px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-align: center;
    padding: 20px 10px;
    transition: all 0.3s ease;
    background: linear-gradient(145deg, #fafafa, #f0f0f0);
    position: relative;
}

.upload-area:hover {
    border-color: #f26522;
    background: linear-gradient(145deg, #fff5f1, #ffeee7);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(242, 101, 34, 0.1);
}

.upload-icon {
    font-size: 28px;
    color: #888;
    margin-bottom: 8px;
    transition: color 0.3s ease;
}

.upload-area:hover .upload-icon {
    color: #f26522;
}

.upload-area p {
    font-size: 13px;
    color: #666;
    margin: 0;
    font-weight: 500;
    line-height: 1.4;
}

.upload-area:hover p {
    color: #f26522;
}

.upload-title {
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    margin: 30px 0 20px 0;
    color: #333;
    border-bottom: 2px solid #f26522;
    padding-bottom: 8px;
    display: inline-block;
    width: 100%;
}

.file-preview {
    margin-top: 10px;
    padding: 8px;
    background: #f0f9ff;
    border: 1px solid #e0f2fe;
    border-radius: 6px;
    font-size: 12px;
    color: #0369a1;
    display: none;
}

.file-preview.show {
    display: block;
}

    .proceed-btn {
        width: 100%;
        background-color: #f26522;
        color: white;
        border: none;
        padding: 15px;
        font-size: 16px;
        border-radius: 20px;
        cursor: pointer;
    }

    .proceed-btn:hover {
        background-color: #d9531e;
    }

.success-box {
    color: green;
    font-weight: bold;
    display: none;
}
.error-box {
    color: red;
    font-weight: bold;
    display: none;
}

/* Responsive */
@media (max-width: 768px) {
    .plans-container {
        flex-direction: column;
        gap: 15px;
    }
    
    .plan-header {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    
    .plan-price {
        font-size: 20px;
    }
    
    .summary-details {
        font-size: 14px;
    }
    
    .photo-logo {
        flex-direction: column;
        gap: 20px;
    }
    
    .upload-box {
        width: 100%;
    }
    
    .upload-area {
        min-height: 120px;
        padding: 15px 10px;
    }
    
    .upload-icon {
        font-size: 24px;
    }
    
    .upload-area p {
        font-size: 12px;
    }

    
}
</style>
</head>
<body>
<div class="form-wrapper">
  <div class="container">
      <form method="POST" action="register-business.php" enctype="multipart/form-data">
        <div class="form-title">Register</div>

        <label>Reference ID (Use Default 9876543210)</label>
        <input type="text" id="reference_id" name="reference_id" required>
        <div id="refSuccess" style="color:green; font-weight:bold; display:none;">✔ Valid Referral</div>
        <div id="refError" style="color:red; font-weight:bold; display:none;">✖ Invalid Referral</div>

        <label>Name:</label>
        <input type="text" name="name" required>

        <label>WhatsApp No:</label>
        <input type="text" name="whatsapp" required>

        <label>Alternate No:</label>
        <input type="text" name="alternate" required>

        <label>Email Id:</label>
        <input type="email" name="email" required>

        <label>Industry Classification:</label>
        <input type="text" name="industry" required>

        <label>Business Category:</label>
        <input type="text" name="category" required>
<label>State:</label>
<select name="stateid" id="state" required>
    <option value="">Select State</option>
</select>

<label>City:</label>
<select name="cityid" id="city" required>
    <option value="">Select City</option>
</select>

        <label>Pincode:</label>
        <input type="text" name="pincode" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <!-- Upload Section -->
        <div class="upload-title">Upload Your Photo & Logo</div>
        <div class="photo-logo">
            <div class="upload-box">
                <label>Your Professional Photo <span style="color:red">*</span> 
                    <small>(Owner's Passport photo preferred)<br>(JPEG, JPG, PNG, max 2 MB)</small>
                </label>
                <input type="file" name="photo" id="photo" accept="image/*" required>
                <div class="upload-area" onclick="document.getElementById('photo').click()">
                    <span class="upload-icon">📷</span>
                    <p>Drag and drop your photo here<br>or <strong>click to browse</strong></p>
                </div>
                <div class="file-preview" id="photo-preview"></div>
            </div>

            <div class="upload-box">
                <label>Business Logo 
                    <small>(Your company/brand logo)<br>(JPEG, JPG, PNG, max 2 MB)</small>
                </label>
                <input type="file" name="logo" id="logo" accept="image/*">
                <div class="upload-area" onclick="document.getElementById('logo').click()">
                    <span class="upload-icon">🏢</span>
                    <p>Drag and drop your logo here<br>or <strong>click to browse</strong></p>
                </div>
                <div class="file-preview" id="logo-preview"></div>
            </div>
        </div>

        <!-- Plan Selection -->
        <div class="plan-selection-title">Choose Your Plan</div>
        <div class="plans-container">
            <div class="plan-option" data-plan="trusted" data-amount="1000">
                <input type="radio" name="plan" id="trusted" value="trusted" required>
                <label for="trusted" class="plan-label">
                    <div class="plan trusted">
                        <div class="plan-badge">RECOMMENDED</div>
                        <div class="plan-header">
                            <h3>Trusted Plan</h3>
                            <div class="plan-price">₹1,000</div>
                        </div>
                        <div class="plan-features">
                            <ul>
                                <li>✅ Trusted Badge</li>
                                <li>✅ Owner's Details</li>
                                <li>✅ Product & Brand Details</li>
                                <li>✅ Licenses & Registrations</li>
                                <li>✅ Alternate Contact Person</li>
                                <li>✅ USP (Unique Selling Point)</li>
                                <li>✅ All basic plan features</li>
                                <li>✅ Priority Support</li>
                            </ul>
                        </div>
                    </div>
                </label>
            </div>

            <div class="plan-option" data-plan="basic" data-amount="250">
                <input type="radio" name="plan" id="basic" value="basic" required>
                <label for="basic" class="plan-label">
                    <div class="plan basic">
                        <div class="plan-header">
                            <h3>Basic Plan</h3>
                            <div class="plan-price">₹250</div>
                        </div>
                        <div class="plan-features">
                            <ul>
                                <li>✅ Business Listings</li>
                                <li>✅ LOGO Upload</li>
                                <li>✅ Photo Upload</li>
                                <li>✅ Website & Social Media Details</li>
                                <li>✅ Basic business information</li>
                                <li>✅ Contact Details</li>
                            </ul>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Selected Plan Summary -->
        <div class="selected-plan-summary" id="planSummary" style="display: none;">
            <div class="summary-content">
                <h4>Selected Plan: <span id="selectedPlanName"></span></h4>
                <div class="summary-details">
                    <div class="amount-row">
                        <span>Plan Amount:</span>
                        <span id="selectedAmount">₹0</span>
                    </div>
                    <div class="discount-row" id="discountRow" style="display: none;">
                        <span>Discount:</span>
                        <span id="discountAmount">-₹0</span>
                    </div>
                    <div class="total-row">
                        <span><strong>Total Amount:</strong></span>
                        <span id="finalAmount"><strong>₹0</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promocode Section -->
        <div class="promocode-section">
            <label>Promocode</label>
            <input type="text" name="promocode" id="promoCode" placeholder="Enter promo code">
            <button type="button" id="applyPromoBtn" class="apply-promo-btn">Apply</button>
            <div class="promo-message" id="promoMessage"></div>
        </div>

        <!-- Hidden fields for final amounts -->
        <input type="hidden" name="final_amount" id="hiddenFinalAmount" value="0">
        <input type="hidden" name="discount_amount" id="hiddenDiscountAmount" value="0">

        <button type="submit" id="submitBtn" class="proceed-btn">Proceed to Pay</button>
    </form>
</div>

<script>
$(document).ready(function(){
    let selectedAmount = 0;
    let discountAmount = 0;
    let finalAmount = 0;
    let promoApplied = false;
    
    // Reference ID validation
    $("#reference_id").on("blur", function(){
        var referral = $(this).val().trim();
        var defaultReferral = "9876543210";

        if(referral !== ""){
            if(referral === defaultReferral){
                $("#refSuccess").show();
                $("#refError").hide();
                $("#submitBtn").prop("disabled", false);
            } else {
                $.ajax({
                    url: "check_referral.php",
                    type: "POST",
                    data: { referral: referral },
                    success: function(response){
                        if(response.trim() === "valid"){
                            $("#refSuccess").show();
                            $("#refError").hide();
                            $("#submitBtn").prop("disabled", false);
                        } else {
                            $("#refSuccess").hide();
                            $("#refError").show();
                            $("#submitBtn").prop("disabled", true);
                        }
                    }
                });
            }
        }
    });

    // Plan selection handler
    $('input[name="plan"]').on('change', function() {
        const selectedPlan = $(this).val();
        selectedAmount = parseInt($(this).closest('.plan-option').data('amount'));
        
        // Reset promo when plan changes
        resetPromo();
        
        // Update plan summary
        updatePlanSummary(selectedPlan, selectedAmount);
        
        // Show summary section
        $('#planSummary').slideDown();
        
        // Add visual feedback
        $('.plan-option').removeClass('selected');
        $(this).closest('.plan-option').addClass('selected');
    });

    // Promocode apply button
    $('#applyPromoBtn').on('click', function(e) {
        e.preventDefault();
        applyPromo();
    });

    // Also allow applying promo on Enter key
    $('#promoCode').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            applyPromo();
        }
    });

   function applyPromo() { 
    let promoCode = $("#promoCode").val().trim();
    let selectedPlan = $("input[name='plan']:checked");

    if (!selectedPlan.length) {
        showPromoMessage("Please select a plan first.", "error");
        return;
    }

    if (promoCode === "") {
        showPromoMessage("Please enter a promo code.", "error");
        return;
    }

    let planAmount = selectedPlan.closest(".plan-option").data("amount");

    // Show loading state
    $('#applyPromoBtn').text('Applying...').prop('disabled', true);

    $.ajax({
        url: "check_promo.php", // PHP file
        type: "POST",
        data: { 
            promo: promoCode, 
            amount: planAmount 
        },
        dataType: 'json',
        success: function(data){
            console.log("Server Response:", data); // 👈 helps debug

            if (data.status === "success") {
                let discountAmount = parseFloat(data.discount);
                let finalAmount = parseFloat(data.discounted_amount);

                promoApplied = true;

                // Update UI
                $("#discountRow").show();
                $("#discountAmount").text("-₹" + discountAmount.toFixed(2));
                $("#finalAmount strong").text("₹" + finalAmount.toFixed(2));

                // Update hidden inputs
                $("#hiddenFinalAmount").val(finalAmount);
                $("#hiddenDiscountAmount").val(discountAmount);

                showPromoMessage("Promo code applied successfully! You saved ₹" + discountAmount.toFixed(2), "success");

                // Disable promo input and button
                $('#promoCode').prop('readonly', true);
                $('#applyPromoBtn').text('Applied').removeClass('apply-promo-btn').addClass('applied-btn');
                
            }  else if (data.status === "error") {
                // Handle error responses
                showPromoMessage(data.message || "Invalid promo code", "error");
                resetPromo();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error, xhr.responseText);
            showPromoMessage("Error applying promo code. Please try again.", "error");
            resetPromo();
        },
        complete: function() {
            // Reset button state if not applied
            if (!promoApplied) {
                $('#applyPromoBtn').text('Apply').prop('disabled', false);
            }
        }
    });
}


    function resetPromo() {
        promoApplied = false;
        discountAmount = 0;
        finalAmount = selectedAmount;
        
        $("#discountRow").hide();
        $("#discountAmount").text("-₹0");
        $("#finalAmount strong").text("₹" + selectedAmount);
        
        // Reset hidden inputs
        $("#hiddenFinalAmount").val(selectedAmount);
        $("#hiddenDiscountAmount").val(0);
        
        // Reset promo input
        $('#promoCode').prop('readonly', false);
        $('#applyPromoBtn').text('Apply').removeClass('applied-btn').addClass('apply-promo-btn');
        $('#applyPromoBtn').prop('disabled', false);
        
        // Hide promo message
        $('#promoMessage').hide();
    }

    function updatePlanSummary(planName, amount) {
        const planDisplayName = planName === 'trusted' ? 'Trusted Plan' : 'Basic Plan';
        
        $('#selectedPlanName').text(planDisplayName);
        $('#selectedAmount').text('₹' + amount);
        
        // Update final amount (without discount initially)
        finalAmount = amount;
        $('#finalAmount strong').text('₹' + finalAmount);
        
        // Update hidden input
        $("#hiddenFinalAmount").val(finalAmount);
    }

    function showPromoMessage(message, type) {
        const messageClass = type === 'success' ? 'promo-success' : 'promo-error';
        $('#promoMessage').removeClass('promo-success promo-error')
                          .addClass(messageClass)
                          .text(message)
                          .show();
        
        if (type === 'error') {
            setTimeout(() => {
                $('#promoMessage').fadeOut();
            }, 5000);
        }
    }

    // File preview functionality
    $('#photo').on('change', function(e) {
        showFilePreview(e.target, 'photo-preview');
    });

    $('#logo').on('change', function(e) {
        showFilePreview(e.target, 'logo-preview');
    });

    function showFilePreview(input, previewId) {
        const preview = $('#' + previewId);
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            // Check file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size should not exceed 2MB');
                input.value = '';
                preview.removeClass('show');
                return;
            }
            
            preview.html(`✅ Selected: ${fileName} (${fileSize} MB)`);
            preview.addClass('show');
        } else {
            preview.removeClass('show');
        }
    }

    // Form validation before submit
    $('form').on('submit', function(e) {
        if (selectedAmount === 0) {
            e.preventDefault();
            alert('Please select a plan before proceeding');
            return false;
        }
        
        // Ensure hidden fields are set correctly
        if (finalAmount === 0) {
            finalAmount = selectedAmount;
        }
        
        $("#hiddenFinalAmount").val(finalAmount);
        $("#hiddenDiscountAmount").val(discountAmount);
        
        return true;
    });

    // Initialize with default referral validation
    $("#reference_id").trigger('blur');
});



</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Load States on Page Load
    $.getJSON("get_states.php", function(data) {
        $.each(data, function(index, state) {
            $("#state").append('<option value="'+ state.stateid +'">'+ state.state +'</option>');
        });
    });

    // Load Cities when State Changes
    $("#state").change(function() {
        var stateid = $(this).val();
        $("#city").html('<option value="">Select City</option>'); // reset

        if (stateid) {
            $.getJSON("get_cities.php?stateid=" + stateid, function(data) {
                $.each(data, function(index, city) {
                    $("#city").append('<option value="'+ city.cityid +'">'+ city.city +'</option>');
                });
            });
        }
    });
});
</script>


<style>
.applied-btn {
    background: #28a745 !important;
    cursor: not-allowed;
}

.applied-btn:hover {
    background: #28a745 !important;
}
</style>

</body>
</html>