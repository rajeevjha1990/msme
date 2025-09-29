<?php
session_start();
include 'dbconfigf/dbconst2025.php'; // Your database connection

// Example: Get logged-in user info
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT name, reference_id, category, photo FROM users WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Load your background card image
$card = imagecreatefrompng("assets/your-uploaded-card.jpg");

// Allocate text color (black)
$black = imagecolorallocate($card, 0, 0, 0);

// Font path (put a .ttf font in assets/fonts/)
$font = __DIR__ . "/assets/fonts/arial.ttf";

// Overlay Name (adjust X,Y coordinates as needed)
imagettftext($card, 20, 0, 150, 380, $black, $font, $user['name']);

// Overlay Membership ID
imagettftext($card, 18, 0, 150, 410, $black, $font, "Ref. ID: " . $user['reference_id']);

// Overlay Category
imagettftext($card, 18, 0, 150, 440, $black, $font, $user['category']);

// Insert User Photo if exists
if (!empty($user['photo']) && file_exists("uploads/users/" . $user['photo'])) {
    $userPhoto = imagecreatefromjpeg("uploads/users/" . $user['photo']);
    $userPhotoResized = imagescale($userPhoto, 120, 120);
    // Position photo (adjust X,Y)
    imagecopy($card, $userPhotoResized, 50, 250, 0, 0, 120, 120);
    imagedestroy($userPhoto);
}

// Output final card as JPEG for download
header('Content-Type: image/jpeg');
header('Content-Disposition: attachment; filename="membership-card.jpg"');
imagejpeg($card, null, 100);

// Free memory
imagedestroy($card);
?>
