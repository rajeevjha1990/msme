
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View User</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
<style>
.label-title { font-weight: 600; color:#444; }
.value-text { color:#000; }
.profile-img { width: 120px; height:120px; border-radius: 6px; object-fit: cover; border:2px solid #ddd; }
.section-title { font-weight:700; font-size:18px; margin:20px 0 12px; border-bottom:2px solid #eee; padding-bottom:6px; }
</style>
</head>
<body class="bg-light">
<div class="content">

<div class="container mt-4 mb-4">

    <a href="index.php?action=allusers" class="btn btn-secondary btn-sm mb-3">
        ← Back to Users
    </a>

    <div class="card shadow-sm">
        <div class="card-body">

            <!-- Profile Header -->
            <div class="row">
                <div class="col-md-3 text-center">
                    <img src="<?php echo SITE_URL; ?>/<?= $user['photo'] ? $user['photo'] : 'uploads/noimage.jpg'; ?>" class="profile-img">
                </div>
                <div class="col-md-9">
                    <h3><?= $user['name']; ?></h3>
                    <p class="mb-1"><b>Reference ID:</b> <?= $user['reference_id']; ?></p>
                    <p class="mb-1"><b>Industry:</b> <?= $user['industry']; ?> (<?= $user['category']; ?>)</p>
                    <p class="mb-0"><b>Plan:</b> <?= $user['plan']; ?> | <b>Amount:</b> ₹<?= $user['amount']; ?></p>
                </div>
            </div>

            <!-- Personal Info -->
            <div class="section-title">Personal Details</div>
            <div class="row">
                <div class="col-md-4"><span class="label-title">Gender:</span> <span class="value-text"><?= $user['gender']; ?></span></div>
                <div class="col-md-4"><span class="label-title">DOB:</span> <span class="value-text"><?= $user['dob']; ?></span></div>
                <div class="col-md-4"><span class="label-title">Blood Group:</span> <span class="value-text"><?= $user['blood_group']; ?></span></div>
            </div>

            <div class="row mt-2">
                <div class="col-md-4"><span class="label-title">Whatsapp:</span> <span class="value-text"><?= $user['whatsapp']; ?></span></div>
                <div class="col-md-4"><span class="label-title">Alternate:</span> <span class="value-text"><?= $user['alternate']; ?></span></div>
                <div class="col-md-4"><span class="label-title">Email:</span> <span class="value-text"><?= $user['email']; ?></span></div>
            </div>

            <!-- Business Info -->
            <div class="section-title">Business Information</div>
            <div class="row">
                <div class="col-md-4"><b>Entity Status:</b> <?= $user['entity_status']; ?></div>
                <div class="col-md-4"><b>Entity Name:</b> <?= $user['entity_name']; ?></div>
                <div class="col-md-4"><b>Nature:</b> <?= $user['nature']; ?></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4"><b>Website:</b> <?= $user['website']; ?></div>
                <div class="col-md-4"><b>Team Size:</b> <?= $user['team_size']; ?></div>
                <div class="col-md-4"><b>Years in Business:</b> <?= $user['years_business']; ?></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4"><b>Turnover:</b> <?= $user['turnover']; ?></div>
                <div class="col-md-4"><b>State:</b> <?= $user['state']; ?></div>
                <div class="col-md-4"><b>City:</b> <?= $user['city']; ?></div>
            </div>
            <div class="mt-2"><b>Address:</b> <?= $user['address']; ?></div>

            <!-- Business Description -->
            <div class="section-title">About Business</div>
            <p><b>Description:</b> <?= $user['description']; ?></p>
            <p><b>Main Products:</b> <?= $user['products']; ?></p>
            <p><b>Gives:</b> <?= $user['gives']; ?></p>
            <p><b>Asks:</b> <?= $user['asks']; ?></p>

            <!-- Social Links -->
            <div class="section-title">Social Media</div>
            <div class="row">
                <div class="col-md-3"><b>Facebook:</b> <?= $user['facebook']; ?></div>
                <div class="col-md-3"><b>Instagram:</b> <?= $user['instagram']; ?></div>
                <div class="col-md-3"><b>YouTube:</b> <?= $user['youtube']; ?></div>
                <div class="col-md-3"><b>LinkedIn:</b> <?= $user['linkedin']; ?></div>
            </div>

            <!-- References -->
            <div class="section-title">References</div>
            <p><b>Reference 1:</b> <?= $user['person_name_1']; ?> (<?= $user['person_contact_1']; ?>)</p>
            <p><b>Reference 2:</b> <?= $user['person_name_2']; ?> (<?= $user['person_contact_2']; ?>)</p>
            <p><b>Reference 3:</b> <?= $user['person_name_3']; ?> (<?= $user['person_contact_3']; ?>)</p>

        </div>
    </div>
</div>
</div>
</body>
</html>
