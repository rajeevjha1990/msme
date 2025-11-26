<style>
/* Footer Specific CSS - Prefixed with .msme-footer to avoid conflicts */
.msme-footer * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.msme-footer {
  background-color: #0a1229;
  color: white;
  padding: 40px 20px;
  font-family: 'Segoe UI', sans-serif;
  margin-top: 50px;
}

.msme-footer .footer-container {
  max-width: 1200px;
  margin: auto;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: 40px;
}
/*
.msme-footer .footer-column {
  flex: 1;
  min-width: 260px;
  flex-basis: 0;
}*/

.msme-footer .footer-logo {
  width: 200px;
  margin-bottom: 15px;
  height: auto;
}

.msme-footer .footer-column p {
  margin-bottom: 10px;
  line-height: 1.5;
  font-size: 14px;
}

.msme-footer .footer-column h4 {
  margin-bottom: 20px;
  font-size: 18px;
  font-weight: bold;
  color: #ffffff;
}

.msme-footer .footer-column a {
  color: white;
  text-decoration: none;
  transition: color 0.3s ease;
}

.msme-footer .footer-column a:hover {
  color: #f26522;
}

.msme-footer .footer-social {
  margin-top: 15px;
  display: flex;
  gap: 10px;
}

.msme-footer .footer-social a {
  display: inline-block;
  width: 32px;
  height: 32px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.msme-footer .footer-social a:hover {
  background: #f26522;
  transform: translateY(-2px);
}

.msme-footer .footer-social img {
  width: 16px;
  height: 16px;
  filter: brightness(0) invert(1);
}

.msme-footer .footer-links {
  list-style: none;
}

.msme-footer .footer-links li {
  margin-bottom: 8px;
  font-size: 14px;
}

.msme-footer .footer-links a {
  color: #cccccc;
  text-decoration: none;
  transition: color 0.3s ease;
}

.msme-footer .footer-links a:hover {
  color: #f26522;
}

.msme-footer .footer-more-link {
  color: #00aaff;
  text-decoration: none;
  font-weight: bold;
}

.msme-footer .footer-more-link:hover {
  color: #f26522;
}

.msme-footer .footer-bottom {
  text-align: center;
  border-top: 1px solid #444;
  padding-top: 20px;
  margin-top: 30px;
}

.msme-footer .footer-bottom p {
  margin-bottom: 15px;
  font-size: 14px;
  color: #cccccc;
}

.msme-footer .footer-register-btn {
  background-color: #f44336;
  color: white;
  padding: 12px 24px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
  text-decoration: none;
  display: inline-block;
  transition: all 0.3s ease;
}

.msme-footer .footer-register-btn:hover {
  background-color: #d32f2f;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
}

/* Mobile Responsive */
@media screen and (max-width: 768px) {
  .msme-footer {
    padding: 30px 15px;
  }

  .msme-footer .footer-container {
    flex-direction: column;
    gap: 30px;
  }

  .msme-footer .footer-column {
    min-width: 100%;
    text-align: center;
  }

  .msme-footer .footer-social {
    justify-content: center;
  }

  .msme-footer .footer-logo {
    display: block;
    margin: 0 auto 15px;
  }
}

@media screen and (max-width: 480px) {
  .msme-footer .footer-container {
    gap: 25px;
  }

  .msme-footer .footer-column h4 {
    font-size: 16px;
  }

  .msme-footer .footer-column p,
  .msme-footer .footer-links li {
    font-size: 13px;
  }
}
/*Business-directory-css*/
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<footer class="msme-footer">
  <div class="footer-container">
    <!-- Column 1: Logo & Contact -->
    <div class="footer-column">
      <img src="assets/logo22.png" alt="MSME Global Logo" class="footer-logo">
      <p><strong>Address:</strong> Mercantile Building, 9 Bentinck Street, 1st Floor,<br>Kolkata 700001. (Landmark - Dalhousie)</p>
      <p><strong>Call:</strong> <a href="tel:+919331177595">+91 9331177595</a></p>
      <p><strong>Email:</strong> <a href="mailto:support@msmeglobal.com">support@msmeglobal.com</a></p>

      <div class="footer-social">
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

    <!-- Column 2: Features -->
    <div class="footer-column">
      <h4>FEATURES</h4>
      <ul class="footer-links">
        <li><a href="#">Corporate Gifting</a></li>
        <li><a href="#">Real Estate Consultant</a></li>
        <li><a href="#">IT & Networking Services</a></li>
        <li><a href="#">Financial Advisor</a></li>
        <li><a href="#">Interior Designer</a></li>
        <li><a href="#">Health & Wellness Services</a></li>
        <li><a href="#">Tarot Card Reader</a></li>
        <li><a href="#">Courier & Local Delivery</a></li>
        <li><a href="#">Fine/Fashion/Imitation Jewelry</a></li>
        <li><a href="#">Mutual Fund & SIP</a></li>
        <li><a href="#">Baker</a></li>
        <li><a href="#">Lawyer</a></li>
      </ul>
      <a href="browse-directory2.php" class="footer-more-link">View More Categories</a>
    </div>

    <!-- Column 3: Quick Links -->
    <div class="footer-column">
      <h4>QUICK LINKS</h4>
      <ul class="footer-links">
        <li><a href="terms.php">Terms & Conditions</a></li>
        <li><a href="privacy.php">Privacy Policy</a></li>
        <li><a href="shipping.php">Shipping & Delivery Policy</a></li>
        <li><a href="refund.php">Cancellation & Refund Policy</a></li>
        <li><a href="contact.php">Contact Us</a></li>
        <li><a href="faq.php">FAQ</a></li>
        <li><a href="sitemap.php">Sitemap</a></li>
      </ul>
    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="footer-bottom">
    <p>&copy; <?php echo date('Y'); ?> MSME Global. All Rights Reserved. | Designed with ❤️ for Indian MSMEs</p>
    <a href="mainregister.php" class="footer-register-btn">
      <i class="fas fa-plus-circle"></i> Register Your Business
    </a>
  </div>
</footer>

<script>
// Smooth scroll for footer links
document.querySelectorAll('.msme-footer a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  });
});

// Add loading animation for register button
document.querySelector('.footer-register-btn').addEventListener('click', function(e) {
  if (this.href.includes('register.php')) {
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Redirecting...';
  }
});
</script>

</body>
</html>
