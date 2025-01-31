<?php
session_start();

// Retrieve actual card details from session
$cardNumber = isset($_SESSION['card_number']) ? $_SESSION['card_number'] : null;
$pin = isset($_SESSION['pin']) ? $_SESSION['pin'] : null;
$expiryDate = isset($_SESSION['expiry_date']) ? $_SESSION['expiry_date'] : null;
$atmId = isset($_SESSION['atm_id']) ? $_SESSION['atm_id'] : 'ATM001'; // Default ATM ID
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

    .modal-footer {
      display: flex;
      justify-content: space-around;
      margin-top: 20px;
    }

    .modal-button {
      padding: 10px 20px;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .modal-button.yes {
      background-color: #4caf50;
      color: white;
    }

    .modal-button.no {
      background-color: #f44336;
      color: white;
    }

    /* Loading Spinner */
    .spinner {
      border: 6px solid #f3f3f3;
      border-top: 6px solid #333;
      border-radius: 50%;
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

    <div class="option" onclick="handleOption(10)">
      <span>$10</span>
    </div>

    <div class="option" onclick="handleOption(20)">
      <span>$20</span>
    </div>

    <div class="option" onclick="handleOption(50)">
      <span>$50</span>
    </div>

    <div class="option" onclick="handleOption('Other')">
      <span>Other Amount</span>
    </div>
  </div>

  <!-- Receipt Confirmation Modal -->
  <div id="receiptModal" class="modal">
    <div class="modal-content">
      <h2>Do you want a receipt?</h2>
      <div class="modal-footer">
        <button class="modal-button yes" onclick="redirectToReceiptPage(true)">Yes</button>
        <button class="modal-button no" onclick="redirectToReceiptPage(false)">No</button>
      </div>
    </div>
  </div>

  <!-- Loading Modal -->
  <div id="loadingModal" class="modal">
    <div class="modal-content">
      <div class="spinner"></div>
      <p>Processing your request...</p>
    </div>
  </div>

  <script>
    let transactionCheckInterval;
    let currentTransactionId;

    function handleOption(option) {
      if (option === 'Other') {
        window.location.href = 'custom_amount.php';
      } else {
        sendWithdrawalData(option);
      }
    }

    function sendWithdrawalData(amount) {
      document.getElementById('loadingModal').style.display = 'block';

      currentTransactionId = 'txn_' + Date.now();
      const transactionData = {
        'card_number': "<?php echo $cardNumber; ?>",
        'pin': "<?php echo $pin; ?>",
        'expiry_date': "<?php echo $expiryDate; ?>",
        'atm_id': "<?php echo $atmId; ?>",
        'transaction_id': currentTransactionId,
        'transaction_type': 'withdrawal',
        'withdrawal_amount': amount
      };

      fetch('http://localhost/../Transaction%20Switch/transaction_switch.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(transactionData).toString()
      })
      .then(response => response.json())
      .then(data => {
        console.log(data);

        if (data.status === 'Pending') {
          transactionCheckInterval = setInterval(() => checkTransactionStatus(currentTransactionId), 3000);
        } else if (data.status === 'Approved') {
          showReceiptModal();
        } else {
          alert('Transaction Failed: ' + data.message);
          document.getElementById('loadingModal').style.display = 'none';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('There was an error processing your request.');
        document.getElementById('loadingModal').style.display = 'none';
      });
    }

    function checkTransactionStatus(transactionId) {
      fetch(`http://localhost/../Transaction%20Switch/check_transaction_status.php?transaction_id=${transactionId}`)
      .then(response => response.json())
      .then(data => {
        console.log("Checking Status:", data);

        if (data.status !== 'Pending') {
          clearInterval(transactionCheckInterval);

          if (data.status === 'Approved') {
            showReceiptModal();
          } else {
            alert('Transaction Declined: ' + data.message);
            document.getElementById('loadingModal').style.display = 'none';
          }
        }
      })
      .catch(error => {
        console.error('Error checking transaction status:', error);
      });
    }

    function showReceiptModal() {
      document.getElementById('loadingModal').style.display = 'none';
      document.getElementById('receiptModal').style.display = 'block';
    }

    function redirectToReceiptPage(wantsReceipt) {
      document.getElementById('receiptModal').style.display = 'none';
      document.getElementById('loadingModal').style.display = 'block';

      setTimeout(() => {
        document.getElementById('loadingModal').style.display = 'none';
        if (wantsReceipt) {
          window.location.href = 'collect_cash_receipt.php';
        } else {
          window.location.href = 'collect_cash.php';
        }
      }, 2000);
    }
  </script>
</body>
</html>
