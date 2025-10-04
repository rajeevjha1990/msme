<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'common/header.php'; 
include 'dbconfigf/dbconst2025.php'; 

// Get filters from URL
$selectedCategory = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$selectedLetter   = isset($_GET['letter']) ? $conn->real_escape_string($_GET['letter']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<title>Browse Directory</title>
<style>
    * { box-sizing: border-box; }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #c9b6ff, #f2d6d6);
        min-height: 100vh;
    }

    .browse-directory-page { font-family: Arial, sans-serif; padding-top: 2%; margin: 0; background-color: #f8f9fa; }

    .browse-directory-header {
        background: linear-gradient(135deg, #e77b3e, #d05b91, #c44db8);
        padding: 40px 20px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .browse-directory-header h1 { font-size: 2.5rem; margin: 0 0 10px 0; font-weight: bold; }
    .browse-directory-header p { font-size: 1.1rem; margin: 0; opacity: 0.9; }

    .section-title { text-align: center; margin: 40px 0 30px; font-size: 2rem; font-weight: bold; color: #4a148c; }

    .filter-bar {
        text-align: center;
        margin: 20px 0;
    }
    .filter-bar select {
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }
    .alpha-filter {
        text-align: center;
        margin: 15px 0;
    }
    .alpha-filter a {
        margin: 0 5px;
        font-weight: bold;
        text-decoration: none;
        color: #4a148c;
    }
    .alpha-filter a.active { 
        color: white; 
        background: #4a148c; 
        padding: 3px 8px; 
        border-radius: 4px; 
    }

    .directory-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        padding: 0 20px 40px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .directory-card {
        background: linear-gradient(135deg, #dcd0d5ff, #bf8b60ff);
        border-radius: 15px;
        padding: 25px;
        text-align: left;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
        position: relative;
        overflow: hidden;
    }

    .directory-card:hover { transform: translateY(-8px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); text-decoration: none; color: inherit; }

    .profile-section { display: flex; align-items: center; margin-bottom: 20px; }
    .profile-image { width: 90px; height: 90px; background: #8e8e8e; border-radius: 50%; margin-right: 15px; flex-shrink: 0; object-fit: cover; }
    .profile-info h3 { font-size: 1.1rem; margin: 0 0 5px 0; color: #333; font-weight: bold; }
    .profile-info .organization { font-size: 0.95rem; color: #555; margin: 0 0 5px 0; }
    .profile-info .category { font-size: 0.9rem; color: #666; margin: 0; }

    .social-icons { display: flex; gap: 10px; justify-content: center; margin-top: 15px; flex-wrap: wrap; }
    .social-icon { width: 35px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; font-size: 18px; transition: transform 0.2s ease, opacity 0.2s ease; cursor: pointer; }
    .social-icon:hover { transform: scale(1.1); }
    .social-icon.unavailable { opacity: 0.5; }

    .social-icon.facebook { background: #1877f2; }
    .social-icon.instagram { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
    .social-icon.linkedin { background: #0077b5; }
    .social-icon.whatsapp { background: #25d366; }
    .social-icon.email { background: #ea4335; }
    .social-icon.twitter { background: #1da1f2; }
    .social-icon.website { background: #6c757d; }

    .view-profile-btn { background: linear-gradient(135deg, #4a148c, #7b1fa2); color: white; border: none; padding: 10px 20px; border-radius: 25px; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; margin-top: 15px; width: 100%; }
    .view-profile-btn:hover { background: linear-gradient(135deg, #7b1fa2, #9c27b0); transform: translateY(-2px); }

    .verified-badge { display: inline-block; margin-left: 6px; vertical-align: middle; position: relative; top: -3px; }
    .verified-badge img { width: 30px; height: 30px; }

    .pagination { text-align: center; margin: 20px 0 40px; }
    .pagination a { display: inline-block; padding: 8px 14px; margin: 0 5px; border-radius: 6px; background: #eee; color: #333; text-decoration: none; font-weight: bold; transition: 0.3s; }
    .pagination a:hover { background: #7b1fa2; color: white; }
    .pagination a.active { background: #4a148c; color: white; }

    @media (max-width: 768px) {
        .browse-directory-header { text-align: center; flex-direction: column; gap: 20px; }
        .browse-directory-header h1 { font-size: 2rem; }
        .directory-grid { grid-template-columns: 1fr; padding: 0 15px 40px; }
        .section-title { font-size: 1.6rem; }
        .social-icons { gap: 8px; }
        .social-icon { width: 32px; height: 32px; font-size: 16px; }
    }
</style>
</head>
<body>

<div class="browse-directory-page">

    <!-- Header -->
    <section class="browse-directory-header">
        <div class="header-text">
            <h1>Browse Directory</h1>
            <p>Discover Local to Global MSME Business Listings</p>
        </div>
        <div class="header-logo">
            DIRECTOR<br>BUSINESS
        </div>
    </section>

    <!-- Filters -->
    <div class="filter-bar">
        <form method="GET" style="display:inline;">
            <select name="category" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php
                $catResult = $conn->query("SELECT DISTINCT category FROM users WHERE type <> 'INTERNAL' ORDER BY category ASC");
                while ($catRow = $catResult->fetch_assoc()) {
                    $cat = htmlspecialchars($catRow['category']);
                    $selected = ($selectedCategory == $cat) ? "selected" : "";
                    echo "<option value='$cat' $selected>$cat</option>";
                }
                ?>
            </select>
            <input type="hidden" name="letter" value="<?php echo htmlspecialchars($selectedLetter); ?>">
        </form>
    </div>

    <!-- Alphabetical Filter -->
    <div class="alpha-filter">
        <?php foreach (range('A','Z') as $letter) { 
            $isActive = ($selectedLetter == $letter) ? "active" : "";
            $queryStr = "?letter=$letter&category=".urlencode($selectedCategory);
            echo "<a href='$queryStr' class='$isActive'>$letter</a>";
        } ?>
        <a href="?category=<?php echo urlencode($selectedCategory); ?>" class="<?php echo ($selectedLetter==''?'active':''); ?>">All</a>
    </div>

    <!-- Section Title -->
    <div class="section-title">Browse Business Directory</div>

    <!-- Directory Grid -->
    <section class="directory-grid">
      <?php
      // Pagination setup
      $limit = 12;
      $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
      if ($page < 1) $page = 1;
      $offset = ($page - 1) * $limit;

      // Build WHERE conditions
      $where = "WHERE type <> 'INTERNAL'";
      if ($selectedCategory != '') {
          $where .= " AND category = '$selectedCategory'";
      }
      if ($selectedLetter != '') {
          $where .= " AND name LIKE '$selectedLetter%'";
      }

      // Count total
      $countSql = "SELECT COUNT(*) AS total FROM users $where";
      $countResult = $conn->query($countSql);
      $totalRows = ($countResult && $countResult->num_rows > 0) ? $countResult->fetch_assoc()['total'] : 0;
      $totalPages = ceil($totalRows / $limit);

      // Fetch users
      $sql = "SELECT user_id, 
                     CONCAT(UCASE(LEFT(name, 1)), LCASE(SUBSTRING(name, 2))) AS name, 
                     category, email, photo, base_amount,
                     facebook, instagram, linkedin, whatsapp, website,pincode,reference_id
              FROM users 
              $where
              ORDER BY category ASC, name ASC
              LIMIT $limit OFFSET $offset";

      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $row['name'] = ucwords(strtolower($row['name']));
              $photoPath = !empty($row['photo']) ? htmlspecialchars($row['photo']) : "uploads/photos/default.png";
            $name_hyphen = str_replace(' ', '-', $row['name']);

// Get last 5 digits of WhatsApp
$whatsapp_last5 = substr($row['reference_id'], -6);

// Combine name, pincode, and last 5 of WhatsApp
$url_name = $name_hyphen . '-' . $row['pincode'] . '-' . $whatsapp_last5;
              ?>



<a href="viewprofile.php?name=<?php echo urlencode($url_name); ?>" class="directory-card">


                  <div class="profile-section">
                      <img src="<?php echo $photoPath; ?>" alt="Profile" class="profile-image">
                      <div class="profile-info">
                          <h3>
                              <?php echo htmlspecialchars($row['name']); ?>
                              <?php if ($row['base_amount'] == 1000) { ?>
                                  <span class="verified-badge">
                                      <img src="assets/icons/verified.png" alt="Verified">
                                  </span>
                              <?php } ?>
                          </h3>
                          <p class="category"><?php echo htmlspecialchars($row['category']); ?></p>
                      </div>
                  </div>
                  <div class="social-icons">
                      <!-- Email -->
                      <?php if(!empty($row['email'])) { ?>
                          <span class="social-icon email" onclick="handleSocialClick(event, 'mailto:<?php echo htmlspecialchars($row['email']); ?>', 'email')" title="Email">
                              <i class="far fa-envelope"></i>
                          </span>
                      <?php } else { ?>
                          <span class="social-icon email unavailable" onclick="showNotAvailableModal(event, 'Email', 'email')" title="Email not available">
                              <i class="far fa-envelope"></i>
                          </span>
                      <?php } ?>
                      <!-- Facebook -->
                      <?php if(!empty($row['facebook'])) { ?>
                          <span class="social-icon facebook" onclick="handleSocialClick(event, '<?php echo htmlspecialchars($row['facebook']); ?>', 'facebook')" title="Facebook">
                              <i class="fab fa-facebook-f"></i>
                          </span>
                      <?php } else { ?>
                          <span class="social-icon facebook unavailable" onclick="showNotAvailableModal(event, 'Facebook', 'facebook')" title="Facebook not available">
                              <i class="fab fa-facebook-f"></i>
                          </span>
                      <?php } ?>
                      <!-- Instagram -->
                      <?php if(!empty($row['instagram'])) { ?>
                          <span class="social-icon instagram" onclick="handleSocialClick(event, '<?php echo htmlspecialchars($row['instagram']); ?>', 'instagram')" title="Instagram">
                              <i class="fab fa-instagram"></i>
                          </span>
                      <?php } else { ?>
                          <span class="social-icon instagram unavailable" onclick="showNotAvailableModal(event, 'Instagram', 'instagram')" title="Instagram not available">
                              <i class="fab fa-instagram"></i>
                          </span>
                      <?php } ?>
                      <!-- LinkedIn -->
                      <?php if(!empty($row['linkedin'])) { ?>
                          <span class="social-icon linkedin" onclick="handleSocialClick(event, '<?php echo htmlspecialchars($row['linkedin']); ?>', 'linkedin')" title="LinkedIn">
                              <i class="fab fa-linkedin-in"></i>
                          </span>
                      <?php } else { ?>
                          <span class="social-icon linkedin unavailable" onclick="showNotAvailableModal(event, 'LinkedIn', 'linkedin')" title="LinkedIn not available">
                              <i class="fab fa-linkedin-in"></i>
                          </span>
                      <?php } ?>
                      <!-- WhatsApp -->
                      <?php if(!empty($row['whatsapp'])) { ?>
                          <span class="social-icon whatsapp" onclick="handleSocialClick(event, 'https://wa.me/<?php echo htmlspecialchars($row['whatsapp']); ?>', 'whatsapp')" title="WhatsApp">
                              <i class="fab fa-whatsapp"></i>
                          </span>
                      <?php } else { ?>
                          <span class="social-icon whatsapp unavailable" onclick="showNotAvailableModal(event, 'WhatsApp', 'whatsapp')" title="WhatsApp not available">
                              <i class="fab fa-whatsapp"></i>
                          </span>
                      <?php } ?>
                      <!-- Website -->
                      <?php if(!empty($row['website'])) { ?>
                          <span class="social-icon website" onclick="handleSocialClick(event, '<?php echo htmlspecialchars($row['website']); ?>', 'website')" title="Website">
                              <i class="fas fa-globe"></i>
                          </span>
                      <?php } else { ?>
                          <span class="social-icon website unavailable" onclick="showNotAvailableModal(event, 'Website', 'website')" title="Website not available">
                              <i class="fas fa-globe"></i>
                          </span>
                      <?php } ?>
                  </div>
              </a>
              <?php
          }
      } else {
          echo "<p style='text-align:center;'>No users found in the directory.</p>";
      }
      ?>
    </section>

    <!-- Pagination -->
    <?php if ($totalPages > 1) { ?>
    <div class="pagination">
        <?php if ($page > 1) { ?>
            <a href="?page=<?php echo $page - 1; ?>&category=<?php echo urlencode($selectedCategory); ?>&letter=<?php echo urlencode($selectedLetter); ?>">&laquo; Prev</a>
        <?php } ?>
        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <a href="?page=<?php echo $i; ?>&category=<?php echo urlencode($selectedCategory); ?>&letter=<?php echo urlencode($selectedLetter); ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php } ?>
        <?php if ($page < $totalPages) { ?>
            <a href="?page=<?php echo $page + 1; ?>&category=<?php echo urlencode($selectedCategory); ?>&letter=<?php echo urlencode($selectedLetter); ?>">Next &raquo;</a>
        <?php } ?>
    </div>
    <?php } ?>
</div>


<script>
function handleSocialClick(event, url, platform) {
    event.preventDefault(); event.stopPropagation();
    if (platform === 'website' && !url.startsWith('http')) url = 'https://' + url;
    window.open(url, '_blank');
}
function showNotAvailableModal(event, platformName, platformType) {
    event.preventDefault(); event.stopPropagation();
    alert(platformName + " information is not available for this business profile.");
}
</script>

<?php include 'common/footer.php'; ?>
</body>
</html>
