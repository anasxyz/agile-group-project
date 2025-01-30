<?php
session_start();

$language = $_SESSION['language'] ?? 'en';
$lang = include "../languages/{$language}.php";

// Retrieve the account type from the URL
$accountType = isset($_GET['account_type']) ? htmlspecialchars($_GET['account_type']) : 'Unknown';
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $lang['take_your_cash'] ?></title>
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
      width: 300px;
      padding: 30px;
      text-align: center;
      border-radius: 10px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .balance-info {
      font-size: 1.2em;
      color: #333;
      margin-bottom: 20px;
    }

    h1 {
      font-size: 26px;
      font-weight: bold;
      color: #333;
      margin-bottom: 20px;
    }

    h3 {
      font-size: 18px;
      color: #555;
      margin-bottom: 10px;
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
      height: 50px;
      margin-top: 20px;
    }

    .option:hover {
      background-color: #f0f0f0;
      transform: scale(1.02);
    }

    .option span {
      font-size: 1.2em;
      color: #333;
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
      background-color: #fff;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      text-align: center;
      border-radius: 25px;
    }

    .modal-footer {
      margin-top: 20px;
    }

    .modal-button {
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      margin: 0 10px;
      border-radius: 25px;
    }

    .modal-button:hover {
      background-color: #ddd;
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
  </style>
</head>

<body>
  <div class="container">
    <h1><?= $lang['take_your_cash'] ?></h1>

    <div class="balance-info">
      <p></p>
    </div>

    <div class="option" onclick="openModal()">
      <span><?= $lang['take_cash'] ?></span>
    </div>
  </div>

  <div id="customModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2 id="modalTitle"><?= $lang['perform_another_transaction_title'] ?></h2>
      <p id="modalMessage"><?= $lang['perform_another_transaction_message'] ?></p>
      <div class="modal-footer">
        <button id="button1" class="modal-button" onclick="performAnotherTransaction()"><?= $lang['yes'] ?></button>
        <button id="button2" class="modal-button" onclick="endTransaction()"><?= $lang['no'] ?></button>
      </div>
    </div>
  </div>

  <script>
    function openModal() {
      document.getElementById("customModal").style.display = "block";
    }

    function closeModal() {
      document.getElementById("customModal").style.display = "none";
    }

    function performAnotherTransaction() {
      window.location.href = "insert_card.php";
    }

    function endTransaction() {
      window.location.href = "take_card_out.php";
    }
  </script>

  <script src="modal.js"></script>
</body>

</html>