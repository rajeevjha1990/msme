<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=Please login first");
    exit();
}

// user session data
$user_name = $_SESSION['name'];
$user_type = isset($_SESSION['type']) ? $_SESSION['type'] : '';
?>
<?php include 'common/header.php'; ?>

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

/* Dashboard Topbar */
.dashboard-topbar {
    background: #0b0b3b;
    color: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.dashboard-topbar .welcome-text {
    font-size: 18px;
    font-weight: 500;
}

.dashboard-content {
    padding: 40px 20px;
    max-width: 1200px;
    margin: auto;
    text-align: center;
}

.dashboard-content h2 {
    margin-bottom: 10px;
}

.dashboard-content p {
    margin-bottom: 30px;
    color: #333;
}

/* Card Container */
.card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

/* Individual Card */
.card {
    background: #fff;
    border-radius: 12px;
    padding: 25px 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    text-decoration: none;
    color: #333;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.2);
}

.card-icon {
    font-size: 28px;
    margin-bottom: 10px;
}

.card-title {
    font-size: 16px;
    font-weight: bold;
}
</style>

<!-- Dashboard Top Section -->
<div class="dashboard-topbar">
    <div class="welcome-text">👋 Hi, <?php echo htmlspecialchars($user_name); ?></div>
</div>

<!-- Dashboard Content -->
<div class="dashboard-content">
    <h2>Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h2>
    <p>Select an option to manage your account:</p>

    <div class="card-container">
        <a href="my-leads.php" class="card">
            <div class="card-icon">📊</div>
            <div class="card-title">My Leads</div>
        </a>
        <a href="my-requirements.php" class="card">
            <div class="card-icon">📝</div>
            <div class="card-title">My Requirements</div>
        </a>
        <a href="requirements-list.php" class="card">
            <div class="card-icon">📋</div>
            <div class="card-title">Requirements List</div>
        </a>
        <a href="profile.php" class="card">
            <div class="card-icon">👤</div>
            <div class="card-title">Profile</div>
        </a>
          <?php if ($user_type === 'TM' || $user_type === 'Trusted'): ?>
        <a href="upload-photo.php" class="card">
            <div class="card-icon">📸</div>
            <div class="card-title">Gallery</div>
        </a>
        <?php endif; ?>
        <a href="change_password.php" class="card">
            <div class="card-icon">🔑</div>
            <div class="card-title">Change Password</div>
        </a>
        <a href="logout.php" class="card">
            <div class="card-icon">🚪</div>
            <div class="card-title">Logout</div>
        </a>
        <a href="download-card.php" class="card">
    <div class="card-icon">💳</div>
    <div class="card-title">Download Membership Card</div>
</a>
    </div>
</div>

<?php include 'common/footer.php'; ?>
