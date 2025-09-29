<?php 
include 'common/header.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<title>Browse Directory</title>
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

/* Page-specific styles */
.browse-directory-page {
    font-family: Arial, sans-serif;
    padding-top: 2%;
    margin: 0;
    background-color: #f8f9fa;
    
}

/* Header Section */
.browse-directory-header {
    background: linear-gradient(135deg, #e77b3e, #d05b91, #c44db8);
    padding: 40px 20px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}

.browse-directory-header h1 {
    font-size: 2.5rem;
    margin: 0 0 10px 0;
    font-weight: bold;
}

.browse-directory-header p {
    font-size: 1.1rem;
    margin: 0;
    opacity: 0.9;
}



/* Section Title */
.section-title {
    text-align: center;
    margin: 40px 0 30px;
    font-size: 2rem;
    font-weight: bold;
    color: #4a148c;
}

/* Directory Grid */
.directory-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    padding: 0 20px 40px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Directory Card */
.directory-card {
    background: linear-gradient(135deg, #f8c4d8, #e6a8cc);
    border-radius: 15px;
    padding: 25px;
    text-align: left;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.directory-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.profile-section {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.profile-image {
    width: 60px;
    height: 60px;
    background: #8e8e8e;
    border-radius: 50%;
    margin-right: 15px;
    flex-shrink: 0;
}

.profile-info h3 {
    font-size: 1.1rem;
    margin: 0 0 5px 0;
    color: #333;
    font-weight: bold;
}

.profile-info .organization {
    font-size: 0.95rem;
    color: #555;
    margin: 0 0 5px 0;
}

.profile-info .category {
    font-size: 0.9rem;
    color: #666;
    margin: 0;
}

.social-icons {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 15px;
}

.social-icon {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    font-size: 18px;
    transition: transform 0.2s ease;
}

.social-icon:hover {
    transform: scale(1.1);
}

.social-icon.facebook {
    background: #1877f2;
}

.social-icon.instagram {
    background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
}

.social-icon.linkedin {
    background: #0077b5;
}

.social-icon.whatsapp {
    background: #25d366;
}

/* Responsive Design */
@media (max-width: 768px) {
    .browse-directory-header {
        text-align: center;
        flex-direction: column;
        gap: 20px;
    }

    .browse-directory-header h1 {
        font-size: 2rem;
    }

    .directory-grid {
        grid-template-columns: 1fr;
        padding: 0 15px 40px;
    }

    .section-title {
        font-size: 1.6rem;
    }
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

    <!-- Section Title -->
    <div class="section-title">Browse Business Directory</div>

    <!-- Directory Grid -->
    <section class="directory-grid">
        
        <div class="directory-card">
            <div class="profile-section">
                <div class="profile-image"></div>
                <div class="profile-info">
                    <h3>Name: Rina Ghosh</h3>
                    <p class="organization">Organization Name: Veda Yogshala</p>
                    <p class="category">Business Category: Yoga / Pilates</p>
                </div>
            </div>
            <div class="social-icons">
                       
        <a href="https://facebook.com" target="_blank" title="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com" target="_blank" title="Instagram">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="https://twitter.com" target="_blank" title="Twitter">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" title="LinkedIn">
          <i class="fab fa-linkedin-in"></i>
        </a>
        <a href="mailto:support@msmeglobal.com" title="Email">
          <i class="far fa-envelope"></i>
        </a>
            </div>
        </div>

        <div class="directory-card">
            <div class="profile-section">
                <div class="profile-image"></div>
                <div class="profile-info">
                    <h3>Name: Rina Ghosh</h3>
                    <p class="organization">Organization Name: Veda Yogshala</p>
                    <p class="category">Business Category: Yoga / Pilates</p>
                </div>
            </div>
            <div class="social-icons">
                   
        <a href="https://facebook.com" target="_blank" title="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com" target="_blank" title="Instagram">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="https://twitter.com" target="_blank" title="Twitter">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" title="LinkedIn">
          <i class="fab fa-linkedin-in"></i>
        </a>
        <a href="mailto:support@msmeglobal.com" title="Email">
          <i class="far fa-envelope"></i>
        </a>
            </div>
        </div>

        <div class="directory-card">
            <div class="profile-section">
                <div class="profile-image"></div>
                <div class="profile-info">
                    <h3>Name: Rina Ghosh</h3>
                    <p class="organization">Organization Name: Veda Yogshala</p>
                    <p class="category">Business Category: Yoga / Pilates</p>
                </div>
            </div>
            <div class="social-icons">
                      
        <a href="https://facebook.com" target="_blank" title="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com" target="_blank" title="Instagram">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="https://twitter.com" target="_blank" title="Twitter">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" title="LinkedIn">
          <i class="fab fa-linkedin-in"></i>
        </a>
        <a href="mailto:support@msmeglobal.com" title="Email">
          <i class="far fa-envelope"></i>
        </a>
            </div>
        </div>

        <div class="directory-card">
            <div class="profile-section">
                <div class="profile-image"></div>
                <div class="profile-info">
                    <h3>Name: Rina Ghosh</h3>
                    <p class="organization">Organization Name: Veda Yogshala</p>
                    <p class="category">Business Category: Yoga / Pilates</p>
                </div>
            </div>
            <div class="social-icons">
                    
        <a href="https://facebook.com" target="_blank" title="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com" target="_blank" title="Instagram">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="https://twitter.com" target="_blank" title="Twitter">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" title="LinkedIn">
          <i class="fab fa-linkedin-in"></i>
        </a>
        <a href="mailto:support@msmeglobal.com" title="Email">
          <i class="far fa-envelope"></i>
        </a>
            </div>
        </div>

        <div class="directory-card">
            <div class="profile-section">
                <div class="profile-image"></div>
                <div class="profile-info">
                    <h3>Name: Rina Ghosh</h3>
                    <p class="organization">Organization Name: Veda Yogshala</p>
                    <p class="category">Business Category: Yoga / Pilates</p>
                </div>
            </div>
            <div class="social-icons">
                      
        <a href="https://facebook.com" target="_blank" title="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com" target="_blank" title="Instagram">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="https://twitter.com" target="_blank" title="Twitter">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" title="LinkedIn">
          <i class="fab fa-linkedin-in"></i>
        </a>
        <a href="mailto:support@msmeglobal.com" title="Email">
          <i class="far fa-envelope"></i>
        </a>
            </div>
        </div>

        <div class="directory-card">
            <div class="profile-section">
                <div class="profile-image"></div>
                <div class="profile-info">
                    <h3>Name: Rina Ghosh</h3>
                    <p class="organization">Organization Name: Veda Yogshala</p>
                    <p class="category">Business Category: Yoga / Pilates</p>
                </div>
            </div>
            <div class="social-icons">
                      
        <a href="https://facebook.com" target="_blank" title="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com" target="_blank" title="Instagram">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="https://twitter.com" target="_blank" title="Twitter">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" title="LinkedIn">
          <i class="fab fa-linkedin-in"></i>
        </a>
        <a href="mailto:support@msmeglobal.com" title="Email">
          <i class="far fa-envelope"></i>
        </a>
            </div>
        </div>

        <div class="directory-card">
            <div class="profile-section">
                <div class="profile-image"></div>
                <div class="profile-info">
                    <h3>Name: Rina Ghosh</h3>
                    <p class="organization">Organization Name: Veda Yogshala</p>
                    <p class="category">Business Category: Yoga / Pilates</p>
                </div>
            </div>
            <div class="social-icons">
                <a href="#" class="social-icon facebook">📘</a>
                <a href="#" class="social-icon instagram">📷</a>
                <a href="#" class="social-icon linkedin">💼</a>
                <a href="#" class="social-icon whatsapp">💬</a>
            </div>
        </div>

        <div class="directory-card">
            <div class="profile-section">
                <div class="profile-image"></div>
                <div class="profile-info">
                    <h3>Name: Rina Ghosh</h3>
                    <p class="organization">Organization Name: Veda Yogshala</p>
                    <p class="category">Business Category: Yoga / Pilates</p>
                </div>
            </div>
            <div class="social-icons">
             
        <a href="https://facebook.com" target="_blank" title="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com" target="_blank" title="Instagram">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="https://twitter.com" target="_blank" title="Twitter">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" title="LinkedIn">
          <i class="fab fa-linkedin-in"></i>
        </a>
        <a href="mailto:support@msmeglobal.com" title="Email">
          <i class="far fa-envelope"></i>
        </a>
    
    </div>
            </div>
        </div>

    </section>

</div>

<?php 
include 'common/footer.php'; 
?>
</body>
</html>