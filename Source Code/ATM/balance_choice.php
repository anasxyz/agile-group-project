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
  <title>Balance Choice</title>
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
    <h1>How would you like your Balance?</h1>

    <div class="option" onclick="handleOption('view')">
      <span>View Balance</span>
    </div>

    <div class="option" onclick="handleOption('print')">
      <span>Print Balance</span>
    </div>
  </div>

  <!-- Modal -->
  <div id="loadingModal" class="modal">
    <div class="modal-content">
      <div class="spinner"></div>
      <p>Processing your request...</p>
    </div>
  </div>

  <script>
    const accountType = "<?php echo $accountType; ?>";
    const cardNumber = "<?php echo $_SESSION['card_number']; ?>";
    const expiry = "<?php echo $_SESSION['expiry']; ?>";
    const pin = "<?php echo $_SESSION['pin']; ?>";

    function handleOption(option) {
      // Show the loading modal
      const modal = document.getElementById('loadingModal');
      modal.style.display = 'block';
      
      if (option === 'view') {
        sendTransactionData(cardNumber, expiry, pin, 'balance inquiry');
      } else if (option === 'print') {
        window.location.href = 'print_receipt.php';
      }
    }

    function sendTransactionData(card_number, expiry_date, pin, transaction_type) {
      const transaction_data = {
        'card_number': card_number,
        'expiry_date': expiry_date,
        'atm_id': 'ATM001',
        'transaction_id': 'txn_' + Math.random().toString(36).substr(2, 9),
        'pin': pin,
        'transaction_type': transaction_type
      };

      function checkStatus() {
        fetch('http://localhost/../Transaction%20Switch/transaction_switch.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams(transaction_data).toString()
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'Approved') {
            setTimeout(5000);
            window.location.href = `view_balance.php?account_type=${encodeURIComponent(accountType)}&balance=${encodeURIComponent(data.balance)}`;
          } else {
            setTimeout(checkStatus, 5000); // Retry every 5 seconds
          }
        })
        .catch(error => {
          console.error('Error:', error);
          setTimeout(checkStatus, 5000); // Retry on failure
        });
      }

      checkStatus(); // Start polling
    }
  </script>
</body>
</html>
