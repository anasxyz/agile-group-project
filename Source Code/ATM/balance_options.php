<?php
session_start(); // Ensure session is started to use session variables


$_SESSION['language'] = 'es';
$language = $_SESSION['language'] ?? 'en';

$lang = include "../languages/{$language}.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Balance Options</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <div class="container">
    <h1><?= $lang['balance_inquiry'] ?></h1>
    <h3><?= $lang['select_account'] ?></h3>

    <div class="option" onclick="redirectToBalanceChoice('checking')">
      <span><?= $lang['checking'] ?></span>
    </div>

    <div class="option" onclick="redirectToBalanceChoice('savings')">
      <span><?= $lang['savings'] ?></span>
    </div>

    <div class="option" onclick="redirectToBalanceChoice('credit')">
      <span><?= $lang['credit'] ?></span>
    </div>

    <div class="option" onclick="redirectToBalanceChoice('loan')">
      <span><?= $lang['loan'] ?></span>
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