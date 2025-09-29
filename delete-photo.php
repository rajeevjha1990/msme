<?php
session_start();
include 'dbconfigf/dbconst2025.php';

// Set content type for JSON response
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No photo ID provided']);
    exit();
}

$photoId = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

try {
    // First, fetch the file path and verify ownership
    $stmt = $conn->prepare("SELECT file_path FROM user_gallery WHERE id = ? AND user_id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("ii", $photoId, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $filePath = $row['file_path'];
        $stmt->close();
        
        // Delete the physical file first
        if (!empty($filePath) && file_exists($filePath)) {
            if (!unlink($filePath)) {
                error_log("Failed to delete file: " . $filePath);
                // Continue anyway - we'll still delete from DB
            }
        }
        
        // Delete from database
        $deleteStmt = $conn->prepare("DELETE FROM user_gallery WHERE id = ? AND user_id = ?");
        if (!$deleteStmt) {
            throw new Exception("Delete prepare failed: " . $conn->error);
        }
        
        $deleteStmt->bind_param("ii", $photoId, $user_id);
        
        if ($deleteStmt->execute()) {
            if ($deleteStmt->affected_rows > 0) {
                $deleteStmt->close();
                echo json_encode(['success' => true, 'message' => 'Photo deleted successfully']);
            } else {
                $deleteStmt->close();
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Photo not found or already deleted']);
            }
        } else {
            throw new Exception("Delete execution failed: " . $deleteStmt->error);
        }
        
    } else {
        $stmt->close();
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Photo not found or you do not have permission to delete it']);
    }
    
} catch (Exception $e) {
    error_log("Delete photo error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error occurred']);
}
?>