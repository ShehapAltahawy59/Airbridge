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
<!DOCTYPE .php>
<.php lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fastest International Shipment
    </title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: Arial, sans-serif;
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

        .hero {
            position: relative;
            /* Required for positioning the pseudo-element */
            background-image: url('./images/bg_img.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 100px 20px;
            margin-top: 56px;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            /* Black overlay with 50% opacity */
            z-index: 1;
            /* Ensure the overlay is above the background image */
        }

        .hero .container {
            position: relative;
            /* Ensure the content is above the overlay */
            z-index: 2;
            /* Higher than the overlay */
        }




        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
            z-index: 100;
        }

        .hero p {
            font-size: 1.2rem;
            margin-top: 20px;
            line-height: 1.8;
        }

        .feature {
            text-align: center;
            margin-top: 50px;
        }

        .feature h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #ffc107;
        }

        .feature p {
            font-size: 1rem;
            line-height: 1.6;
            color: #ddd;
        }

        .feature-box {
            background-color: #1d1e22;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #ffc107;
            border: none;
            color: #000;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #e0a800;
        }

        footer {
            background-color: #1d1e22;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Fastest International Shipment
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="AboutUs.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="HowToWork.php">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://www.flightaware.com/">Track Request</a></li>
                    <li class="nav-item"><a class="nav-link" href="ClientsContact.php">Help</a></li>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Fastest International Shipment</h1>
            <p>
                Welcome to Fastest International Shipment
                ! Your trusted platform for fast, secure, and affordable airport-to-airport delivery.
                Join us and experience logistics made simple, efficient, and rewarding. Let's bridge the gap together!
            </p>
        </div>
    </section>

    <!-- Welcome Section -->
    <section class="container feature">
        <div class="row">
            <div class="col-md-6 mb-4">
                <img src="./images/welcom.jpg" alt="Welcome" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <div class="feature-box">
                    <h2>Welcome to Fastest International Shipment
                    </h2>
                    <p>
                        Empowering Requesters and travelers alike, Fastest International Shipment
                        transforms logistics with real-time tracking, cost-effective solutions, and secure transactions. Join us today and redefine the way you send and deliver. Let’s bridge the gap together!
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Requester and Traveler Section -->
    <section class="container feature">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="feature-box">
                    <h2>Requester</h2>
                    <p>
                        Say goodbye to high costs and delays! Fastest International Shipment
                        makes it easy to send your small items safely, quickly, and affordably. With trusted travelers and real-time tracking, your delivery is in safe hands. Start sending smarter today and experience worry-free logistics!
                    </p>
                    <a href="HowToWork.php" class="btn btn-primary">Read More</a>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="feature-box">
                    <h2>Traveler</h2>
                    <p>
                        Make every trip more rewarding by using your extra luggage space to earn extra income. Join Fastest International Shipment
                        and become a trusted traveler who bridges the gap in airport logistics. Help others while you travel and turn your flights into profitable ventures!
                    </p>
                    <a href="HowToWork.php#instructions_traveller" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
    <p>&copy; 2025 Fastest International Shipment
. All rights reserved. | <a href="AboutUs.php">About Us</a> | <a href="ClientsContact.php">Contact</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</.php>
