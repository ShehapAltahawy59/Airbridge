<?php
session_start();
require_once 'DB.php';  // Assume DB.php contains your database connection

// Ensure you are using prepared statements to prevent SQL injection
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = :user_id order by `timestamp` DESC");

// Bind the parameter securely
$stmt->bindParam(':user_id', $_SESSION['UserID'], PDO::PARAM_INT);
echo $_SESSION['UserID'];
// Execute the statement
$stmt->execute();

// Fetch the notifications
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <title>Notification List</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #d8d8d8b3;
      font-family: 'Poppins', sans-serif;
    }

    .navbar {
      background-color: #000; /* Changed to black */
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 10px 20px;
    }

    .navbar-brand {
      font-size: 24px;
      font-weight: 600;
      color: #ffc107 !important;
    }

    .nav-link {
      color: #fff !important;
      font-weight: 500;
    }

    .nav-link:hover {
      color: #ffc107 !important;
    }

    .wrapper-customized {
      margin-top: 100px;
      padding: 20px;
    }

    .form.signup {
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      padding: 20px;
      max-width: 800px;
      margin: 0 auto;
    }

    .form.signup header {
      font-size: 24px;
      font-weight: 600;
      color: #333;
      text-align: center;
      margin-bottom: 20px;
    }

    .list-group-item {
      border: none;
      border-radius: 10px;
      margin-bottom: 10px;
      padding: 15px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .list-group-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .list-group-item a {
      text-decoration: none;
      color: inherit;
      display: block;
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
            <a class="nav-link" href="ClientsContact.php?Type=Sender">Help</a>
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

  <!-- Notification List -->
  <section class="wrapper-customized">
    <div class="form signup">
      <header>Notification List</header>
      <ul class="list-group">
      <?php foreach ($notifications as $notification): ?>
    <?php
    // Extract the request ID from the notification body
    $body = $notification['body'];
    preg_match('/ID: (\d+)/', $body, $matches);
    $request_id = $matches[1] ?? null; // Get the first captured group (the ID)
    ?>

    <li class="list-group-item list-group-item-primary">
        <?php if ($request_id): ?>
            <a href="Requests.php?id=<?php echo htmlspecialchars($request_id); ?>">
                <?php echo htmlspecialchars($body); ?>
            </a>
        <?php else: ?>
            <span><?php echo htmlspecialchars($body); ?></span>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
        
      </ul>
    </div>
  </section>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
