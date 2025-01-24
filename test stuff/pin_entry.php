<?php
session_start(); // Start the session to retrieve the card details

// Check if the card details are set in the session
if (!isset($_SESSION['card_number']) || !isset($_SESSION['expiry_date'])) {
    header('Location: insert_card.php'); // Redirect to the insert card page if details are not available
    exit();
}

$card_number = $_SESSION['card_number'];
$expiry_date = $_SESSION['expiry_date'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENTER PIN</title>
    <style>
        /* General Body Styling */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        /* PIN Screen Container */
        .pin-screen {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 600px;
            height: 500px;
            padding: 20px;
            border-radius: 15px;
            /* background-color: #fff; */
            /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); */
        }

        /* Title Styling */
        h1 {
            margin-bottom: 20px;
            font-size: 26px;
            font-weight: bold;
            color: #333;
        }

        /* PIN Input Styling */
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

        /* Keypad Styling */
        .pin-keyboard {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            width: 300px;
        }

        /* Individual Button Styling */
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

        /* Clear Button */
        .pin-keyboard-key--clear {
            background-color: #f44336;
            color: white;
            font-weight: bold;
        }

        .pin-keyboard-key--clear:hover {
            background-color: #d32f2f;
        }

        /* Enter Button */
        .pin-keyboard-key--enter {
            background-color: #4caf50;
            color: white;
            font-weight: bold;
        }

        .pin-keyboard-key--enter:hover {
            background-color: #388e3c;
        }

        /* Popup Styles */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.3s;
        }

        .popup.active {
            visibility: visible;
            opacity: 1;
        }

        .popup-content {
            width: 400px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            padding: 20px;
        }

        .popup-title {
            font-size: 24px;
            font-weight: bold;
            color: #f44336;
            margin-bottom: 20px;
        }

        .popup-body {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .popup-icon {
            font-size: 40px;
            color: #f44336;
        }

        .popup-text {
            font-size: 18px;
            color: #333;
        }

        .popup-close {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border-radius: 30px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup-close:hover {
            background-color: #d32f2f;
        }
    </style>
</head>

<body>
    <!-- PIN Screen -->
    <div class="pin-screen">
        <h1>Enter PIN</h1>
        <input type="password" class="pin-value" readonly>
        <div class="pin-keyboard">
            <!-- Keypad Buttons -->
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

    <!-- Popup -->
    <div id="transaction-popup" class="popup">
        <div class="popup-content">
            <div class="popup-title">Transaction Cancelled!</div>
            <div class="popup-body">
                <div class="popup-icon">&#9888;</div>
                <div class="popup-text">Your transaction has been cancelled.</div>
            </div>
            <button class="popup-close" onclick="closePopup()">Close</button>
        </div>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const input = document.querySelector(".pin-value");
        const keys = document.querySelectorAll(".pin-keyboard-key");
        const popup = document.getElementById("transaction-popup");

        keys.forEach(key => {
            key.addEventListener("click", () => {
                const value = key.textContent.trim();

                if (key.classList.contains("pin-keyboard-key--clear")) {
                    // Show popup on exit and redirect after closing the popup
                    showPopup("Transaction Cancelled!", "Your transaction has been cancelled.");
                } else if (key.classList.contains("pin-keyboard-key--enter")) {
                    if (input.value.length === 4) {
                        input.value = ""; // Clear PIN after submission
                        window.location.href = 'transaction_options.php'; // Redirect if PIN is 4 digits
                    } else {
                        // Show error message if PIN is not 4 digits
                        showPopup("Invalid PIN", "Please enter a 4-digit PIN.");
                    }
                } else {
                    if (input.value.length < 4) {  // Limit PIN to 4 digits
                        input.value += value;
                    }
                }
            });
        });

        // Function to show the popup with a custom message
        function showPopup(title, message) {
            const titleElement = document.querySelector(".popup-title");
            const messageElement = document.querySelector(".popup-text");

            titleElement.textContent = title;
            messageElement.textContent = message;

            popup.classList.add("active");
        }

        window.closePopup = function () {
            popup.classList.remove("active");
            window.location.href = 'ui.html'; 
        };
    });


    </script>
</body>

</html>
