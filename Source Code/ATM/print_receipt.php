<?php
$_SESSION['language'] = 'es';
$language = $_SESSION['language'] ?? 'en';

$lang = include "../languages/{$language}.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print Receipt</title>
  <link rel="stylesheet" href="styles.css">
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
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 20px 30px;
      border-radius: 10px;
    }

    h1 {
      font-size: 26px;
      font-weight: bold;
      color: #333;
      margin-bottom: 30px;
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
      height: 150px;
      width: 250px;
    }

    .option:hover {
      background-color: #f0f0f0;
      transform: scale(1.02);
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
    <div class="option" onclick="perform_another_transaction()">
      <span style="padding-bottom: 20px;"><?= $lang['take_your_receipt'] ?></span>
      <img src="https://img.icons8.com/pulsar-line/100/receipt.png" alt="receipt" />
    </div>
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

  <script src="modal.js">

  </script>
</body>

</html>