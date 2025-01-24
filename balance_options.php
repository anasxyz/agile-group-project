<?php
session_start(); // Ensure session is started to use session variables
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
      grid-column: span 2; /* Make the title span both columns */
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
      height: 100px;
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

    /* Modal Styles */
    .modal {
      display: none; /* Hidden by default */
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
    }

    .modal-button:hover {
      background-color: #ddd;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Select a Transaction</h1>

    <!-- Checking -->
    <div class="option" onclick="showModal('Checking', 'You selected the Checking account. Would you like to proceed?', 'Yes', 'Cancel', 'redirectToPinEntry()', 'closeModal()')">
      <span>Checking</span>
    </div>

    <!-- Savings -->
    <div class="option" onclick="redirect()">
      <span>Savings</span>
    </div>

    <!-- Credit -->
    <div class="option" onclick="">
      <span>Credit</span>
    </div>

    <!-- Loan -->
    <div class="option" onclick="">
      <span>Loan</span>
    </div>
  </div>

  <!-- Modal -->
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
      // Set the title, message, and button text
      document.getElementById('modalTitle').textContent = title;
      document.getElementById('modalMessage').textContent = message;
      document.getElementById('button1').textContent = button1Text;
      document.getElementById('button2').textContent = button2Text;

      // Set the button actions
      document.getElementById('button1').setAttribute('onclick', button1Action);
      document.getElementById('button2').setAttribute('onclick', button2Action);

      // Show the modal
      document.getElementById('customModal').style.display = 'block';
    }

    function closeModal() {
      // Close the modal
      document.getElementById('customModal').style.display = 'none';
    }

    function redirect() {
      
    }
  </script>
</body>
</html>
