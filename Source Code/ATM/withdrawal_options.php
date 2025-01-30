<?php
session_start(); // Ensure session is started to use session variables
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Withdrawal Options</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Withdrawal Options</h1>
	<h3>Select an Account</h3>

    <div class="option" onclick="redirectToWithdrawalChoice('checking')">
      <span>Checking</span>
    </div>

    <div class="option" onclick="redirectToWithdrawalChoice('savings')">
      <span>Savings</span>
    </div>
   
    <div class="option" onclick="redirectToWithdrawalChoice('credit')">
      <span>Credit</span>
    </div>
  
    <div class="option" onclick="redirectToWithdrawalChoice('loan')">
      <span>Loan</span>
    </div>

    <div class="option" onclick="transaction_cancelled()">
      <span>Exit</span>
    </div>

    <div class="option" onclick="backTo('txn_types')">
      <span>Main Menu</span>
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

  <script>
    function backTo(page) {
      window.location.href = page.endsWith(".php") ? page : page + ".php";
    }

    function take_out_card() {
      window.location.href = 'take_card_out.php'
    }

    function transaction_cancelled() {
        showModal("Transaction Cancelled!", "Your transaction has been cancelled.", "Okay", "", "redirectCardOut()", "")
    }
  </script>
</body>
</html>
