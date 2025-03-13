<?php
// Include your database connection
require_once 'DB.php';

// Get the updated data from the POST request
$user_id = $_POST['user_id'];
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$user_type = $_POST['user_type'];

// Update the user in the database
try {
    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, user_type = ? WHERE UserID = ?");
    $stmt->execute([$full_name, $email, $user_type, $user_id]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
