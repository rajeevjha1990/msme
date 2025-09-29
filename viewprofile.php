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
$sql = "SELECT u.*, cm.city 
        FROM users u 
        LEFT JOIN city_master cm ON u.city = cm.cityid 
        WHERE u.name = ? AND u.pincode = ? AND RIGHT(u.reference_id, 6) = ?";
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
$category = $user['category'];

// Fetch same-category profiles; also return ref suffix for link building
$sqlSimilar = "
    SELECT 
        name, category, photo, pincode, 
        RIGHT(reference_id, 6) AS ref_suffix
    FROM users
    WHERE category = ?
      AND NOT (
          name = ?
          AND pincode = ?
          AND RIGHT(reference_id, CHAR_LENGTH(?)) = ?
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
<title><?php echo htmlspecialchars($user['name']); ?> - Profile</title>
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

    .profile-page {
        padding-top: 2%;
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    /* Header Section */
    .profile-header {
        background: linear-gradient(135deg, #e77b3e, #d05b91, #c44db8);
        padding: 40px 20px;
        color: white;
        text-align: center;
    }

    .back-btn {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .back-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        text-decoration: none;
    }

    .profile-main {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 30px;
    }

    .profile-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        margin-bottom: 20px;
    }

    .profile-name {
        font-size: 2.5rem;
        margin: 0 0 10px 0;
        font-weight: bold;
    }

    .profile-category {
        font-size: 1.2rem;
        opacity: 0.9;
        margin: 0;
    }

    /* Content Section */
    .profile-content {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    .info-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #4a148c;
        margin-bottom: 20px;
        border-bottom: 3px solid #e77b3e;
        padding-bottom: 10px;
    }

    .info-row {
        display: flex;
        margin-bottom: 15px;
        align-items: flex-start;
    }

    .info-label {
        font-weight: bold;
        color: #555;
        min-width: 140px;
        margin-right: 15px;
    }

    .info-value {
        color: #333;
        flex: 1;
        word-break: break-word;
    }

    .logo-section {
        text-align: center;
        margin-bottom: 20px;
    }

    .company-logo {
        max-width: 150px;
        max-height: 100px;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .social-links {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .social-link {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        font-size: 20px;
        transition: transform 0.2s ease;
    }

    .social-link:hover {
        transform: scale(1.1);
        text-decoration: none;
        color: white;
    }

    .social-link.facebook { background: #1877f2; }
    .social-link.instagram { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
    .social-link.linkedin { background: #0077b5; }
    .social-link.youtube { background: #ff0000; }
    .social-link.website { background: #4a148c; }
    .social-link.email { background: #ea4335; }

    .full-width-card {
        grid-column: 1 / -1;
    }

    .contact-info {
        background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
        border-left: 5px solid #4a148c;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .profile-content {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 0 15px;
        }

        .profile-header {
            position: relative;
            padding: 60px 20px 40px;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            transform: none;
        }

        .profile-name {
            font-size: 2rem;
        }

        .info-row {
            flex-direction: column;
        }

        .info-label {
            min-width: auto;
            margin-right: 0;
            margin-bottom: 5px;
        }
    }

    .empty-value {
        color: #999;
        font-style: italic;
    }

/* --- Similar Profiles Section --- */
.similar-profiles {
    margin: 50px auto;
    padding: 20px;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    max-width: 1200px;
}

.similar-profiles h2 {
    font-size: 1.8rem;
    margin-bottom: 20px;
    color: #4a148c;
    text-align: center;
    font-weight: bold;
}

.slider-container {
    position: relative;
    overflow: hidden;
    padding: 10px 40px;
}

.slider-wrapper {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none; /* Firefox */
}

.slider-wrapper::-webkit-scrollbar {
    display: none; /* Chrome, Safari */
}

.profile-card {
    flex: 0 0 200px;
    background: #f8f9fa;
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.profile-card:hover {
    transform: translateY(-5px);
}

.profile-card img {
    width: 100%;
    height: 150px;
    border-radius: 10px;
    object-fit: cover;
    margin-bottom: 10px;
}

.profile-card h4 {
    font-size: 1.1rem;
    margin: 5px 0;
    font-weight: bold;
    color: #333;
}

.profile-card p {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 10px;
}

.view-btn {
    display: inline-block;
    padding: 8px 15px;
    background: #4a148c;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: background 0.3s ease;
}

.view-btn:hover {
    background: #6a1b9a;
}

/* Slider buttons */
.slider-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: #4a148c;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    transition: background 0.3s ease;
}

.slider-btn:hover {
    background: #6a1b9a;
}

.slider-btn.left {
    left: 10px;
}

.slider-btn.right {
    right: 10px;
}



/* Lightbox Overlay */
#lightboxOverlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.9);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    cursor: zoom-out;
}

#lightboxOverlay img {
    max-width: 90%;
    max-height: 90%;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.5);
    animation: zoomIn 0.3s ease;
}

@keyframes zoomIn {
    from { transform: scale(0.7); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

</style>
</head>
<body>

<div class="profile-page">
    <!-- Header -->
    <section class="profile-header">
        <a href="browse-directory2.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Directory
        </a>
        
       <div class="profile-main"> <?php $photoPath = !empty($user['photo']) ? htmlspecialchars($user['photo']) : "uploads/photos/default.png"; ?> <img src="<?php echo $photoPath; ?>" alt="Profile Photo" class="profile-photo"> <h1 class="profile-name"><?php echo htmlspecialchars($user['name']); ?></h1> <p class="profile-category"><?php echo htmlspecialchars($user['category']); ?></p> </div> </section>
    
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
                <span class="info-value"><?php echo htmlspecialchars($user['category']); ?></span>
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

<script>
// Add smooth scrolling and enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation for images
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
    });
    
    // Add click feedback for social links
    const socialLinks = document.querySelectorAll('.social-link');
    socialLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1.1)';
            }, 100);
        });
    });
});
</script>


<script>
// Function to initialize sliders
function initSlider(sliderId) {
    const slider = document.getElementById(sliderId);
    if (!slider) return;

    const container = slider.closest('.slider-container');
    const leftBtn = container.querySelector('.slider-btn.left');
    const rightBtn = container.querySelector('.slider-btn.right');

    leftBtn.addEventListener('click', () => {
        slider.scrollBy({ left: -220, behavior: "smooth" });
    });

    rightBtn.addEventListener('click', () => {
        slider.scrollBy({ left: 220, behavior: "smooth" });
    });
}

document.addEventListener("DOMContentLoaded", function() {
    initSlider("profileSlider"); // similar profiles
    initSlider("gallerySlider"); // gallery
});
</script>



<script>
// Gallery Zoom Feature
document.addEventListener('DOMContentLoaded', function() {
    const lightbox = document.getElementById('lightboxOverlay');
    const lightboxImg = lightbox.querySelector('img');

    // Open lightbox on image click
    const galleryImages = document.querySelectorAll('.slider-wrapper .profile-card img');
    galleryImages.forEach(img => {
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', function() {
            lightbox.style.display = 'flex';
            lightboxImg.src = this.src;
        });
    });

    // Close lightbox on click
    lightbox.addEventListener('click', function() {
        lightbox.style.display = 'none';
        lightboxImg.src = '';
    });
});
</script>

<div id="lightboxOverlay">
    <img src="" alt="Zoomed Image">
</div>

</body>
</html>