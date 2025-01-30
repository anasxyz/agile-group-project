<?php
session_start();

$language = $_SESSION['language'] ?? 'en';
$lang = include "../languages/{$language}.php";
?>

<!DOCTYPE html>
<html lang="<?= $language ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $lang['welcome'] ?></title>
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
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      width: 600px;
      text-align: center;
    }

    h1 {
      font-size: 26px;
      font-weight: bold;
      color: #333;
      text-align: center;
      grid-column: span 2;
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

    .exit-btn {
      padding: 10px 20px;
      font-size: 1.2em;
      background-color: rgb(243, 187, 183);
      color: white;
      border: none;
      cursor: pointer;
    }

    .exit-btn:hover {
      background-color: rgb(250, 157, 157);
    }
  </style>
</head>

<body>
  <div class="container">
    <h1><?= $lang['select_transaction'] ?></h1>

    <!-- Balance Inquiry -->
    <form method="POST" action="balance_options.php">
      <div class="option" onclick="this.closest('form').submit();">
        <img src="https://img.icons8.com/ios/50/sales-performance-balance.png" alt="Balance Inquiry">
        <span><?= $lang['balance_inquiry'] ?></span>
      </div>
    </form>

    <!-- Cash Withdrawal -->
    <form method="POST" action="withdrawal_options.php">
      <div class="option" onclick="this.closest('form').submit();">
        <img src="https://img.icons8.com/pulsar-line/100/withdrawal-limit.png" alt="Cash Withdrawal">
        <span><?= $lang['cash_withdrawal'] ?></span>
      </div>
    </form>

    <!-- Deposits (Inactive) -->
    <div class="option inactive">
      <img src="https://img.icons8.com/ios-filled/50/deposit.png" alt="Deposits">
      <span><?= $lang['deposits'] ?></span>
    </div>

    <!-- Payments (Inactive) -->
    <div class="option inactive">
      <img src="https://img.icons8.com/ios/50/cash-in-hand.png" alt="Payments">
      <span><?= $lang['payments'] ?></span>
    </div>

    <!-- Mixed Deposits (Inactive) -->
    <div class="option inactive">
      <img src="https://img.icons8.com/pulsar-line/100/cash.png" alt="Mixed Deposits">
      <span><?= $lang['mixed_deposits'] ?></span>
    </div>

    <!-- Cash a Check (Inactive) -->
    <div class="option inactive">
      <img src="https://img.icons8.com/pulsar-line/100/check.png" alt="Cash a Check">
      <span><?= $lang['cash_a_check'] ?></span>
    </div>

    <!-- Check Deposit With CashBack (Inactive) -->
    <div class="option inactive">
      <img src="https://img.icons8.com/wired/64/cash-in-hand.png" alt="Check Deposit With CashBack">
      <span><?= $lang['check_deposit_cashback'] ?></span>
    </div>

    <div class="option exit-btn" onclick="window.location.href='language_choice.php'">
      <img src="https://img.icons8.com/ios-filled/50/logout-rounded.png" alt="Exit">
      <span><?= $lang['exit'] ?></span>
    </div>
  </div>
</body>

</html>