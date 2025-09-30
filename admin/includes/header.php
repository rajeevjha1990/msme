<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSME Admin</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>admin/assets/css/dataTables.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>admin/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>admin/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>admin/assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>admin/assets/css/select2.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>admin/assets/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>admin/assets/css/all.min.css">

    <style>
.header {
    background: #2c3e50; /* soft navy-blue */
    color: #ecf0f1; /* light gray text */
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.header h1 {
    margin: 0;
    font-size: 22px;
    color: #ecf0f1;
}

.user-section {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-section span {
    font-weight: bold;
    color: #ecf0f1;
}

.logout-btn {
    background: #e74c3c; /* soft red */
    color: #fff;
    border: none;
    padding: 5px 12px;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}

.logout-btn:hover {
    background: #c0392b;
}

.notification-bell {
    position: relative;
    display: inline-block;
    margin: 0 10px;
    cursor: pointer;
    color: #ecf0f1;
}

.notification-count {
    position: absolute;
    top: -8px;
    right: -10px;
    background: #e74c3c; /* matching red */
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    font-weight: bold;
}

    </style>
</head>

<body>
    <header class="header">
    <h1>MSME Admin</h1>
    <div class="user-section">
        <a href="index.php?action=notifications" class="notification-bell" style="color:#fff; position:relative; font-size:20px;">
            <i class="fas fa-bell"></i>
            <?php if (!empty($notifCount) && $notifCount > 0) { ?>
                <span class="notification-count" id="notifCount"><?php echo $notifCount; ?></span>
            <?php } else { ?>
                <span class="notification-count" id="notifCount" style="display:none;">0</span>
            <?php } ?>
        </a>
        <span><?php echo $loggedAdmin['admin_name'] ?? 'Guest'; ?></span>
        <form method="post" action="<?php echo SITE_URL; ?>admin/index.php?action=logout" style="margin:0;">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</header>
<div class="main-container">
