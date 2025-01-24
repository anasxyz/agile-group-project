<!-- popup.php -->
<?php
// Ensure that you pass the variables $title, $message, $button1_text, $button2_text, $button1_link, and $button2_link
// to customize the popup content and button redirections.

$title = isset($title) ? $title : 'Popup Title';
$message = isset($message) ? $message : 'Popup message goes here.';
$button1_text = isset($button1_text) ? $button1_text : 'Button 1';
$button2_text = isset($button2_text) ? $button2_text : 'Button 2';
$button1_link = isset($button1_link) ? $button1_link : '#';
$button2_link = isset($button2_link) ? $button2_link : '#';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Custom Popup</title>
  <style>
    /* Popup background */
    .popup-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    /* Popup container */
    .popup-container {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      width: 300px;
      text-align: center;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
    }

    /* Popup Title */
    .popup-container h2 {
      margin: 0;
      font-size: 24px;
      color: #333;
    }

    /* Popup message */
    .popup-container p {
      font-size: 16px;
      color: #555;
    }

    /* Button container */
    .popup-buttons {
      margin-top: 20px;
    }

    /* Buttons styling */
    .popup-buttons a {
      text-decoration: none;
      padding: 10px 20px;
      margin: 0 10px;
      border-radius: 5px;
      font-size: 16px;
      color: white;
      background-color: #4CAF50;
      transition: background-color 0.3s;
    }

    .popup-buttons a:hover {
      background-color: #45a049;
    }

    /* Button 2 (custom color) */
    .popup-buttons .button2 {
      background-color: #f44336;
    }

    .popup-buttons .button2:hover {
      background-color: #e53935;
    }
  </style>
</head>
<body>

<!-- Popup overlay and container -->
<div class="popup-overlay" id="popupOverlay">
  <div class="popup-container">
    <h2 id="popupTitle"><?php echo $title; ?></h2>
    <p id="popupMessage"><?php echo $message; ?></p>
    <div class="popup-buttons">
      <a href="<?php echo $button1_link; ?>" id="popupButton1"><?php echo $button1_text; ?></a>
      <a href="<?php echo $button2_link; ?>" id="popupButton2" class="button2"><?php echo $button2_text; ?></a>
    </div>
  </div>
</div>

<script>
  // Function to show the popup
  function showPopup() {
    document.getElementById('popupOverlay').style.display = 'flex';
  }

  // Function to close the popup
  function closePopup() {
    document.getElementById('popupOverlay').style.display = 'none';
  }

  // Automatically show the popup (can be called in any file using include)
  window.onload = function() {
    showPopup();
  };
</script>

</body>
</html>
