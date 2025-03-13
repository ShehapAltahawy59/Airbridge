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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sender Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #1d1e22;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    position: relative; /* Required for pseudo-element positioning */
    background: url('./images/guarantee.jpg') center/cover no-repeat;
    height: 500px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
    margin-top: 56px; /* Adjusted for fixed navbar */
}

.hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black overlay */
    z-index: 1; /* Ensure the overlay is above the background image */
}

.hero * {
    position: relative; /* Ensure text/content is above the overlay */
    z-index: 2;
}

        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
            
            
        }

        section {
            padding: 50px 20px;
            background-color: #1d1e22;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .about-section img {
            border-radius: 10px;
            max-width: 100%;
            height: auto;
        }

        .about-text h4 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #ffc107;
        }

        .about-text p {
            font-size: 1rem;
            line-height: 1.8;
            color: #ddd;
        }

        .trust-section h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #ffc107;
            text-align: center;
        }

        .trust-section p {
            font-size: 1rem;
            line-height: 1.8;
            color: #ddd;
        }

        footer {
            background-color: #1d1e22;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        footer a {
            color: #ffc107;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
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
    <div class="hero">
        <h1>Fastest International Shipment</h1>
    </div>

    <!-- About Us Section -->
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="./images/guarantee.jpg" alt="Guarantee">
                </div>
                <div class="col-md-6 about-text">
                    <h4>Welcome to Fastest International Shipment
</h4>
                    <p>Experience the Future of Logistics with Fastest International Shipment
! Say goodbye to high costs and slow deliveries - Fastest International Shipment
 offers fast, secure, and affordable airport-to-airport delivery solutions. Whether you're sending small items or utilizing your travel space to earn extra income, Fastest International Shipment
 is here to redefine convenience and efficiency in logistics. Join us today and be part of a smarter, more connected delivery revolution!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Section -->
    <section class="trust-section">
        <div class="container">
            <h2>How to Trust Us</h2>
            <div class="row">
                <div class="col-md-6">
                    <img src="./images/website-trust.jpg" alt="Trust Us" class="img-fluid rounded">
                </div>
                <div class="col-md-6">
                    <p>The requester is fully responsible for paying the total cost of the item and delivery fee, ensuring they are aware of the product's value. The amount will be held in the system until the state of the request changes to "Delivered" or "Cancelled." This ensures fair handling of disputes while maintaining transparency and accountability between requesters, travelers, and the platform. The system administrator oversees payment suspensions, ensuring secure transactions and timely updates.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Fastest International Shipment
. All rights reserved. | <a href="AboutUs.php">About Us</a> | <a href="ClientsContact.php">Contact</a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
