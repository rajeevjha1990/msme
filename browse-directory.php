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
<title>Browse Category</title>
<style>
/* Page-specific styles */
.browse-category-page {
    font-family: Arial, sans-serif;
    padding: 0;
    margin: 0;
}

/* Header Section */
.browse-category-header {a
    background: linear-gradient(135deg, #e77b3e, #d05b91);
    padding: 40px 20px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}

.browse-category-header h1 {
    font-size: 2rem;
    margin: 0 0 10px 0;
}
.browse-category-header p {
    font-size: 1rem;
    margin: 0;
}
.browse-category-header .header-logo {
    width: 120px;
    height: 120px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #c33;
    text-align: center;
}

/* Section Title */
.section-title {
    text-align: center;
    margin: 40px 0 20px;
    font-size: 1.8rem;
    font-weight: bold;
    color: #4a148c;
}

/* Category Grid */
.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    padding: 0 20px 40px;
}

/* Category Card */
.category-card {
    background: #f2f2f2;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.category-icon {
    width: 80px;
    height: 80px;
    background: #ccc;
    border-radius: 50%;
    margin: 0 auto 15px;
}
.category-card h2 {
    font-size: 1.2rem;
    margin: 10px 0;
    color: #333;
}
.category-card p {
    font-size: 0.9rem;
    color: #666;
}
</style>
</head>
<body>

<div class="browse-category-page">

    <!-- Header -->
    <section class="browse-category-header">
        <div class="header-text">
            <h1>Browse Category</h1>
            <p>Discover Local to Global MSME Business Categories</p>
        </div>
        <div class="header-logo">LOGO</div>
    </section>

    <!-- Section Title -->
    <div class="section-title">Browse Business Categories</div>

    <!-- Category Grid -->
    <section class="category-grid">
        <div class="category-card">
            <div class="category-icon"></div>
            <h2>Yoga / Pilates</h2>
            <p>Explore listings in health and wellness</p>
        </div>
        <div class="category-card">
            <div class="category-icon"></div>
            <h2>IT Services</h2>
            <p>Software, web, and app development</p>
        </div>
        <div class="category-card">
            <div class="category-icon"></div>
            <h2>Food & Beverages</h2>
            <p>Restaurants, catering, and more</p>
        </div>
        <div class="category-card">
            <div class="category-icon"></div>
            <h2>Education</h2>
            <p>Schools, coaching, and training</p>
        </div>
        <div class="category-card">
            <div class="category-icon"></div>
            <h2>Fashion</h2>
            <p>Clothing, accessories, and design</p>
        </div>
        <div class="category-card">
            <div class="category-icon"></div>
            <h2>Healthcare</h2>
            <p>Hospitals, clinics, and wellness</p>
        </div>
    </section>

</div>

</body>
</html>
