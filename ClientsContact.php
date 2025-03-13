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
  <title>FAQ - Fastest International Shipment</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  
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

    /* Custom white toggler icon */
    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255, 255, 255, 1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }
    .faq-section {
      padding: 50px 20px;
      max-width: 800px;
      margin: 0 auto;
    }
    .faq-section h1 {
      font-size: 36px;
      font-weight: bold;
      color: #ffc107;
      text-align: center;
      margin-bottom: 40px;
    }
    .faq-item {
      margin-bottom: 30px;
    }
    .faq-item h3 {
      font-size: 24px;
      font-weight: bold;
      color: #ffc107;
      margin-bottom: 10px;
    }
    .faq-item p {
      font-size: 16px;
      color: #fff;
      line-height: 1.6;
    }
    .faq-item ul {
      list-style-type: disc;
      margin-left: 20px;
      color: #fff;
    }
    .faq-item ul li {
      margin-bottom: 10px;
    }
    .contact-section {
      text-align: center;
      padding: 50px 20px;
      background-color: #1d1e22;
      margin-top: 50px;
    }
    .contact-section h2 {
      font-size: 28px;
      font-weight: bold;
      color: #ffc107;
      margin-bottom: 20px;
    }
    .contact-section a {
      display: inline-block;
      padding: 10px 20px;
      background-color: #ffc107;
      color: #121212;
      text-decoration: none;
      font-size: 18px;
      font-weight: bold;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }
    .contact-section a:hover {
      background-color: #e0a800;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
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

  <!-- FAQ Section -->
  <div class="faq-section">
    <h1>General FAQ (Frequently Asked Questions)</h1>

    <!-- About the System -->
    <div class="faq-item">
      <h3>About the System</h3>
      <ul>
        <li>
          <strong>1. What is this system, and how does it work?</strong>
          <p>This system connects requesters with travelers who have available luggage space, enabling small-item deliveries between airports.</p>
        </li>
        <li>
          <strong>2. Who can use this platform?</strong>
          <p>Anyone who needs to send an item or travelers who want to earn money by carrying deliveries while flying.</p>
        </li>
        <li>
          <strong>3. Is this service available globally, or is it limited to certain countries?</strong>
          <p>The service currently operates in select countries, such as Malaysia and Saudi Arabia, but may expand in the future.</p>
        </li>
        <li>
          <strong>4. How is this system different from traditional courier services?</strong>
          <p>Unlike traditional courier services, this system leverages travelers' unused luggage space to provide a cost-effective and flexible delivery option.</p>
        </li>
        <li>
          <strong>5. What are the benefits of using this system for airport-to-airport delivery?</strong>
          <p>Lower costs, faster deliveries, the traveler can use their luggage to earn money, and the ability to track shipments in real-time while using a secure network.</p>
        </li>
      </ul>
    </div>

    <!-- Registration & User Accounts -->
    <div class="faq-item">
      <h3>Registration & User Accounts</h3>
      <ul>
        <li>
          <strong>6. How do I create an account?</strong>
          <p>You can sign up through the website using your email.</p>
        </li>
        <li>
          <strong>7. Do I need to verify my identity before using the service?</strong>
          <p>Yes, all users must verify their email account for security purposes. Travelers may need to provide flight details.</p>
        </li>
        <li>
          <strong>8. Can I register as both a requester and a traveler?</strong>
          <p>yes you can switch to be sender or traveller.</p>
        </li>
        <li>
          <strong>9. What should I do if I forget my password?</strong>
          <p>Use the "Forgot Password" option to reset your credentials via email.</p>
        </li>
        <li>
          <strong>10. Is my personal information secure?</strong>
          <p>Yes, we use encryption and data protection measures to keep your information safe.</p>
        </li>
      </ul>
    </div>

    <!-- Sending & Receiving Deliveries -->
    <div class="faq-item">
      <h3>Sending & Receiving Deliveries</h3>
      <ul>
        <li>
          <strong>11. How do I send an item using this system?</strong>
          <p>Create a delivery request, provide item details, select a location, and complete the payment.</p>
        </li>
        <li>
          <strong>12. What types of items can I send?</strong>
          <p>Small items such as Zamzam water, fruit, perfume, clothes, and others you can find in the list.</p>
        </li>
        <li>
          <strong>13. Are there any prohibited items?</strong>
          <p>Yes, every item not allowed in both countries is prohibited.</p>
        </li>
        <li>
          <strong>14. What should I do if no travelers are available?</strong>
          <p>You can wait for new travelers to become available or try adjusting your request details.</p>
        </li>
      </ul>
    </div>

    <!-- Traveler Responsibilities -->
    <div class="faq-item">
      <h3>Traveler Responsibilities</h3>
      <ul>
        <li>
          <strong>16. How can I sign up as a traveler?</strong>
          <p>Register on the platform and verify your identity.</p>
        </li>
        <li>
          <strong>17. How do I ensure that I am not carrying illegal or restricted items?</strong>
          <p>Every item listed on the platform is legal.</p>
        </li>
        <li>
          <strong>19. What happens if I am unable to complete the delivery?</strong>
          <p>Notify the requester and customer support immediately for assistance.</p>
        </li>
        <li>
          <strong>20. Can travelers refuse a delivery request?</strong>
          <p>Yes, travelers can accept or decline any delivery based on their preferences.</p>
        </li>
      </ul>
    </div>

    <!-- Payments & Fees -->
    <div class="faq-item">
      <h3>Payments & Fees</h3>
      <ul>
        <li>
          <strong>21. How are payments processed?</strong>
          <p>Payments are securely processed through the platform, with funds held until delivery confirmation.</p>
        </li>
        <li>
          <strong>22. Are there any service fees for using the platform?</strong>
          <p>The first 50 orders are free, and a small fee is charged per transaction to cover operational costs.</p>
        </li>
        <li>
          <strong>23. When does the traveler receive their payment?</strong>
          <p>After the requester confirms that the package was successfully delivered.</p>
        </li>
        <li>
          <strong>24. What happens if a requester cancels a delivery after payment?</strong>
          <p>The traveler may receive partial compensation, and the remaining amount is refunded to the requester.</p>
        </li>
        <li>
          <strong>25. Are refunds available if a delivery is not completed?</strong>
          <p>Yes, refunds are processed if the delivery is canceled or not successfully completed.</p>
        </li>
      </ul>
    </div>

    <!-- Security & Tracking -->
    <div class="faq-item">
      <h3>Security & Tracking</h3>
      <ul>
        <li>
          <strong>26. How does the system ensure the security of shipments?</strong>
          <p>Through user verification, real-time tracking, and secure payment processing.</p>
        </li>
        <li>
          <strong>27. Can I track my package in real-time?</strong>
          <p>Yes, the system provides live tracking and status updates.</p>
        </li>
        <li>
          <strong>28. What should I do if my package is lost or damaged?</strong>
          <p>Contact customer support immediately for assistance and dispute resolution.</p>
        </li>
        <li>
          <strong>29. How does the platform prevent fraud?</strong>
          <p>The system includes identity verification, transaction monitoring, and strict compliance with aviation regulations.</p>
        </li>
        <li>
          <strong>30. Is there customer support available for assistance?</strong>
          <p>Yes, 24/7 customer support is available for any issues or concerns.</p>
        </li>
      </ul>
    </div>
  </div>

  <!-- Contact Section -->
  <div class="contact-section">
    <h2>Need Further Assistance?</h2>
    <a href="https://wa.me/60105999414" target="_blank">Contact Us on WhatsApp</a>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
