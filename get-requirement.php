<?php
session_start();
include 'dbconfigf/dbconst2025.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo '<div class="alert alert-danger">Please login first</div>';
    exit();
}

// Validate input
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="alert alert-danger">Invalid request - Missing or invalid ID</div>';
    exit();
}

$id = intval($_GET['id']);
$current_user_email = $_SESSION['email'];

// Get current user's state for security check
$current_user_state = null;
$stmt = $conn->prepare("
    SELECT state FROM users WHERE email = ?
    UNION
    SELECT state FROM non_business_users WHERE email = ? LIMIT 1
");
$stmt->bind_param("ss", $current_user_email, $current_user_email);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $current_user_state = $row['state'];
}
$stmt->close();

// Get requirement details
// Get requirement details
$stmt = $conn->prepare("SELECT * FROM user_details WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$req = $stmt->get_result()->fetch_assoc();
$stmt->close();


if (!$req) {
    echo '<div class="alert alert-danger">Requirement not found</div>';
    exit();
}

// Get user info who posted this requirement
$email = $req['email'];
// First check business users (with photo)
$stmt = $conn->prepare("SELECT 'Business' as user_type, name, email, state, whatsapp, photo FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// If not found, check non-business users (no photo column)
if (!$user) {
    $stmt = $conn->prepare("SELECT 'Non-Business' as user_type, name, email, state, whatsapp FROM non_business_users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    // Add photo as null for non-business users
    if ($user) {
        $user['photo'] = null;
    }
}

if (!$user) {
    echo '<div class="alert alert-danger">User information not found</div>';
    exit();
}

// Security check - only show requirements from same state
if ($user['state'] !== $current_user_state) {
    echo '<div class="alert alert-warning">You can only view requirements from your state</div>';
    exit();
}

// Fix photo path
$photo_path = !empty($user['photo']) ? $user['photo'] : 'images/default-user.png';

// Format dates
$created_date = date("d M Y, h:i A", strtotime($req['created_at']));
$deadline_date = !empty($req['deadline']) ? date("d M Y", strtotime($req['deadline'])) : 'Not specified';

// Format budget
$budget_range = $req['min_budget'] . ' - ' . $req['max_budget'];
?>

<style>
.req-detail-card {
    border: none;
    box-shadow: none;
}
.req-header {
    background: linear-gradient(135deg, #6a1b9a, #8e24aa);
    color: white;
    padding: 20px;
    border-radius: 8px 8px 0 0;
    margin: -15px -15px 20px -15px;
}
.user-info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}
.user-photo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 15px;
    border: 3px solid rgba(255,255,255,0.3);
}
.user-details h5 {
    margin: 0;
    font-size: 1.1em;
}
.user-details small {
    opacity: 0.9;
}
.req-title {
    font-size: 1.3em;
    font-weight: 600;
    margin: 10px 0 5px 0;
}
.req-meta {
    display: flex;
    gap: 20px;
    margin-top: 10px;
}
.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9em;
}
.detail-section {
    margin-bottom: 20px;
}
.detail-label {
    font-weight: 600;
    color: #6a1b9a;
    margin-bottom: 5px;
}
.detail-value {
    color: #555;
    line-height: 1.5;
}
.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 600;
    display: inline-block;
}
.status-active { background: #e8f5e8; color: #2e7d32; }
.status-pending { background: #fff3e0; color: #f57c00; }
.status-closed { background: #ffebee; color: #c62828; }
.priority-high { color: #d32f2f; font-weight: 600; }
.priority-medium { color: #f57c00; font-weight: 600; }
.priority-low { color: #388e3c; font-weight: 600; }
</style>

<div class="req-detail-card">
    <div class="req-header">
        <div class="user-info">
            <?php if ($user['user_type'] === 'Business' && !empty($user['photo'])): ?>
                <img src="<?php echo htmlspecialchars($photo_path); ?>" 
                     alt="User Photo" 
                     class="user-photo" 
                     onerror="this.src='images/default-user.png'">
            <?php else: ?>
                <!-- Non-business users or business users without photos get letter avatar -->
                <div class="default-avatar">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
            <?php endif; ?>
            <div class="user-details">
                <h5><?php echo htmlspecialchars($user['name']); ?></h5>
                <small><?php echo htmlspecialchars($user['user_type']); ?> User • <?php echo htmlspecialchars($user['state']); ?></small>
                <?php if (!empty($user['whatsapp'])): ?>
                    <small><br>📞 <?php echo htmlspecialchars($user['whatsapp']); ?></small>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="req-title"><?php echo htmlspecialchars($req['requirement_type']); ?></div>
        
        <div class="req-meta">
            <div class="meta-item">
                <span>📅</span> <?php echo $created_date; ?>
            </div>
            <div class="meta-item">
                <span>⏰</span> Deadline: <?php echo $deadline_date; ?>
            </div>
            <div class="meta-item">
                <span class="status-badge <?php 
                    if ($req['status'] == 'Active') echo 'status-active'; 
                    elseif ($req['status'] == 'Pending') echo 'status-pending'; 
                    else echo 'status-closed'; ?>">
                    <?php echo htmlspecialchars($req['status']); ?>
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="detail-section">
                <div class="detail-label">Priority</div>
                <div class="detail-value priority-<?php echo strtolower($req['priority']); ?>">
                    <?php echo htmlspecialchars($req['priority']); ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="detail-section">
                <div class="detail-label">Quantity Required</div>
                <div class="detail-value"><?php echo htmlspecialchars($req['quantity']); ?></div>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <div class="detail-label">Budget Range</div>
        <div class="detail-value">
            <strong>₹<?php echo number_format($req['min_budget']); ?> - ₹<?php echo number_format($req['max_budget']); ?></strong>
        </div>
    </div>

    <?php if (!empty($req['description'])): ?>
    <div class="detail-section">
        <div class="detail-label">Description</div>
        <div class="detail-value"><?php echo nl2br(htmlspecialchars($req['description'])); ?></div>
    </div>
    <?php endif; ?>

    <?php if (!empty($req['location'])): ?>
    <div class="detail-section">
        <div class="detail-label">Location</div>
        <div class="detail-value"><?php echo htmlspecialchars($req['location']); ?></div>
    </div>
    <?php endif; ?>

    <?php if (!empty($req['additional_info'])): ?>
    <div class="detail-section">
        <div class="detail-label">Additional Information</div>
        <div class="detail-value"><?php echo nl2br(htmlspecialchars($req['additional_info'])); ?></div>
    </div>
    <?php endif; ?>

    <div class="row mt-3">
        <div class="col-12 text-center">
            <button class="btn btn-success btn-sm me-2">
                <i class="fas fa-phone"></i> Contact
            </button>
            <button class="btn btn-primary btn-sm me-2">
                <i class="fas fa-envelope"></i> Send Message
            </button>
            <button class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-bookmark"></i> Save Lead
            </button>
        </div>
    </div>
</div>