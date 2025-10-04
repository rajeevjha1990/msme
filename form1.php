<?php
// Include the header file
include 'common/header.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "candidate_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $introduction_summary = $_POST['introduction_summary'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $alternate_number = $_POST['alternate_number'] ?? '';
    $role_applying_for = $_POST['role_applying_for'] ?? '';
    $opportunity_type = $_POST['opportunity_type'] ?? '';
    $highest_qualification = $_POST['highest_qualification'] ?? '';
    $year_of_passing = $_POST['year_of_passing'] ?? '';
    $current_course = $_POST['current_course'] ?? '';
    $current_stage = $_POST['current_stage'] ?? '';
    $current_city = $_POST['current_city'] ?? '';
    $current_state = $_POST['current_state'] ?? '';
    $current_pin = $_POST['current_pin'] ?? '';
    $permanent_city = $_POST['permanent_city'] ?? '';
    $permanent_state = $_POST['permanent_state'] ?? '';
    $permanent_pin = $_POST['permanent_pin'] ?? '';
    $interview_date = $_POST['interview_date'] ?? '';
    $joining_date = $_POST['joining_date'] ?? '';
    $expected_salary = $_POST['expected_salary'] ?? '';
    $experience_status = $_POST['experience_status'] ?? '';
    $experience_years = $_POST['experience_years'] ?? '';
    $last_salary = $_POST['last_salary'] ?? '';
    $organisation = $_POST['organisation'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $laptop_config = $_POST['laptop_config'] ?? '';
    $english = $_POST['english'] ?? '';
    $hindi = $_POST['hindi'] ?? '';
    $facebook = $_POST['facebook'] ?? '';
    $instagram = $_POST['instagram'] ?? '';
    $youtube = $_POST['youtube'] ?? '';
    $linkedin = $_POST['linkedin'] ?? '';
    $description = $_POST['description'] ?? '';
    $ref_name_1 = $_POST['ref_name_1'] ?? '';
    $ref_relation_1 = $_POST['ref_relation_1'] ?? '';
    $ref_designation_1 = $_POST['ref_designation_1'] ?? '';
    $ref_contact_1 = $_POST['ref_contact_1'] ?? '';
    $ref_name_2 = $_POST['ref_name_2'] ?? '';
    $ref_relation_2 = $_POST['ref_relation_2'] ?? '';
    $ref_designation_2 = $_POST['ref_designation_2'] ?? '';
    $ref_contact_2 = $_POST['ref_contact_2'] ?? '';
    $ref_name_3 = $_POST['ref_name_3'] ?? '';
    $ref_relation_3 = $_POST['ref_relation_3'] ?? '';
    $ref_designation_3 = $_POST['ref_designation_3'] ?? '';
    $ref_contact_3 = $_POST['ref_contact_3'] ?? '';
    
    // Validation
    $errors = [];
    
    // Required field validation
    if (empty($first_name)) $errors[] = "First name is required.";
    if (empty($last_name)) $errors[] = "Last name is required.";
    if (empty($phone_number)) $errors[] = "Phone number is required.";
    if (empty($gender)) $errors[] = "Gender is required.";
    if (empty($date_of_birth)) $errors[] = "Date of birth is required.";
    if (empty($highest_qualification)) $errors[] = "Highest qualification is required.";
    if (empty($year_of_passing)) $errors[] = "Year of passing is required.";
    if (empty($role_applying_for)) $errors[] = "Role you are applying for is required.";
    if (empty($opportunity_type)) $errors[] = "Type of opportunity is required.";
    if (empty($expected_salary)) {
    $errors[] = "Expected salary is required.";
} elseif (!is_numeric($expected_salary)) {
    $errors[] = "Expected salary must be a number.";
}
    if (empty($current_city)) $errors[] = "Current city is required.";
    if (empty($current_state)) $errors[] = "Current state is required.";
    if (empty($current_pin)) $errors[] = "Current PIN code is required.";
    if (empty($permanent_city)) $errors[] = "Permanent city is required.";
    if (empty($permanent_state)) $errors[] = "Permanent state is required.";
    if (empty($permanent_pin)) $errors[] = "Permanent PIN code is required.";
    if (empty($laptop_config)) $errors[] = "Laptop configuration is required.";
    
    // File upload validation
    if (!isset($_FILES['resume']) || $_FILES['resume']['error'] != 0) {
        $errors[] = "Resume upload is required.";
    }
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] != 0) {
        $errors[] = "Photo upload is required.";
    }
    
    // Phone number validation
    if (!empty($phone_number) && !preg_match('/^[0-9]{10}$/', $phone_number)) {
        $errors[] = "Phone number must be exactly 10 digits.";
    }
    
    if (!empty($alternate_number) && !preg_match('/^[0-9]{10}$/', $alternate_number)) {
        $errors[] = "Alternate number must be exactly 10 digits.";
    }
    
    // Year of passing validation (should be a 4-digit year)
    if (!empty($year_of_passing) && !preg_match('/^[0-9]{4}$/', $year_of_passing)) {
        $errors[] = "Year of passing must be a valid 4-digit year.";
    }
    
    // Date validations
    $today = date('Y-m-d');
    if (!empty($interview_date) && $interview_date < $today) {
        $errors[] = "Interview date cannot be in the past.";
    }
    
    if (!empty($interview_date) && !empty($joining_date)) {
        $min_joining_date = date('Y-m-d', strtotime($interview_date . ' +1 day'));
        if ($joining_date < $min_joining_date) {
            $errors[] = "Joining date must be at least 1 day after the interview date.";
        }
    }
    
    // PIN code validation
    if (!empty($current_pin) && !preg_match('/^[0-9]{6}$/', $current_pin)) {
        $errors[] = "Current PIN code must be exactly 6 digits.";
    }
    
    if (!empty($permanent_pin) && !preg_match('/^[0-9]{6}$/', $permanent_pin)) {
        $errors[] = "Permanent PIN code must be exactly 6 digits.";
    }
    
    // File size validation (1MB = 1048576 bytes)
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        if ($_FILES['resume']['size'] > 1048576) {
            $errors[] = "Resume file size must be under 1MB.";
        }
    }
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        if ($_FILES['photo']['size'] > 1048576) {
            $errors[] = "Photo file size must be under 1MB.";
        }
    }
    
    // Reference contact validation
    if (!empty($ref_contact_1) && !preg_match('/^[0-9]{10}$/', $ref_contact_1)) {
        $errors[] = "Reference 1 contact must be exactly 10 digits.";
    }
    if (!empty($ref_contact_2) && !preg_match('/^[0-9]{10}$/', $ref_contact_2)) {
        $errors[] = "Reference 2 contact must be exactly 10 digits.";
    }
    if (!empty($ref_contact_3) && !preg_match('/^[0-9]{10}$/', $ref_contact_3)) {
        $errors[] = "Reference 3 contact must be exactly 10 digits.";
    }
    
    if (empty($errors)) {
        // Handle file uploads
        $resume_path = '';
        $photo_path = '';
        
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
            $resume_path = 'uploads/resumes/' . time() . '_' . $_FILES['resume']['name'];
            move_uploaded_file($_FILES['resume']['tmp_name'], $resume_path);
        }
        
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $photo_path = 'uploads/photos/' . time() . '_' . $_FILES['photo']['name'];
            move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
        }



        

        

        
        
        
        // Insert into database
      $sql = "INSERT INTO candidates (
    first_name, last_name, introduction_summary, gender, date_of_birth,
    phone_number, alternate_number, role_applying_for, opportunity_type,
    highest_qualification, year_of_passing, current_course, current_stage,
    current_city, current_state, current_pin, permanent_city, permanent_state,
    permanent_pin, interview_date, joining_date, expected_salary, experience_status,
    experience_years, last_salary, organisation, designation, laptop_config,
    english, hindi, facebook, instagram, youtube, linkedin,
    description,
    ref_name_1, ref_relation_1, ref_designation_1, ref_contact_1,
    ref_name_2, ref_relation_2, ref_designation_2, ref_contact_2,
    ref_name_3, ref_relation_3, ref_designation_3, ref_contact_3,
    resume_path, photo_path,
    created_at
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    str_repeat('s', 49),
    $first_name, $last_name, $introduction_summary, $gender, $date_of_birth,
    $phone_number, $alternate_number, $role_applying_for, $opportunity_type,
    $highest_qualification, $year_of_passing, $current_course, $current_stage,
    $current_city, $current_state, $current_pin, $permanent_city, $permanent_state,
    $permanent_pin, $interview_date, $joining_date, $expected_salary, $experience_status,
    $experience_years, $last_salary, $organisation, $designation, $laptop_config,
    $english, $hindi, $facebook, $instagram, $youtube, $linkedin,
    $description, $resume_path, $photo_path,
    $ref_name_1, $ref_relation_1, $ref_designation_1, $ref_contact_1,
    $ref_name_2, $ref_relation_2, $ref_designation_2, $ref_contact_2,
    $ref_name_3, $ref_relation_3, $ref_designation_3, $ref_contact_3
);

if ($stmt->execute()) {
    $success_message = "Candidate inserted successfully.";
} else {
    echo "Error: " . $stmt->error;
}


        $stmt->close();
    } else {
        $error_message = implode("<br>", $errors);
    }
}

$conn->close();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Candidate Details Form</title>
<link rel="stylesheet" href="candidate.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<script>
// Replace the entire script section in your HTML with this:

function showLoadingModal() {
    document.getElementById("submitModal").style.display = "block";
    document.getElementById("modalTitle").textContent = "Submitting...";
    document.getElementById("modalMessage").textContent = "Please wait while we process your form.";
    document.getElementById("modalLoader").style.display = "block";
    document.getElementById("modalMessage").classList.remove("success");
}

function showSuccessModal() {
    document.getElementById("submitModal").style.display = "block";
    document.getElementById("modalTitle").textContent = "Success!";
    document.getElementById("modalMessage").textContent = "Your form has been submitted successfully!";
    document.getElementById("modalLoader").style.display = "none";
    document.getElementById("modalMessage").classList.add("success");
}

function hideModal() {
    document.getElementById("submitModal").style.display = "none";
}

   








function validateDates() {
    var interviewDate = document.getElementsByName('interview_date')[0].value;
    var joiningDate = document.getElementsByName('joining_date')[0].value;
    var today = new Date().toISOString().split('T')[0];
    
    // Set minimum interview date to today
    var interviewInput = document.getElementsByName('interview_date')[0];
    if (interviewInput) {
        interviewInput.setAttribute('min', today);
    }
    
    if (interviewDate) {
        var minJoiningDate = new Date(interviewDate);
        minJoiningDate.setDate(minJoiningDate.getDate() + 1);
        var minJoiningDateStr = minJoiningDate.toISOString().split('T')[0];
        
        var joiningInput = document.getElementsByName('joining_date')[0];
        if (joiningInput) {
            joiningInput.setAttribute('min', minJoiningDateStr);
        }
        
        if (joiningDate && joiningDate < minJoiningDateStr) {
            alert('Joining date must be at least 1 day after the interview date.');
            joiningInput.value = '';
        }
    }
}

function validatePhoneNumber(input) {
    // Remove all non-numeric characters
    input.value = input.value.replace(/[^0-9]/g, '');
    // Limit to 10 digits
    if (input.value.length > 10) {
        input.value = input.value.slice(0, 10);
    }
}

function validatePinCode(input) {
    // Remove all non-numeric characters
    input.value = input.value.replace(/[^0-9]/g, '');
    // Limit to 6 digits
    if (input.value.length > 6) {
        input.value = input.value.slice(0, 6);
    }
}

function validateYear(input) {
    // Remove all non-numeric characters
    input.value = input.value.replace(/[^0-9]/g, '');
    // Limit to 4 digits
    if (input.value.length > 4) {
        input.value = input.value.slice(0, 4);
    }
}

function validateFileSize(input, type) {
    const maxSize = 1048576; // 1MB in bytes
    const file = input.files[0];
    
    if (file && file.size > maxSize) {
        alert(`${type === 'resume' ? 'Resume' : 'Photo'} file size must be under 1MB. Please select a smaller file.`);
        input.value = '';
        return false;
    }
    return true;
}

function countCharacters(input) {
    var maxLength = input.getAttribute('maxlength');
    var currentLength = input.value.length;
    var counter = input.parentNode.querySelector('.char-count');
    
    if (counter && maxLength) {
        counter.textContent = currentLength + '/' + maxLength + ' characters';
    }
}

 function validateForm() {
        let fname = document.getElementById('first_name').value.trim();
        let lname = document.getElementById('last_name').value.trim();
     

        if (fname.length < 3) {
            alert("First Name must be at least 3 characters long");
            return false;
        }
        if (lname.length < 3) {
            alert("Last Name must be at least 3 characters long");
            return false;
        }
      
        return true;
    }

    function showSubmittingModal(e) {
    if (!validateForm()) return false; // run your existing validation

    // Show the modal
    showLoadingModal();

    // Allow form to actually submit after modal shows
    return true;
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing scripts...');
    
    // Initialize all character counters for elements with maxlength
    document.querySelectorAll('textarea[maxlength], input[maxlength]').forEach(function(element) {
        // Set initial count
        countCharacters(element);
        
        // Add event listener if not already present
        if (!element.getAttribute('oninput')) {
            element.addEventListener('input', function() {
                countCharacters(element);
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
    <?php if (isset($success_message)) { ?>
        document.getElementById("submitModal").style.display = "block";
        document.getElementById("modalTitle").textContent = "Success!";
        document.getElementById("modalMessage").textContent = "<?php echo $success_message; ?>";
        document.getElementById("modalLoader").style.display = "none";
        document.getElementById("modalMessage").classList.add("success");
        setTimeout(() => {
            document.getElementById("submitModal").style.display = "none";
        }, 2500);
    <?php } ?>
});
    
    // Initialize date constraints
    validateDates();
    
    // Add event listener for interview date changes
    var interviewInput = document.getElementsByName('interview_date')[0];
    if (interviewInput) {
        interviewInput.addEventListener('change', validateDates);
    }
    
    // Add event listener for joining date changes
    var joiningInput = document.getElementsByName('joining_date')[0];
    if (joiningInput) {
        joiningInput.addEventListener('change', validateDates);
    }
    
    console.log('Script initialization complete');
});

</script>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-user-plus"></i> Candidate Details Form</h2>
    
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST"  id="myForm" enctype="multipart/form-data" onsubmit="return showSubmittingModal(event)">
        <div class="form-row">
            <div class="form-group half-width">
                <label><i class="fas fa-user"></i> First Name: <span class="required">*</span></label>
                <input type="text" name="first_name" required>
            </div>
            <div class="form-group half-width">
                <label><i class="fas fa-user"></i> Last Name: <span class="required">*</span></label>
                <input type="text" name="last_name" required>
            </div>
        </div>

        <div class="form-group">
            <label><i class="fas fa-info-circle"></i> Short Introduction Summary:</label>
            <input type="text" name="introduction_summary" maxlength="100" placeholder="Brief introduction about yourself (max 100 characters)" oninput="countCharacters(this)">
            <small class="char-count">0/100 characters</small>
        </div>

        <div class="form-row">
            <div class="form-group half-width">
                <label><i class="fas fa-venus-mars"></i> Gender: <span class="required">*</span></label>
                <select name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                    <option value="Prefer not to say">Prefer not to say</option>
                </select>
            </div>
            <div class="form-group half-width">
                <label><i class="fas fa-birthday-cake"></i> Date of Birth: <span class="required">*</span></label>
               <input type="date" name= "date_of_birth"max="<?php echo date('Y-m-d'); ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group half-width">
                <label><i class="fas fa-phone"></i> Phone Number: <span class="required">*</span></label>
                <input type="tel" name="phone_number" maxlength="10" placeholder="Enter 10-digit mobile number" required oninput="validatePhoneNumber(this)">
            </div>
            <div class="form-group half-width">
                <label><i class="fas fa-phone-alt"></i> Alternate Number:</label>
                <input type="tel" name="alternate_number" maxlength="10" placeholder="Enter 10-digit alternate number" oninput="validatePhoneNumber(this)">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group half-width">
               
            <label for="'role_applying_for">Role Applying For:</label>
            <select id="'role_applying_for" name="role_applying_for" required>
                <option value="">Select Role</option>
                <?php
                $roles = [
                    "Backend Developer",
                    "Cloud Engineer",
                    "Data Analyst",
                    "Database Administrator",
                    "DevOps Engineer",
                    "Frontend Developer",
                    "Full Stack Developer",
                    "IT Support Engineer",
                    "QA Tester",
                    "UI/UX Designer"
                ];
                sort($roles);
                foreach ($roles as $role) {
                    echo "<option value='$role'>$role</option>";
                }
                ?>
            </select>
        </div>
            <div class="form-group half-width">
                <label><i class="fas fa-clock"></i> Type of Opportunity: <span class="required">*</span></label>
                <select name="opportunity_type" required>
                    <option value="">Select Opportunity Type</option>
                    <option value="Full Time">Full Time</option>
                    <option value="Part Time">Part Time</option>
                    <option value="Internship">Internship</option>
                    <option value="Any of the above">Any of the above</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group half-width">
                <label><i class="fas fa-graduation-cap"></i> Highest Qualification: <span class="required">*</span></label>
                <input type="text" name="highest_qualification" required>
            </div>
            <div class="form-group half-width">
                <label><i class="fas fa-calendar-alt"></i> Year of Passing: <span class="required">*</span></label>
                <input type="text" name="year_of_passing" maxlength="4" placeholder="e.g., 2024" required oninput="validateYear(this)">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group half-width">
                <label><i class="fas fa-book-open"></i> Currently undergoing course:</label>
                <input type="text" name="current_course">
            </div>
            <div class="form-group half-width">
                <label><i class="fas fa-layer-group"></i> Current stage:</label>
                <input type="text" name="current_stage" placeholder="e.g., 2nd Year, Final Semester">
            </div>
        </div>
        
        <h3><i class="fas fa-map-marker-alt"></i> Current Address <span class="required">*</span></h3>
        <div class="form-row">
            <div class="form-group third-width">
                <label><i class="fas fa-city"></i> City: <span class="required">*</span></label>
                <input type="text" name="current_city" required>
            </div>
            <div class="form-group third-width">
                   <label for="current_state">Current State:</label>
            <select id="current_state" name="current_state" required>
                <option value="">Select State/UT</option>
                <?php
                $states = [
                    "Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chhattisgarh","Goa","Gujarat",
                    "Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kerala","Madhya Pradesh",
                    "Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Punjab",
                    "Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh",
                    "Uttarakhand","West Bengal","Andaman and Nicobar Islands","Chandigarh",
                    "Dadra and Nagar Haveli and Daman and Diu","Delhi","Jammu and Kashmir","Ladakh","Lakshadweep","Puducherry"
                ];
                foreach ($states as $state) {
                    echo "<option value='$state'>$state</option>";
                }
                ?>
            </select>
            </div>
            <div class="form-group third-width">
                <label><i class="fas fa-mail-bulk"></i> PIN Code: <span class="required">*</span></label>
                <input type="text" name="current_pin" maxlength="6" placeholder="6-digit PIN" required oninput="validatePinCode(this)">
            </div>
        </div>

        <h3><i class="fas fa-home"></i> Permanent Address <span class="required">*</span></h3>
        <div class="form-row">
            <div class="form-group third-width">
                <label><i class="fas fa-city"></i> City: <span class="required">*</span></label>
                <input type="text" name="permanent_city" required>
            </div>
            <div class="form-group third-width">
              <label for="permanent_state">Permanent State:</label>
            <select id="permanent_state" name="permanent_state" required>
                <option value="">Select State/UT</option>
                <?php
                $states = [
                    "Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chhattisgarh","Goa","Gujarat",
                    "Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kerala","Madhya Pradesh",
                    "Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Punjab",
                    "Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh",
                    "Uttarakhand","West Bengal","Andaman and Nicobar Islands","Chandigarh",
                    "Dadra and Nagar Haveli and Daman and Diu","Delhi","Jammu and Kashmir","Ladakh","Lakshadweep","Puducherry"
                ];
                foreach ($states as $state) {
                    echo "<option value='$state'>$state</option>";
                }
                ?>
            </select>
            </div>
            <div class="form-group third-width">
                <label><i class="fas fa-mail-bulk"></i> PIN Code: <span class="required">*</span></label>
                <input type="text" name="permanent_pin" maxlength="6" placeholder="6-digit PIN" required oninput="validatePinCode(this)">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group half-width">
                <label><i class="fas fa-calendar"></i> Tentative Date of Interview:*</label>
                <input type="date" name="interview_date" required onchange="validateDates()">
            </div>
            <div class="form-group half-width">
                <label><i class="fas fa-calendar-check"></i> Earliest Joining Date:*</label>
                <input type="date" name="joining_date" required onchange="validateDates()">
            </div>
        </div>
        
        <div class="form-row">
             <div class="form-group half-width">
            <label><i class="fas fa-money-bill-wave"></i> Expected Salary/ Stipend: <span class="required">*</span></label>
            <input type="text" name="expected_salary" required>
            </div>
                 <div class="form-group half-width">
            <label><i class="fas fa-chart-line"></i> Experience Level:</label>
            <select name="experience_status" required>
                <option value="">Select</option>
                <option value="Experienced">Experienced</option>
                <option value="Fresher">Fresher</option>
            </select>
        </div>
            
        </div>
        
       
        
        <div class="form-row">
            <div class="form-group half-width">
                <label><i class="fas fa-clock"></i> Years of Experience:</label>
                <input type="text" name="experience_years">
            </div>
            <div class="form-group half-width">
                <label><i class="fas fa-money-bill"></i> Last Salary/Stipend:</label>
                <input type="text" name="last_salary">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group half-width">
                <label><i class="fas fa-building"></i> Organisation name (if experienced):</label>
                <input type="text" name="organisation">
            </div>
            <div class="form-group half-width">
                <label><i class="fas fa-id-badge"></i> Designation (if experienced):</label>
                <input type="text" name="designation">
            </div>
        </div>
    
        
        <div class="form-group">
            <label><i class="fas fa-laptop"></i> Laptop Configuration (Processor and RAM): <span class="required">*</span></label>
            <input type="text" name="laptop_config" required>
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-language"></i> Language Comfort</label>
            <div class="language-section">
                <div class="language-item">
                    <span class="language-label">English:</span>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="english" value="Excellent" required>
                            <span class="radio-custom">
                                <i class="fas fa-star"></i>
                                <span class="radio-text">Excellent</span>
                            </span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="english" value="Good">
                            <span class="radio-custom">
                                <i class="fas fa-thumbs-up"></i>
                                <span class="radio-text">Good</span>
                            </span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="english" value="Average">
                            <span class="radio-custom">
                                <i class="fas fa-minus-circle"></i>
                                <span class="radio-text">Average</span>
                            </span>
                        </label>
                    </div>
                </div>
                
                <div class="language-item">
                    <span class="language-label">Hindi:</span>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="hindi" value="Excellent" required>
                            <span class="radio-custom">
                                <i class="fas fa-star"></i>
                                <span class="radio-text">Excellent</span>
                            </span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="hindi" value="Good">
                            <span class="radio-custom">
                                <i class="fas fa-thumbs-up"></i>
                                <span class="radio-text">Good</span>
                            </span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="hindi" value="Average">
                            <span class="radio-custom">
                                <i class="fas fa-minus-circle"></i>
                                <span class="radio-text">Average</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-share-alt"></i> Social Media Presence</label>
            <div class="social-media-grid">
                <div class="social-item">
                    <label><i class="fab fa-facebook"></i> Facebook:*</label>
                    <select name="facebook" required>
                        <option value="">Select</option>
                        <option value="Active">Active</option>
                        <option value="Not active">Not active</option>
                        <option value="Not present">Not present</option>
                    </select>
                </div>
                <div class="social-item">
                    <label><i class="fab fa-instagram"></i> Instagram:*</label>
                    <select name="instagram" required>
                        <option value="">Select</option>
                        <option value="Active">Active</option>
                        <option value="Not active">Not active</option>
                        <option value="Not present">Not present</option>
                    </select>
                </div>
                <div class="social-item">
                    <label><i class="fab fa-youtube"></i> YouTube:*</label>
                    <select name="youtube" required>
                        <option value="">Select</option>
                        <option value="Active">Active</option>
                        <option value="Not active">Not active</option>
                        <option value="Not present">Not present</option>
                    </select>
                </div>
                <div class="social-item">
                    <label><i class="fab fa-linkedin"></i> LinkedIn:*</label>
                    <select name="linkedin" required>
                        <option value="">Select</option>
                        <option value="Active">Active</option>
                        <option value="Not active">Not active</option>
                        <option value="Not present">Not present</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group half-width">
                <label><i class="fas fa-file-pdf"></i> Resume (PDF/DOC/DOCX):* <span class="required">*</span></label>
                <input type="file" name="resume" accept=".pdf,.doc,.docx" required onchange="validateFileSize(this, 'resume')">
                <small class="file-info">Maximum file size: 1MB</small>
            </div>
            <div class="form-group half-width">
                <label><i class="fas fa-camera"></i> Recent photograph:* <span class="required">*</span></label>
                <input type="file" name="photo" accept="image/*" required onchange="validateFileSize(this, 'photo')">
                <small class="file-info">Maximum file size: 1MB</small>
            </div>
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-align-left"></i> Short Description / Additional Information:</label>
            <textarea name="description" rows="4" placeholder="Tell us about yourself, your interests, achievements, or any additional information you'd like to share..." maxlength="500" oninput="countCharacters(this)"></textarea>
            <small class="char-count">0/500 characters</small>
        </div>

        <h3><i class="fas fa-users"></i> References</h3>
        <div class="reference-section">
            <h4>Reference 1:</h4>
            <div class="form-row">
                <div class="form-group quarter-width">
                    <label><i class="fas fa-user"></i> Name:</label>
                    <input type="text" name="ref_name_1">
                </div>
                <div class="form-group quarter-width">
                    <label><i class="fas fa-heart"></i> Relation:</label>
                    <input type="text" name="ref_relation_1">
                </div>
                <div class="form-group quarter-width">
                    <label><i class="fas fa-id-badge"></i> Designation:</label>
                    <input type="text" name="ref_designation_1">
                </div>
                <div class="form-group quarter-width">
                    <label><i class="fas fa-phone"></i> Contact:</label>
                    <input type="tel" name="ref_contact_1" maxlength="10" placeholder="10-digit number" oninput="validatePhoneNumber(this)">
                </div>
            </div>

            <h4>Reference 2:</h4>
            <div class="form-row">
                <div class="form-group quarter-width">
                    <label><i class="fas fa-user"></i> Name:</label>
                    <input type="text" name="ref_name_2">
                </div>
                <div class="form-group quarter-width">
                    <label><i class="fas fa-heart"></i> Relation:</label>
                    <input type="text" name="ref_relation_2">
                </div>
                <div class="form-group quarter-width">
                    <label><i class="fas fa-id-badge"></i> Designation:</label>
                    <input type="text" name="ref_designation_2">
                </div>
                <div class="form-group quarter-width">
                    <label><i class="fas fa-phone"></i> Contact:</label>
                    <input type="tel" name="ref_contact_2" maxlength="10" placeholder="10-digit number" oninput="validatePhoneNumber(this)">
                </div>
            </div>

            <h4>Reference 3:</h4>
            <div class="form-row">
                <div class="form-group quarter-width">
                    <label><i class="fas fa-user"></i> Name:</label>
                    <input type="text" name="ref_name_3">
                </div>
                <div class="form-group quarter-width">
                    <label><i class="fas fa-heart"></i> Relation:</label>
                    <input type="text" name="ref_relation_3">
                </div>
                <div class="form-group quarter-width">
                    <label><i class="fas fa-id-badge"></i> Designation:</label>
                    <input type="text" name="ref_designation_3">
                </div>
                <div class="form-group quarter-width">
                    <label><i class="fas fa-phone"></i> Contact:</label>
                    <input type="tel" name="ref_contact_3" maxlength="10" placeholder="10-digit number" oninput="validatePhoneNumber(this)">
                </div>
            </div>
        </div>
        
        <button type="submit">
            <i class="fas fa-paper-plane"></i> Submit Application
        </button>
    </form>
</div>
<div id="submitModal">
    <div class="submitModalContent">
        <h3 id="modalTitle" class="modalTitle">Submitting...</h3>
        <div id="modalLoader" class="loader"></div>
        <p id="modalMessage" class="modalMessage">Please wait while we process your form.</p>
    </div>
</div>

<div id="overlay">
    <div class="spinner"></div>
    <div id="overlayText">Submitting, please wait...</div>
</div>

<script>
    const form = document.getElementById("myForm");
    const overlay = document.getElementById("overlay");
    const overlayText = document.getElementById("overlayText");

    form.addEventListener("submit", function(e) {
        overlay.style.display = "flex"; // Show overlay

        // Optional: Simulate backend processing
        setTimeout(() => {
            overlayText.textContent = "Thank you for submitting!";
            document.querySelector(".spinner").classList.add("hidden");

            // Hide overlay after 2s (optional)
            setTimeout(() => {
                overlay.style.display = "none";
                document.querySelector(".spinner").classList.remove("hidden");
                overlayText.textContent = "Submitting, please wait...";
            }, 2000);

        }, 2000);
    });
</script>
<script>
// Initialize date validations on page load
window.onload = function() {
    validateDates();
};
</script>

<script>
function updateCount(fieldId, counterId) {
    const field = document.getElementById(fieldId);
    const counter = document.getElementById(counterId);
    counter.textContent = field.value.length;
}
</script>

</body>
</html>