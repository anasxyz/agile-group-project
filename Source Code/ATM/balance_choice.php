<?php
session_start(); // Start session if needed

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

  <!-- Loading Modal -->
  <div id="loadingModal" class="modal">
    <div class="modal-content">
      <div class="spinner"></div>
      <p>Processing your request...</p>
    </div>
  </div>

  <!-- Error/Success Modal -->
  <div id="customModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2 id="modalTitle">Default Title</h2>
      <p id="modalMessage">Default message goes here.</p>
      <div class="modal-footer">
        <button id="button1" class="modal-button" onclick="">Close</button>
      </div>
    </div>
  </div>

  <script>
    const accountType = "<?php echo $accountType; ?>";
    const cardNumber = "<?php echo $_SESSION['card_number']; ?>";
    const expiry = "<?php echo $_SESSION['expiry']; ?>";
    const pin = "<?php echo $_SESSION['pin']; ?>";

    let transactionCheckInterval;

    function handleOption(option) {
      document.getElementById('loadingModal').style.display = 'block';

      setTimeout(() => {
        document.getElementById('loadingModal').style.display = 'none';

        if (option === 'view') {
          sendTransactionData(cardNumber, expiry, pin, 'balance inquiry');
        } else if (option === 'print') {
          sendTransactionData(cardNumber, expiry, pin, 'print balance');
        }
      }, 2000);
    }

    function sendTransactionData(card_number, expiry_date, pin, transaction_type) {
      const transaction_id = 'txn_' + Math.random().toString(36).substr(2, 9);

      const transaction_data = {
          'card_number': card_number,
          'expiry_date': expiry_date,
          'atm_id': 'ATM001',
          'transaction_id': transaction_id,
          'pin': pin,
          'transaction_type': transaction_type
      };

      fetch('http://localhost/../Transaction%20Switch/transaction_switch.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams(transaction_data).toString()
      })
      .then(response => response.json())
      .then(data => {
          console.log(data);

          if (data.status === 'Pending') {
              window.currentTransactionId = transaction_id;
              transactionCheckInterval = setInterval(() => checkTransactionStatus(window.currentTransactionId, transaction_type), 3000);
          } else if (data.status === 'Approved' && transaction_type === 'balance inquiry') {
              window.location.href = `view_balance.php?account_type=${encodeURIComponent(accountType)}&balance=${encodeURIComponent(data.balance)}`;
          } else if (data.status === 'Approved' && transaction_type === 'print balance') {
              window.location.href = 'print_receipt.php';
          } else {
              showModal('Transaction Declined', data.message, 'Close', 'closeModal()');
          }
      })
      .catch(error => {
          console.error('Error:', error);
          showModal('Error', 'There was an error processing your request. Please try again later.', 'Close', 'closeModal()');
      });
    }

    function checkTransactionStatus(transaction_id, transaction_type) {
        fetch(`http://localhost/../Transaction%20Switch/check_transaction_status.php?transaction_id=${transaction_id}`)
        .then(response => response.json())
        .then(data => {
            console.log("Checking Status:", data);

            if (data.status !== 'Pending') {
                clearInterval(transactionCheckInterval);

                if (data.status === 'Approved' && transaction_type === 'balance inquiry') {
                    window.location.href = `view_balance.php?account_type=${encodeURIComponent(accountType)}&balance=${encodeURIComponent(data.balance)}`;
                } else if (data.status === 'Approved' && transaction_type === 'print balance') {
                    window.location.href = 'print_receipt.php';
                } else {
                    showModal('Transaction Declined', data.message, 'Close', 'redirectCardOut()');
                }
            }
        })
        .catch(error => {
            console.error('Error checking transaction status:', error);
            showModal('Error', 'There was an error checking your transaction status. Please try again later.', 'Close', 'redirectCardOut()');
        });
    }

    function showModal(title, message, button1Text, button1Action) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalMessage').textContent = message;
        document.getElementById('button1').textContent = button1Text;

        document.getElementById('button1').setAttribute('onclick', button1Action);

        document.getElementById('customModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('customModal').style.display = 'none';
    }

    function redirectCardOut() {
      window.location.href = 'take_card_out.php';
    }
  </script>
</body>
</html>
