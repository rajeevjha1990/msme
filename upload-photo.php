<?php
session_start();
include 'dbconfigf/dbconst2025.php'; // your DB connection file

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?error=Please login first");
    exit();
}

$user_id    = $_SESSION['user_id'];
$user_name  = $_SESSION['name'];
$user_email = $_SESSION['email'];
$whatsapp   = $_SESSION['whatsapp'];

$upload_message = '';

// Handle uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['gallery'])) {
    echo "<pre>";
    echo "POST data received\n";
    print_r($_FILES);
    echo "</pre>";
    
    $uploadDir = "uploads/gallery/";
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            $upload_message = "Error: Could not create upload directory";
        }
    }

    $uploaded_count = 0;
    
    // Check if $_FILES['gallery'] is an array of files or single file
    if (is_array($_FILES['gallery']['tmp_name'])) {
        // Multiple files
        foreach ($_FILES['gallery']['tmp_name'] as $key => $tmp_name) {
            if (!empty($_FILES['gallery']['name'][$key]) && $_FILES['gallery']['error'][$key] === 0) {
                echo "Processing file: " . $_FILES['gallery']['name'][$key] . "<br>";
                
                $fileName = time() . "_" . $key . "_" . basename($_FILES['gallery']['name'][$key]);
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($tmp_name, $targetPath)) {
                    echo "File moved to: $targetPath<br>";

                    $stmt = $conn->prepare("INSERT INTO user_gallery (user_id, user_name, user_email, whatsapp, file_path) VALUES (?, ?, ?, ?, ?)");
                    if ($stmt === false) {
                        echo "Prepare failed: " . $conn->error . "<br>";
                        continue;
                    }

                    $stmt->bind_param("issss", $user_id, $user_name, $user_email, $whatsapp, $targetPath);
                    if ($stmt->execute()) {
                        echo "DB Insert success for $fileName<br>";
                        $uploaded_count++;
                    } else {
                        echo "DB Insert failed: " . $stmt->error . "<br>";
                    }
                    $stmt->close();
                } else {
                    echo "Upload failed for " . $_FILES['gallery']['name'][$key] . "<br>";
                    echo "Error code: " . $_FILES['gallery']['error'][$key] . "<br>";
                }
            } elseif ($_FILES['gallery']['error'][$key] !== 4) { // 4 = no file uploaded
                echo "File error for " . $_FILES['gallery']['name'][$key] . ": " . $_FILES['gallery']['error'][$key] . "<br>";
            }
        }
    }
    
    if ($uploaded_count > 0) {
        $upload_message = "$uploaded_count file(s) uploaded successfully!";
        // Redirect to prevent resubmission
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=" . $uploaded_count);
        exit();
    } else {
        $upload_message = "No files were uploaded. Please select files to upload.";
    }
}

// Show success message from redirect
if (isset($_GET['success'])) {
    $upload_message = $_GET['success'] . " file(s) uploaded successfully!";
}

// Fetch photos for this user
$photos = [];
$stmt = $conn->prepare("SELECT id, file_path FROM user_gallery WHERE user_id = ? ORDER BY id DESC");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $photos[] = $row;
    }
    $stmt->close();
} else {
    echo "Error preparing select statement: " . $conn->error;
}
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
.gallery-container {
    max-width: 1100px;
    margin: 40px auto;
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.gallery-container h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 22px;
    color: #0b0b3b;
    font-weight: bold;
}
.upload-message {
    text-align: center;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
}
.upload-message.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.upload-message.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 20px;
}
.upload-box {
    width: 160px;
    height: 160px;
    border: 2px dashed #aaa;
    border-radius: 10px;
    position: relative;
    background: #fafafa;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}
.upload-box span {
    font-size: 28px;
    color: #888;
}
.upload-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
}
.upload-box input {
    display: none;
}
.remove-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: #ff3b30;
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    cursor: pointer;
    font-size: 16px;
    line-height: 22px;
    text-align: center;
    display: none;
}
.upload-box.has-image .remove-btn {
    display: block;
}
.save-btn {
    margin: 30px auto 0 auto;
    padding: 12px 25px;
    background: #0b0b3b;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    display: block;
}
.save-btn:hover {
    background: #1c1c7a;
}

.photo-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(5px);
    animation: fadeIn 0.3s ease;
}

.photo-modal.show {
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
    animation: zoomIn 0.3s ease;
}

.modal-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
}

.modal-controls {
    position: absolute;
    top: -50px;
    right: 0;
    display: flex;
    gap: 10px;
}

.modal-btn {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    backdrop-filter: blur(10px);
}

.modal-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: scale(1.1);
}

.modal-delete-btn {
    background: rgba(255, 59, 48, 0.8);
    border-color: rgba(255, 59, 48, 0.6);
}

.modal-delete-btn:hover {
    background: rgba(255, 59, 48, 1);
    border-color: rgba(255, 59, 48, 0.8);
}

.modal-close-btn {
    background: rgba(108, 117, 125, 0.8);
    border-color: rgba(108, 117, 125, 0.6);
}

.modal-close-btn:hover {
    background: rgba(108, 117, 125, 1);
    border-color: rgba(108, 117, 125, 0.8);
}

/* Update your existing upload-box styles */
.upload-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
    cursor: pointer; /* Make it clear the image is clickable */
}

.upload-box.has-image:hover .remove-btn {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes zoomIn {
    from { 
        opacity: 0;
        transform: scale(0.5);
    }
    to { 
        opacity: 1;
        transform: scale(1);
    }
}

@media (max-width: 768px) {
    .modal-controls {
        top: 10px;
        right: 10px;
        position: fixed;
    }
    
    .modal-btn {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
}
</style>

<div class="gallery-container">
    <h2>📸 My Gallery</h2>
    
    <?php if (!empty($upload_message)): ?>
        <div class="upload-message <?php echo strpos($upload_message, 'success') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($upload_message); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" id="galleryForm">
        <div class="gallery-grid">
    <!-- Existing Photos -->
    <?php foreach ($photos as $photo): ?>
        <div class="upload-box has-image" data-photo-id="<?php echo $photo['id']; ?>">
            <img src="<?php echo htmlspecialchars($photo['file_path']); ?>" alt="Photo">
            <button type="button" class="remove-btn" onclick="showDeleteConfirm(<?php echo $photo['id']; ?>)">&times;</button>
        </div>
    <?php endforeach; ?>

    <!-- Empty slots for new uploads -->
    <?php for ($i = count($photos); $i < 15; $i++): ?>
        <div class="upload-box">
            <input type="file" name="gallery[]" accept="image/*">
            <span>+</span>
        </div>
    <?php endfor; ?>
</div>
        <button type="submit" class="save-btn">💾 Save</button>
    </form>
</div>

<div id="photoModal" class="photo-modal">
    <div class="modal-content">
        <div class="modal-controls">
            <button class="modal-btn modal-delete-btn" id="modalDeleteBtn" title="Delete Photo">
                🗑️
            </button>
            <button class="modal-btn modal-close-btn" id="modalCloseBtn" title="Close">
                ✕
            </button>
        </div>
        <img id="modalImage" class="modal-image" src="" alt="Full size photo">
    </div>
</div>

<script>
// Make entire box clickable
// Make entire box clickable
document.querySelectorAll('.upload-box').forEach(box => {
    const input = box.querySelector('input');
    if (input) {
        box.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-btn')) return;
            input.click();
        });

        input.addEventListener('change', () => {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const newInput = document.createElement('input');
                    newInput.type = 'file';
                    newInput.name = 'gallery[]';
                    newInput.accept = 'image/*';
                    newInput.style.display = 'none';
                    newInput.files = input.files; // Preserve the selected file
                    
                    box.innerHTML = `<img src="${e.target.result}" alt="Preview">
                                     <button type="button" class="remove-btn">&times;</button>`;
                    box.appendChild(newInput);
                    box.classList.add("has-image");

                    // Bind remove button
                    box.querySelector(".remove-btn").addEventListener("click", ev => {
                        ev.stopPropagation();
                        box.innerHTML = `<input type="file" name="gallery[]" accept="image/*">
                                         <span>+</span>`;
                        box.classList.remove("has-image");
                        
                        // Rebind click event for the new input
                        const newInput = box.querySelector('input');
                        box.addEventListener('click', () => newInput.click());
                        newInput.addEventListener('change', arguments.callee);
                    });
                };
                reader.readAsDataURL(input.files[0]);
            }
        });

        
    }
});

// Improved delete photo function
function deletePhoto(photoId) {
    if (!confirm("Are you sure you want to delete this photo?")) {
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '...';
    button.disabled = true;
    
    fetch("delete-photo.php?id=" + photoId, { 
        method: "GET",
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Success - reload the page or remove the element
            location.reload();
        } else {
            // Show error message
            alert('Error deleting photo: ' + (data.error || 'Unknown error'));
            // Restore button
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('Error deleting photo: ' + error.message);
        // Restore button
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Debug form submission
document.getElementById('galleryForm').addEventListener('submit', function(e) {
    const formData = new FormData(this);
    let hasFiles = false;
    
    for (let [key, value] of formData.entries()) {
        if (key === 'gallery[]' && value.size > 0) {
            hasFiles = true;
            console.log('File to upload:', value.name, 'Size:', value.size);
        }
    }
    
    if (!hasFiles) {
        alert('Please select at least one file to upload');
        e.preventDefault();
        return false;
    }
    
    console.log('Form submitted with files');
});


const modal = document.getElementById('photoModal');
const modalImage = document.getElementById('modalImage');
const modalDeleteBtn = document.getElementById('modalDeleteBtn');
const modalCloseBtn = document.getElementById('modalCloseBtn');
let currentPhotoId = null;

// Open modal when clicking on existing photos
document.addEventListener('click', function(e) {
    if (e.target.matches('.upload-box.has-image img')) {
        e.stopPropagation();
        const photoBox = e.target.closest('.upload-box');
        const photoId = photoBox.dataset.photoId;
        const imageSrc = e.target.src;
        
        openModal(imageSrc, photoId);
    }
});

function openModal(imageSrc, photoId) {
    currentPhotoId = photoId;
    modalImage.src = imageSrc;
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
    currentPhotoId = null;
}

// Close modal events
modalCloseBtn.addEventListener('click', closeModal);

modal.addEventListener('click', function(e) {
    if (e.target === modal) {
        closeModal();
    }
});

// Close with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.classList.contains('show')) {
        closeModal();
    }
});

// Delete from modal
modalDeleteBtn.addEventListener('click', function() {
    if (currentPhotoId) {
        deletePhotoFromModal(currentPhotoId);
    }
});


function showMessage(message, type) {
    const msgBox = document.createElement('div');
    msgBox.className = 'upload-message ' + (type === 'success' ? 'success' : 'error');
    msgBox.textContent = message;

    // Insert at the top of gallery container
    const container = document.querySelector('.gallery-container');
    container.insertBefore(msgBox, container.firstChild);

    // Auto-hide after 3 seconds
    setTimeout(() => {
        msgBox.remove();
    }, 3000);
}


// Updated delete function that works from modal
function deletePhotoFromModal(photoId) {
    if (!confirm("Are you sure you want to delete this photo?")) {
        return;
    }
    
fetch("delete-photo.php?id=" + photoId, { 
    method: "GET",
    headers: {
        'Accept': 'application/json'
    }
})
.then(response => {
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response.json();   // ✅ parse JSON instead of text
})
.then(data => {
    if (data.success) {
        // Remove from DOM
        const photoBox = document.querySelector(`[data-photo-id="${photoId}"]`);
        if (photoBox) {
            photoBox.remove();
        }
        closeModal();
        showMessage(data.message, 'success');  // ✅ use message from backend
    } else {
        throw new Error(data.message || 'Unknown error');
    }
})
.catch(error => {
    console.error('Delete error:', error);
    showMessage('Error deleting photo: ' + error.message, 'error');
});

}

// For the small delete button (non-modal)
function showDeleteConfirm(photoId) {
    event.stopPropagation(); // Prevent opening modal
    deletePhotoFromModal(photoId);
}

// Update your existing file upload code to not interfere with image clicks

</script>

<?php include 'common/footer.php'; ?>