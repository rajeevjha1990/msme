<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'common/header.php';  
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Plans & Video</title>
<style>

       * { box-sizing: border-box; }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #c9b6ff, #f2d6d6);
        min-height: 100vh;
    }

/* ✅ Banner */
.banner-container {
      margin: 70px;
    position: relative;
    width: 100vw;
    height: 200px;
    margin-left: calc(-50vw + 50%);
    overflow: hidden;
    text-align: center;
}
.banner-slide {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}
.banner-slide.active { opacity: 1; }
.banner-slide img {
    width: 100%; height: 100%;
    object-fit: cover;
}
.banner-dots {
    position: absolute;
    bottom: 10px;
    width: 100%;
    text-align: center;
}
.banner-dots span {
    display: inline-block;
    width: 12px; height: 12px;
    margin: 0 4px;
    background: #ddd;
    border-radius: 50%;
    cursor: pointer;
}
.banner-dots .active { background: #333; }

/* ✅ Plans + Video layout */
.plans-video-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 30px;
    margin-top: 40px;
    padding: 0 20px;
}
.plans-container { flex: 2; }

/* ✅ Table Styling */
.plans-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 20px;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.plans-table th, .plans-table td {
    padding: 14px 16px;
    text-align: center;
    border-bottom: 1px solid #eee;
    font-size: 15px;
}
.plans-table th {
    background: linear-gradient(135deg, #4a148c, #7b1fa2);
    color: #fff;
    text-transform: uppercase;
    font-size: 16px;
    font-weight: bold;
}
.plans-table .feature {
    text-align: left;
    font-weight: bold;
    color: #333;
}

/* Alternating row colors */
.plans-table tr:nth-child(even) { background: #f9f9f9; }
.plans-table tr:hover { background: #f1ecfa; transition: 0.3s; }

/* ✅ Check & Cross styling */
.check {
    color: #2e7d32;
    font-size: 18px;
}
.cross {
    color: #d32f2f;
    font-size: 18px;
}

/* ✅ Register Button */
.register-btn {
    padding: 12px 18px;
    background: linear-gradient(135deg, #4a148c, #7b1fa2);
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    margin: auto;
}
.register-btn i {
    font-size: 16px;
}
.register-btn:hover {
    background: linear-gradient(135deg, #6a1b9a, #9c27b0);
    transform: translateY(-2px);
}
/* ✅ Video */
.video-container {
    flex: 1;
    max-width: 400px;
}
.video-container iframe {
    width: 100%;
    height: 220px;
    margin-top: 50px;
    margin-bottom: 30px;
    border-radius: 8px;
}

/* ✅ Responsive */
@media (max-width: 768px) {
    .plans-video-section { flex-direction: column; }
    .video-container {
        width: 100%;
        max-width: 100%;
        margin-top: 20px;
    }
}
</style>
</head>
<body>

<!-- ✅ Banner -->
<?php
$bannerDir = "uploads/banner/advertisement/";
$bannerFiles = glob($bannerDir . "*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
?>
<div class="banner-container">
    <?php if ($bannerFiles): ?>
        <?php foreach ($bannerFiles as $i => $file): ?>
            <div class="banner-slide <?php echo $i === 0 ? 'active' : ''; ?>">
                <img src="<?php echo $file; ?>" alt="Banner">
            </div>
        <?php endforeach; ?>
        <div class="banner-dots">
            <?php foreach ($bannerFiles as $i => $file): ?>
                <span class="<?php echo $i === 0 ? 'active' : ''; ?>" onclick="showSlide(<?php echo $i; ?>)"></span>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="banner-slide active"><p>Advertisement</p></div>
    <?php endif; ?>
</div>

<!-- ✅ Plans + Videos -->
<div class="plans-video-section">
    <!-- Left: Plans -->
    <div class="plans-container">
        <table class="plans-table">
            <tr class="plans-header">
                <th>Features</th>
                <th>Basic Plan<br>₹250/-</th>
                <th>Trusted Plan<br>₹1000/-</th>
            </tr>
            <tr><td class="feature">Business Listing</td><td class="check">✔</td><td class="check">✔</td></tr>
            <tr><td class="feature">Personal Photo & Logo</td><td class="check">✔</td><td class="check">✔</td></tr>
            <tr><td class="feature">Basic Business Details</td><td class="check">✔</td><td class="check">✔</td></tr>
            <tr><td class="feature">Trade Bodies & Networking Forums</td><td class="cross">✖</td><td class="check">✔</td></tr>
            <tr><td class="feature">Address Verification</td><td class="cross">✖</td><td class="check">✔</td></tr>
            <tr><td class="feature">Business Representatives</td><td class="cross">✖</td><td class="check">✔</td></tr>
            <tr><td class="feature">Premium Search Visibility</td><td class="cross">✖</td><td class="check">✔</td></tr>
            <tr><td class="feature">License & Registration Details</td><td class="cross">✖</td><td class="check">✔</td></tr>
            <tr><td class="feature">Major Product & Brands Listing</td><td class="cross">✖</td><td class="check">✔</td></tr>
            <tr><td class="feature">Clientele Showcase</td><td class="cross">✖</td><td class="check">✔</td></tr>
            <tr><td class="feature">Trusted Badge</td><td class="cross">✖</td><td class="check">✔</td></tr>
        </table>
        </table>
        <button class="register-btn" onclick="window.location.href='mainregister.php'">Register Now</button>
    </div>

    <!-- Right: 2 Videos -->
    <div class="video-container">
        <!-- First video -->
        <iframe src="https://www.youtube.com/embed/Vhy47JbhKoE?si=uGSS0waJIuv_uKmq" 
            title="YouTube video player" frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
        </iframe>

        <!-- Second video -->
        <iframe src="https://www.youtube.com/embed/lfiRlkZk23U?si=TApmLYhe8S4i6RZq" 
            title="Vimeo video player" frameborder="0" 
            allow="autoplay; fullscreen; picture-in-picture" allowfullscreen>
        </iframe>
    </div>
</div>

<script>
// ✅ Banner slider
let currentSlide = 0;
const slides = document.querySelectorAll('.banner-slide');
const dots = document.querySelectorAll('.banner-dots span');

function showSlide(index) {
    slides.forEach((s, i) => s.classList.toggle('active', i === index));
    dots.forEach((d, i) => d.classList.toggle('active', i === index));
    currentSlide = index;
}
function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
}
if (slides.length > 1) { setInterval(nextSlide, 4000); }
</script>

</body>
</html>
