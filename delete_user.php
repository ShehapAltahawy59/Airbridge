<?php
// Include your database connection
require_once 'DB.php';

// Get the user ID from the POST request
$user_id = $_POST['user_id'];

// Validate the user ID
if (empty($user_id)) {
    echo json_encode(['status' => 'error', 'message' => 'User ID is required.']);
    exit;
}

try {
    // Prepare and execute the delete query
    $stmt = $pdo->prepare("DELETE FROM users WHERE UserID = ?");
    $stmt->execute([$user_id]);

    // Check if any row was affected
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found or already deleted.']);
    }
} catch (PDOException $e) {
    // Handle database errors
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
