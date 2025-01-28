<?php
session_start(); // Ensure session is started to use session variables

if (!isset($_SESSION['currencyType'])) {
  $_SESSION['currencyType'] = '£';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Currency Options</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Currency Choice</h1>
	<h3>Select a Currency</h3>

    <div class="option" onclick="setCurrencyType('£')">
      <span>£</span>
    </div>

    <div class="option" onclick="setCurrencyType('$')">
      <span>$</span>
    </div>
   
    <div class="option" onclick="setCurrencyType('€')">
      <span>€</span>
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
</body>
</html>