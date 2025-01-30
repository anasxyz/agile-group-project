<?php
session_start(); // If session data is needed

// Retrieve the account type from the URL
$accountType = isset($_GET['account_type']) ? htmlspecialchars($_GET['account_type']) : 'Unknown';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal Choice</title>
    <link rel="stylesheet" href="styles.css">

    <style>
    /* Modal Styles */
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
      width: 300px;
      border-radius: 25px;
      text-align: center;
      position: relative;
    }

    .spinner {
      border: 6px solid #f3f3f3;
      border-radius: 50%;
      border-top: 6px solid #333;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
      margin: 20px auto;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Fast Cash</h1>

    <div class="option" onclick="handleOption('10')">
      <span>$10</span>
    </div>

    <div class="option" onclick="handleOption('20')">
      <span>$20</span>
    </div>

    <div class="option" onclick="handleOption('50')">
      <span>$50</span>
    </div>

    <div class="option" onclick="handleOption('Other Amount')">
      <span>Other Amount</span>
    </div>
  </div>

  <script>
    const accountType = "<?php echo $accountType; ?>";
    const cardNumber = "<?php echo $_SESSION['card_number']; ?>";
    const expiry = "<?php echo $_SESSION['expiry']; ?>";
    const pin = "<?php echo $_SESSION['pin']; ?>";

    function handleOption(option) {
      // Redirect to the appropriate page
      if (option === '10') {
          window.location.href = `do_you_want_receipt.php?amount=${encodeURIComponent('10')}`;
        } 
        else if (option === '20') {
          window.location.href = `do_you_want_receipt.php?amount=${encodeURIComponent('20')}`;
        } 
        else if (option === '50') {
          window.location.href = `do_you_want_receipt.php?amount=${encodeURIComponent('50')}`;
        } 
        else if (option === 'Other Amount') {
            window.location.href = 'custom_amount.php';
        } 
        else{
          window.location.href = 'print_receipt.php';
        }
    }

    function take_out_card() {
      window.location.href = 'take_card_out.php'
    }
  </script>
</body>
    
</html>
