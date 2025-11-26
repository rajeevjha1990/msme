<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'common/header.php';
include 'dbconfigf/dbconst2025.php';


// Get user ID from URL
$combined = isset($_GET['name']) ? urldecode($_GET['name']) : '';

if (empty($combined)) {
    echo "<script>window.location.href='browse-directory2.php';</script>";
    exit();
}

// Split by hyphen
$parts = explode('-', $combined);

// Last element = last 5 digits of WhatsApp
$whatsapp_last5 = array_pop($parts);

// Second last element = pincode
$pincode = array_pop($parts);

// Remaining parts = name
$name = implode(' ', $parts);

// Fetch user details using name, pincode, and last 5 digits of WhatsApp
$sql = "SELECT
            u.*,
            cm.city,
            bc.name AS category_name
        FROM users u
        LEFT JOIN city_master cm ON u.city = cm.cityid
        LEFT JOIN business_categories bc ON u.category = bc.id
        WHERE u.name = ?
          AND u.pincode = ?
          AND RIGHT(u.reference_id, 6) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $pincode, $whatsapp_last5);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: browse_directory.php");
    exit();
}

$user = $result->fetch_assoc();

$gallery = [];
if (in_array($user['type'], ['Trusted', 'TM'])) {
    $sqlGallery = "SELECT * FROM user_gallery WHERE user_id = ? ORDER BY uploaded_at DESC";
    $stmtGallery = $conn->prepare($sqlGallery);
    $stmtGallery->bind_param("i", $user['user_id']);
    $stmtGallery->execute();
    $gallery = $stmtGallery->get_result();
}





// --- SIMILAR PROFILES (same category) ---
// how many digits are in the URL suffix? Your SQL uses 6. Keep it consistent:
$suffixFromUrl = $whatsapp_last5;               // this is what you parsed from URL
// If your DB uses RIGHT(...,6), keep 6 in the WHERE; we also use dynamic length so it's safe.
$category = $user['category_name'];

// Fetch same-category profiles; also return ref suffix for link building
$sqlSimilar = "
    SELECT
        u.name,
        u.photo,
        u.pincode,
        bc.name AS category_name,
        RIGHT(u.reference_id, 6) AS ref_suffix
    FROM users u
    LEFT JOIN business_categories bc ON u.category = bc.id
    WHERE u.category = ?
      AND NOT (
          u.name = ?
          AND u.pincode = ?
          AND RIGHT(u.reference_id, CHAR_LENGTH(?)) = ?
      )
    LIMIT 20
";
$stmtSimilar = $conn->prepare($sqlSimilar);
$stmtSimilar->bind_param(
    "sssss",
    $category,              // same category
    $name,                  // current name (from URL)
    $pincode,               // current pincode (from URL)
    $suffixFromUrl,         // dynamic length matches what came in URL
    $suffixFromUrl
);
$stmtSimilar->execute();
$similar = $stmtSimilar->get_result();
// link to this same page
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/viewprofile.css">
<title><?php echo htmlspecialchars($user['name']); ?> - Profile</title>
</head>
<body>

<div class="profile-page">
    <!-- Header -->
    <section class="profile-header">
        <a href="browse-directory2.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Directory
        </a>

       <div class="profile-main"> <?php $photoPath = !empty($user['photo']) ? htmlspecialchars($user['photo']) : "uploads/photos/default.png"; ?> <img src="<?php echo $photoPath; ?>" alt="Profile Photo" class="profile-photo"> <h1 class="profile-name">
         <?php echo htmlspecialchars($user['name']); ?></h1> <p class="profile-category"><?php echo htmlspecialchars($user['category_name']); ?></p> </div> </section>

    <!-- Profile Content -->
     <section class="profile-content">

        <!-- Basic Information Card -->
        <div class="info-card">
            <h2 class="card-title"><i class="fas fa-user"></i> Basic Information</h2>

            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['name']); ?></span>
            </div>

            <div class="info-row">
                <span class="info-label">Category:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['category_name']); ?></span>
            </div>

            <div class="info-row">
                <span class="info-label">Organization:</span>
                <span class="info-value">
                    <?php echo !empty($user['entity_name']) ? htmlspecialchars($user['entity_name']) : '<span class="empty-value">Not specified</span>'; ?>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Industry:</span>
                <span class="info-value">
                    <?php echo !empty($user['nature']) ? htmlspecialchars($user['nature']) : '<span class="empty-value">Not specified</span>'; ?>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Team Size:</span>
                <span class="info-value">
                    <?php echo !empty($user['team_size']) ? htmlspecialchars($user['team_size']) : '<span class="empty-value">Not specified</span>'; ?>
                </span>
            </div>
        </div>
        <!-- Business Details Card -->
           <!-- Business Details Card -->
        <div class="info-card">
            <h2 class="card-title"><i class="fas fa-building"></i> Business Details</h2>

            <?php if(!empty($user['logo_path'])) { ?>
                <div class="logo-section">
                    <img src="<?php echo htmlspecialchars($user['logo_path']); ?>" alt="Company Logo" class="company-logo">
                </div>
            <?php } ?>

            <div class="info-row">
                <span class="info-label">Years in Business:</span>
                <span class="info-value">
                    <?php echo !empty($user['years_business']) ? htmlspecialchars($user['years_business']) : '<span class="empty-value">Not specified</span>'; ?>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Current Turnover:</span>
                <span class="info-value">
                    <?php echo !empty($user['turnover']) ? htmlspecialchars($user['turnover']) . ' INR' : '<span class="empty-value">Not specified</span>'; ?>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Description:</span>
                <span class="info-value">
                    <?php echo !empty($user['description']) ? htmlspecialchars($user['description']) : '<span class="empty-value">Not specified</span>'; ?>
                </span>
            </div>
        </div>
        <!-- Contact Information Card -->
        <div class="info-card contact-info">
            <h2 class="card-title"><i class="fas fa-address-book"></i> Contact Information</h2>

            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">
                    <?php if(!empty($user['email'])) { ?>
                        <a href="mailto:<?php echo $user['email']; ?>" style="color: #4a148c; text-decoration: none;">
                            <?php echo htmlspecialchars($user['email']); ?>
                        </a>
                    <?php } else { ?>
                        <span class="empty-value">Not provided</span>
                    <?php } ?>
                </span>
            </div>
            <div class="info-row">
    <span class="info-label">City:</span>
    <span class="info-value">
        <?php echo !empty($user['city']) ? htmlspecialchars($user['city']) : '<span class="empty-value">Not provided</span>'; ?>
    </span>
</div>
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value">
                    <?php echo !empty($user['address']) ? htmlspecialchars($user['address']) : '<span class="empty-value">Not provided</span>'; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Pin Code:</span>
                <span class="info-value">
                    <?php echo !empty($user['pincode']) ? htmlspecialchars($user['pincode']) : '<span class="empty-value">Not provided</span>'; ?>
                </span>
            </div>
          <div class="info-row">
    <span class="info-label">Contact Number:</span>
    <span class="info-value">
        <?php if(!empty($user['whatsapp'])):
            // Remove any non-numeric characters
            $whatsappNumber = preg_replace('/\D/', '', $user['whatsapp']);
        ?>
            <a href="https://wa.me/<?php echo $whatsappNumber; ?>" target="_blank" style="color: #4a148c; text-decoration: none;">
                <?php echo htmlspecialchars($user['whatsapp']); ?>
            </a>
        <?php else: ?>
            <span class="empty-value">Not provided</span>
        <?php endif; ?>
    </span>
</div>
        </div>
        <!-- Professional Network Card -->
        <div class="info-card">
            <h2 class="card-title"><i class="fas fa-network-wired"></i> Professional Network</h2>

            <div class="info-row">
                <span class="info-label">Backup Team Member:</span>
                <span class="info-value">
                    <?php echo !empty($user['backup_team_member']) ? htmlspecialchars($user['backup_team_member']) : '<span class="empty-value">Not specified</span>'; ?>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">BTM Contact:</span>
                <span class="info-value">
                    <?php echo !empty($user['btm_contact']) ? htmlspecialchars($user['btm_contact']) : '<span class="empty-value">Not specified</span>'; ?>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Networking Association:</span>
                <span class="info-value">
                    <?php echo !empty($user['networking_association']) ? htmlspecialchars($user['networking_association']) : '<span class="empty-value">Not specified</span>'; ?>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Alumni:</span>
                <span class="info-value">
                    <?php echo !empty($user['alumni']) ? htmlspecialchars($user['alumni']) : '<span class="empty-value">Not specified</span>'; ?>
                </span>
            </div>
        </div>

        <!-- Personal Interests Card -->
        <div class="info-card full-width-card">
            <h2 class="card-title"><i class="fas fa-heart"></i> Personal Interests</h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="info-row">
                    <span class="info-label">Hobbies:</span>
                    <span class="info-value">
                        <?php echo !empty($user['hobbies']) ? htmlspecialchars($user['hobbies']) : '<span class="empty-value">Not specified</span>'; ?>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Interest Areas:</span>
                    <span class="info-value">
                        <?php echo !empty($user['interest_area']) ? htmlspecialchars($user['interest_area']) : '<span class="empty-value">Not specified</span>'; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Social Media & Website Card -->
         <!-- Online Presence Card -->
        <div class="info-card full-width-card">
            <h2 class="card-title"><i class="fas fa-globe"></i> Online Presence</h2>

         <div class="info-row">
    <span class="info-label">Website:</span>
    <span class="info-value">
        <?php if(!empty($user['website'])) {
            $website = $user['website'];
            // Add https:// if missing
            if (!preg_match("~^(?:f|ht)tps?://~i", $website)) {
                $website = "https://" . $website;
            }
        ?>
            <a href="<?php echo htmlspecialchars($website); ?>" target="_blank" rel="noopener noreferrer" style="color: #4a148c; text-decoration: none;">
                <?php echo htmlspecialchars($user['website']); ?>
                <i class="fas fa-external-link-alt" style="margin-left: 5px; font-size: 0.8rem;"></i>
            </a>
        <?php } else { ?>
            <span class="empty-value">Not provided</span>
        <?php } ?>
    </span>
    <span class="info-label">Facebook:</span>
    <span class="info-value">
        <?php if(!empty($user['facebook'])) {
            $facebook = $user['facebook'];
            // Add https:// if missing
            if (!preg_match("~^(?:f|ht)tps?://~i", $facebook)) {
                $facebook = "https://" . $facebook;
            }
        ?>
            <a href="<?php echo htmlspecialchars($facebook); ?>" target="_blank" rel="noopener noreferrer" style="color: #4a148c; text-decoration: none;">
                <?php echo htmlspecialchars($user['facebook']); ?>
                <i class="fas fa-external-link-alt" style="margin-left: 5px; font-size: 0.8rem;"></i>
            </a>
        <?php } else { ?>
            <span class="empty-value">Not provided</span>
        <?php } ?>
    </span>
</div>
    <div class="info-row">
    <span class="info-label">Instagram:</span>
    <span class="info-value">
        <?php if(!empty($user['instagram'])) {
            $instagram = $user['instagram'];
            // Add https:// if missing
            if (!preg_match("~^(?:f|ht)tps?://~i", $instagram)) {
                $instagram = "https://" . $instagram;
            }
        ?>
            <a href="<?php echo htmlspecialchars($instagram); ?>" target="_blank" rel="noopener noreferrer" style="color: #4a148c; text-decoration: none;">
                <?php echo htmlspecialchars($user['instagram']); ?>
                <i class="fas fa-external-link-alt" style="margin-left: 5px; font-size: 0.8rem;"></i>
            </a>
        <?php } else { ?>
            <span class="empty-value">Not provided</span>
        <?php } ?>
    </span>

      <span class="info-label">Youtube:</span>
    <span class="info-value">
        <?php if(!empty($user['youtube'])) {
            $youtube = $user['youtube'];
            // Add https:// if missing
            if (!preg_match("~^(?:f|ht)tps?://~i", $youtube)) {
                $youtube = "https://" . $youtube;
            }
        ?>
            <a href="<?php echo htmlspecialchars($youtube); ?>" target="_blank" rel="noopener noreferrer" style="color: #4a148c; text-decoration: none;">
                <?php echo htmlspecialchars($user['youtube']); ?>
                <i class="fas fa-external-link-alt" style="margin-left: 5px; font-size: 0.8rem;"></i>
            </a>
        <?php } else { ?>
            <span class="empty-value">Not provided</span>
        <?php } ?>
    </span>
        </div>
    </section>
</div>


<!-- Gallery Section -->
<section class="similar-profiles">
    <h2>Gallery</h2>
   <?php if (!empty($gallery)): ?>
    <div class="slider-container">
        <button class="slider-btn left"><i class="fas fa-chevron-left"></i></button>
        <div class="slider-wrapper" id="gallerySlider">
            <?php foreach ($gallery as $img): ?>
                <div class="profile-card">
                    <img src="<?php echo htmlspecialchars($img['file_path']); ?>" alt="Gallery Image">
                </div>
            <?php endforeach; ?>

        </div>
         <p style="text-align:center; color:#666;">No gallery images uploaded yet.</p>
        <button class="slider-btn right"><i class="fas fa-chevron-right"></i></button>
    </div>
<?php else: ?>
    <p style="text-align:center; color:#666;">This feature is available for only Trusted Members.</p>
<?php endif; ?>
</section>





<?php if ($similar->num_rows > 0): ?>
<section class="similar-profiles">
    <h2>Other <?php echo htmlspecialchars($category); ?> Profiles</h2>
    <div class="slider-container">
        <button class="slider-btn left"><i class="fas fa-chevron-left"></i></button>
        <div class="slider-wrapper" id="profileSlider">
            <?php while ($sim = $similar->fetch_assoc()): ?>
                <?php
                // Format name with hyphens instead of spaces
                $formattedName = str_replace(' ', '-', $sim['name']);

                // Final URL in desired format
                $profileUrl = "viewprofile.php?name=" . $formattedName . "-" . $sim['pincode'] . "-" . $sim['ref_suffix'];
                ?>
                <div class="profile-card">
                    <img src="<?php echo !empty($sim['photo']) ? htmlspecialchars($sim['photo']) : 'uploads/photos/default.png'; ?>" alt="Profile">
                    <h4><?php echo htmlspecialchars($sim['name']); ?></h4>
                    <p><?php echo htmlspecialchars($sim['category']); ?></p>
                    <a href="<?php echo $profileUrl; ?>" class="view-btn">View</a>
                </div>
            <?php endwhile; ?>
        </div>
        <button class="slider-btn right"><i class="fas fa-chevron-right"></i></button>
    </div>
</section>
<?php endif; ?>
<?php include 'common/footer.php'; ?>
<div id="lightboxOverlay">
    <img src="" alt="Zoomed Image">
</div>

</body>
</html>
