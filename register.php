<?php
include 'common/header.php';
include 'dbconfigf/dbconst2025.php'; // Your DB connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reference_id = $_POST['reference_id'];
    $name = $_POST['name'];
    $whatsapp = $_POST['whatsapp'];
    $alternate = $_POST['alternate'];
    $email = $_POST['email'];
    $industry = $_POST['industry'];
    $category = $_POST['category'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $plan = $_POST['plan'];
    $password = $_POST['password'];

    // Set plan amount
    $amount = ($plan == "trusted") ? 1000 : 250;

    // Encrypt password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (reference_id,name,whatsapp,alternate,email,industry,category,state,city,pincode,plan,amount,password_hash) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssssssssis", $reference_id,$name,$whatsapp,$alternate,$email,$industry,$category,$state,$city,$pincode,$plan,$amount,$password_hash);
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Also insert into login table
        $stmt2 = $conn->prepare("INSERT INTO logins (user_id,email,password_hash) VALUES (?,?,?)");
        $stmt2->bind_param("iss",$user_id,$email,$password_hash);
        $stmt2->execute();

        // Redirect to payment page
        header("Location: payment.php?user_id=".$user_id);
        exit();
    } else {
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
    body {
        font-family: Arial, sans-serif;
        background-color: #f6ebeb;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }
    .container {
        background-color: white;
        border-radius: 15px;
        padding: 30px;
        width: 600px;
        max-width: 90%;
        box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
        margin-top: 40px; /* Reduced gap from header */
    }
    .form-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #f26522;
    }
    form label {
        margin-top: 15px;
        display: block;
        font-weight: bold;
    }
    form input[type="text"], 
    form input[type="email"] {
        margin-bottom: 15px;
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 20px;
        width: 100%;
        box-sizing: border-box;
    }
    .photo-logo {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
    }
    .photo-logo div {
        background-color: #ddd;
        width: 48%;
        height: 100px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: gray;
        font-weight: bold;
        font-size: 18px;
    }
    .plan {
        background-color: white;
        border-radius: 10px;
        padding: 10px;
        margin: 15px 0;
        box-shadow: 0px 0px 5px rgba(0,0,0,0.1);
    }
    .trusted {
        border-top: 6px solid #f26522;
    }
    .trusted h3 {
        background-color: #f26522;
        color: white;
        margin: -10px -10px 10px -10px;
        padding: 10px;

        font-size: 16px;
    }
    .basic {
        border-top: 6px solid #6a1b9a;
    }
    .basic h3 {
        background-color: #6a1b9a;
        color: white;
        margin: -10px -10px 10px -10px;
        padding: 10px;
       
        font-size: 16px;
    }
    ul {
        margin: 0;
        padding-left: 20px;
        font-size: 14px;
    }
    .promocode {
        margin: 20px 0;
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
</style>
</head>
<body>

<div class="container">
    <form method="POST" action="register.php">
        <div class="form-title">Register</div>

        <label>Reference ID :( Use Default 9876543210)</label>
        <input type="text" id="reference_id" name="reference_id" required>
        <div id="refSuccess" style="color:green; font-weight:bold; display:none;">✔ Valid Referral</div>
        <div id="refError" style="color:red; font-weight:bold; display:none;">✖ Invalid Referral</div>

        <label>Name :</label>
        <input type="text" name="name" required>

        <label>WhatsApp No :</label>
        <input type="text" name="whatsapp" required>

        <label>Alternate No :</label>
        <input type="text" name="alternate" required>

        <label>Email Id :</label>
        <input type="email" name="email" required>

        <label>Industry Classification :</label>
        <input type="text" name="industry" required>

        <label>Business Category :</label>
        <input type="text" name="category" required>

        <label>State :</label>
        <input type="text" name="state" required>

        <label>City :</label>
        <input type="text" name="city" required>

        <label>Pincode :</label>
        <input type="text" name="pincode" required>

        <h3 style="text-align:center; font-size: 16px;">Enter Your Photo & Logo</h3>
        <div class="photo-logo">
            <div>Photo</div>
            <div>Logo</div>
        </div>

        <div class="plan trusted">
            <h3>Trusted Plan (1000 Rs)</h3>
            <ul>
                <li>Trusted Badge</li>
                <li>Owner’s Details</li>
                <li>Product & Brand Details</li>
                <li>Licenses & Registrations</li>
                <li>Alternate Contact Person</li>
                <li>USP</li>
                <li>All basic plan features</li>
            </ul>
        </div>

        <div class="plan basic">
            <h3>Basic Plan (250 Rs)</h3>
            <ul>
                <li>Business Listings</li>
                <li>LOGO</li>
                <li>Photo</li>
                <li>Website & Social Media Details</li>
                <li>Basic business details</li>
            </ul>
        </div>

        <div class="promocode">
            <label>Promocode</label>
            <input type="text" name="promocode">
        </div>

        <button type="submit" id="submitBtn" class="proceed-btn">Proceed to Pay</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $("#reference_id").on("blur", function(){
        var referral = $(this).val().trim();

        // ✅ Define your default referral ID here
        var defaultReferral = "9876543210";

        if(referral !== ""){
            if(referral === defaultReferral){
                // If referral matches default, mark as valid directly
                $("#refSuccess").show();
                $("#refError").hide();
                $("#submitBtn").prop("disabled", false);
            } else {
                // Otherwise check with server
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
});
</script>


</body>
</html>
