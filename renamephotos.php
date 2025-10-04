<?php
// DB connections
$old = new mysqli("localhost", "root", "", "msmeglobalold");
$new = new mysqli("localhost", "root", "", "msmeglobal");

if ($old->connect_error) die("Old DB Connection failed: " . $old->connect_error);
if ($new->connect_error) die("New DB Connection failed: " . $new->connect_error);

// Folders
$oldFolder = __DIR__ . "/uploads/member_photos/";
$newFolder = __DIR__ . "/uploads/gallery/";

// Ensure new folder exists
if (!is_dir($newFolder)) {
    if (!mkdir($newFolder, 0777, true)) {
        die("Error: Could not create gallery folder at $newFolder");
    }
}

// Fetch users with gallery from old DB
$result = $old->query("SELECT whatsapp, gallery FROM form_2nd_round WHERE gallery IS NOT NULL AND gallery != ''");

if (!$result) die("Query failed: " . $old->error);

while ($row = $result->fetch_assoc()) {
    $whatsapp = $row['whatsapp'];
    $oldGallery = explode(",", $row['gallery']);
    $newGallery = [];

    // Get user_id, name, email from new DB using whatsapp
    $stmtUser = $new->prepare("SELECT user_id, name, email FROM users WHERE whatsapp = ?");
    $stmtUser->bind_param("s", $whatsapp);
    $stmtUser->execute();
    $resUser = $stmtUser->get_result();
    if ($resUser->num_rows === 0) {
        echo "No user found with whatsapp $whatsapp<br>";
        $stmtUser->close();
        continue;
    }
    $userData = $resUser->fetch_assoc();
    $user_id = $userData['user_id'];
    $user_name = $userData['name'];
    $user_email = $userData['email'];
    $stmtUser->close();

    foreach ($oldGallery as $key => $file) {
        $file = trim($file);
        if ($file === '') continue;

        // Rename like your current upload logic
        $newName = time() . "_" . $key . "_" . basename($file);

        $oldPath = $oldFolder . $file;
        $newPath = 'uploads/gallery/'. $newName;

        if (file_exists($oldPath)) {
            if (!copy($oldPath, $newPath)) {
                echo "Failed to copy file: $file<br>";
                continue;
            }
            echo "Copied $file → $newName<br>";
        } else {
            echo "File not found in old folder: $file<br>";
            continue;
        }

        // Insert into user_gallery
        $stmtInsert = $new->prepare("INSERT INTO user_gallery (user_id, user_name, user_email, whatsapp, file_path) VALUES (?, ?, ?, ?, ?)");
        if ($stmtInsert) {
            $stmtInsert->bind_param("issss", $user_id, $user_name, $user_email, $whatsapp, $newPath);
            if ($stmtInsert->execute()) {
                echo "Inserted into user_gallery: $newName<br>";
                $newGallery[] = $newName;
            } else {
                echo "DB insert failed: " . $stmtInsert->error . "<br>";
            }
            $stmtInsert->close();
        } else {
            echo "Prepare failed for insert: " . $new->error . "<br>";
        }
    }

    // Optional: update users.gallery column
    if (!empty($newGallery)) {
        $finalGallery = implode(",", $newGallery);
        $stmtUpd = $new->prepare("UPDATE users SET gallery = ? WHERE whatsapp = ?");
        if ($stmtUpd) {
            $stmtUpd->bind_param("ss", $finalGallery, $whatsapp);
            $stmtUpd->execute();
            $stmtUpd->close();
        }
    }

    echo "<br>";
}

echo "Gallery migration completed.";
?>
