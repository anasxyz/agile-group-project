<?php
session_start();


$language = $_SESSION['language'] ?? 'en';
$lang = include "../languages/{$language}.php";


// Clear all session values related to card information
unset($_SESSION['card_number']);
unset($_SESSION['expiry']);
unset($_SESSION['pin']);
?>

<!DOCTYPE html>
<html lang="<?= $language ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Card</title>
    <style>
        @import url('https://fonts.cdnfonts.com/css/credit-card');

        /* Body Styling */
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

        /* Main container with flex to align items horizontally */
        .main-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 1200px;
        }

        /* Credit Card Styling */
        .card-container {
            position: relative;
            width: 400px;
            height: 250px;
            /* background: linear-gradient(135deg,rgb(82, 82, 82),rgb(24, 24, 24)); */
            color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            box-sizing: border-box;
            transition: transform 1s ease-in-out;
            background-image: url("map.png");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        .card-container .chip {
            width: 60px;
            height: 45px;
            background-color: transparent;
            border-radius: 10px;
            margin-top: 25px;
            margin-left: 10px;
            background-image: url("chip2.png");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        /* .card-container .card-number {
            font-family: 'Credit Card', sans-serif;
            font-size: 1rem;
            letter-spacing: 3px;
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
            padding: 10px;
        } */

        .card-container .card-number {
            font-family: 'Credit Card', monospace;
            font-size: 1rem;
            letter-spacing: 3px;
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
            padding: 10px;
            color: rgb(160, 160, 160);
            text-shadow: 2px 3px 3px rgba(0, 0, 0, 0.5);
        }

        .date {
            font-family: 'Credit Card', monospace;
            font-size: 10px;
            letter-spacing: 3px;
            margin: 20px 0;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            padding: 10px;
            color: rgb(160, 160, 160);
            text-shadow: 2px 3px 3px rgba(0, 0, 0, 0.5);
        }

        .card-number span {
            cursor: text;
            border-bottom: 1px dashed transparent;
        }

        .card-number span:focus {
            border-bottom: 1px dashed #fff;
            outline: none;
        }

        .card-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
        }

        .editable {
            border-bottom: 1px dashed transparent;
            outline: none;
            cursor: text;
        }

        .editable:focus {
            border-bottom: 1px dashed #fff;
        }

        .insert-btn {
            margin-top: 20px;
            background: #22c55e;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .insert-btn:hover {
            background: #16a34a;
        }

        /* PIN Entry Section Styling */
        .pin-screen {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 600px;
            height: 500px;
            padding: 20px;
            border-radius: 15px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 26px;
            font-weight: bold;
            color: #333;
        }

        .pin-value {
            width: 47%;
            padding: 10px;
            font-size: 32px;
            text-align: center;
            letter-spacing: 6px;
            border: 2px solid #ddd;
            border-radius: 30px;
            margin-bottom: 20px;
            background-color: #f0f0f0;
            color: #333;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .pin-keyboard {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            width: 300px;
        }

        .pin-keyboard-key {
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

        .pin-keyboard-key:hover {
            background-color: #e0e0e0;
            transform: translateY(-2px);
        }

        .pin-keyboard-key--clear {
            background-color: #f44336;
            color: white;
            font-weight: bold;
        }

        .pin-keyboard-key--clear:hover {
            background-color: #d32f2f;
        }

        .pin-keyboard-key--enter {
            background-color: #4caf50;
            color: white;
            font-weight: bold;
        }

        .pin-keyboard-key--enter:hover {
            background-color: #388e3c;
        }

        /* Logo Styling */
        .card-logo {
            position: absolute;
            bottom: 20px;
            right: 20px;
            height: 40px;
            width: auto;
        }

        .contactless-logo {
            position: absolute;
            top: 25px;
            right: 25px;
            width: 30px;
            height: 30px;
            background-image: url('contactless-logo.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
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
        <!-- Credit Card Design Section -->
        <div class="card-container" id="card-container">
            <div class="contactless-logo">
            </div>
            <div class="chip"></div>
            <div class="card-number">
                <span contenteditable="true" class="editable" id="cardNumber1">1111</span>
                <span contenteditable="true" class="editable" id="cardNumber2">1111</span>
                <span contenteditable="true" class="editable" id="cardNumber3">1111</span>
                <span contenteditable="true" class="editable" id="cardNumber4">1111</span>
            </div>
            <div class="card-details">
                <div style="display: flex; align-items: center; gap: 5px;">
                    <div style="display: flex; flex-direction: column; align-items: flex-start; margin-bottom: 20px; margin-left: 10px;">
                        <label for="expiry" style="margin: 0;">VALID</label>
                        <label for="expiry" style="margin: 0;">THRU</label>
                    </div>
                    <div contenteditable="true" id="expiry" class="editable date" style="margin-top: 10px;">12/25</div>
                </div>
            </div>



            <img src="" alt="Card Logo" class="card-logo" id="cardLogo">
        </div>

        <!-- PIN Entry Section -->
        <div class="pin-screen">
            <h1><?= $lang['enter_pin'] ?></h1>
            <input type="password" class="pin-value" readonly>
            <div class="pin-keyboard">
                <button class="pin-keyboard-key">1</button>
                <button class="pin-keyboard-key">2</button>
                <button class="pin-keyboard-key">3</button>
                <button class="pin-keyboard-key">4</button>
                <button class="pin-keyboard-key">5</button>
                <button class="pin-keyboard-key">6</button>
                <button class="pin-keyboard-key">7</button>
                <button class="pin-keyboard-key">8</button>
                <button class="pin-keyboard-key">9</button>
                <button class="pin-keyboard-key pin-keyboard-key--clear"><?= $lang['exit'] ?></button>
                <button class="pin-keyboard-key">0</button>
                <button class="pin-keyboard-key pin-keyboard-key--enter"><?= $lang['enter'] ?></button>
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
        document.addEventListener("DOMContentLoaded", () => {
            const input = document.querySelector(".pin-value");
            const keys = document.querySelectorAll(".pin-keyboard-key");
            const cardNumber1 = document.getElementById("cardNumber1");
            const cardLogo = document.getElementById("cardLogo");

            const cardLogos = {
                "4": "visa-logo-2.png",
                "5": "mastercard-logo.png",
                "3": "amex-logo.png",
                "6": "discover-logo.png"
            };

            const updateCardLogo = () => {
                const firstDigit = cardNumber1.innerText.trim()[0];
                if (cardLogos[firstDigit]) {
                    cardLogo.src = cardLogos[firstDigit];
                    cardLogo.style.display = "block";
                } else {
                    cardLogo.style.display = "none";
                }
            };

            cardNumber1.addEventListener("input", updateCardLogo);

            keys.forEach(key => {
                key.addEventListener("click", () => {
                    const value = key.textContent.trim();

                    if (key.classList.contains("pin-keyboard-key--clear")) {
                        transaction_cancelled();
                    } else if (key.classList.contains("pin-keyboard-key--enter")) {
                        if (input.value.length === 4) {
                            const cardNumber = `${document.getElementById("cardNumber1").innerText}${document.getElementById("cardNumber2").innerText}${document.getElementById("cardNumber3").innerText}${document.getElementById("cardNumber4").innerText}`;
                            const expiry = document.getElementById("expiry").innerText;
                            const pin = input.value;

                            sendTransactionData(cardNumber, expiry, pin, 'authorisation');

                            input.value = ""; // Clear PIN after submission
                        } else {
                            alert("Please enter a 4-digit PIN.");
                        }
                    } else {
                        if (input.value.length < 4) {
                            input.value += value;
                        }
                    }
                });
            });

            updateCardLogo(); // Initialize logo on page load
        });

        function sendTransactionData(card_number, expiry_date, pin, transaction_type) {
            const transaction_data = {
                'card_number': card_number,
                'expiry_date': expiry_date,
                'atm_id': 'ATM001',
                'transaction_id': 'txn_' + Math.random().toString(36).substr(2, 9),
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
                    if (data.status === 'Approved') {
                        const url = `save_card_data.php?card_number=${encodeURIComponent(card_number)}&expiry=${encodeURIComponent(expiry_date)}&pin=${encodeURIComponent(pin)}`;
                        window.location.href = url;
                    } else {
                        showModal('Declined', data.message, 'Close', 'Take Card Out', 'closeModal()', 'redirectCardOut()');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showModal('Error', 'There was an error processing your request.', 'Close', '', 'closeModal()', '');
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


        function redirectCardOut() {
            window.location.href = 'take_card_out.php';
        }

        function transaction_cancelled() {
            showModal("Transaction Cancelled", 'Your transaction has been cancelled', "Okay", "", "redirectCardOut()", "");
        }
    </script>
</body>

</html>