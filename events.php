<?php
// Include your common header file
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
    <title>Events Banner</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* About Events Section Styles */
        .about-events-section {
            max-width: 500px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            margin: 0 20px;
            position: relative;
            white-space: nowrap;
        }

        .section-title::before,
        .section-title::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #6b4c93 0%, #e67e49 100%);
            border-radius: 2px;
        }

        .section-title::before {
            right: 100%;
            margin-right: 20px;
        }

        .section-title::after {
            left: 100%;
            margin-left: 20px;
        }

        .title-underline {
            display: none;
        }

        .media-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 40px;
        }

        .card {
            height: 180px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .photos-card {
            background: #6b4c93;
        }

        .videos-card {
            background: #e67e49;
        }

        .card-content {
            text-align: center;
            color: white;
        }

        .card-content h3 {
            font-size: 1.5rem;
            font-weight: 500;
            margin: 0;
            opacity: 0.9;
        }

        .play-icon {
            margin-top: 15px;
            display: flex;
            justify-content: center;
        }

        .event-form {
            margin-top: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-size: 0.95rem;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        .input-container {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px;
            border: none;
            background: #f5f5f5;
            border-radius: 25px;
            font-size: 1rem;
            color: #333;
            box-sizing: border-box;
            transition: background-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            background: #eeeeee;
        }

        .form-input::placeholder {
            color: #999;
        }

        .dropdown-arrow {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 0.8rem;
            pointer-events: none;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .about-events-section {
                padding: 0 15px;
            }
            
            .media-cards {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .card {
                height: 150px;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
        }

        .events-banner {
            position: relative;
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #4a1c40 0%, #2d1b3d 30%, #6b4c93 70%, #e67e49 100%);
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .banner-content {
            position: relative;
            z-index: 10;
            padding: 0 60px;
            color: white;
        }

        .banner-title {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            letter-spacing: -1px;
        }

        .banner-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Decorative shapes */
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .shape-1 {
            width: 80px;
            height: 80px;
            top: 50px;
            left: 450px;
            background: rgba(231, 126, 73, 0.3);
        }

        .shape-2 {
            width: 60px;
            height: 60px;
            top: 180px;
            left: 380px;
            background: rgba(107, 76, 147, 0.4);
        }

        .shape-3 {
            width: 40px;
            height: 40px;
            top: 120px;
            left: 500px;
            background: rgba(231, 126, 73, 0.5);
        }

        /* Triangle cutouts with images */
        .image-triangles {
            position: absolute;
            right: 0;
            top: 0;
            width: 60%;
            height: 100%;
        }

        .triangle-container {
            position: absolute;
            width: 200px;
            height: 150px;
        }

        .triangle {
            
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: transform 0.3s ease;
        }

        .triangle:hover {
            transform: scale(1.05);
        }

        /* Large rounded triangle - top right */
        .triangle-1 {
            top: 30px;
            right: 80px;
            width: 250px;
            height: 200px;
        }

        .triangle-1 .triangle {
            clip-path: path('M125,15 Q180,10 220,45 Q240,70 245,100 Q250,130 240,160 Q230,180 210,185 L40,185 Q15,185 10,160 Q0,130 5,100 Q10,70 30,45 Q70,10 125,15 Z');
            background-image: url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=500&h=300&fit=crop');
            border-radius: 25px;
        }

        /* Medium rounded triangle - middle */
        .triangle-2 {
            top: 90px;
            right: 320px;
            width: 180px;
            height: 140px;
        }

        .triangle-2 .triangle {
            clip-path: path('M90,12 Q125,8 150,30 Q165,50 168,75 Q170,95 160,115 Q150,130 135,132 L45,132 Q25,132 20,115 Q10,95 12,75 Q15,50 30,30 Q55,8 90,12 Z');
            background-image: url('https://images.unsplash.com/photo-1511578314322-379afb476865?w=500&h=300&fit=crop');
            border-radius: 20px;
        }

        /* Small rounded triangle - bottom right */
        .triangle-3 {
            top: 150px;
            right: 150px;
            width: 140px;
            height: 110px;
        }

        .triangle-3 .triangle {
            clip-path: path('M70,10 Q95,7 115,25 Q125,40 127,55 Q128,70 120,85 Q112,95 100,97 L40,97 Q25,97 20,85 Q12,70 13,55 Q15,40 25,25 Q45,7 70,10 Z');
            background-image: url('https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=500&h=300&fit=crop');
            border-radius: 18px;
        }

        /* Additional rounded triangle */
        .triangle-4 {
            top: 60px;
            right: 420px;
            width: 120px;
            height: 90px;
        }

        .triangle-4 .triangle {
            clip-path: path('M60,8 Q80,6 95,20 Q102,30 103,42 Q104,54 98,65 Q92,72 85,74 L35,74 Q22,74 18,65 Q12,54 13,42 Q14,30 21,20 Q40,6 60,8 Z');
            background-image: url('https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=500&h=300&fit=crop');
            border-radius: 15px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .events-banner {
                height: 250px;
            }
            
            .banner-content {
                padding: 0 30px;
            }
            
            .banner-title {
                font-size: 2.5rem;
            }
            
            .banner-subtitle {
                font-size: 1rem;
            }
            
            .image-triangles {
                width: 50%;
            }
            
            .triangle-container {
                transform: scale(0.8);
            }
        }

        @media (max-width: 480px) {
            .banner-title {
                font-size: 2rem;
            }
            
            .image-triangles {
                opacity: 0.7;
            }
        }


        .event-form {
    max-width: 500px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.event-form label {
    text-align: left;
    font-size: 0.9rem;
    margin-bottom: 5px;
}
.event-form input,
.event-form select {
    padding: 10px;
    border-radius: 20px;
    border: 1px solid #ccc;
    background-color: #f0f0f0;
}
    </style>
</head>
<body>
    <div class="events-banner">
        <!-- Text Content -->
        <div class="banner-content">
            <h1 class="banner-title">Events</h1>
            <p class="banner-subtitle">Stay ahead with latest events</p>
        </div>

        <!-- Decorative Shapes -->
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>

        <!-- Triangle Image Cutouts -->
        <div class="image-triangles">
            <div class="triangle-container triangle-1">
                <div class="triangle"></div>
            </div>
            
            <div class="triangle-container triangle-2">
                <div class="triangle"></div>
            </div>
            
            <div class="triangle-container triangle-3">
                <div class="triangle"></div>
            </div>
            
            <div class="triangle-container triangle-4">
                <div class="triangle"></div>
            </div>
        </div>
    </div>

    <!-- About Events Section -->
    <div class="about-events-section">
        <div class="section-header">
            <h2 class="section-title">About Events</h2>
            <div class="title-underline"></div>
        </div>
        
        <div class="media-cards">
            <div class="card photos-card">
                <div class="card-content">
                    <h3>Photos</h3>
                </div>
            </div>
            
            <div class="card videos-card">
                <div class="card-content">
                    <h3>Videos</h3>
                    <div class="play-icon">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <circle cx="20" cy="20" r="18" fill="rgba(0,0,0,0.3)"/>
                            <polygon points="16,12 16,28 28,20" fill="white"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <form class="event-form">
        <label>Date And Time Of The Event</label>
        <select>
            <option value="">Select Date & Time</option>
        </select>

        <label>Venue Of The Event</label>
        <input type="text" placeholder="Enter venue">

        <label>Purpose Of The Event</label>
        <input type="text" placeholder="Enter purpose">

        <label>Special Guest Of The Event ( if any)</label>
        <input type="text" placeholder="Enter special guest">
    </form>


<!-- Upcoming Events -->
  <div class="about-events-section">
        <div class="section-header">
            <h2 class="section-title">Upcoming Events Information </h2>
            <div class="title-underline"></div>
        </div>
        
        <div class="media-cards">
            <div class="card photos-card">
                <div class="card-content">
                    <h3>Photos</h3>
                </div>
            </div>
            
            <div class="card videos-card">
                <div class="card-content">
                    <h3>Videos</h3>
                    <div class="play-icon">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <circle cx="20" cy="20" r="18" fill="rgba(0,0,0,0.3)"/>
                            <polygon points="16,12 16,28 28,20" fill="white"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    <form class="event-form">
        <label>Date And Time Of The Event</label>
        <select>
            <option value="">Select Date & Time</option>
        </select>

        <label>Venue Of The Event</label>
        <input type="text" placeholder="Enter venue">

        <label>Purpose Of The Event</label>
        <input type="text" placeholder="Enter purpose">

        <label>Special Guest Of The Event ( if any)</label>
        <input type="text" placeholder="Enter special guest">
    </form>
    </div></div>
 

</body>
   <?php
// Include your common header file
include 'common/footer.php';
?>
</html>