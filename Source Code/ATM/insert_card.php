<?php
session_start();

$_SESSION['language'] = 'es';
$language = $_SESSION['language'] ?? 'en';

$lang = include "../languages/{$language}.php";

// Clear all session values related to card information
unset($_SESSION['card_number']);
unset($_SESSION['expiry']);
unset($_SESSION['pin']);
?>


<!DOCTYPE html>
<html lang="en">

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
            /* Ensures the image fits within the element */
            background-repeat: no-repeat;
            /* Prevents the image from repeating */
            background-position: center;
            /* Centers the image */
        }

        .card-container .chip {
            width: 60px;
            /* Adjusted width */
            height: 45px;
            /* Adjusted height */
            background-color: transparent;
            /* Removed background color */
            border-radius: 10px;
            margin-top: 25px;
            margin-left: 10px;
            background-image: url("chip2.png");
            /* Keep the chip image */
            background-size: contain;
            /* Ensures the image fits within the element */
            background-repeat: no-repeat;
            /* Prevents the image from repeating */
            background-position: center;
            /* Centers the image */
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
            /* Realistic font */
            font-size: 1rem;
            /* Slightly larger for readability */
            letter-spacing: 3px;
            /* Space between characters */
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
            padding: 10px;
            color: rgb(160, 160, 160);
            /* Metallic grey color */
            text-shadow: 2px 3px 3px rgba(0, 0, 0, 0.5);
            /* Subtle shadow for depth */
        }

        .date {
            font-family: 'Credit Card', monospace;
            /* Realistic font */
            font-size: 10px;
            /* Slightly larger for readability */
            letter-spacing: 3px;
            /* Space between characters */
            margin: 20px 0;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            padding: 10px;
            color: rgb(160, 160, 160);
            /* Metallic grey color */
            text-shadow: 2px 3px 3px rgba(0, 0, 0, 0.5);
            /* Subtle shadow for depth */
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
            /* Replace with the actual path to the contactless logo image */
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
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
                <span contenteditable="true" class="editable" id="cardNumber1">4234</span>
                <span contenteditable="true" class="editable" id="cardNumber2">5678</span>
                <span contenteditable="true" class="editable" id="cardNumber3">1234</span>
                <span contenteditable="true" class="editable" id="cardNumber4">5678</span>
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
                <button class="pin-keyboard-key pin-keyboard-key--clear"><?= $lang['enter'] ?></button>
                <button class="pin-keyboard-key">0</button>
                <button class="pin-keyboard-key pin-keyboard-key--enter"><?= $lang['enter'] ?></button>
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
                "4": "visa-logo-2.png", // Replace with actual path to Visa logo
                "5": "mastercard-logo.png", // Replace with actual path to Mastercard logo
                "3": "amex-logo.png", // Replace with actual path to Amex logo
                "6": "discover-logo.png" // Replace with actual path to Discover logo
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
                        window.location.href = 'insert_card.php'; // Go back to insert card page
                    } else if (key.classList.contains("pin-keyboard-key--enter")) {
                        if (input.value.length === 4) {
                            const cardNumber = `${document.getElementById("cardNumber1").innerText}${document.getElementById("cardNumber2").innerText}${document.getElementById("cardNumber3").innerText}${document.getElementById("cardNumber4").innerText}`;
                            const expiry = document.getElementById("expiry").innerText;

                            const url = `save_card_data.php?card_number=${encodeURIComponent(cardNumber)}&expiry=${encodeURIComponent(expiry)}&pin=${encodeURIComponent(input.value)}`;
                            window.location.href = url;

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
    </script>
</body>

</html>