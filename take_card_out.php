<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Take Your Card</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f5f5f5;
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 20px 30px;
      border-radius: 10px;
    }

    h1 {
      font-size: 26px;
      font-weight: bold;
      color: #333;
      margin-bottom: 30px;
    }

    .option {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
      height: 150px;
      width: 250px;
    }

    .option:hover {
      background-color: #f0f0f0;
      transform: scale(1.02);
    }

    .option span {
      font-size: 1.2em;
      color: #333;
    }

    /* Inactive Button Styling */
    .option.inactive {
      background-color: #b0b0b0;
      color: #7a7a7a;
      cursor: not-allowed;
      box-shadow: none;
    }

    .option.inactive:hover {
      background-color: #b0b0b0;
      transform: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="option" onclick="take_card_out()">
      <span style="padding-bottom: 20px;">Take Your Card</span>
      <img src="https://img.icons8.com/ios/100/bank-card-back-side--v1.png" alt="">
    </div>

    <script>
        function take_card_out() {
            window.location.href = 'thank_you.php'
        }
    </script>
</body>
</html>
