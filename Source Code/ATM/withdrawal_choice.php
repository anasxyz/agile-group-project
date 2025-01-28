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
    const accountType = "<?php echo $accountType; ?>";

    function handleOption(option) {
      if (option === '10' || option === '20' || option === '50') {
        sendWithdrawalData(option);
      } else if (option === 'Other Amount') {
        window.location.href = 'custom_amount.php';
      }
    }

    function sendWithdrawalData(withdrawal_amount) {
      const transaction_data = {
        'card_number': '1234123412341234',
        'expiry_date': '12/25',
        'atm_id': 'ATM001',
        'transaction_id': 'txn_random',
        'pin': '1234',
        'transaction_type': 'withdrawal',
        'withdrawal_amount': withdrawal_amount
      };

      fetch('http://localhost/test/transaction_switch.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(transaction_data).toString()
      })
      .then(response => response.json())
      .then(data => {
        console.log(data);
        if (data.status === 'Approved') {
          showReceiptModal();
        } else {
          alert('Transaction Failed: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('There was an error processing your request.');
      });
    }

    function showReceiptModal() {
      const modal = document.getElementById('receiptModal');
      modal.style.display = 'block';
    }

    function redirectToReceiptPage(wantsReceipt) {
      const modal = document.getElementById('receiptModal');
      modal.style.display = 'none';

      // Show the loading modal
      const loadingModal = document.getElementById('loadingModal');
      loadingModal.style.display = 'block';

      // Redirect after 2 seconds
      setTimeout(() => {
        loadingModal.style.display = 'none';
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
