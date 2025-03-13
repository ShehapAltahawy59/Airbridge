<?php
session_start();
require_once 'DB.php';  // Assume DB.php contains your database connection

// Ensure you are using prepared statements to prevent SQL injection
$stmt = $pdo->prepare("SELECT * FROM users WHERE UserID  = :user_id");

// Bind the parameter securely
$stmt->bindParam(':user_id', $_SESSION['UserID'], PDO::PARAM_INT);

// Execute the statement
$stmt->execute();

// Fetch the notifications
$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Requester Dashboard</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    body {
      background-color: #121212;
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

    .dashboard-header {
      text-align: center;
      margin-top: 100px;
      font-size: 2.5rem;
      font-weight: 600;
      color: #ffc107;
    }

    .dashboard-buttons {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
    }

    .btn-custom {
      background-color: #ffc107;
      color: #000;
      padding: 15px 30px;
      border-radius: 25px;
      font-weight: 500;
      transition: background-color 0.3s;
      text-decoration: none;
    }

    .btn-custom:hover {
      background-color: #e0a800;
      color: #000;
    }
  </style>
</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Fastest International Shipment
      </a>
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
            <?php if (isset($_SESSION['UserID'])): ?>
                <a class="nav-link" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="nav-link" href="login.php">Login</a>
            <?php endif; ?>
        </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Dashboard Section -->
  <section class="container">
    <h1 class="dashboard-header">Dashboard</h1>
    <div class="dashboard-buttons">
      <a href="Sender-Dashboard.php" class="btn btn-custom">Be Requester</a>
      <a href="Traveler-Dashboard.php" class="btn btn-custom">Be Traveler</a>
    </div>
  </section>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
