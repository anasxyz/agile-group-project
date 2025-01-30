<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Withdrawal Amount</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .cash-screen {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 400px;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 26px;
            font-weight: bold;
            color: #333;
        }

        .cash-value {
            width: 70%;
            padding: 15px;
            font-size: 32px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 35px;
            background-color: #f0f0f0;
            color: #333;
            margin-bottom: 20px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .number-pad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            width: 300px;
        }

        .number-key {
            height: 60px;
            font-size: 24px;
            border: 1px solid #ddd;
            border-radius: 30px;
            background-color: #f8f8f8;
            cursor: pointer;
            text-align: center;
            line-height: 60px;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .number-key:hover {
            background-color: #e0e0e0;
            transform: translateY(-2px);
        }

        .number-key--clear {
            background-color: #f44336;
            color: white;
            font-weight: bold;
        }

        .number-key--clear:hover {
            background-color: #d32f2f;
        }

        .number-key--enter {
            background-color: #4caf50;
            color: white;
            font-weight: bold;
        }

        .number-key--enter:hover {
            background-color: #388e3c;
        }

        .number-key--zero {
            grid-column: span 3;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 15px;
        }

        .button-container button {
            width: 45%;
            height: 100px;
            padding: 10px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid #ddd;
            transition: all 0.2s ease-in-out;
        }

        .exit-button {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .exit-button:hover {
            background-color: #f0f0f0;
            transform: scale(1.02);
            color: #333
        }

        .menu-button {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .menu-button:hover {
            background-color: #f0f0f0;
            transform: scale(1.02);
            color: #333
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
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            text-align: center;
            border-radius: 30px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-footer {
            margin-top: 20px;
        }

        .modal-button {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin: 0 10px;
            border-radius: 30px;
        }

        .modal-button:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="cash-screen">
            <h1>Enter Custom Amount</h1>
            <input type="text" class="cash-value" id="cashInput" readonly>
            <div class="number-pad">
                <button class="number-key" onclick="addNumber(1)">1</button>
                <button class="number-key" onclick="addNumber(2)">2</button>
                <button class="number-key" onclick="addNumber(3)">3</button>
                <button class="number-key" onclick="addNumber(4)">4</button>
                <button class="number-key" onclick="addNumber(5)">5</button>
                <button class="number-key" onclick="addNumber(6)">6</button>
                <button class="number-key" onclick="addNumber(7)">7</button>
                <button class="number-key" onclick="addNumber(8)">8</button>
                <button class="number-key" onclick="addNumber(9)">9</button>
                <button class="number-key number-key--clear" onclick="clearInput()">Clear</button>
                <button class="number-key" onclick="addNumber(0)">0</button>
                <button class="number-key number-key--enter" onclick="submitAmount()">Enter</button>
            </div>

            <div class="button-container">
                <button class="exit-button" onclick="transaction_cancelled()">Exit</button>
                <button class="menu-button" onclick="goToMainMenu()">Main Menu</button>
            </div>
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

    <script>
        let cashInput = document.getElementById('cashInput');

        function addNumber(num) {
            if (cashInput.value.length < 9) { // Limit to 9 characters
                cashInput.value += num;
            }
        }

        function clearInput() {
            cashInput.value = "";
        }

        function submitAmount() {
            let amount = cashInput.value.trim();
            if (amount === "") {
                showModal('Please Enter Amount', 'Amount cannot be empty', 'Okay', '', 'closeModal()', '');
                return;
            }
            window.location.href = `do_you_want_receipt.php?amount=${encodeURIComponent(amount)}`;
        }

        function goToMainMenu() {
            window.locaiton.href = "txn_types.php";
        }

        function transaction_cancelled() {
            showModal("Transaction Cancelled!", "Your transaction has been cancelled.", "Okay", "", "take_out_card()", "")
        }

        function take_out_card() {
            window.location.href = 'take_card_out.php'
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
    </script>
</body>
</html>
