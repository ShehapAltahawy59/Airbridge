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
      color: #fff !important;
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
            <a class="nav-link" href="login.php">Login</a>
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
        <li class="list-group-item list-group-item-primary">
          <a href="ClientsContact.php?Type=AdminAsSender">You Have New Message From The Admin!</a>
        </li>
        <li class="list-group-item list-group-item-secondary">
          <a href="ClientsContact.php?Type=Sender">You Have New Message From The Traveller!</a>
        </li>
        <li class="list-group-item list-group-item-success">Request 3</li>
        <li class="list-group-item list-group-item-danger">Request 4</li>
        <li class="list-group-item list-group-item-warning">Request 5</li>
        <li class="list-group-item list-group-item-info">Request 6</li>
        <li class="list-group-item list-group-item-light">Request 7</li>
        <li class="list-group-item list-group-item-dark">Request 8</li>
      </ul>
    </div>
  </section>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
