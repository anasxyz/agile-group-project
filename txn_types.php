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

    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      text-align: center;
      border-radius: 30px;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    .modal-footer {
      margin-top: 20px;
    }

    .modal-button {
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      margin: 0 10px;
      border-radius: 30px;
    }

    .modal-button:hover {
      background-color: #ddd;
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
    <form id="redirectForm" method="POST" action="withdrawal_options.php">
      <div class="option" onclick="showModal('Acknowledgement', 'You may be charged a fee for this transaction. Do you wish to continue?', 'Decline', 'Accept', 'closeModal()', 'redirectWithdrawal()')">
      <img src="https://img.icons8.com/pulsar-line/100/withdrawal-limit.png" alt="">
        <span>Cash Withdrawal</span>
      </div>
    </form>

    <!-- Deposits (Inactive) -->
    <form method="POST" action="">
      <div class="option inactive" onclick="">
      <img src="https://img.icons8.com/ios-filled/50/deposit.png" alt="">
        <span>Deposits</span>
      </div>
    </form>

    <!-- Payments (Inactive) -->
    <form method="POST" action="">
      <div class="option inactive" onclick="">
        <img src="https://img.icons8.com/ios/50/cash-in-hand.png" alt="">
        <span>Payments</span>
      </div>
    </form>

    <!-- Mixed Deposits (Inactive) -->
    <form method="POST" action="">
      <div class="option inactive" onclick="">
        <img src="https://img.icons8.com/pulsar-line/100/cash.png" alt="">
        <span>Mixed Deposits</span>
      </div>
    </form>

    <!-- Cash a Check (Inactive) -->
    <form method="POST" action="">
      <div class="option inactive" onclick="">
        <img src="https://img.icons8.com/pulsar-line/100/check.png" alt="">
        <span>Cash a Check</span>
      </div>
    </form>

    <!-- Check Deposit With CashBack (Inactive) -->
    <form method="POST" action="">
      <div class="option inactive" onclick="">
        <img src="https://img.icons8.com/wired/64/cash-in-hand.png" alt="">
        <span>Check Deposit With CashBack</span>
      </div>
    </form>
  </div>

  <div id="customModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2 id="modalTitle">Default Title</h2>
      <p id="modalMessage">Default message goes here.</p>
      <div class="modal-footer">
        <button id="button1" class="modal-button" onclick="">Button 1</button>
        <button id="button2" class="modal-button" onclick="">Button 2</button>
      </div>
    </div>
  </div>

  <script>
    function showModal(title, message, button1Text, button2Text, button1Action, button2Action) {
      document.getElementById('modalTitle').textContent = title;
      document.getElementById('modalMessage').textContent = message;
      document.getElementById('button1').textContent = button1Text;
      document.getElementById('button2').textContent = button2Text;

      document.getElementById('button1').setAttribute('onclick', button1Action);
      document.getElementById('button2').setAttribute('onclick', button2Action);

      document.getElementById('customModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('customModal').style.display = 'none';
    }

    function redirect() {
      document.getElementById("redirectForm").submit();
    }

    function redirectWithdrawal() {
      window.location.href = "withdrawal_options.php";
    }
  </script>
</body>
</html>
