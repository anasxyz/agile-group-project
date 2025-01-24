<?php
session_start(); // Start the session

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Card Interaction UI</title>
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

    .container {
      display: grid;
      grid-template-columns: repeat(2, 1fr); /* Create two equal columns */
      gap: 20px;
      width: 600px;
      text-align: center;
    }

    h1 {
      font-size: 26px;
      font-weight: bold;
      color: #333;
      text-align: center;
      grid-column: span 3; /* Make the title span both columns */
    }

    .option {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      cursor: pointer;
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
        background-color: #b0b0b0;
        transform: none;
    }

  </style>
</head>
<body>
  <div class="container">
    <h1>Select a Transaction</h1>

    <!-- Balance Inquiry -->
    <form id="redirectForm" method="POST" action="balance_options.php">
      <div class="option" onclick="redirect()">
        <img src="https://img.icons8.com/ios/50/sales-performance-balance.png" alt="Balance Inquiry">
        <span>Balance Inquiry</span>
      </div>
    </form>

    <!-- Cash Withdrawal -->
    <form id="redirectForm" method="POST" action="pin_entry.php">
      <div class="option" onclick="redirect()">
      <img src="https://img.icons8.com/pulsar-line/100/withdrawal-limit.png" alt="Balance Inquiry">
        <span>Cash Withdrawal</span>
      </div>
    </form>

    <!-- Deposits (Inactive) -->
    <form method="POST" action="pin_entry.php">
      <div class="option inactive" onclick="">
      <img src="https://img.icons8.com/ios-filled/50/deposit.png" alt="Balance Inquiry">
        <span>Deposits</span>
      </div>
    </form>

    <!-- Payments (Inactive) -->
    <form method="POST" action="pin_entry.php">
      <div class="option inactive" onclick="">
        <img src="https://img.icons8.com/ios/50/cash-in-hand.png" alt="Balance Inquiry">
        <span>Payments</span>
      </div>
    </form>

    <!-- Mixed Deposits (Inactive) -->
    <form method="POST" action="pin_entry.php">
      <div class="option inactive" onclick="">
        <img src="https://img.icons8.com/pulsar-line/100/cash.png" alt="Balance Inquiry">
        <span>Mixed Deposits</span>
      </div>
    </form>

    <!-- Cash a Check (Inactive) -->
    <form method="POST" action="pin_entry.php">
      <div class="option inactive" onclick="">
        <img src="https://img.icons8.com/pulsar-line/100/check.png" alt="Balance Inquiry">
        <span>Cash a Check</span>
      </div>
    </form>

    <!-- Check Deposit With CashBack (Inactive) -->
    <form method="POST" action="pin_entry.php">
      <div class="option inactive" onclick="">
        <img src="https://img.icons8.com/wired/64/cash-in-hand.png" alt="Balance Inquiry">
        <span>Check Deposit With CashBack</span>
      </div>
    </form>
  </div>

  <script>
    function redirect() {
      document.getElementById("redirectForm").submit();
    }
  </script>
</body>
</html>
