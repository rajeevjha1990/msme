<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'dbconfigf/dbconst2025.php';

// ---------------- MENTORS ----------------
$mentor_query = "
    SELECT
        u.name,
        bc.name AS category_name,
        u.photo,
        u.description
    FROM users u
    LEFT JOIN business_categories bc ON u.category = bc.id
    WHERE u.type = 'TM'
    LIMIT 5
";
$mentor_result = mysqli_query($conn, $mentor_query);
$mentors = [];

if ($mentor_result) {
    while ($row = mysqli_fetch_assoc($mentor_result)) {
        $row['name'] = ucwords(strtolower($row['name']));
        $mentors[] = $row;
    }
}

// ---------------- INFLUENCERS ----------------
$influencer_query = "
    SELECT
        u.name,
        bc.name AS category_name,
        u.photo,
        COUNT(r.referencename) AS reference_count
    FROM users u
    LEFT JOIN business_categories bc ON u.category = bc.id
    LEFT JOIN users r ON u.referenceid = r.referencename
    WHERE u.referenceid IS NOT NULL
    GROUP BY u.referenceid, u.name, bc.name, u.photo
    HAVING reference_count > 0
    ORDER BY reference_count DESC
";

$influencer_result = mysqli_query($conn, $influencer_query);

// ---------------- CATEGORIES ----------------
$category_query = "SELECT * FROM business_categories WHERE status = 1";
$category_result = mysqli_query($conn, $category_query);
$categories = [];

if ($category_result) {
    while ($row = mysqli_fetch_assoc($category_result)) {
        $row['name'] = ucwords(strtolower($row['name']));
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MSME Global - Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'common/header.php'; ?>

<!-- HERO SECTION -->
<section class="hero">
  <div class="hero-content">
    <div class="hero-left">
      <h2>India's First and World's <span>only</span> Business Owner's Directory</h2>
    </div>
    <div class="hero-right">
      <img src="home_3.png" alt="Hero Image">
    </div>
  </div>
</section>
<!-- ================= CATEGORIES ================= -->

<h2 style="text-align:center;">Browse Businesses By Categories</h2>
<div class="category-grid">
<?php
if (!empty($categories)) {
   foreach ($categories as $cate) {
  ?>
  <a href="browse-directory2.php?category=<?php echo urlencode($cate['id']); ?>" class="category-card">
    <img src="admin/views/uploads/<?php echo htmlspecialchars($cate['icon']); ?>"
         alt="<?php echo htmlspecialchars($cate['name']); ?>"
         onerror="this.src='assets/no-image.png';">
    <p><?php echo htmlspecialchars($cate['name']); ?></p>
  </a>
<?php
   }
    } else {
      echo "<p style='text-align:center;color:red;'>No categories found.</p>";
    }
 ?>
</div>

<!-- ================= BANNERS ================= -->
<section>
  <div class="advertisement-banner">
    <div class="banner-slider">
      <?php
      $bannerDir = "uploads/banner/advertisement/";
      $banners = glob($bannerDir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

      if (!empty($banners)) {
        foreach ($banners as $banner) {
          echo '<div class="slide"><img src="' . $banner . '" alt="Banner"></div>';
        }
      } else {
        echo "<p>No banners uploaded yet.</p>";
      }
      ?>
    </div>
  </div>
</section>

<!-- ================= INFLUENCERS ================= -->
<h2 style="text-align:center;">Influencers</h2>

<div class="influencer-grid">
<?php if ($influencer_result && mysqli_num_rows($influencer_result) > 0) {
  $count = 0;
  while ($row = mysqli_fetch_assoc($influencer_result)) {
    $count++;
?>
  <div class="influencer-card <?php echo $count > 4 ? 'hidden-influencer' : ''; ?>">
    <img src="<?php echo htmlspecialchars($row['photo']); ?>">
    <div class="influencer-info">
      <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
      <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category_name']); ?></p>
      <p><strong>Influenced:</strong> <?php echo $row['reference_count']; ?> Business Owners</p>
    </div>
  </div>
<?php }} ?>
</div>

<div class="see-more-container">
  <button id="toggleBtn" class="toggle-btn see-more">See More</button>
</div>

<!-- ================= MENTORS ================= -->
<div class="pillars-mentors-container">
  <h2 class="pillars-mentors-title">Pillars And Mentors</h2>

  <div class="pillars-mentors-content">
    <div class="mentors-column">
      <?php foreach ($mentors as $mentor) { ?>
      <div class="mentor-dropdown">
        <div class="mentor-header">
          <div class="mentor-avatar" style="background-image:url('<?php echo $mentor['photo']; ?>')"></div>
          <div class="mentor-info">
            <div class="mentor-name"><?php echo $mentor['name']; ?></div>
            <div class="mentor-title"><?php echo $mentor['category_name']; ?></div>
          </div>
        </div>
        <div class="mentor-content">
          <p><?php echo $mentor['description']; ?></p>
        </div>
      </div>
      <?php } ?>
    </div>

    <div class="video-column">
      <iframe src="https://www.youtube.com/embed/LrrO_yUakPw" allowfullscreen></iframe>
    </div>
  </div>
</div>

<!-- ================= TESTIMONIALS ================= -->
<section class="testimonial-section">

  <h2 class="testimonial-heading">What People Say About Us</h2>

  <div class="testimonial-container testimonial-left">
    <div class="testimonial-card">
      <img src="assets/pic/p5.jpg">
      <p>Anurag Agarwal</p>
      <p>ITAG Business Solutions</p>
    </div>
  </div>

  <div class="testimonial-container testimonial-right">
    <div class="testimonial-card">
      <img src="assets/pic/p6.jpg">
      <p>Sweta Agarwal</p>
      <p>The Teacher's Hub</p>
    </div>
  </div>

</section>

<!-- ================= WHATSAPP ================= -->
<a href="https://wa.me/1234567890" target="_blank" class="whatsapp-btn">
  <i class="fab fa-whatsapp"></i>
</a>

<?php include 'common/footer.php'; ?>

<script src="assets/js/web.js"></script>
</body>
</html>
