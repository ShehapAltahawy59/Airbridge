<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment Information</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://js.stripe.com/v3/"></script>
  <!-- Custom CSS -->
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      font-family: 'Poppins', sans-serif;
    }

    .payment-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      max-width: 1200px;
      width: 100%;
      gap: 40px;
      padding: 20px;
    }

    .payment-title {
      width: 100%;
      text-align: center;
      margin-bottom: 30px;
    }

    .payment-title h1 {
      font-size: 2rem;
      font-weight: 600;
      color: #333;
    }

    .card-container {
      flex: 1;
      max-width: 400px;
      min-width: 300px;
    }

    .form-container {
      flex: 1;
      max-width: 500px;
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      padding: 30px;
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .field-container {
      margin-bottom: 20px;
    }

    .field-container label {
      display: block;
      font-size: 14px;
      font-weight: 500;
      color: #555;
      margin-bottom: 5px;
    }

    .field-container input {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      border: 1px solid #ddd;
      border-radius: 8px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .field-container input:focus {
      border-color: #007bff;
      outline: none;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .ccicon {
      height: 38px;
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      width: 60px;
    }

    .btn-primary {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      font-weight: 500;
      background-color: #007bff;
      border: none;
      border-radius: 8px;
      color: #fff;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      transform: translateY(-2px);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    /* Card Design (Unchanged) */
    .creditcard {
      width: 100%;
      max-width: 400px;
      transform-style: preserve-3d;
      transition: transform 0.6s;
      cursor: pointer;
    }

    .creditcard .front,
    .creditcard .back {
      position: absolute;
      width: 100%;
      max-width: 400px;
      backface-visibility: hidden;
      color: #2565a4;
    }

    .creditcard .back {
      transform: rotateY(180deg);
    }

    .creditcard.flipped {
      transform: rotateY(180deg);
    }

    .creditcard svg#cardfront,
    .creditcard svg#cardback {
      width: 100%;
      box-shadow: 1px 5px 6px 0px rgb(28, 1, 167);
      border-radius: 22px;
    }

    .creditcard .lightcolor,
    .creditcard .darkcolor {
      -webkit-transition: fill .5s;
      transition: fill .5s;
    }

    .creditcard .lightblue {
      fill: #03A9F4;
    }

    .creditcard .lightbluedark {
      fill: #0288D1;
    }

    .creditcard .red {
      fill: #ef5350;
    }

    .creditcard .reddark {
      fill: #d32f2f;
    }

    .creditcard .purple {
      fill: #ab47bc;
    }

    .creditcard .purpledark {
      fill: #7b1fa2;
    }

    .creditcard .cyan {
      fill: #26c6da;
    }

    .creditcard .cyandark {
      fill: #0097a7;
    }

    .creditcard .green {
      fill: #66bb6a;
    }

    .creditcard .greendark {
      fill: #388e3c;
    }

    .creditcard .lime {
      fill: #d4e157;
    }

    .creditcard .limedark {
      fill: #afb42b;
    }

    .creditcard .yellow {
      fill: #ffeb3b;
    }

    .creditcard .yellowdark {
      fill: #f9a825;
    }

    .creditcard .orange {
      fill: #ff9800;
    }

    .creditcard .orangedark {
      fill: #ef6c00;
    }

    .creditcard .grey {
      fill: #4f7eee;
    }

    .creditcard .greydark {
      fill: #0833eb;
    }

    /* FRONT OF CARD */
    #svgname {
      text-transform: uppercase;
    }

    #cardfront .st2 {
      fill: #FFFFFF;
    }

    #cardfront .st3 {
      font-family: 'Source Code Pro', monospace;
      font-weight: 600;
    }

    #cardfront .st4 {
      font-size: 54.7817px;
    }

    #cardfront .st5 {
      font-family: 'Source Code Pro', monospace;
      font-weight: 400;
    }

    #cardfront .st6 {
      font-size: 33.1112px;
    }

    #cardfront .st7 {
      opacity: 0.6;
      fill: #FFFFFF;
    }

    #cardfront .st8 {
      font-size: 24px;
    }

    #cardfront .st9 {
      font-size: 36.5498px;
    }

    #cardfront .st10 {
      font-family: 'Source Code Pro', monospace;
      font-weight: 300;
    }

    #cardfront .st11 {
      font-size: 16.1716px;
    }

    #cardfront .st12 {
      fill: #4C4C4C;
    }

    /* BACK OF CARD */
    #cardback .st0 {
      fill: none;
      stroke: #0F0F0F;
      stroke-miterlimit: 10;
    }

    #cardback .st2 {
      fill: #111111;
    }

    #cardback .st3 {
      fill: #F2F2F2;
    }

    #cardback .st4 {
      fill: #D8D2DB;
    }

    #cardback .st5 {
      fill: #C4C4C4;
    }

    #cardback .st6 {
      font-family: 'Source Code Pro', monospace;
      font-weight: 400;
    }

    #cardback .st7 {
      font-size: 27px;
    }

    #cardback .st8 {
      opacity: 0.6;
    }

    #cardback .st9 {
      fill: #FFFFFF;
    }

    #cardback .st10 {
      font-size: 24px;
    }

    #cardback .st11 {
      fill: #EAEAEA;
    }

    #cardback .st12 {
      font-family: 'Rock Salt', cursive;
    }

    #cardback .st13 {
      font-size: 37.769px;
    }
  </style>
</head>

<body>
  <div class="payment-container">
    
    <!-- Card on the Left -->
    <div class="card-container">
      <div class="creditcard">
        <div class="front">
          <div id="ccsingle"></div>
          <svg version="1.1" id="cardfront" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            x="0px" y="0px" viewBox="0 0 750 471" style="enable-background:new 0 0 750 471;" xml:space="preserve">
            <g id="Front">
              <g id="CardBackground">
                <g id="Page-1_1_">
                  <g id="amex_1_">
                    <path id="Rectangle-1_1_" class="lightcolor grey" d="M40,0h670c22.1,0,40,17.9,40,40v391c0,22.1-17.9,40-40,40H40c-22.1,0-40-17.9-40-40V40
                    C0,17.9,17.9,0,40,0z" />
                  </g>
                </g>
                <path class="darkcolor greydark" d="M750,431V193.2c-217.6-57.5-556.4-13.5-750,24.9V431c0,22.1,17.9,40,40,40h670C732.1,471,750,453.1,750,431z" />
              </g>
              <text transform="matrix(1 0 0 1 60.106 295.0121)" id="svgnumber" class="st2 st3 st4">0123 4567 8910 1112</text>
              <text transform="matrix(1 0 0 1 54.1064 428.1723)" id="svgname" class="st2 st5 st6">JOHN DOE</text>
              <text transform="matrix(1 0 0 1 54.1074 389.8793)" class="st7 st5 st8">cardholder name</text>
              <text transform="matrix(1 0 0 1 479.7754 388.8793)" class="st7 st5 st8">expiration</text>
              <text transform="matrix(1 0 0 1 65.1054 241.5)" class="st7 st5 st8">card number</text>
              <g>
                <text transform="matrix(1 0 0 1 574.4219 433.8095)" id="svgexpire" class="st2 st5 st9">01/23</text>
                <text transform="matrix(1 0 0 1 479.3848 417.0097)" class="st2 st10 st11">VALID</text>
                <text transform="matrix(1 0 0 1 479.3848 435.6762)" class="st2 st10 st11">THRU</text>
                <polygon class="st2" points="554.5,421 540.4,414.2 540.4,427.9 		" />
              </g>
              <g id="cchip">
                <g>
                  <path class="st2" d="M168.1,143.6H82.9c-10.2,0-18.5-8.3-18.5-18.5V74.9c0-10.2,8.3-18.5,18.5-18.5h85.3
                  c10.2,0,18.5,8.3,18.5,18.5v50.2C186.6,135.3,178.3,143.6,168.1,143.6z" />
                </g>
                <g>
                  <g>
                    <rect x="82" y="70" class="st12" width="1.5" height="60" />
                  </g>
                  <g>
                    <rect x="167.4" y="70" class="st12" width="1.5" height="60" />
                  </g>
                  <g>
                    <path class="st12" d="M125.5,130.8c-10.2,0-18.5-8.3-18.5-18.5c0-4.6,1.7-8.9,4.7-12.3c-3-3.4-4.7-7.7-4.7-12.3
                    c0-10.2,8.3-18.5,18.5-18.5s18.5,8.3,18.5,18.5c0,4.6-1.7,8.9-4.7,12.3c3,3.4,4.7,7.7,4.7,12.3
                    C143.9,122.5,135.7,130.8,125.5,130.8z M125.5,70.8c-9.3,0-16.9,7.6-16.9,16.9c0,4.4,1.7,8.6,4.8,11.8l0.5,0.5l-0.5,0.5
                    c-3.1,3.2-4.8,7.4-4.8,11.8c0,9.3,7.6,16.9,16.9,16.9s16.9-7.6,16.9-16.9c0-4.4-1.7-8.6-4.8-11.8l-0.5-0.5l0.5-0.5
                    c3.1-3.2,4.8-7.4,4.8-11.8C142.4,78.4,134.8,70.8,125.5,70.8z" />
                  </g>
                  <g>
                    <rect x="82.8" y="82.1" class="st12" width="25.8" height="1.5" />
                  </g>
                  <g>
                    <rect x="82.8" y="117.9" class="st12" width="26.1" height="1.5" />
                  </g>
                  <g>
                    <rect x="142.4" y="82.1" class="st12" width="25.8" height="1.5" />
                  </g>
                  <g>
                    <rect x="142" y="117.9" class="st12" width="26.2" height="1.5" />
                  </g>
                </g>
              </g>
            </g>
            <g id="Back">
            </g>
          </svg>
        </div>
        <div class="back">
          <svg version="1.1" id="cardback" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            x="0px" y="0px" viewBox="0 0 750 471" style="enable-background:new 0 0 750 471;" xml:space="preserve">
            <g id="Front">
              <line class="st0" x1="35.3" y1="10.4" x2="36.7" y2="11" />
            </g>
            <g id="Back">
              <g id="Page-1_2_">
                <g id="amex_2_">
                  <path id="Rectangle-1_2_" class="darkcolor greydark" d="M40,0h670c22.1,0,40,17.9,40,40v391c0,22.1-17.9,40-40,40H40c-22.1,0-40-17.9-40-40V40
                  C0,17.9,17.9,0,40,0z" />
                </g>
              </g>
              <rect y="61.6" class="st2" width="750" height="78" />
              <g>
                <path class="st3" d="M701.1,249.1H48.9c-3.3,0-6-2.7-6-6v-52.5c0-3.3,2.7-6,6-6h652.1c3.3,0,6,2.7,6,6v52.5
                C707.1,246.4,704.4,249.1,701.1,249.1z" />
                <rect x="42.9" y="198.6" class="st4" width="664.1" height="10.5" />
                <rect x="42.9" y="224.5" class="st4" width="664.1" height="10.5" />
                <path class="st5" d="M701.1,184.6H618h-8h-10v64.5h10h8h83.1c3.3,0,6-2.7,6-6v-52.5C707.1,187.3,704.4,184.6,701.1,184.6z" />
              </g>
              <text transform="matrix(1 0 0 1 621.999 227.2734)" id="svgsecurity" class="st6 st7">985</text>
              <g class="st8">
                <text transform="matrix(1 0 0 1 518.083 280.0879)" class="st9 st6 st10">security code</text>
              </g>
              <rect x="58.1" y="378.6" class="st11" width="375.5" height="13.5" />
              <rect x="58.1" y="405.6" class="st11" width="421.7" height="13.5" />
              <text transform="matrix(1 0 0 1 59.5073 228.6099)" id="svgnameback" class="st12 st13">John Doe</text>
            </g>
          </svg>
        </div>
      </div>
    </div>

    <!-- Form on the Right -->
    <div class="form-container">
      <div class="field-container">
        <label for="name">Name</label>
        <input id="name" maxlength="50" type="text">
      </div>
      <div class="field-container">
        <label for="cardnumber">Card Number</label>
        <input id="cardnumber" maxlength="19" type="text">
      </div>
      <div class="field-container">
        <label for="expirationdate">Expiration (mm/yy)</label>
        <input id="expirationdate" type="text" maxlength="5" placeholder="MM/YY" inputmode="numeric" required>
      </div>
      <div class="field-container">
        <label for="securitycode">Security Code</label>
        <input id="securitycode" maxlength="3" type="text" pattern="[0-9]*" inputmode="numeric">
      </div>
      <div class="field-container">
        <!-- Replace the link with a Stripe Checkout button -->
        <button id="checkout-button" class="btn btn-primary">Pay Now</button>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- Custom Script -->
  <script>
    document.getElementById('expirationdate').addEventListener('input', function(e) {
      let value = e.target.value;
      value = value.replace(/[^0-9]/g, '');

      if (value.length > 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
      }

      if (value.length > 5) {
        value = value.slice(0, 5);
      }

      e.target.value = value;
    });

    document.getElementById('cardnumber').addEventListener('input', function(e) {
      let value = e.target.value.replace(/\s/g, '');
      value = value.replace(/(\d{4})/g, '$1 ').trim();
      e.target.value = value;
    });

    
    // Hint: Replace 'YOUR_PUBLISHABLE_KEY_HERE' with your Stripe Publishable Key (test key)
    const stripe = Stripe('pk_test_51QufPbArzzfywmXvTtRhVBNxx9adgX4K1OVOsqF8yaKUZ7uQ3xnTmruKhPMUOL9ipbqDr5EGBvCCMJ0z8oFLdZte00BrH9RsIS'); // Add your Stripe Publishable Key here

    // Handle the payment button click
    document.getElementById('checkout-button').addEventListener('click', async () => {
      try {
        // Create a payment session (you need a backend for this)
        const response = await fetch('http://localhost/AirBridgeDesign/create-checkout-session.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            amount: 1000, // Amount in cents (e.g., $10.00)
            currency: 'usd', // Currency
          }),
        });

        const session = await response.json();

        // Redirect to Stripe Checkout
        const result = await stripe.redirectToCheckout({
          sessionId: session.id,
        });

        if (result.error) {
          alert(result.error.message);
        }
      } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
      }
    });
  
  </script>
</body>

</html>
