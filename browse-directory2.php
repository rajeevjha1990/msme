<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'common/header.php';
include 'dbconfigf/dbconst2025.php';

// Get category from URL
$selectedCategory = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/business-directory.css">
<title>Browse Directory</title>
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

    <!-- Category Filter -->
    <div class="filter-bar">
        <form method="GET" style="display:inline;">
            <select name="category" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php
                $catResult = $conn->query("SELECT id, name FROM business_categories WHERE status = 1 ORDER BY name ASC");
                while ($catRow = $catResult->fetch_assoc()) {
                    $catId   = $catRow['id'];
                    $catName = htmlspecialchars($catRow['name']);
                    $selected = ($selectedCategory == $catId) ? "selected" : "";
                    echo "<option value='$catId' $selected>$catName</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <!-- Section Title -->
    <div class="section-title">Browse Business Directory</div>

    <!-- Directory Grid -->
    <section class="directory-grid">
          <?php
      // Pagination
      $limit = 12;
      $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
      if ($page < 1) $page = 1;
      $offset = ($page - 1) * $limit;

      // WHERE condition
      $where = "WHERE u.type <> 'INTERNAL'";
      if ($selectedCategory != '') {
          $where .= " AND u.category = '$selectedCategory'";
      }

      // Count total
      $countSql = "SELECT COUNT(*) AS total
                   FROM users u
                   LEFT JOIN business_categories c ON u.category = c.id
                   $where";
      $countResult = $conn->query($countSql);
      $totalRows = ($countResult && $countResult->num_rows > 0) ? $countResult->fetch_assoc()['total'] : 0;
      $totalPages = ceil($totalRows / $limit);

      // Fetch users with category name
      $sql = "SELECT u.user_id,
                     CONCAT(UCASE(LEFT(u.name, 1)), LCASE(SUBSTRING(u.name, 2))) AS name,
                     u.category AS category_id,
                     c.name AS category_name,
                     u.email, u.photo, u.base_amount,
                     u.facebook, u.instagram, u.linkedin, u.whatsapp, u.website, u.pincode, u.reference_id
              FROM users u
              LEFT JOIN business_categories c ON u.category = c.id
              $where
              ORDER BY c.name ASC, u.name ASC
              LIMIT $limit OFFSET $offset";

      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {

              $row['name'] = ucwords(strtolower($row['name']));
              $photoPath = !empty($row['photo']) ? htmlspecialchars($row['photo']) : "uploads/photos/default.png";
              $name_hyphen = str_replace(' ', '-', $row['name']);
              $whatsapp_last5 = substr($row['reference_id'], -6);
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
                <p class="category">
                <?php
                    echo !empty($row['category_name']) ? htmlspecialchars($row['category_name']) : 'NA';
                ?>
                </p>
                </div>
            </div>

            <div class="social-icons">

                <?php if(!empty($row['email'])) { ?>
                    <span class="social-icon email" onclick="handleSocialClick(event, 'mailto:<?php echo htmlspecialchars($row['email']); ?>', 'email')">
                        <i class="far fa-envelope"></i>
                    </span>
                <?php } else { ?>
                    <span class="social-icon email unavailable"><i class="far fa-envelope"></i></span>
                <?php } ?>

                <?php if(!empty($row['facebook'])) { ?>
                    <span class="social-icon facebook" onclick="handleSocialClick(event, '<?php echo htmlspecialchars($row['facebook']); ?>', 'facebook')">
                        <i class="fab fa-facebook-f"></i>
                    </span>
                <?php } else { ?>
                    <span class="social-icon facebook unavailable"><i class="fab fa-facebook-f"></i></span>
                <?php } ?>

                <?php if(!empty($row['instagram'])) { ?>
                    <span class="social-icon instagram" onclick="handleSocialClick(event, '<?php echo htmlspecialchars($row['instagram']); ?>', 'instagram')">
                        <i class="fab fa-instagram"></i>
                    </span>
                <?php } else { ?>
                    <span class="social-icon instagram unavailable"><i class="fab fa-instagram"></i></span>
                <?php } ?>

                <?php if(!empty($row['linkedin'])) { ?>
                    <span class="social-icon linkedin" onclick="handleSocialClick(event, '<?php echo htmlspecialchars($row['linkedin']); ?>', 'linkedin')">
                        <i class="fab fa-linkedin-in"></i>
                    </span>
                <?php } else { ?>
                    <span class="social-icon linkedin unavailable"><i class="fab fa-linkedin-in"></i></span>
                <?php } ?>

                <?php if(!empty($row['whatsapp'])) { ?>
                    <span class="social-icon whatsapp" onclick="handleSocialClick(event, 'https://wa.me/<?php echo htmlspecialchars($row['whatsapp']); ?>', 'whatsapp')">
                        <i class="fab fa-whatsapp"></i>
                    </span>
                <?php } else { ?>
                    <span class="social-icon whatsapp unavailable"><i class="fab fa-whatsapp"></i></span>
                <?php } ?>

                <?php if(!empty($row['website'])) { ?>
                    <span class="social-icon website" onclick="handleSocialClick(event, '<?php echo htmlspecialchars($row['website']); ?>', 'website')">
                        <i class="fas fa-globe"></i>
                    </span>
                <?php } else { ?>
                    <span class="social-icon website unavailable"><i class="fas fa-globe"></i></span>
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
            <a href="?page=<?php echo $page - 1; ?>&category=<?php echo urlencode($selectedCategory); ?>">&laquo; Prev</a>
        <?php } ?>

        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <a href="?page=<?php echo $i; ?>&category=<?php echo urlencode($selectedCategory); ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php } ?>

        <?php if ($page < $totalPages) { ?>
            <a href="?page=<?php echo $page + 1; ?>&category=<?php echo urlencode($selectedCategory); ?>">Next &raquo;</a>
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
</script>

<?php include 'common/footer.php'; ?>
</body>
</html>
