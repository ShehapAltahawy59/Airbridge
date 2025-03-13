<?php
session_start(); // Start the session
require 'DB.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE UserID  = :user_id");

// Bind the parameter securely
$stmt->bindParam(':user_id', $_SESSION['UserID'], PDO::PARAM_INT);

// Execute the statement
$stmt->execute();

$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
error_reporting(0);
unset($_SESSION['form_data']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>New Sender Request</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://js.stripe.com/v3/"></script>
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

    .wrapper-customized {
      margin-top: 100px;
      padding: 20px;
    }

    .form {
      background-color: #1d1e22;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form header {
      font-size: 24px;
      font-weight: 600;
      color: #ffc107;
      text-align: center;
      margin-bottom: 20px;
    }

    .form input,
    .form select {
      background-color: #2a2b2f;
      color: #fff;
      border: 1px solid #444;
      border-radius: 5px;
      padding: 10px;
      margin-bottom: 15px;
      width: 100%;
    }

    .form input:focus,
    .form select:focus {
      border-color: #ffc107;
      outline: none;
    }

    .form img {
      border-radius: 5px;
      width: 200px;
      height: auto;
      margin-bottom: 15px;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }

    .btn-custom {
      background-color: #ffc107;
      color: #000;
      padding: 10px 20px;
      border-radius: 25px;
      font-weight: 500;
      transition: background-color 0.3s;
      text-decoration: none;
      border: none;
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

  <!-- New Sender Request Form -->
  <section class="wrapper-customized">
    <div class="form">
      <header>New Sender Request</header>
      <form id="senderForm" action="" method="POST">
        <div class="row">
          <div class="col-md-6">
            <select id="Item_List" class="form-select" name="Item_List" required>
              <option value="" selected disabled>Select Item</option>
              <option value="Zamzam Water">Zamzam Water</option>
              <option value="Dates">Dates</option>
              <option value="Perfume">Perfume</option>
              <option value="Bakhoor">Bakhoor</option>
              <option value="Traditional Arabic Clothes">Traditional Arabic Clothes</option>
              <option value="Traditional Malaysian Clothes">Traditional Malaysian Clothes</option>
              <option value="Malaysian fruits">Malaysian fruits</option>
              <option value="LABAN">LABAN</option>
              <option value="SPICE">SPICE</option>
            </select>
          </div>
          <div class="col-md-6">
            <img id="Item_Img" src="./images/00.jpg" alt="Item Image" class="img-fluid">
          </div>
        </div>

        <input type="text" placeholder="Please Enter The Brand Name" name="BrandName" required />

        <div class="row">
          <div class="col-md-6">
            <select id="Item_Weight" name="Item_Weight" class="form-select" required>
              <option value="" selected disabled>Select Item Weight</option>
              <option value="1 kg">1 kg</option>
              <option value="2 kg">2 kg</option>
              <option value="3 kg">3 kg</option>
              <option value="4 kg">4 kg</option>
              <option value="5 kg">5 kg</option>
            </select>
          </div>
          <div class="col-md-6">
            <input id="Shipment_Cost" type="text" name="Shipment_Cost" placeholder="Shipment Cost" readonly required />
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <input id="Item_Price" type="text" name="Item_Price" placeholder="Item Price" required />
          </div>
          <div class="col-md-6">
            <input id="Total_Price" type="text" name="Total_Price" placeholder="Total Price" readonly required />
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <select id="airport_ddl" name="PickupAirport" class="form-select" required>
              <option value="Select To Airport" selected>Select From Airport</option>
              <option value="Saudi Arabia">Saudi Arabia</option>
              <option value="Malaysia">Malaysia</option>
            </select>
          </div>
          <div class="col-md-6">
            <select id="malysia_ddl" class="form-select" name="DropoffAirport" style="display: none;" required>
              <option value="Select To Airport" selected>Select To Airport</option>
              <option value="Jeddah Airport">Jeddah Airport</option>
              <option value="Dammam Airport">Dammam Airport</option>
              <option value="Riyadh Airport">Riyadh Airport</option>
            </select>
            <select id="saudi_ddl" class="form-select" name="DropoffAirport" style="display: none;" required>
              <option value="" selected>Select To Airport</option>
              <option value="Kuala Lumpur Airport">Kuala Lumpur Airport</option>
              <option value="Johor Airport">Johor Airport</option>
              <option value="Penang Airport">Penang Airport</option>
            </select>
          </div>
        </div>


        <div class="row">
          <div class="col-md-6">
            <label for="fromDate">From Date</label>
            <input type="date" id="fromDate" name="fromDate" class="form-control" required />
          </div>
          <div class="col-md-6">
            <label for="toDate">To Date</label>
            <input type="date" id="toDate" name="toDate" class="form-control" required />
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">
            <button type="button" id="payButton" class="btn btn-custom">Pay</button>
            <!-- <input type="submit" value="Submit" class="btn btn-custom" /> -->
          </div>
        </div>
      </form>
    </div>
  </section>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- Custom Script -->
  <script>
  $(document).ready(function() {
  $("#Item_Img").attr('src', './images/00.jpg');
  $("#malysia_ddl").hide();
  $("#saudi_ddl").hide();

  const airport_select = $('#airport_ddl');
  const Item_weight_select = $('#Item_Weight');
  const Item_Price = $('#Item_Price');
  const Item_List = $('#Item_List');

  $('#airport_ddl').on('change', function() {
    const ddl_val = $(this).val();

    if (ddl_val === "Saudi Arabia") {
      $("#malysia_ddl").hide(); // Hide Malaysia dropdown
      $("#saudi_ddl").show();  // Show Saudi Arabia dropdown

      // Update the name attribute for Saudi Arabia dropdown
      $("#saudi_ddl").attr('name', 'DropoffAirport');
      $("#malysia_ddl").removeAttr('name'); // Remove name attribute for Malaysia dropdown
    } else if (ddl_val === "Malaysia") {
      $("#saudi_ddl").hide(); // Hide Saudi Arabia dropdown
      $("#malysia_ddl").show(); // Show Malaysia dropdown

      // Update the name attribute for Malaysia dropdown
      $("#malysia_ddl").attr('name', 'DropoffAirport');
      $("#saudi_ddl").removeAttr('name'); // Remove name attribute for Saudi Arabia dropdown
    } else {
      $("#saudi_ddl").hide(); // Hide both dropdowns
      $("#malysia_ddl").hide();
      $("#saudi_ddl").removeAttr('name'); // Remove name attributes
      $("#malysia_ddl").removeAttr('name');
    }
  });


  Item_weight_select.on('change', () => {
    const Item_weight_select_val = $('#Item_Weight').val();
    const shipmentCosts = {
      "1 kg": 99,
      "2 kg": 149,
      "3 kg": 174,
      "4 kg": 199,
      "5 kg": 219
    };
    $("#Shipment_Cost").val(shipmentCosts[Item_weight_select_val] || '');

    if ($("#Shipment_Cost").val() && $("#Item_Price").val()) {
      const _Total_Price = Calculate_Total_Price($("#Shipment_Cost").val(), $("#Item_Price").val());
      $("#Total_Price").val(_Total_Price);
    }
  });

  Item_Price.on('input', () => {
    if ($("#Shipment_Cost").val() && $("#Item_Price").val()) {
      const _Total_Price = Calculate_Total_Price($("#Shipment_Cost").val(), $("#Item_Price").val());
      $("#Total_Price").val(_Total_Price);
    }
  });

  Item_List.on('change', () => {
    const Item_List_val = $('#Item_List').val();
    const images = {
      "Zamzam Water": './images/07.jpeg',
      "Dates": './images/06.jpeg',
      "Perfume": './images/05.jpeg',
      "Bakhoor": './images/01.jpeg',
      "Traditional Arabic Clothes": './images/03.jpeg',
      "Traditional Malaysian Clothes": './images/04.jpeg',
      "Malaysian fruits": './images/02.jpeg',
      "LABAN": './images/08.jpeg',
      "SPICE": './images/09.jpeg'
    };
    $("#Item_Img").attr('src', images[Item_List_val] || './images/01.jpg');
  });

  // Set the minimum date for "fromDate" to today
  const now = new Date();
  const saudiOffset = 3 * 60 * 60 * 1000; // 3 hours in milliseconds
  const saudiDate = new Date(now.getTime() + saudiOffset);
  const today = saudiDate.toISOString().split('T')[0];
  $("#fromDate").attr('min', today);
  $("#toDate").attr('min', today);
  // Validate "fromDate" and "toDate" when the form is submitted
  
  
  $('#payButton').on('click', async function() {
    const fromDate = $("#fromDate").val();
    const toDate = $("#toDate").val();
    const totalprice = $("#Total_Price").val();
    // Check if "fromDate" is earlier than today
    

    // Proceed with the rest of the validation and payment process
    const button = $(this);
    button.prop('disabled', true); // Disable the button
    button.text('Processing...'); // Change button text

    let isValid = true;
    let missingFields = []; // Array to store names of missing fields

    // Check all visible input fields and select elements
    $('#senderForm input:visible, #senderForm select:visible').each(function() {
      if (!$(this).val()) {
        isValid = false;
        $(this).addClass('is-invalid'); // Highlight the missing field
        const fieldName = $(this).attr('name') || $(this).attr('id'); // Get the field name or ID
        missingFields.push(fieldName); // Add the missing field to the list
      } else {
        $(this).removeClass('is-invalid'); // Remove highlight if the field is filled
      }
    });

    if (new Date(fromDate) < new Date(today)) {
      alert("The 'From Date' cannot be earlier than today.");
      button.prop('disabled', false); // Re-enable the button
      button.text('Proceed to Payment'); // Restore button text
      return;
    }

    // Check if "toDate" is earlier than "fromDate"
    if (new Date(toDate) < new Date(fromDate)) {
      alert("The 'To Date' cannot be earlier than the 'From Date'.");
      button.prop('disabled', false); // Re-enable the button
      button.text('Proceed to Payment'); // Restore button text
      return;
    }

    if (isValid) {
      const formData_ = new FormData(document.getElementById('senderForm'));
            const response = await fetch('submit-form.php', {
                method: 'POST',
                body: formData_,
            });

            if (!response.ok) {
                throw new Error('Failed to submit form data');
            }

            const result = await response.json();
            if (result.status !== 'success') {
                throw new Error('Failed to update session data');
            }

      const stripe = Stripe('pk_test_51QufPsPJzSYNNBgpSbjZiYaCG8UlgORFuNamvLGOp5eXnhRJZXiLXyFctj0hBpZ1cyHyMr0Nv7uADMuLMFeWs5qT00lRn3ZfZD'); // Add your Stripe Publishable Key here
      try {
        const response = await fetch('http://localhost/AirBridgeDesign/create-checkout-session.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            request_id: <?= $_SESSION['UserID'] ?>,
            amount: parseFloat($("#Total_Price").val()) * 100 ,
            currency: 'usd',
          }),
        });
        console.log("Fetch response:", response);

        if (!response.ok) {
          throw new Error('Network response was not ok');
        }

        const session = await response.json();
        console.log("Stripe session:", session);

        const result = await stripe.redirectToCheckout({
          sessionId: session.id,
        });

        if (result.error) {
          console.error("Stripe redirect error:", result.error);
          alert(result.error.message);
        }
      } catch (error) {
        console.error("Error:", error);
        alert('An error occurred. Please try again.');
      } finally {
        button.prop('disabled', false); // Re-enable the button
        button.text('Proceed to Payment'); // Restore button text
      }
    } else {
      // Generate a user-friendly message listing the missing fields
      let errorMessage = 'Please fill out the following fields before proceeding to payment:\n\n';
      missingFields.forEach((field, index) => {
        errorMessage += `${index + 1}. ${field}\n`;
      });

      // Display the error message in an alert or a modal
      alert(errorMessage);

      // Alternatively, display the error message in a div below the form
      $('#errorMessage').html(`<div class="alert alert-danger">${errorMessage}</div>`);

      button.prop('disabled', false); // Re-enable the button
      button.text('Proceed to Payment'); // Restore button text
    }
  });
});

function Calculate_Total_Price(val1, val2) {
  return parseFloat(val1) + parseFloat(val2);
}
  </script>
</body>

</html>
