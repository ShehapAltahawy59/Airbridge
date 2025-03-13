<?php
session_start();

// Disable error reporting for JSON response
error_reporting(0);

// Store form data in session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['form_data'] = [
        'senderID' => $_SESSION['UserID'], // Replace with actual SenderID (e.g., from session)
        'itemDescription' => $_POST['Item_List'],
        'brandName' => $_POST['BrandName'],
        'itemWeight' => $_POST['Item_Weight'],
        'shipmentCost' => $_POST['Shipment_Cost'],
        'itemPrice' => $_POST['Item_Price'],
        'totalPrice' => $_POST['Total_Price'],
        'pickupAirport' => $_POST['PickupAirport'],
        'dropoffAirport' => $_POST['DropoffAirport'],
        'fromDate' => $_POST['fromDate'],
        'toDate' => $_POST['toDate']
    ];

    // Return a success response
    echo json_encode(['status' => 'success']);
    exit();
}
?>
