<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'dbconfigf/dbconst2025.php'; // DB connection

// Fetch 5 mentors from users table where type = 'TM'
$mentor_query = "SELECT name, category, photo,description FROM users WHERE type = 'TM' LIMIT 5";
$mentor_result = mysqli_query($conn, $mentor_query);
$mentors = [];
if ($mentor_result) {
    while ($row = mysqli_fetch_assoc($mentor_result)) {
      $row['name'] = ucwords(strtolower($row['name']));
        $mentors[] = $row;
    }
}

$influencer_query = "
    SELECT u.name,  u.category, u.photo, COUNT(r.referencename) as reference_count
    FROM users u
    LEFT JOIN users r ON u.referenceid = r.referencename
    WHERE u.referenceid IS NOT NULL
    GROUP BY u.referenceid, u.name,  u.category, u.photo
    HAVING reference_count > 0
    ORDER BY reference_count DESC

";

$influencer_result = mysqli_query($conn, $influencer_query);




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MSME Global - Home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scroll Animations Demo</title>
  <link rel="stylesheet" href="assets/css/style.css"> <!-- CSS file include -->

</head>
<body>

<?php include 'common/header.php'; ?>

    </nav>
  </header>

 <section class="hero">
  <div class="hero-content">
    <div class="hero-left">
      <h2>India's First and World's <span>only</span> Business Owner's Directory</h2>
      <div class="dropdown">
        <!--<select>
          <option>Select Category</option>
          <option>Consulting Firms</option>
          <option>Online Academy</option>
          <option>Corporate Events</option>
        </select>-->
      </div>
    </div>
    <div class="hero-right">
      <img src="home_3.png" alt="Hero Image" />
    </div>
  </div>
</section>


<!-- Categories -->
  <h2 style="text-align: center;">Browse Businesses By Categories</h2>
  <div class="category-grid">
    <a href="browse-directory2.php?category=Banking%20Services" class="category-card">
      <img src="assets/icons/p1.jpg">
      <p>Banking Services</p>
    </a>
    <a href="browse-directory2.php?category=Textiles+%26+Fabric+Reseller&letter=" class="category-card">
      <img src="assets/icons/p2.jpg">
      <p>Textiles & Fabric Reseller</p>
    </a>
    <a href="browse-directory2.php?category=Corporate%20Gifting" class="category-card">
      <img src="assets/icons/p3.jpg">
      <p>Corporate Gifting</p>
    </a>
    <a href="browse-directory2.php?category=General%20Insurance" class="category-card">
      <img src="assets/icons/p4.jpg">
      <p>General Insurance</p>
    </a>
    <a href="browse-directory2.php?category=Graphic+Designing&letter=" class="category-card">
      <img src="assets/icons/p5.jpg">
      <p>Graphic Designing</p>
    </a>
    <a href="browse-directory2.php?category=Life%20Insurance" class="category-card">
      <img src="assets/icons/p6.jpg">
      <p>Life Insurance</p>
    </a>
    <a href="browse-directory2.php?category=Yoga%2FPilates%2FQi-gong+Trainer&letter=" class="category-card">
      <img src="assets/icons/p7.jpg">
      <p>Fitness Trainer</p>
    </a>
    <a href="browse-directory2.php?category=Real+Estate+Consultant&letter=" class="category-card">
      <img src="assets/icons/p8.jpg">
      <p>Real Estate</p>
    </a>
    <a href="browse-directory2.php?category=Wedding+Planner&letter=" class="category-card">
      <img src="assets/icons/p9.jpg">
      <p>Event Manager</p>
    </a>
    <a href="browse-directory2.php?category=Stock%20Broker" class="category-card">
      <img src="assets/icons/p10.jpg">
      <p>Stock Broker</p>
    </a>
  </div>
</section>


  <!-- Advertisement -->
<section>
  <div class="advertisement-banner">


    <!-- Banner Slider -->
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

 <h2 style="text-align: center;">Influencers</h2>
<div class="influencer-grid">
  <?php if ($influencer_result && mysqli_num_rows($influencer_result) > 0) { ?>
    <?php
      $count = 0;
      while ($row = mysqli_fetch_assoc($influencer_result)) {
        $count++;
    ?>
      <div class="influencer-card <?php echo $count > 4 ? 'hidden-influencer' : ''; ?>">
        <img src="<?php echo htmlspecialchars($row['photo']); ?>"
             alt="<?php echo htmlspecialchars($row['name']); ?>">
        <div class="influencer-info">
          <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
          <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
          <p><strong>Influenced:</strong> <?php echo $row['reference_count']; ?> Business Owners</p>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <p>No influencers found.</p>
  <?php } ?>
</div>

<!-- See More button -->
<div class="see-more-container">
  <button id="toggleBtn" class="toggle-btn see-more">See More</button>
</div>
</section>
  <!-- Pillars & Mentors -->
<div class="pillars-mentors-container">
        <h2 class="pillars-mentors-title">Pillars And Mentors</h2>

    <div class="pillars-mentors-content">
    <div class="mentors-column">
        <?php
        if (!empty($mentors)) {
            foreach ($mentors as $mentor) {
                $mentor_name = htmlspecialchars($mentor['name'] ?? 'Name not available');
                $category = htmlspecialchars($mentor['category'] ?? 'Category not specified');
                $description = htmlspecialchars($mentor['description'] ?? 'No description available.');

                // Fetch photo URL from the photo column
                $photo_url = htmlspecialchars($mentor['photo'] ?? '');
                ?>
                <div class="mentor-dropdown" onclick="toggleDropdown(this)">
                    <div class="mentor-header">
                        <div class="mentor-avatar"
                             <?php if (!empty($photo_url)): ?>
                             style="background-image: url('<?php echo $photo_url; ?>');"
                             <?php endif; ?>
                        ></div>
                        <div class="mentor-info">
                            <div class="mentor-name"><?php echo $mentor_name; ?></div>
                            <div class="mentor-title"><a href="#"><?php echo $category; ?></a></div>
                        </div>
                        <div class="dropdown-arrow">⌄</div>
                    </div>
                    <div class="mentor-content">
                        <p><?php echo $description; ?></p>
                    </div>
                </div>
                <?php
            }
        }
                ?>
            </div>

            <div class="video-column">
                <div class="video-container" id="videoContainer">
                    <!-- Replace 'dQw4w9WgXcQ' with your desired YouTube video ID -->
                    <iframe
                        src="https://www.youtube.com/embed/LrrO_yUakPw?si=zTY0KvZewNger2zb"
                        allow="autoplay; encrypted-media"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>

  <!-- About Us -->
 <section class="about-us-section">
        <div class="about-us-container">
            <div class="about-us-header">About Us</div>

            <div class="about-us-box">
                <div class="about-us-description">
                    MSME Global is a city-based business directory platform designed exclusively for entrepreneurs, business owners, and service providers who form the backbone of India's economy - the MSME sector. We aim to build a unified, swadeshi business network that promotes local businesses, enhances visibility, and unlocks opportunities for collaboration, referrals and growth through digital presence and offline engagement.
                </div>

                <div class="vision-mission-container">
                    <div class="vision-box">
                        <h3 class="vision-title">Our Vision</h3>
                        <p class="vision-content">
                            To become India's most trusted MSME business directory fostering local global reach for small and medium enterprises by enabling them to build strong connections, share success stories and generate quality leads through verified connections.
                        </p>
                    </div>

                    <div class="divider"></div>

                    <div class="mission-box">
                        <h3 class="mission-title">Our Mission</h3>
                        <p class="mission-content">
                            To empower MSMEs by providing a dynamic, trust-driven platform where local businesses can connect, collaborate, and grow together - digitally and through on-ground networking, ensuring every enterprise gains recognition and revenue.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

     <section class="msme-hero-section">
        <!-- Your Complete Background Image with circles, branches, stars, buildings -->
        <div class="background-image background-fallback"></div>

        <!-- Dynamic Text Content Overlay -->
        <div class="content-overlay">
            <!-- MSME GLOBAL Text - Positioned over the circular ring in your background -->
            <div class="msme-logo">
                <div class="msme-text">MSME </div>
                <div class="global-text">GLOBAL</div>
            </div>

            <!-- Dynamic Info Boxes - Positioned over branch endpoints in your background -->
            <div class="info-boxes">
                <div class="info-box goals-box">
                    <h3>Our Goals</h3>
                    <ul>
                        <li>Create a comprehensive online directory of city-based verified business owners</li>
                        <li>Offer a platform for digital visibility and networking at minimal cost</li>
                        <li>Facilitate cross-referral and collaborations among business categories</li>
                        <li>Promote Made in India business through targeted first listing</li>
                        <li>Organize offline business meets, testimonials, and workshops for community building</li>
                    </ul>
                </div>

                <div class="info-box who-should-join-box">
                    <h3>Who Should Join</h3>
                    <ul>
                        <li>City-based manufacturers, traders, service providers, consultants, solo entrepreneurs</li>
                        <li>Business owners looking to expand visibility and find clients and receive referrals</li>
                        <li>MSMEs who believe in sustained networking, real connections and digital branding</li>
                    </ul>
                </div>

                <div class="info-box usp-box">
                    <h3>Our USP (Unique Selling Proposition)</h3>
                    <ul>
                        <li>City Based Verified Listings: Local discovery with credibility</li>
                        <li>Business + Relationship Oriented: Built by business owners, for business owners</li>
                        <li>Photo-Logic Banner Showcase: Visual visibility included in every listing</li>
                        <li>Digital + Offline Ecosystem: Website visibility + Face to face networking opportunities</li>
                        <li>Referral and support ecosystem: Built-in support for cross-industry referrals</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


  <!-- Benefits -->
<section style="background: linear-gradient(180deg, rgba(246, 142, 57, 0.9) 0%, rgba(115, 115, 115, 0.9) 100%);; color: #070B16;
; padding: 40px 20px; text-align: center;">
  <h2>Benefits Of Listing On MSME Global</h2>
  <ul style="max-width: 800px; margin: 0 auto; font-size: 18px; text-align: left; display: inline-block;">
    <li>Professional brand visibility</li>
    <li>New discovery & trust building</li>
    <li>Connect with like-minded people</li>
    <li>Generate leads</li>
    <li>Collaborate for growth</li>
  </ul>


    <div style="max-width: 800px; margin: 30px auto 5px auto; padding: 30px; border-radius: 15px; background: rgba(255, 255, 255, 0.1);">
    <h2>Our Philosophy</h2>
    <p>
      We believe that Bharat’s business future lies in its roots — the MSMEs that drive real growth.
      <br>MSME GLOBAL is not just a listing platform; it’s a community of achievers, collaborators,
      <br>and visionaries who believe in growing together.
    </p>
  </div>
</section>



  <!-- Testimonials -->
<section class="testimonial-section">
  <h2 class="testimonial-heading">What People Say About Us</h2>

  <div class="testimonial-container testimonial-left">
    <div class="testimonial-card testimonial-anurag">
      <div class="testimonial-content">
        <img src="assets/pic/p5.jpg" alt="Anurag Agarwal" class="testimonial-image" />
        <div>
          <p class="testimonial-name">Anurag Agarwal</p>
          <p class="testimonial-title">ITAG Business Solutions</p>
          <p class="testimonial-text">
            My name is Anurag Agarwal, founder of ITAG Business Solutions.<br>
            We are into Kolkata-based law firm specialising in intellectual property.<br>
            We have got a few clients from this contact sphere and also a few solutions and services I am availing from members of Biz Mitr and MSME Global. This is really helpful and useful.
          </p>
        </div>
      </div>
    </div>
  </div>



  <div class="testimonial-container testimonial-right">
    <div class="testimonial-card testimonial-sweta">
      <div class="testimonial-content">
        <img src="assets/pic/p6.jpg" alt="Sweta Agarwal" class="testimonial-image" />
        <div>
          <p class="testimonial-name">Sweta Agarwal</p>
          <p class="testimonial-title">The Teacher's Hub (your personal tutor)</p>
          <p class="testimonial-text">
            I recently listed myself with MSME Global, a transparent and affordable platform that truly empowers businesses. For just Rs 1000/year, it gives direct brand visibility and is also built to support genuine entrepreneurs. Through this platform, I received many references, and some even converted into successful business. Highly recommended for all MSMEs.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<a href="https://wa.me/1234567890" target="_blank" class="whatsapp-btn" title="Chat with us on WhatsApp">
  <i class="fab fa-whatsapp"></i>
</a>
<!-- Footer -->
<?php include 'common/footer.php'; ?>
<script src="assets/js/web.js"></script> <!-- JS file include -->

</body>
</html>
