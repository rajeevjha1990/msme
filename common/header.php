<style>
    /* Header Specific CSS - Prefixed with .msme-header to avoid conflicts */
    .msme-header * { 
      margin: 0; 
      padding: 0; 
      box-sizing: border-box; 
    }
    
    .msme-header {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
      backdrop-filter: blur(10px);
      background: rgba(11, 11, 59, 0.6);
      padding: 10px 30px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      font-family: 'Segoe UI', sans-serif;
    }
    
    .msme-header .header-nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }
    
    .msme-header .header-logo {
      font-size: 24px;
      color: #ff6600;
      font-weight: bold;
      text-decoration: none;
    }
    
    .msme-header .header-nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
      align-items: center;
      padding: 0;
      margin: 0;
    }
    
    .msme-header .header-nav ul li {
      font-size: 16px;
    }
    
    .msme-header .header-nav ul li a {
      color: white;
      text-decoration: none;
      font-size: inherit;
      transition: color 0.3s ease;
    }
    
    .msme-header .header-nav ul li a:hover {
      color: #f26522;
    }
    
    .msme-header .header-login-btn {
      border: 2px solid white;
      padding: 5px 15px;
      border-radius: 25px;
      background: transparent;
      color: white;
      font-weight: bold;
      cursor: pointer;
      display: inline-block;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    
    .msme-header .header-login-btn:hover {
      background: white;
      color: #f26522;
    }
    
    /* Mobile Menu Toggle */
    .msme-header .mobile-menu-toggle {
      display: none;
      color: white;
      font-size: 24px;
      cursor: pointer;
    }
    
    /* Mobile Responsive */
    @media screen and (max-width: 768px) {
      .msme-header {
        padding: 10px 15px;
      }
      
      .msme-header .header-nav ul {
        position: fixed;
        top: 60px;
        left: -100%;
        width: 100%;
        height: calc(100vh - 60px);
        background: rgba(11, 11, 59, 0.95);
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        gap: 30px;
        padding-top: 50px;
        transition: left 0.3s ease;
      }
      
      .msme-header .header-nav ul.active {
        left: 0;
      }
      
      .msme-header .mobile-menu-toggle {
        display: block;
      }
      
      .msme-header .header-nav ul li {
        font-size: 18px;
      }
    }
    

    .msme-header .header-nav ul li ul {
  display: none;
  position: absolute;
  background: rgba(11,11,59,0.95);
  padding: 10px 0;
  border-radius: 6px;
}

.msme-header .header-nav ul li:hover ul {
  display: block;
}

.msme-header .header-logo {
  display: flex;
  align-items: center;
  font-size: 24px;
  color: #ff6600;
  font-weight: bold;
  text-decoration: none;
  gap: 10px; /* space between logo and text */
}

.msme-header .header-logo img {
  height: 50px;  /* adjust size as needed */
  width: auto;
}

  </style>

<header class="msme-header">
  <nav class="header-nav">
<a href="home.php" class="header-logo">
  <img src="assets/logo22.png" alt="MSME Global Logo">

</a>
    
    <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
      <i class="fas fa-bars"></i>
    </div>
    
    <ul id="headerMenu">
      <li><a href="home.php">Home</a></li>
      <li><a href="browse-directory2.php">Browse Directory</a></li>
      <li><a href="plans.php">Plans</a></li>
      <li>
  <a href="mainregister.php">Register</a></li>


      <li><a href="requirements.php">Leads</a></li>
      <li><a href="events.php">Events</a></li>
            <?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
        <li><a href="login.php" class="header-login-btn">Login</a></li>
      <?php else: ?>
        <li class="dropdown">
          <a href="#">Hi, <?php echo htmlspecialchars($_SESSION['name']); ?> ▾</a>
          <ul class="dropdown-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="my-requirements.php">My Requirements</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<script>
function toggleMobileMenu() {
  const menu = document.getElementById('headerMenu');
  const toggle = document.querySelector('.mobile-menu-toggle i');
  
  menu.classList.toggle('active');
  
  if (menu.classList.contains('active')) {
    toggle.classList.remove('fa-bars');
    toggle.classList.add('fa-times');
  } else {
    toggle.classList.remove('fa-times');
    toggle.classList.add('fa-bars');
  }
}

// Close mobile menu when clicking on a link
document.querySelectorAll('.msme-header .header-nav ul li a').forEach(link => {
  link.addEventListener('click', () => {
    const menu = document.getElementById('headerMenu');
    const toggle = document.querySelector('.mobile-menu-toggle i');
    
    menu.classList.remove('active');
    toggle.classList.remove('fa-times');
    toggle.classList.add('fa-bars');
  });
});

// Close mobile menu when clicking outside
document.addEventListener('click', (e) => {
  const header = document.querySelector('.msme-header');
  const menu = document.getElementById('headerMenu');
  const toggle = document.querySelector('.mobile-menu-toggle i');
  
  if (!header.contains(e.target) && menu.classList.contains('active')) {
    menu.classList.remove('active');
    toggle.classList.remove('fa-times');
    toggle.classList.add('fa-bars');
  }
});
</script>