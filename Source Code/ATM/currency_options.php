<?php
session_start(); // Ensure session is started to use session variables

$language = $_SESSION['language'] ?? 'en';
$lang = include "../languages/{$language}.php";
?>

<!DOCTYPE html>
<html lang="<?= $language ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Currency Options</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <div class="container">
    <h1><?= $lang['currency_choice'] ?></h1>
    <h3><?= $lang['select_currency'] ?></h3>

    <div class="option" onclick="setCurrencyType('£')">
      <span>£</span>
    </div>

    <div class="option" onclick="setCurrencyType('$')">
      <span>$</span>
    </div>

    <div class="option" onclick="setCurrencyType('€')">
      <span>€</span>
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

    function transaction_cancelled() {
      showModal("Transaction Cancelled!", "Your transaction has been cancelled.", "Okay", "", "redirectCardOut()", "")
    }

    function setCurrencyType(currencyType) {
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "set_currency.php?currency=" + currencyType, true);
      xhr.send();

      xhr.onload = function() {
        if (xhr.status === 200) {
          if (document.referrer) {
            window.location.href = document.referrer;
          } else {
            window.location.href = "insert_card.php";
          }
        } else {
          alert("Error: Could not set the currency.");
        }
      };
    }
  </script>
</body>

</html>