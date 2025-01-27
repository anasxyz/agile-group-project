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

  <!-- Modal -->
  <div id="loadingModal" class="modal">
    <div class="modal-content">
      <div class="spinner"></div>
      <p>Processing your request...</p>
    </div>
  </div>

  <!-- Modal 2 -->
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
    const accountType = "<?php echo $accountType; ?>";

    function handleOption(option) {
      // Show the loading modal
      const modal = document.getElementById('loadingModal');
      modal.style.display = 'block';

      // Simulate a delay of 5 seconds
      setTimeout(() => {
        modal.style.display = 'none';

        // Redirect to the appropriate page
        if (option === '10') {
            sendWithdrawalData(10);
        } 
        else if (option === '20') {
            sendWithdrawalData(20);
        } 
        else if (option === '50') {
            sendWithdrawalData(50);
        } 
        else if (option === 'Other Amount') {
            window.location.href = 'custom_amount.php';
        } 
        else{
          window.location.href = 'print_receipt.php';
        }
      }, 2000); // 5000ms = 5 seconds
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

      fetch('http://localhost/../Transaction Switch/transaction_switch.php', {
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
            const url = `take_card_out.php`;
            window.location.href = url;
        } else {
          showModal('Transaction Failed', data.message, 'Close', 'Take Card Out', 'closeModal()', 'take_out_card()');
          
        }
      })
      .catch(error => {
        console.error('Error:', error);
        console.log(data);
        showModal('Error', 'There was an error processing your request.', 'Close', 'Take Card Out', 'closeModal()', 'take_out_card()');
      });
    }

    function showModal(title, message, button1Text, button2Text, button1Action, button2Action) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalMessage').textContent = message;
        document.getElementById('button1').textContent = button1Text;
        document.getElementById('button2').textContent = button2Text;

        document.getElementById('button1').setAttribute('onclick', button1Action);
        document.getElementById('button2').setAttribute('onclick', button2Action);

        document.getElementById('customModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('customModal').style.display = 'none';
    }

    function take_out_card() {
      window.location.href = 'take_card_out.php'
    }
  </script>
</body>
    
</html>
