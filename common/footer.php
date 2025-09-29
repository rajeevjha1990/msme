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