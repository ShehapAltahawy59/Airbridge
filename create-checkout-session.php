
<?php
session_start();
header('Access-Control-Allow-Origin: *'); // Allow all domains (for testing)
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
// create-checkout-session.php

require 'vendor/autoload.php'; // Include the Stripe PHP SDK

// Set your Stripe Secret Key
\Stripe\Stripe::setApiKey('sk_test_51QufPsPJzSYNNBgpysMRuzwnZoYPRx6PUqhv8XyB35zIShNln9kqZKCQPQo5WDf8Ddx3m549kH1QZlLS5d0aoquR00lsk7jr0i'); // Replace with your Stripe Secret Key

// Get the request body
$input = file_get_contents('php://input');
$request = json_decode($input, true);

// Debugging: Log the incoming request
error_log("Received request: " . print_r($request, true)); // Log the incoming request

try {
  // Validate the request
  if (!isset($request['request_id']) || !isset($request['amount']) || !isset($request['currency'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request', 'received' => $request]);
    exit;
  }

  // Log the validated data
  error_log("Validated request: Request ID = " . $request['request_id'] . ", Amount = " . $request['amount'] . ", Currency = " . $request['currency']);

  // Create a Stripe Checkout Session
  $session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [
      [
        'price_data' => [
          'currency' => $request['currency'],
          'product_data' => [
            'name' => 'Shipment Payment',
          ],
          'unit_amount' => $request['amount'],
        ],
        'quantity' => 1,
      ],
    ],
    'mode' => 'payment',
    'success_url' => 'http://localhost/AirBridgeDesign/payment-success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'http://localhost/AirBridgeDesign/cancel.php',
  ]);

  // Log the session ID
  error_log("Stripe session created: " . $session->id);

  // Return the session ID to the frontend
  echo json_encode(['id' => $session->id]);
} catch (\Stripe\Exception\ApiErrorException $e) {
  // Log Stripe API errors
  error_log("Stripe API error: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
} catch (Exception $e) {
  // Log other errors
  error_log("Error: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
