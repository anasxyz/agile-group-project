<?php
session_start(); // If session data is needed

$language = $_SESSION['language'] ?? 'en';
$lang = include "../languages/{$language}.php";
?>

<!DOCTYPE html>
<html lang="<?= $language ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Take Your Card</title>
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
  </style>
</head>

<body>
  <div class="container">
    <div class="option" onclick="take_card_out()">
      <span style="padding-bottom: 20px;"><?= $lang['take_card'] ?></span>
      <img src="https://img.icons8.com/ios/100/bank-card-back-side--v1.png" alt="">
    </div>
  </div>

  <script>
    function take_card_out() {
      // Get URL parameters
      const urlParams = new URLSearchParams(window.location.search);
      const source = urlParams.get('source'); // Retrieve the 'source' parameter

      // Log the source value for debugging
      console.log('Source parameter:', source);

      // Redirect based on the 'source' value
      if (source === 'withdrawal_choice.php?account_type=${encodeURIComponent(checking)}') {
        window.location.href = 'print_reciept.php'; // Go to print receipt
      } else {
        window.location.href = 'thank_you.php'; // Default redirect
      }
    }
  </script>
</body>

</html>