<?php
// Include the database connection file
session_start();
require_once 'DB.php';

// Get the ID from the URL
if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Fetch the request details from the database
  try {
    $sql = "SELECT * FROM deliveryrequests WHERE RequestID  = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    // if (!$request) {
    //     die("The request has been deleted.");
    // }
  } catch (PDOException $e) {
    die("Error fetching request details: " . $e->getMessage());
  }
} else {
  die("Invalid request.");
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE UserID  = :user_id");

// Bind the parameter securely
$stmt->bindParam(':user_id', $_SESSION['UserID'], PDO::PARAM_INT);

// Execute the statement
$stmt->execute();

// Fetch the notifications
$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $status = $_POST['status']; // Get the status (Approve or Reject)
  $id = $_POST['id']; // Get the request ID

  // Update the status in the database
  try {
    // Update the status and set the TravelerID to the current user's ID
    $sql = "UPDATE deliveryrequests SET Status = :status, Traveler_id = :traveler_id WHERE RequestID = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'status' => $status,
        'traveler_id' => $_SESSION['UserID'], // Set TravelerID to the current user's ID
        'id' => $id
    ]);

    // Get the requester's ID from the request
    $senderID = $request['SenderID'];

    // Notification message
    $body = "Your delivery request (ID: $id) has been $status.";

    // Insert the notification into the notifications table
    $sql = "INSERT INTO notifications (user_id, body) VALUES (:user_id, :body)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $senderID,
        'body' => $body
    ]);

    // Redirect to the same page to reflect the updated status
    header("Location: TravellerRequestList.php");
    exit();
} catch (PDOException $e) {
    die("Error updating status: " . $e->getMessage());
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Traveler Request Details</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    body {
      background-color: #696969;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }

    .navbar {
      background-color: #1d1e22;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-size: 24px;
      font-weight: bold;
      color: #ffc107 !important;
    }

    .nav-link {
      color: #fff !important;
      font-weight: 500;
    }

    .nav-link:hover {
      color: #ffc107 !important;
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255, 255, 255, 1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }

    .wrapper-customized {
      margin-top: 100px;
      padding: 20px;
    }

    .form.signup {
      background-color: #1d1e22;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      padding: 20px;
      max-width: 800px;
      margin: 0 auto;
    }

    .form.signup header {
      font-size: 24px;
      font-weight: 600;
      color: #ffc107;
      text-align: center;
      margin-bottom: 20px;
    }

    .form.signup input,
    .form.signup textarea {
      background-color: #2a2b2f;
      border: 1px solid #444;
      color: #fff;
      padding: 10px;
      border-radius: 8px;
      width: 100%;
      margin-bottom: 15px;
    }

    .form.signup input:disabled,
    .form.signup textarea:disabled {
      background-color: #444;
      color: #aaa;
    }

    .form.signup .btn {
      padding: 10px 20px;
      border-radius: 25px;
      font-weight: 500;
      transition: background-color 0.3s;
      text-decoration: none;
      width: 100%;
    }

    .form.signup .btn-approve {
      background-color: green;
      color: #fff;
    }

    .form.signup .btn-reject {
      background-color: darkred;
      color: #fff;
    }

    .form.signup .btn-approve:hover {
      background-color: #8fbc8f;
    }

    .form.signup .btn-reject:hover {
      background-color: #8b0000;
    }
  </style>
</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Fastest International Shipment</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['UserID'])): ?>
                <?php if ($user[0]['user_type'] == 'requester'): ?>
                    <a class="nav-link" href="Sender-Dashboard.php">Dashboard</a>
                <?php elseif($user[0]['user_type'] == 'traveler'): ?>
                        <a class="nav-link" href="Traveler-Dashboard.php">Dashboard</a>
                <?php elseif($user[0]['user_type'] == 'admin'): ?>
                        <a class="nav-link" href="Admin.php">Dashboard</a>
                <?php endif; ?>
            <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="AboutUs.php">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="HowToWork.php">How It Works</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://www.flightaware.com/">Track Your Request</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="ClientsContact.php?Type=User">Help</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Sender Request Form -->
  <section class="wrapper-customized">
    <div class="form signup">
      <header>Sender Request Details</header>
      <?php if ($request): ?>
      <form action="" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($request['RequestID']) ?>">
        <div class="row">
          <div class="col-md-12">
            <label style="color: #ffc107; font-weight: bold; font-size: large;">Item Type</label>
            <input type="text" placeholder="Item Type" value="<?= htmlspecialchars($request['ItemDescription']) ?>" disabled required />
          </div>
          <div class="col-md-12">
            <label style="color: #ffc107; font-weight: bold; font-size: large;">Item Description</label>
            <textarea placeholder="Item Description" disabled required style="height: 100px;"><?= htmlspecialchars($request['ItemDescription']) ?></textarea>
          </div>
          <div class="col-md-6">
            <label style="color: #ffc107; font-weight: bold; font-size: large;">Item Weight</label>
            <input type="text" placeholder="Item Weight" value="<?= htmlspecialchars($request['ItemWeight']) ?>" disabled required />
          </div>
          <div class="col-md-6">
            <label style="color: #ffc107; font-weight: bold; font-size: large;">Shipment Cost</label>
            <input type="text" placeholder="Shipment Cost" value="<?= htmlspecialchars($request['ShipmentCost']) ?>" disabled required />
          </div>
          <div class="col-md-6">
            <label style="color: #ffc107; font-weight: bold; font-size: large;">Item Cost</label>
            <input type="text" placeholder="ItemPrice" value="<?= htmlspecialchars($request['ItemPrice']) ?>" disabled required />
          </div>
          <div class="col-md-6">
            <label style="color: #ffc107; font-weight: bold; font-size: large;">From Date</label>
            <input type="date" placeholder="From Date" value="<?= htmlspecialchars($request['fromDate']) ?>" disabled required />
          </div>
          <div class="col-md-6">
            <label style="color: #ffc107; font-weight: bold; font-size: large;">To Date</label>
            <input type="date" placeholder="To Date" value="<?= htmlspecialchars($request['toDate']) ?>" disabled required />
          </div>
          <div class="col-md-12">
            <label style="color: #ffc107; font-weight: bold; font-size: large;">Total Price</label>
            <input type="text" placeholder="Total Price" value="<?= htmlspecialchars($request['TotalPrice']) ?>" disabled required />
          </div>
          <div class="col-md-12">
            <label style="color: #ffc107; font-weight: bold; font-size: large;">Pickup Airport</label>
            <input type="text" placeholder="PickupAirport" value="<?= htmlspecialchars($request['PickupAirport']) ?>" disabled required />
          </div>
          <div class="col-md-12">
            <label style="color: #ffc107; font-weight: bold; font-size: large;">Dropoff Airport</label>
            <input type="text" placeholder="DropoffAirport" value="<?= htmlspecialchars($request['DropoffAirport']) ?>" disabled required />
          </div>
          <div class="col-md-12">
            <label style="color: #ffc107; font-weight: bold; font-size: large;">Created At</label>
            <input type="text" placeholder="Total Price" value="<?= htmlspecialchars($request['CreatedAt']) ?>" disabled required />
          </div>
          
        </div>
      </form>
    </div>
    <?php else: ?>
        <p>The request has been deleted.</p>
    <?php endif; ?>
  </section>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
