<?php
session_start();
require 'DB.php'; // Include your database connection
require 'vendor/autoload.php'; // Include Stripe PHP library

\Stripe\Stripe::setApiKey('sk_test_51QufPsPJzSYNNBgpysMRuzwnZoYPRx6PUqhv8XyB35zIShNln9kqZKCQPQo5WDf8Ddx3m549kH1QZlLS5d0aoquR00lsk7jr0i'); // Replace with your Stripe secret key

// Retrieve the session ID from the query parameters
$session_id = $_GET['session_id'];

try {
    // Retrieve the session from Stripe
    $session = \Stripe\Checkout\Session::retrieve($session_id);

    // Get the request data from the session
    $formData = $_SESSION['form_data'];

    // Insert the delivery request into the database
    $sql = "INSERT INTO deliveryrequests (
                senderID, 
                itemDescription, 
                brandName, 
                itemWeight, 
                shipmentCost, 
                itemPrice, 
                totalPrice, 
                pickupAirport, 
                dropoffAirport, 
                fromDate, 
                toDate
            ) VALUES (
                :senderID, 
                :itemDescription, 
                :brandName, 
                :itemWeight, 
                :shipmentCost, 
                :itemPrice, 
                :totalPrice, 
                :pickupAirport, 
                :dropoffAirport, 
                :fromDate, 
                :toDate
            )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':senderID' => $formData['senderID'],
        ':itemDescription' => $formData['itemDescription'],
        ':brandName' => $formData['brandName'],
        ':itemWeight' => $formData['itemWeight'],
        ':shipmentCost' => $formData['shipmentCost'],
        ':itemPrice' => $formData['itemPrice'],
        ':totalPrice' => $formData['totalPrice'],
        ':pickupAirport' => $formData['pickupAirport'],
        ':dropoffAirport' => $formData['dropoffAirport'],
        ':fromDate' => $formData['fromDate'],
        ':toDate' => $formData['toDate'],
    ]);

    // Clear the session data
    unset($_SESSION['form_data']);
    
    // Redirect to Payment.php
    $senderID = $request['SenderID']; // Get the requester's ID from the request
    $body = "Your delivery request has been published."; // Notification message
    
    // Insert the notification into the notifications table
    $sql = "INSERT INTO notifications (user_id, body) VALUES (:user_id, :body)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $_SESSION['UserID'], 'body' => $body]);

    // Redirect to a success page
    header('Location: start_page.php');
    exit();
} catch (Exception $e) {
    // Handle errors
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
?>
