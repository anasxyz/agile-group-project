
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Take Your Cash and Receipt</title>
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
    <div class="option" onclick="perform_another_transaction()">
      <span style="padding-bottom: 20px;">Collect Your Cash and Receipt</span>
      <img src="https://img.icons8.com/?size=100&id=2FhXTN29SoMh&format=png&color=000000" alt="">
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

  <script src = "modal.js">
    
  </script>
</body>
</html>
