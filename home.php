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
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif;

          background: #fff; color: #222; line-height: 1.6;

         overflow-x: hidden;
        }

        header {
          position: fixed;
          top: 0;
          width: 100%;
          z-index: 1000;
          backdrop-filter: blur(10px);
          background: rgba(11, 11, 59, 0.6);
          padding: 10px 30px;
          border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        nav {
          display: flex;
          justify-content: space-between;
          align-items: center;
          flex-wrap: wrap;
        }
        .logo {
          font-size: 24px;
          color: #ff6600;
          font-weight: bold;
        }
        nav ul {
          list-style: none;
          display: flex;
          gap: 20px;
          align-items: center;
          padding: 0;
          margin: 0;
        }

        nav ul li {
          font-size: 16px;
        }

        nav ul li a {
          color: white;
          text-decoration: none;
          font-size: inherit;
        }

        nav ul li a:hover {
          color: #f26522;
        }

        .login-btn {
          border: 2px solid white;
          padding: 5px 15px;
          border-radius: 25px;
          background: transparent;
          color: white;
          font-weight: bold;
          cursor: pointer;
          display: inline-block;
        }

        .login-btn:hover {
          background: white;
          color: #f26522;
        }

         .hero {
          background: url('assets/a3.jpg') no-repeat center center/cover;
          height: 100vh;
           backdrop-filter: blur(10px);
          position: relative;
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .hero-content {
          display: flex;
          justify-content: space-between;
          align-items: center;
          width: 90%;
          max-width: 1200px;
          gap: 30px;
          color: white;
        }

        .hero-left {
          flex: 1;
          padding: 30px;
          border-radius: 20px;
        }

        .hero-left h2 {
          font-size: 3em;
          line-height: 1.4;
        }

        .hero-left h2 span {
          color: yellow;
        }

        .hero-left .dropdown {
          margin-top: 20px;
        }

        .hero-left select {
          padding: 10px 15px;
          font-size: 16px;
          border-radius: 25px;
          border: none;
          background: rgba(255, 255, 255, 0.9);
        }

        .hero-right {
          flex: 1;
          display: flex;
          justify-content: flex-end;
          align-items: center;
          padding:0px;
        }

        .hero-right img {
          width: 550px;
          height: auto;
          object-fit: contain;
          filter: brightness(1.1) contrast(1.1);
        }

        .hero-overlay {
          backdrop-filter: blur(10px);
          background: rgba(0, 0, 0, 0.45);
          padding: 30px;
          border-radius: 20px;
          max-width: 600px;
          color: white;
        }
        .hero-overlay h2 {
          font-size: 2em;
        }
        .hero-overlay h2 span {
          color: yellow;
        }

        select {
          padding: 10px 15px;
          font-size: 16px;
          border-radius: 25px;
          border: none;
          background: rgba(255, 255, 255, 0.9);
        }

        section {
          padding: 60px 30px;
        }

        .category-grid {
          display: grid;
          grid-template-columns: repeat(5, 1fr);
          gap: 30px;
          max-width: 1200px;
          margin: 30px auto 0;
          padding: 0 20px;
        }

        .category-card {
          text-decoration: none;
          color: inherit;
          display: block;
          text-align: center;
          padding: 10px;
          transition: transform 0.2s ease;
        }

        .category-card:hover {
          transform: scale(1.05);
          text-decoration: none;
          color: inherit;
        }

        .category-card img {
          width: 80px;
          height: 80px;
          object-fit: contain;
          margin-bottom: 10px;
        }

        .category-card p {
          font-size: 14px;
          color: #333;
          margin: 0;
        }

        .category-card:hover p {
          color: #f26522;
          font-weight: bold;
        }



          .hidden-influencer {
    display: none;
  }

  /* Scrollable box for more influencers */
  .influencer-grid.show-more {
    max-height: 400px; /* adjust height */
    overflow-y: auto;
    border: 1px solid #ccc;
    padding: 10px;
  }

  .influencer-card {
    border: 1px solid #ddd;
    padding: 10px;
    margin: 10px;
    border-radius: 6px;
  }

        .gradient-box {
          background: linear-gradient(90deg, #7f4db8, #f09224);
          color: #fff;
          padding: 15px;
          margin-bottom: 30px;
          text-align: center;
          font-weight: bold;
        }

        .pillars {
          display: flex;
          gap: 30px;
          flex-wrap: wrap;
          justify-content: center;
        }

        .pillars button {
          display: block;
          background: #eee;
          border: none;
          padding: 15px 20px;
          font-weight: bold;
          border-radius: 10px;
          width: 250px;
          margin-bottom: 10px;
          text-align: left;
          cursor: pointer;
        }

        .about-box {
          background: linear-gradient(to right, #f3e5f5, #fbe9e7);
          padding: 40px;
          border-radius: 15px;
          text-align: center;
        }

        .testimonial {
          background: #f0f0f0;
          padding: 30px;
          border-radius: 15px;
          margin: 20px auto;
          width: 60%;
        }

        /* Sliding banner */
        .advertisement-banner {
          background: linear-gradient(90deg, #7f4db8, #f09224);
          color: #fff;
          font-weight: bold;
          padding: 15px 0;
          overflow: hidden;
          position: relative;
          margin-bottom: 30px;
        }

        .advertisement-text {
          display: inline-block;
          white-space: nowrap;
          animation: slide-left 15s linear infinite;
          font-size: 18px;
          padding-left: 100%;
        }

        @keyframes slide-left {
          0% { transform: translateX(0); }
          100% { transform: translateX(-100%); }
        }

        /* Influencer layout */
        .influencer-grid {
          display: flex;
          flex-wrap: wrap;
          gap: 20px;
          justify-content: center;
        }

        .influencer-card {
          background: #eee;
          border-radius: 20px;
          display: flex;
          align-items: center;
          gap: 20px;
          padding: 20px;
          width: 500px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .influencer-card img {
          width: 80px;
          height: 80px;
          object-fit: cover;
          border-radius: 50%;
        }

        .influencer-info p {
          margin: 5px 0;
        }

        .pillars-mentors-container {
          max-width: 1200px;
          margin: 0 auto;
          font-family: 'Arial', sans-serif;
        }

        .pillars-mentors-title {
          text-align: center;
          font-size: 2.5rem;
          color: #333;
          margin-bottom: 40px;
          font-weight: bold;
        }

        .pillars-mentors-content {
          display: flex;
          gap: 40px;
          align-items: flex-start;
        }

        .mentors-column {
          flex: 1;
          display: flex;
          flex-direction: column;
          gap: 15px;
        }

        .mentor-dropdown {
          position: relative;
          background: #e8e8e8;
          border-radius: 25px;
          overflow: hidden;
          box-shadow: 0 2px 5px rgba(0,0,0,0.1);
          transition: all 0.3s ease;
        }

        .mentor-dropdown:hover {
          transform: translateY(-2px);
          box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .mentor-header {
          display: flex;
          align-items: center;
          padding: 15px 20px;
          cursor: pointer;
          background: #e8e8e8;
          transition: background-color 0.3s ease;
        }

        .mentor-header:hover {
          background: #ddd;
        }

     .mentor-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #666;
    margin-right: 15px;
    flex-shrink: 0;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    /* Default fallback avatar when no photo URL */
    background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"><circle cx="20" cy="15" r="8" fill="%23fff"/><path d="M4 35c0-8 7-15 16-15s16 7 16 15" fill="%23fff"/></svg>');
}

/* When photo is available, it overrides the default */
.mentor-avatar[style*="background-image: url("] {
    background-size: cover;
}
        .mentor-info {
          flex: 1;
        }

        .mentor-name {
          font-weight: bold;
          color: #333;
          font-size: 16px;
        }

        .mentor-title {
          color: #666;
          font-size: 14px;
          margin-top: 2px;
        }

        .mentor-title a {
          color: #4a90e2;
          text-decoration: none;
        }

        .dropdown-arrow {
          font-size: 18px;
          color: #666;
          transition: transform 0.3s ease;
          margin-left: 10px;
        }

        .mentor-dropdown.active .dropdown-arrow {
          transform: rotate(180deg);
        }

        .mentor-content {
          max-height: 0;
          overflow: hidden;
          background: #f9f9f9;
          transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .mentor-dropdown.active .mentor-content {
          max-height: 200px;
          padding: 20px;
        }

        .video-column {
          flex: 0 0 500px;
        }

        .video-container {
          width: 100%;
          height: 375px;
          border-radius: 15px;
          overflow: hidden;
          box-shadow: 0 8px 25px rgba(0,0,0,0.15);
          background: linear-gradient(135deg, #b48ad2, #f3a450);
          position: relative;
        }

        .video-container iframe {
          width: 100%;
          height: 100%;
          border: none;
        }

        .video-placeholder {
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          color: rgba(255,255,255,0.8);
          font-size: 16px;
          text-align: center;
        }

        .play-button {
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          width: 60px;
          height: 60px;
          background: rgba(0,0,0,0.7);
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          cursor: pointer;
          transition: all 0.3s ease;
        }

        .play-button:hover {
          background: rgba(0,0,0,0.9);
          transform: translate(-50%, -50%) scale(1.1);
        }

        .play-button::after {
          content: '';
          width: 0;
          height: 0;
          border-left: 20px solid white;
          border-top: 12px solid transparent;
          border-bottom: 12px solid transparent;
          margin-left: 4px;
        }

        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }

        .msme-hero-section {
          position: relative;
          height: 80vh;
          overflow: hidden;
          background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }

        .background-image {
          position: absolute;
          top: 0;
          left: 0;
          width: 60%;
          height: 100%;
          background-image: url('./assets/a2.jpg');
          background-size:cover;
          background-position: center;
          background-repeat: no-repeat;
          opacity: 0.7;
        }

        .content-overlay {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          z-index: 10;
        }

        /* MSME GLOBAL Text - Modified for scroll animations */
        .msme-logo {
          position: absolute;
          top: 25%;
          left: 15%;
          transform: translate(-50%, -50%);
          text-align: center;
        }

        .msme-text {
          font-family: 'Arial Black', Arial, sans-serif;
          font-size: 4rem;
          display: block;
          font-weight: 900;
          color: #f5a623;
          line-height: 0.8;
          margin-bottom: -10px;
          text-shadow: 3px 3px 6px rgba(0,0,0,0.8);
          opacity: 0;
          transform: scale(0.3);
          transition: all 1s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .msme-text.animate {
          opacity: 1;
          transform: scale(1);
        }

        .global-text {
          font-family: 'Arial', sans-serif;
          font-size: 2.5rem;
          font-weight: 300;
          color: white;
          text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
          margin-top: -10px;
          margin-left: -110px;
          line-height: 1;
          text-align: center;
          opacity: 0;
          transform: translateY(20px);
          transition: all 1s ease-out;
          transition-delay: 0.5s;
        }

        .global-text.animate {
          opacity: 1;
          transform: translateY(0);
        }

        /* Info Boxes - Modified for scroll animations */
        .info-boxes {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
        }

        .info-box {
          position: absolute;
          backdrop-filter: blur(10px);
          background: rgba(0, 0, 0, 0.45);
          border: 2px solid #f5a623;
          border-radius: 15px;
          padding: 20px;
          color: white;
          font-family: 'Arial', sans-serif;
          max-width: 320px;
          backdrop-filter: blur(8px);
          box-shadow: 0 8px 25px rgba(165, 158, 158, 0.6);
          opacity: 0;
          transform: translateY(30px) scale(0.9);
          transition: all 1s ease-out;
        }

        .info-box.animate {
          opacity: 1;
          transform: translateY(0) scale(1);
        }

        .info-box h3 {
          color: #f5a623;
          margin-bottom: 12px;
          font-size: 1.3rem;
          font-weight: bold;
          text-align: center;
        }

        .info-box ul {
          list-style: none;
          line-height: 1.5;
        }

        .info-box li {
          margin-bottom: 6px;
          font-size: 0.85rem;
          position: relative;
          padding-left: 10px;
          opacity: 0;
          transform: translateX(-15px);
          transition: all 0.6s ease-out;
        }

        .info-box.animate li {
          opacity: 1;
          transform: translateX(0);
        }

        .info-box li:before {
          content: "•";
          color: #f5a623;
          position: absolute;
          left: 0;
          font-weight: bold;
        }

        /* Add staggered delays for list items */
        .info-box.animate li:nth-child(1) { transition-delay: 0.2s; }
        .info-box.animate li:nth-child(2) { transition-delay: 0.4s; }
        .info-box.animate li:nth-child(3) { transition-delay: 0.6s; }
        .info-box.animate li:nth-child(4) { transition-delay: 0.8s; }
        .info-box.animate li:nth-child(5) { transition-delay: 1s; }

        /* Position boxes based on your background image layout */
        .goals-box {
          top: 12%;
          right: 8%;
          padding: 30px;
          transition-delay: 0.5s;
        }

        .who-should-join-box {
          bottom: 0%;
          right: 70%;
          transition-delay: 1s;
        }

        .usp-box {
          bottom: 35%;
          left: 30%;
          transition-delay: 1.5s;
        }

        @media (max-width: 1200px) {
          .msme-text { font-size: 3.2rem; }
          .global-text { font-size: 2rem; }
          .info-box { max-width: 280px; padding: 18px; }
        }

        @media (max-width: 768px) {
          .msme-logo {
            top: 30%;
            left: 50%;
          }
          .msme-text { font-size: 2.5rem; }
          .global-text { font-size: 1.6rem; }

          .info-box {
            position: relative;
            margin: 20px auto;
            max-width: 90%;
            transform: none !important;
            opacity: 1 !important;
            transition: none !important;
          }

          .goals-box, .who-should-join-box, .usp-box {
            position: static;
            margin-top: 400px;
          }

          .msme-hero-section { height: auto; min-height: 100vh; }
        }

        @media (max-width: 768px) {
          .pillars-mentors-content {
            flex-direction: column;
          }

          .video-column {
            flex: none;
            width: 100%;
          }

          .pillars-mentors-title {
            font-size: 2rem;
          }
        }

        .about-us-section {
          padding: 60px 20px;
          background: linear-gradient(135deg, #e8d5f2, #f4e4d1);
          min-height: 100vh;
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .about-us-container {
          max-width: 800px;
          width: 100%;
        }

        .about-us-header {
          background: linear-gradient(135deg, #6b46c1, #f59e0b);
          color: white;
          padding: 15px 40px;
          border-radius: 25px;
          display: inline-block;
          font-size: 24px;
          font-weight: bold;
          margin-bottom: 30px;
          box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .about-us-box {
          background: rgba(255, 255, 255, 0.6);
          backdrop-filter: blur(10px);
          border: 1px solid rgba(255, 255, 255, 0.3);
          border-radius: 20px;
          padding: 40px;
          box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .about-us-description {
          text-align: center;
          font-size: 16px;
          line-height: 1.6;
          color: #333;
          margin-bottom: 40px;
          font-weight: 400;
        }

        .vision-mission-container {
          display: flex;
          gap: 30px;
          margin-top: 30px;
        }

        .vision-box, .mission-box {
          flex: 1;
          text-align: center;
        }

        .vision-title, .mission-title {
          font-size: 20px;
          font-weight: bold;
          margin-bottom: 20px;
          color: #333;
        }

        .vision-title {
          color: #1f2937;
        }

        .mission-title {
          color: #d97706;
        }

        .vision-content, .mission-content {
          font-size: 14px;
          line-height: 1.5;
          color: #4b5563;
          font-weight: 400;
        }

        .divider {
          width: 1px;
          background: linear-gradient(to bottom, transparent, #d1d5db, transparent);
          margin: 0 15px;
        }

        /* Modified testimonial section for scroll animations */
        .testimonial-section {
          padding: 60px 20px;
          background: linear-gradient(135deg, #ffffff, #eef2f3);
        }

        .testimonial-heading {
          text-align: center;
          font-size: 32px;
          font-weight: bold;
          margin-bottom: 50px;
          color: #222;
        }

        .testimonial-container {
          display: flex;
          margin: 30px 0;
          opacity: 0;
          transition: all 1s ease-out;
        }

        .testimonial-left {
          justify-content: flex-start;
          transform: translateX(-100px);
        }

        .testimonial-right {
          justify-content: flex-end;
          transform: translateX(100px);
        }

        .testimonial-container.animate {
          opacity: 1;
          transform: translateX(0);
        }

        .testimonial-card {
          max-width: 600px;
          width: 90%;
          padding: 25px;
          border-radius: 20px;
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .testimonial-anurag {
          background-color: #e1d5f5;
        }

        .testimonial-sweta {
          background-color: #fbe9e7;
        }

        .testimonial-content {
          display: flex;
          gap: 20px;
          align-items: flex-start;
        }

        .testimonial-image {
          width: 80px;
          height: 80px;
          border-radius: 50%;
          object-fit: cover;
          border: 3px solid white;
          box-shadow: 0 0 6px rgba(0, 0, 0, 0.2);
        }

        .testimonial-name {
          font-size: 20px;
          font-weight: bold;
          margin-bottom: 5px;
        }

        .testimonial-title {
          font-size: 14px;
          font-style: italic;
          margin-bottom: 10px;
          color: #555;
        }

        .testimonial-text {
          font-size: 15px;
          line-height: 1.6;
          color: #333;
        }

        .whatsapp-btn {
          position: fixed;
          bottom: 30px;
          right: 20px;
          background-color: #25D366;
          color: white;
          font-size: 28px;
          width: 55px;
          height: 55px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          box-shadow: 0 4px 6px rgba(0,0,0,0.3);
          z-index: 2000;
          transition: transform 0.2s ease-in-out;
          text-decoration: none;
        }

        .whatsapp-btn:hover {
          transform: scale(1.1);
          background-color: #20b858;
          color: white;
        }

        @media (max-width: 768px) {
          .about-us-section {
            padding: 40px 15px;
          }

          .about-us-box {
            padding: 30px 20px;
          }

          .vision-mission-container {
            flex-direction: column;
            gap: 25px;
          }

          .divider {
            display: none;
          }

          .about-us-header {
            font-size: 20px;
            padding: 12px 30px;
          }
        }

        @media screen and (max-width: 768px) {
          .pillars, .category-grid, .influencer-grid {
            flex-direction: column;
            align-items: center;
          }
          .testimonial {
            width: 90%;
          }
        }

        .banner-slider {
          position: relative;
          width: 100%;
          height: 400px;
          overflow: hidden;
        }

        .banner-slider .slide {
          position: absolute;
          width: 100%;
          height: 100%;
          opacity: 0;
          transition: opacity 1s ease-in-out;
        }

        .banner-slider .slide img {
          width: 100%;
          height: 100%;
          object-fit: contain;
        }

        .banner-slider .slide.active {
          opacity: 1;
        }
        .hidden-influencer {
    display: none !important;
}

.influencer-grid.show-more .hidden-influencer {
    display: flex !important;
}


/* See More Button Styling */
.see-more-container {
  text-align: center;
  margin: 30px 0;
  padding: 20px;
}

/* Base button styling */
.toggle-btn {
  background: linear-gradient(135deg, #7f4db8, #f09224);
  color: white;
  border: none;
  padding: 15px 40px;
  font-size: 16px;
  font-weight: 600;
  border-radius: 30px;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(127, 77, 184, 0.3);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  min-width: 160px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.toggle-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(127, 77, 184, 0.4);
  background: linear-gradient(135deg, #8c5bc4, #f5a623);
}

.toggle-btn:active {
  transform: translateY(0);
  box-shadow: 0 2px 10px rgba(127, 77, 184, 0.3);
}

/* Ripple effect on click */
.toggle-btn::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.toggle-btn:active::before {
  width: 300px;
  height: 300px;
}

/* Icons for See More/Less */
.toggle-btn.see-more::after {
  content: '▼';
  margin-left: 8px;
  font-size: 12px;
  transition: transform 0.3s ease;
}

.toggle-btn.see-less::after {
  content: '▲';
  margin-left: 8px;
  font-size: 12px;
  transition: transform 0.3s ease;
}

.toggle-btn:hover::after {
  transform: translateY(2px);
}

/* See Less specific styling */
.toggle-btn.see-less {
  background: linear-gradient(135deg, #dc3545, #c82333);
  box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.toggle-btn.see-less:hover {
  background: linear-gradient(135deg, #e74c3c, #d63031);
  box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
}

/* Hidden influencers styling */
.hidden-influencer {
  display: none !important;
}

.influencer-grid.show-more .hidden-influencer {
  display: flex !important;
}

/* Scrollable container when expanded */
.influencer-grid.show-more {
  max-height: 500px;
  overflow-y: auto;
  border: 2px solid #f0f0f0;
  border-radius: 15px;
  padding: 20px;
  background: #fafafa;
}

/* Custom scrollbar for the expanded view */
.influencer-grid.show-more::-webkit-scrollbar {
  width: 8px;
}

.influencer-grid.show-more::-webkit-scrollbar-track {
  background: #e0e0e0;
  border-radius: 4px;
}

.influencer-grid.show-more::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #7f4db8, #f09224);
  border-radius: 4px;
}

.influencer-grid.show-more::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #8c5bc4, #f5a623);
}

/* Responsive styling */
@media (max-width: 768px) {
  .toggle-btn {
    padding: 12px 30px;
    font-size: 14px;
    min-width: 140px;
  }

  .see-more-container {
    margin: 20px 0;
    padding: 15px;
  }

  .influencer-grid.show-more {
    max-height: 400px;
    padding: 15px;
  }
}
    </style>
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

 <script>
        function toggleDropdown(element) {
            // Close all other dropdowns
            const allDropdowns = document.querySelectorAll('.mentor-dropdown');
            allDropdowns.forEach(dropdown => {
                if (dropdown !== element) {
                    dropdown.classList.remove('active');
                }
            });

            // Toggle current dropdown
            element.classList.toggle('active');
        }

        // Optional: Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.mentor-dropdown')) {
                const allDropdowns = document.querySelectorAll('.mentor-dropdown');
                allDropdowns.forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }
        });

        // Auto-play video when scrolling into view
        function isElementInViewport(el) {
            const rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        function handleScroll() {
            const videoContainer = document.getElementById('videoContainer');
            const iframe = document.getElementById('pillarsVideo');

            if (isElementInViewport(videoContainer)) {
                // Video is in viewport, play it
                iframe.src = iframe.src.replace('mute=0', 'mute=0&autoplay=1');
            }
        }

        // Listen for scroll events
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(handleScroll, 100);
        });

        // Check on page load
        document.addEventListener('DOMContentLoaded', handleScroll);
    </script>


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
 <script>
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                } else {
                    // Remove animate class when element goes out of view (for re-animation)
                    entry.target.classList.remove('animate');
                }
            });
        }, observerOptions);

        // Observe all scroll-animate elements
        document.addEventListener('DOMContentLoaded', () => {
            const scrollElements = document.querySelectorAll('.scroll-animate');
            scrollElements.forEach(el => observer.observe(el));
        });

        // Special handling for testimonials with different delays
        const testimonialObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    // Add delay for staggered animation
                    setTimeout(() => {
                        entry.target.classList.add('animate');
                    }, index * 300); // 300ms delay between each testimonial
                } else {
                    entry.target.classList.remove('animate');
                }
            });
        }, observerOptions);

        // Observe testimonial containers separately
        document.addEventListener('DOMContentLoaded', () => {
            const testimonialContainers = document.querySelectorAll('.testimonial-container');
            testimonialContainers.forEach(el => testimonialObserver.observe(el));
        });

        // Handle MSME logo animations with proper sequencing
        const msmeObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const msmeText = entry.target.querySelector('.msme-text');
                    const globalText = entry.target.querySelector('.global-text');

                    // Animate MSME first
                    if (msmeText) {
                        msmeText.classList.add('animate');
                    }

                    // Animate GLOBAL after MSME with delay
                    if (globalText) {
                        setTimeout(() => {
                            globalText.classList.add('animate');
                        }, 500);
                    }
                } else {
                    // Remove animations when out of view
                    const msmeText = entry.target.querySelector('.msme-text');
                    const globalText = entry.target.querySelector('.global-text');

                    if (msmeText) msmeText.classList.remove('animate');
                    if (globalText) globalText.classList.remove('animate');
                }
            });
        }, observerOptions);

        // Observe MSME logo section
        document.addEventListener('DOMContentLoaded', () => {
            const msmeLogoSection = document.querySelector('.msme-logo');
            if (msmeLogoSection) {
                msmeObserver.observe(msmeLogoSection);
            }
        });

        // Enhanced observer for info boxes with staggered animations
        const infoBoxObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                } else {
                    entry.target.classList.remove('animate');
                }
            });
        }, {
            threshold: 0.2,
            rootMargin: '0px 0px -100px 0px'
        });

        // Observe info boxes
        document.addEventListener('DOMContentLoaded', () => {
            const infoBoxes = document.querySelectorAll('.info-box');
            infoBoxes.forEach((box, index) => {
                // Add staggered delay for each box
                box.style.transitionDelay = `${index * 0.3}s`;
                infoBoxObserver.observe(box);
            });
        });
    </script>

  <script>
    let slideIndex = 0;
    showSlides();

    function showSlides() {
      let slides = document.querySelectorAll(".banner-slider .slide");
      slides.forEach(slide => slide.classList.remove("active"));
      slideIndex++;
      if (slideIndex > slides.length) { slideIndex = 1; }
      slides[slideIndex - 1].classList.add("active");
      setTimeout(showSlides, 4000); // Change every 4s
    }



  </script>



<script>
document.addEventListener('DOMContentLoaded', function() {
  const toggleBtn = document.getElementById('toggleBtn');
  const influencerGrid = document.querySelector('.influencer-grid');
  let isExpanded = false;

  toggleBtn.addEventListener('click', function() {
    if (!isExpanded) {
      // Show more influencers
      influencerGrid.classList.add('show-more');

      // Change button to "See Less"
      toggleBtn.textContent = 'See Less';
      toggleBtn.className = 'toggle-btn see-less';

      // Smooth scroll to the expanded section
      setTimeout(() => {
        toggleBtn.scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
      }, 100);

      isExpanded = true;
    } else {
      // Hide extra influencers
      influencerGrid.classList.remove('show-more');

      // Change button back to "See More"
      toggleBtn.textContent = 'See More';
      toggleBtn.className = 'toggle-btn see-more';

      // Scroll back to the top of influencers section
      setTimeout(() => {
        influencerGrid.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }, 100);

      isExpanded = false;
    }
  });
});


</script>

<!-- Footer -->
<?php include 'common/footer.php'; ?>

</body>
</html>
