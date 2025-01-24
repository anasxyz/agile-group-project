<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card and PIN Entry</title>
    <style>
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
            background: linear-gradient(135deg, #3b82f6, #1e3a8a);
            color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            box-sizing: border-box;
            transition: transform 1s ease-in-out;
        }

        .card-container .chip {
            width: 50px;
            height: 35px;
            background-color: #fcd34d;
            border-radius: 5px;
        }

        .card-container .card-number {
            font-size: 1.5rem;
            letter-spacing: 3px;
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
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
            margin-top: 10px;
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
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Credit Card Design Section -->
        <div class="card-container" id="card-container">
            <div class="chip"></div>
            <div class="card-number">
                <span contenteditable="true" class="editable" id="cardNumber1">1234</span>
                <span contenteditable="true" class="editable" id="cardNumber2">5678</span>
                <span contenteditable="true" class="editable" id="cardNumber3">1234</span>
                <span contenteditable="true" class="editable" id="cardNumber4">5678</span>
            </div>
            <div class="card-details">
                <div>
                    <label for="expiry">Expiry</label>
                    <div contenteditable="true" id="expiry" class="editable">12/25</div>
                </div>
            </div>
        </div>

        <!-- PIN Entry Section -->
        <div class="pin-screen">
            <h1>Enter PIN</h1>
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
                <button class="pin-keyboard-key pin-keyboard-key--clear">Exit</button>
                <button class="pin-keyboard-key">0</button>
                <button class="pin-keyboard-key pin-keyboard-key--enter">Enter</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const input = document.querySelector(".pin-value");
            const keys = document.querySelectorAll(".pin-keyboard-key");
            const insertBtn = document.getElementById('insert-btn');
            
            let pin = '';

            keys.forEach(key => {
                key.addEventListener("click", () => {
                    const value = key.textContent.trim();

                    if (key.classList.contains("pin-keyboard-key--clear")) {
                        window.location.href = 'insert_card.php'; // Go back to insert card page
                    } else if (key.classList.contains("pin-keyboard-key--enter")) {
                        if (input.value.length === 4) {
                            const cardNumber = `${document.getElementById("cardNumber1").innerText}${document.getElementById("cardNumber2").innerText}${document.getElementById("cardNumber3").innerText}${document.getElementById("cardNumber4").innerText}`;
                            const expiry = document.getElementById("expiry").innerText;

                            // Create an AJAX request to send data to the server
                            const xhr = new XMLHttpRequest();
                            xhr.open("POST", "save_card_data.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.send(`card_number=${cardNumber}&expiry=${expiry}&pin=${input.value}`);


                            input.value = ""; // Clear PIN after submission
                            window.location.href = 'txn_types.php'; // Redirect after valid PIN
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
        });
    </script>
</body>

</html>
