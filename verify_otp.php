<?php
session_start(); // Start the session
require_once 'DB.php'; // Include your database connection

$error_message = "";
$success_message = "";

// Check if the user is coming from the signup process
if (!isset($_SESSION['signup_data']) || !isset($_SESSION['otp'])) {
    // Redirect to the signup page if the session data is missing
    header("Location: signup.php");
    exit();
}

// Handle OTP verification form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $entered_otp = htmlspecialchars($_POST['otp']); // Get the OTP entered by the user

    // Verify the OTP
    if ($entered_otp == $_SESSION['otp']) {
        // OTP is correct, save the user data to the database
        $full_name = $_SESSION['signup_data']['full_name'];
        $email = $_SESSION['signup_data']['email'];
        $password = $_SESSION['signup_data']['password'];
       

        // Insert the user into the database
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$full_name, $email, $password]);

        if ($stmt->rowCount() > 0) {
            // Clear the session data
            unset($_SESSION['signup_data']);
            unset($_SESSION['otp']);

            // Redirect to the login page
            $success_message = "Account created successfully! Redirecting to login page...";
            header("Refresh: 3; url=login.php"); // Redirect after 3 seconds
        } else {
            $error_message = "Failed to save user data. Please try again.";
        }
    } else {
        $error_message = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Fastest International Shipment</title>
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


        .form-container {
            max-width: 400px;
            margin: 150px auto 0 auto;
            padding: 20px;
            background-color: #1d1e22;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-container header {
            font-size: 24px;
            margin-bottom: 20px;
            color: #ffc107;
        }

        .form-container form input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            width: 100%;
        }

        .form-container form input[type="submit"] {
            background-color: #ffc107;
            color: #000;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .form-container form input[type="submit"]:hover {
            background-color: #e0a800;
        }

        #message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }

        /* Footer Styling */
        footer {
            background-color: #1d1e22;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
        }

        footer .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
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
            <a class="navbar-brand" href="index.php">Fastest International Shipment</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="AboutUs.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="HowToWork.php">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://www.flightaware.com/">Track Request</a></li>
                    <li class="nav-item"><a class="nav-link" href="ClientsContact.php">Help</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- OTP Verification Form -->
    <div class="form-container">
        <header>Verify OTP</header>
        <form action="" method="POST" autocomplete="off">
            <input type="text" name="otp" placeholder="Enter OTP" required autocomplete="off">
            <input type="submit" value="Verify OTP">
        </form>
        <?php if ($error_message) {
            echo "<div id='message' style='color: red;'>$error_message</div>";
        } ?>
        <?php if ($success_message) {
            echo "<div id='message' style='color: green;'>$success_message</div>";
        } ?>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Fastest International Shipment. All rights reserved. | <a href="AboutUs.php">About Us</a> | <a href="ClientsContact.php">Contact</a></p>
        </div>
    </footer>

</body>

</html>
