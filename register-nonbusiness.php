<?php
ob_start();
session_start();
include 'common/header.php';
include 'dbconfigf/dbconst2025.php';
 // DB connection

// Helper: Generate a new CAPTCHA code and store in session
function generate_captcha($length = 6) {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // avoid 0,O,1,I
    $code = '';
    for ($i = 0; $i < $length; $i++) { $code .= $chars[random_int(0, strlen($chars)-1)]; }
    $_SESSION['captcha_code'] = $code;
    return $code;
}

// AJAX: Refresh captcha request
if (isset($_GET['refresh_captcha'])) {
    header('Content-Type: application/json');
    echo json_encode(['code' => generate_captcha()]);
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect & sanitize
    $name      = trim($_POST['name'] ?? '');
    $whatsapp  = trim($_POST['whatsapp'] ?? '');
    $alt_phone = trim($_POST['alternate'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $address   = trim($_POST['address'] ?? ''); // New address field
    $state     = trim($_POST['state'] ?? '');
    $city      = trim($_POST['city'] ?? '');
    $pincode   = trim($_POST['pincode'] ?? '');
    $dob       = trim($_POST['dob'] ?? '');
    $occupation= trim($_POST['occupation'] ?? '');
    $captcha   = trim($_POST['captcha'] ?? '');

    // Server-side validation
    if ($name === '')            $errors[] = 'Name is required.';
    if ($whatsapp === '')        $errors[] = 'WhatsApp No. is required.';
    if (!preg_match('/^[6-9][0-9]{9}$/', $whatsapp)) $errors[] = 'WhatsApp No. must be a valid 10-digit Indian mobile number.';
    if ($state === '')           $errors[] = 'State is required.';
    if ($city === '')            $errors[] = 'City is required.';
    if ($pincode === '' || !preg_match('/^[1-9][0-9]{5}$/', $pincode)) $errors[] = 'PIN Code must be a valid 6-digit number.';
    if ($dob === '')             $errors[] = 'Date of Birth is required.';
    if ($occupation === '')      $errors[] = 'Occupation is required.';

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email ID is invalid.';
    }

    // Optional address validation (if provided, check length)
    if ($address !== '' && strlen($address) > 500) {
        $errors[] = 'Address must be less than 500 characters.';
    }

    // CAPTCHA check
    if ($captcha === '') {
        $errors[] = 'Captcha is required.';
    } else {
        $session_code = $_SESSION['captcha_code'] ?? '';
        if (strcasecmp($captcha, $session_code) !== 0) {
            $errors[] = 'Captcha does not match.';
        }
    }

    if (empty($errors)) {
    // ✅ Check if WhatsApp already exists
    $check = $conn->prepare("SELECT user_id FROM non_business_users WHERE whatsapp = ?");
    $check->bind_param("s", $whatsapp);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $errors[] = "This WhatsApp number is already registered.";
    }
    $check->close();
}

    if (empty($errors)) {
        // Insert into DB (table: users_public or non_business_users)
        // Updated query to include address field
        $stmt = $conn->prepare("INSERT INTO non_business_users 
        (name, whatsapp, password_hash, alternate, email, address, state, city, pincode, dob, occupation, created_at) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?, NOW())");

        if (!$stmt) {
            $errors[] = 'DB Prepare failed: ' . $conn->error;
        } else {
            // hash the whatsapp number as default password
            $passwordHash = password_hash($whatsapp, PASSWORD_DEFAULT);

            $stmt->bind_param(
                'sssssssssss', // Added one more 's' for address
                $name,
                $whatsapp,
                $passwordHash,
                $alt_phone,
                $email,
                $address, // New address parameter
                $state,
                $city,
                $pincode,
                $dob,
                $occupation
            );

           if ($stmt->execute()) {
    $success = true;
    generate_captcha();

    // Redirect to avoid form resubmission and clear POST data
    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
    exit;
} else {
    $errors[] = 'DB Insert failed: ' . $stmt->error;
}
            $stmt->close();
        }
    }
    
    // Regenerate captcha after any submission (success or error)
    generate_captcha();
} else {
    // First load: generate a captcha
    if (!isset($_SESSION['captcha_code'])) {
        generate_captcha();
    }
}

// Current captcha code to render
$currentCaptcha = $_SESSION['captcha_code'] ?? generate_captcha();

$success = isset($_GET['success']) ? true : false;

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Register (Non‑Business User)</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --brand:#0aa54a; /* green accents */
  --brand-dark:#07833b;
  --bg:#f5f7fb;
  --text:#222;
  --muted:#6b7280;
  --card:#fff;
  --ring: rgba(10,165,74,0.15);
}
* {
    box-sizing: border-box;
}
.page-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh; /* full viewport height */
  padding: 20px; /* prevent edges on small screens */
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #c9b6ff, #f2d6d6);
    min-height: 100vh;
}

.container{width:760px;max-width:94%;background:var(--card);margin:32px 0 64px;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.06);overflow:hidden}
.header{padding:22px 26px;border-bottom:1px solid #eef2f7;display:flex;justify-content:space-between;align-items:center}
.header h1{font-size:22px;margin:0}
.header small{color:var(--muted)}
.form{padding:24px 26px}
.row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px}
label{font-size:13px;font-weight:600;display:block;margin:10px 0 6px}
.required::after{content:' *';color:#ef4444}
.hint{font-size:12px;color:var(--muted);margin:6px 0 0}
.input, select, textarea{width:100%;padding:12px 14px;border:2px solid #e5e7eb;border-radius:12px;font-size:14px;outline:none;transition:border-color .2s, box-shadow .2s;background:#fff;font-family:inherit}
.input:focus, select:focus, textarea:focus{border-color:var(--brand);box-shadow:0 0 0 4px var(--ring)}
select:invalid{color:#9ca3af}
textarea{resize:vertical;min-height:80px;max-height:120px}
.button{width:100%;padding:14px 16px;margin-top:10px;background:var(--brand);color:#fff;border:0;border-radius:14px;font-weight:700;cursor:pointer;transition:background .2s, transform .02s}
.button:hover{background:var(--brand-dark)}
.button:active{transform:translateY(1px)}
.badge{display:inline-block;padding:2px 8px;border-radius:999px;background:#ecfdf5;color:#065f46;font-size:11px;font-weight:700}
.notice{background:#f8fafc;border:1px dashed #cbd5e1;padding:10px 12px;border-radius:10px;color:#334155;font-size:12px}
.errors{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:12px 14px;border-radius:10px;margin-bottom:14px}
.success{background:#ecfdf5;border:1px solid #bbf7d0;color:#065f46;padding:12px 14px;border-radius:10px;margin-bottom:14px}

/* Captcha */
.captcha-wrap{display:flex;align-items:center;gap:12px;margin-top:8px}
.captcha-box{letter-spacing:6px;font-weight:800;font-size:22px;padding:12px 16px;border-radius:10px;background:repeating-linear-gradient( 45deg, #f1f5f9, #f1f5f9 10px, #e2e8f0 10px, #e2e8f0 20px );border:2px solid #e5e7eb;user-select:none;min-width:120px;text-align:center}
.refresh{padding:10px 16px;border-radius:10px;border:2px solid #e5e7eb;background:#fff;cursor:pointer;font-size:12px;font-weight:600;transition:all 0.2s ease}
.refresh:hover{border-color:var(--brand);background:#f8fffe;color:var(--brand)}
.refresh:active{transform:translateY(1px)}

@media (max-width:720px){
  .row, .row-3{grid-template-columns:1fr}
  .captcha-wrap{flex-direction:column;align-items:stretch;gap:8px}
  .captcha-box{min-width:auto}
}
</style>
</head>
<body>
    <div class="page-wrapper">

  <div class="container">
    <div class="header">
      <div>
        <h1>Register – Non‑Business User</h1>
        <small>Public profile (no business listing)</small>
      </div>
      <span class="badge">Free</span>
    </div>

    <div class="form">
      <?php if (!empty($errors)): ?>
        <div class="errors">
          <strong>Please fix the following:</strong>
          <ul>
            <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
          </ul>
        </div>
      <?php elseif ($success): ?>
        <div class="success">
          ✅ Registration submitted successfully. We will review and publish details where applicable.
        </div>
      <?php endif; ?>

      <form method="POST" action="" id="nonBizForm" novalidate>
        <label class="required">Name</label>
        <input class="input" type="text" name="name" placeholder="Full name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required />

        <div class="row">
          <div>
            <label class="required">WhatsApp No.</label>
            <input class="input" type="text" name="whatsapp" placeholder="10-digit mobile" maxlength="10" inputmode="numeric" value="<?= htmlspecialchars($_POST['whatsapp'] ?? '') ?>" required />
            <p class="hint">Confidential and for internal purpose only</p>
          </div>
          <div>
            <label>Alternate Contact No.</label>
            <input class="input" type="text" name="alternate" placeholder="Optional alternate number" maxlength="10" inputmode="numeric" value="<?= htmlspecialchars($_POST['alternate'] ?? '') ?>" />
            <p class="hint">Will be visible on website after approval</p>
          </div>
        </div>

        <label>Email ID</label>
        <input class="input" type="email" name="email" placeholder="name@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />

        <label>Address</label>
        <textarea class="input" name="address" placeholder="Enter your full address (optional)" maxlength="500"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
        <p class="hint">Optional field - will be visible on website after approval</p>

        <div class="row">
  <div>
    <label class="required">State</label>
    <select class="input" name="state" id="state" required>
      <option value="" disabled selected>Select State</option>
      <?php
      $states = json_decode(file_get_contents("get_states.php"), true);
      foreach ($states as $st) {
          $selected = (isset($_POST['state']) && $_POST['state'] == $st['id']) ? "selected" : "";
          echo "<option value='{$st['id']}' $selected>".htmlspecialchars($st['state_name'])."</option>";
      }
      ?>
    </select>
  </div>

  <div>
    <label class="required">City</label>
    <select class="input" name="city" id="city" required>
      <option value="" disabled selected>Select City</option>
    </select>
  </div>
</div>

        <div class="row">
          <div>
            <label class="required">PIN Code</label>
            <input class="input" type="text" name="pincode" placeholder="6-digit PIN" maxlength="6" inputmode="numeric" value="<?= htmlspecialchars($_POST['pincode'] ?? '') ?>" required />
          </div>
          <div>
            <label class="required">Date of Birth</label>
            <input class="input" type="date" name="dob" value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>" required />
          </div>
        </div>

        <label class="required">Occupation</label>
        <select class="input" name="occupation" required>
          <option value="" selected disabled>Please Select</option>
          <option <?= ($_POST['occupation'] ?? '') === 'Student' ? 'selected' : '' ?>>Student</option>
          <option <?= ($_POST['occupation'] ?? '') === 'Salaried' ? 'selected' : '' ?>>Salaried</option>
          <option <?= ($_POST['occupation'] ?? '') === 'Self‑Employed' ? 'selected' : '' ?>>Self‑Employed</option>
          <option <?= ($_POST['occupation'] ?? '') === 'Business Owner' ? 'selected' : '' ?>>Business Owner</option>
          <option <?= ($_POST['occupation'] ?? '') === 'Homemaker' ? 'selected' : '' ?>>Homemaker</option>
          <option <?= ($_POST['occupation'] ?? '') === 'Retired' ? 'selected' : '' ?>>Retired</option>
          <option <?= ($_POST['occupation'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
        </select>

        <label class="required">Captcha</label>
        <div class="captcha-wrap">
          <div class="captcha-box" id="captchaBox"><?= htmlspecialchars($currentCaptcha) ?></div>
          <button type="button" class="refresh" id="refreshCaptcha">🔄 Refresh</button>
        </div>
        <input class="input" type="text" name="captcha" placeholder="Enter the code above" maxlength="6" required style="margin-top:8px" />

        <button class="button" type="submit">Submit</button>
      </form>

      <p class="hint" style="margin-top:16px">By submitting this form you agree to our terms and privacy policy.</p>

      <div class="notice" style="margin-top:10px">
        <strong>Note:</strong> Only your <em>Alternate Contact</em> and <em>Address</em> may be shown publicly after approval. WhatsApp numbers are kept private.
      </div>
    </div>
  </div>

<script>
// Basic state → city mapping (extend as needed)
const stateCities = {
  'Andhra Pradesh': ['Visakhapatnam','Vijayawada','Guntur','Nellore','Tirupati'],
  'Bihar': ['Patna','Gaya','Muzaffarpur','Bhagalpur'],
  'Delhi': ['New Delhi','Dwarka','Rohini','Saket'],
  'Gujarat': ['Ahmedabad','Surat','Vadodara','Rajkot'],
  'Karnataka': ['Bengaluru','Mysuru','Mangaluru','Hubballi'],
  'Maharashtra': ['Mumbai','Pune','Nagpur','Nashik','Thane'],
  'Tamil Nadu': ['Chennai','Coimbatore','Madurai','Salem'],
  'Telangana': ['Hyderabad','Warangal','Nizamabad'],
  'Uttar Pradesh': ['Lucknow','Kanpur','Noida','Ghaziabad','Varanasi'],
  'West Bengal': ['Kolkata','Howrah','Siliguri','Durgapur'],
  'Other': ['Other']
};

const stateSel = document.getElementById('state');
const citySel  = document.getElementById('city');

function populateStates(){
  Object.keys(stateCities).forEach(st => {
    const opt = document.createElement('option');
    opt.value = st; opt.textContent = st; 
    // Restore selected state if form was submitted with errors
    <?php if (isset($_POST['state'])): ?>
    if (st === '<?= addslashes($_POST['state']) ?>') opt.selected = true;
    <?php endif; ?>
    stateSel.appendChild(opt);
  });
}

function populateCities(state){
  citySel.innerHTML = '<option value="" disabled selected>Select City</option>';
  (stateCities[state] || []).forEach(c => {
    const opt = document.createElement('option');
    opt.value = c; opt.textContent = c; 
    // Restore selected city if form was submitted with errors
    <?php if (isset($_POST['city'])): ?>
    if (c === '<?= addslashes($_POST['city']) ?>') opt.selected = true;
    <?php endif; ?>
    citySel.appendChild(opt);
  });
}

// Initialize dropdowns
populateStates();
<?php if (isset($_POST['state'])): ?>
populateCities('<?= addslashes($_POST['state']) ?>');
<?php endif; ?>

stateSel.addEventListener('change', e => populateCities(e.target.value));

// Client-side light validation
$('#nonBizForm').on('submit', function(e){
  const w = $('input[name="whatsapp"]').val().trim();
  const pin = $('input[name="pincode"]').val().trim();
  if(!/^[6-9][0-9]{9}$/.test(w)) { alert('Please enter a valid 10-digit WhatsApp number starting with 6-9.'); e.preventDefault(); return false; }
  if(!/^[1-9][0-9]{5}$/.test(pin)) { alert('Please enter a valid 6-digit PIN code.'); e.preventDefault(); return false; }
});

// CAPTCHA refresh - Fixed version
$('#refreshCaptcha').on('click', function(){
  const btn = $(this);
  btn.text('🔄 Loading...').prop('disabled', true);
  
  $.ajax({
    url: window.location.pathname + '?refresh_captcha=1',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response && response.code) {
        $('#captchaBox').text(response.code);
        $('input[name="captcha"]').val(''); // Clear captcha input
        console.log('Captcha refreshed successfully');
      } else {
        console.error('Invalid response format:', response);
        alert('Failed to refresh captcha. Please try again.');
      }
    },
    error: function(xhr, status, error) {
      console.error('CAPTCHA refresh failed:', error, xhr.responseText);
      alert('Failed to refresh captcha. Please reload the page.');
    },
    complete: function() {
      btn.text('🔄 Refresh').prop('disabled', false);
    }
  });
});

// Clear captcha input when user starts typing
$('input[name="captcha"]').on('input', function(){
  $(this).removeClass('error');
});
</script>

<?php if ($success): ?>
<script>
  // Reset the form fields after success
  document.getElementById("nonBizForm").reset();
</script>
<?php endif; ?>

<!--
SQL (MySQL/MariaDB) for the table used above:

CREATE TABLE non_business_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  whatsapp VARCHAR(15) NOT NULL,
  alternate VARCHAR(15) DEFAULT NULL,
  email VARCHAR(190) DEFAULT NULL,
  state VARCHAR(100) NOT NULL,
  city VARCHAR(120) NOT NULL,
  pincode VARCHAR(6) NOT NULL,
  dob DATE NOT NULL,
  occupation VARCHAR(60) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-->
</div>
<?php include 'common/footer.php'; ?>

</body>
</html>