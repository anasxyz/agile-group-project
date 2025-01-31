<?php
unset($_SESSION['card_number']);
unset($_SESSION['expiry']);
unset($_SESSION['pin']);

$targetUrl1 = 'http://transactionswitch.us-east-1.elasticbeanstalk.com/clear_log.php';
$targetUrl2 = 'http://networksim.us-east-1.elasticbeanstalk.com/clear_log.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ATM</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f5f5f5;
    }

    /* .container {
      text-align: center;
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
      width: 600px;
    } */

    .option {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      margin: 15px 0;
      width: 500px;
      cursor: pointer;
      padding: 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      transition: 0.3s;
    }

    .option:hover {
      background-color: #f0f0f0;
      transform: scale(1.02);
    }

    .option img {
      width: 80px;
      height: 80px;
      margin-bottom: 10px;
    }

    .option span {
      font-size: 1.2em;
      color: #333;
    }

    /* Inactive Button Styling */
    .option.inactive {
        background-color: #b0b0b0;
        color: #7a7a7a;
        cursor: not-allowed;
        box-shadow: none;
    }

    .option.inactive:hover {
        background-color: #b0b0b0; /* Disable hover effect */
        transform: none; /* Disable hover transform */
    }

  </style>
</head>
<body>
  <div class="container">
    <form id="redirectForm" method="POST" action="insert_card.php">
      <div class="option" onclick="redirectToPinEntry()">
        <img src="https://img.icons8.com/ios/50/bank-card-back-side--v1.png" alt="Insert Card Icon">
        <span>Insert Card</span>
      </div>
    </form>
    <div class="option inactive">
      <img src="https://img.icons8.com/pulsar-line/48/credit-card-contactless.png" alt="Tap Card Icon">
      <span>Tap Card</span>
    </div>
    <div class="option inactive">
      <img src="https://img.icons8.com/fluency-systems-regular/50/multiple-smartphones.png" alt="Tap Mobile Icon">
      <span>Tap Mobile</span>
    </div>
  </div>

  <script>
    function redirectToPinEntry() {
      document.getElementById("redirectForm").submit();
    }
  </script>
</body>
</html>
