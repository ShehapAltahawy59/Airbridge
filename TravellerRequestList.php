<?php
// Include the database connection file
require_once 'DB.php';
session_start();


// Ensure you are using prepared statements to prevent SQL injection
$stmt = $pdo->prepare("SELECT * FROM users WHERE UserID  = :user_id");

// Bind the parameter securely
$stmt->bindParam(':user_id', $_SESSION['UserID'], PDO::PARAM_INT);

// Execute the statement
$stmt->execute();

// Fetch the notifications
$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Fetch data from the deliveryrequests table
try {
  $sql = "SELECT * FROM deliveryrequests where status='Pending' ORDER BY `deliveryrequests`.`CreatedAt` DESC;";
  $stmt = $pdo->query($sql);
  $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error fetching data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Traveler Requests List</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    body {
      background-color: #b7b7b7;
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

    .list-group-item {
      border: none;
      border-radius: 10px;
      margin-bottom: 10px;
      padding: 15px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      display: flex;
      justify-content: space-between; /* Align items to the left and right */
      align-items: center; /* Vertically center items */
    }

    .list-group-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .list-group-item a {
      text-decoration: none;
      color: inherit;
      display: flex;
      justify-content: space-between;
      width: 100%; /* Ensure the link takes full width */
    }

    .list-group-item-primary {
      background-color: #007bff;
      color: #fff;
    }

    .list-group-item-secondary {
      background-color: #6c757d;
      color: #fff;
    }

    .list-group-item-success {
      background-color: #28a745;
      color: #fff;
    }

    .list-group-item-danger {
      background-color: #dc3545;
      color: #fff;
    }

    .list-group-item-warning {
      background-color: #ffc107;
      color: #000;
    }

    .list-group-item-info {
      background-color: #17a2b8;
      color: #fff;
    }

    .list-group-item-light {
      background-color: #f8f9fa;
      color: #000;
    }

    .list-group-item-dark {
      background-color: #343a40;
      color: #fff;
    }

    /* Align the span to the right */
    .list-group-item span {
      margin-left: auto; /* Push the span to the right */
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
                    <a class="nav-link" href="start_page.php">Dashboard</a>
                <?php elseif($user[0]['user_type'] == 'traveler'): ?>
                        <a class="nav-link" href="start_page.php">Dashboard</a>
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

  <!-- Traveler Requests List -->
  <section class="wrapper-customized">
    <div class="form signup">
      <header>Traveler Requests List</header>
      <ul class="list-group">
        <?php if (!empty($requests)): ?>
          <?php foreach ($requests as $request): ?>
            <a href="TravelerRequest.php?id=<?= $request['RequestID'] ?>" class="list-group-item list-group-item-primary">
              Request ID: <?= $request['RequestID'] ?> - <?= $request['ItemDescription'] ?>
              <span><?= $request['CreatedAt'] ?></span>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <li class="list-group-item list-group-item-warning">No requests found.</li>
        <?php endif; ?>
      </ul>
    </div>
  </section>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
