<?php
session_start();
require_once 'DB.php';
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_message = "";
$success_message = "";

function generateOTP() {
    return rand(100000, 999999);
}

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth   = true; // Enable SMTP authentication
        $mail->Username   = 'shehapkhalil@gmail.com'; // Your Gmail address
        $mail->Password   = 'yzdj ffur sfmy xdvm'; // Use the App Password here
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('no-reply@example.com', 'Fastest International Shipment');
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(false); // Set email format to plain text
        $mail->Subject = 'Your OTP for Signup Verification';
        $mail->Body    = "Your OTP is: $otp";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'];

    if ($action == "signup") {
        $full_name = htmlspecialchars($_POST['full_name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error_message = "Email already exists.";
        } else {
            $otp = generateOTP();
            $_SESSION['otp'] = $otp;
            $_SESSION['signup_data'] = [
                'full_name' => $full_name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                
            ];

            if (sendOTP($email, $otp)) {
                header("Location: verify_otp.php");
                exit();
            } else {
                $error_message = "Failed to send OTP. Please try again.";
            }
        }
     } elseif ($action == "login") {
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $is_admin = isset($_POST['is_admin']) ? 1 : 0; // Check if the admin checkbox is checked
    
        // Check if the user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
    
        if ($user && password_verify($password, $user['password'])) {
            
            if ($user['status'] == 'active') {
                session_start();
                // Check if the user is an admin
                if ($is_admin && $user['user_type'] == 'admin') {
                    $_SESSION['UserID'] = $user['UserID'];
                    $_SESSION['full_name'] = $user['full_name'];
                    header("Location: admin.php");
                    exit; // Ensure no further code is executed after redirection
                } elseif ($user['user_type'] == 'requester') {
                    $_SESSION['UserID'] = $user['UserID'];
                    $_SESSION['full_name'] = $user['full_name'];
                    header("Location: start_page.php");  // Redirect to requester dashboard
                    exit; // Ensure no further code is executed after redirection
                } elseif ($user['user_type'] == 'traveler') {
                    $_SESSION['UserID'] = $user['UserID'];
                    $_SESSION['full_name'] = $user['full_name'];
                    header("Location: start_page.php");  // Redirect to traveler dashboard
                    exit; // Ensure no further code is executed after redirection
                } else {
                    $error_message = "Invalid email or password ";
                }
            } else {
                $error_message = "Account is Banned";
            }
        }
        else {
            $error_message = "Invalid email or password.";
        }
     } 
        elseif ($action == "forgot_password") {
            $email = htmlspecialchars($_POST['email']);
    
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
    
            if ($user) {
                $otp = generateOTP();
                $_SESSION['reset_otp'] = $otp;
                $_SESSION['reset_email'] = $email;
    
                if (sendOTP($email, $otp)) {
                    header("Location: reset_password.php");
                    exit();
                } else {
                    $error_message = "Failed to send OTP. Please try again.";
                }
            } else {
                $error_message = "Email not found.";
            }
        }
        else {
            $error_message = "Invalid email or password.";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fastest International Shipment - Fastest International Shipment</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
    display: none;
    text-align: center;
}

.form-container.active {
    display: block;
}

        .form-container header {
            font-size: 24px;
            margin-bottom: 20px;
            color: #ffc107;
        }

        select {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="%23ffffff" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            cursor: pointer;
        }

        select:focus {
            border-color: #ffc107;
            outline: none;
        }

        select option {
            background-color: #333;
            color: #fff;
        }

        select option:hover {
            background-color: #ffc107;
            color: #000;
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

        .toggle-buttons {
            text-align: center;
            margin: 20px 0;
        }

        .toggle-buttons button {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            background-color: #ffc107;
            color: #000;
            cursor: pointer;
            font-weight: bold;
        }

        .toggle-buttons button:hover {
            background-color: #e0a800;
        }

        #message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }

        footer {
            background-color: #1d1e22;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
        }

        footer a {
            color: #ffc107;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .create-account-link {
            color: #ffc107;
            text-decoration: none;
            cursor: pointer;
        }

        .create-account-link:hover {
            text-decoration: underline;
        }

        /* Updated Checkbox Styling */
        .checkbox-container {
            
    display: block;
    align-items: center;
    justify-content: flex-start; /* Align items to the left */
    margin-bottom: 15px;
    text-align: center; /* Ensure left alignment */
}

.checkbox-container input[type="checkbox"] {
    margin-right: 10px; /* Add space between checkbox and label */
    accent-color: #ffc107; /* Customizes checkbox color */
    cursor: pointer;
}

.checkbox-container label {
    color: #fff;
    cursor: pointer;
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

    <!-- Form Containers -->
    <div class="form-container" id="signup-container">
        <header>Signup</header>
        <form action="" method="POST" autocomplete="off">
            <input type="text" name="full_name" placeholder="Full name" required autocomplete="off">
            <input type="email" name="email" placeholder="Email Address" required autocomplete="off">
            <input type="password" name="password" placeholder="Password" required autocomplete="new-password">
            <input type="hidden" name="action" value="signup">
            
            <input type="submit" value="Signup">
        </form>
        <?php if ($error_message) {
            echo "<div style='color: red;'>$error_message</div>";
        } ?>
        <?php if ($success_message) {
            echo "<div style='color: green;'>$success_message</div>";
        } ?>
    </div>

    <div class="form-container" id="login-container">
    <header>Login</header>
    <form action="" method="POST" autocomplete="off">
        <input type="email" name="email" placeholder="Email Address" required autocomplete="off">
        <input type="password" name="password" placeholder="Password" required autocomplete="new-password">
        <!-- Admin Checkbox -->
        <div class="checkbox-container">
            <input type="checkbox" name="is_admin" id="is_admin" value="1">
            <label for="is_admin">Log in as Admin</label>
        </div>
        <input type="hidden" name="action" value="login">
        <input type="submit" value="Login">
        <div id="message">
            <?php if ($error_message) {
                echo "<div style='color: red;'>$error_message</div>";
            } ?>
        </div>
        <p style="margin-top: 15px;">
            Don't have an account? <span class="create-account-link" id="show-signup-link">Create one</span>
        </p>
        <p style="margin-top: 15px;">
            <a href="#" id="forgot-password-link">Forgot Password?</a>
        </p>
    </form>
</div>
<div class="form-container" id="forgot-password-container">
    <header>Forgot Password</header>
    <form action="" method="POST" autocomplete="off">
        <input type="email" name="email" placeholder="Email Address" required autocomplete="off">
        <input type="hidden" name="action" value="forgot_password">
        <input type="submit" value="Send OTP">
        <div id="message">
            <?php if ($error_message) {
                echo "<div style='color: red;'>$error_message</div>";
            } ?>
        </div>
        <p style="margin-top: 15px;">
            Remember your password? <span class="create-account-link" id="show-login-link">Login</span>
        </p>
    </form>
</div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Fastest International Shipment. All rights reserved. | <a href="AboutUs.php">About Us</a> | <a href="ClientsContact.php">Contact</a></p>
        </div>
    </footer>

    <script>
        const signupContainer = document.getElementById("signup-container");
const loginContainer = document.getElementById("login-container");
const forgotPasswordContainer = document.getElementById("forgot-password-container");
const showSignupButton = document.getElementById("show-signup-link");
const showLoginButton = document.getElementById("show-login-link");
const forgotPasswordLink = document.getElementById("forgot-password-link");

const urlParams = new URLSearchParams(window.location.search);
const defaultView = urlParams.get('view');

if (defaultView === 'signup') {
    signupContainer.classList.add("active");
    loginContainer.classList.remove("active");
    forgotPasswordContainer.classList.remove("active");
} else {
    loginContainer.classList.add("active");
    signupContainer.classList.remove("active");
    forgotPasswordContainer.classList.remove("active");
}

showSignupButton.addEventListener("click", () => {
    signupContainer.classList.add("active");
    loginContainer.classList.remove("active");
    forgotPasswordContainer.classList.remove("active");
});

showLoginButton.addEventListener("click", () => {
    loginContainer.classList.add("active");
    signupContainer.classList.remove("active");
    forgotPasswordContainer.classList.remove("active");
});

forgotPasswordLink.addEventListener("click", () => {
    forgotPasswordContainer.classList.add("active");
    loginContainer.classList.remove("active");
    signupContainer.classList.remove("active");
});
       
    </script>

</body>

</html>
