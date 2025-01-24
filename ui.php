<?php
// db_connection.php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $cardNumber = $data['cardNumber'];
    $pin = $data['pin'];

    // Clear any output before the JSON response (important for ensuring no HTML/text before JSON)
    ob_clean();

    // Step 1: Validate PIN and Card Number in the Accounts table
    $stmt = $conn->prepare("SELECT * FROM Accounts WHERE CardNumber = ? AND PIN = ?");
    $stmt->bind_param("ss", $cardNumber, $pin);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $account = $result->fetch_assoc();

        // Step 2: Transaction logic (e.g., initiate a cash withdrawal or balance inquiry)
        // Assume it's a balance inquiry for simplicity
        $balance = $account['Balance'];

        // Step 3: Log transaction to Transactions table
        $stmt = $conn->prepare("INSERT INTO Transactions (CardNumber, Date, PreBalance, NewBalance) VALUES (?, NOW(), ?, ?)");
        $stmt->bind_param("sdd", $cardNumber, $balance, $balance);
        $stmt->execute();

        // Step 4: Simulate Network Simulator response
        $transactionId = $stmt->insert_id;
        $transactionStatus = 'approved';  // Always approve for testing

        // Step 5: Return response to the ATM
        echo json_encode([
            'transactionId' => $transactionId,
            'status' => $transactionStatus,
            'balance' => $balance
        ]);
    } else {
        echo json_encode(['error' => 'Invalid PIN or Card Number']);
    }

    // Ensure we exit after output to avoid extra text
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive ATM UI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .container {
            position: relative;
            width: 800px;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-container {
            position: absolute;
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

        .number-pad {
            display: none;
            position: absolute;
            top: 50%;
            right: 10%;
            transform: translateY(-50%);
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .number-pad button {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            margin: 5px;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .number-pad button:hover {
            background: #1e40af;
        }

        .pin-display {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 10px;
            letter-spacing: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
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
            <button class="insert-btn" id="insert-btn">Insert Card</button>
        </div>

        <div class="number-pad" id="number-pad">
            <div class="pin-display" id="pin-display">____</div>
            <div>
                <button>1</button>
                <button>2</button>
                <button>3</button>
            </div>
            <div>
                <button>4</button>
                <button>5</button>
                <button>6</button>
            </div>
            <div>
                <button>7</button>
                <button>8</button>
                <button>9</button>
            </div>
            <div>
                <button>0</button>
                <button id="clear-btn">C</button>
                <button id="submit-pin-btn">✔</button>
            </div>
        </div>
    </div>

    <script>
        const insertBtn = document.getElementById('insert-btn');
        const cardContainer = document.getElementById('card-container');
        const numberPad = document.getElementById('number-pad');
        const pinDisplay = document.getElementById('pin-display');
        const clearBtn = document.getElementById('clear-btn');
        const submitPinBtn = document.getElementById('submit-pin-btn');

        let pin = '';
        let cardNumber = '';

        // Card insertion button animation
        insertBtn.addEventListener('click', () => {
            cardNumber = `${document.getElementById('cardNumber1').innerText}${document.getElementById('cardNumber2').innerText}${document.getElementById('cardNumber3').innerText}${document.getElementById('cardNumber4').innerText}`;
            cardContainer.style.transform = 'translateX(-500px)';
            setTimeout(() => {
                numberPad.style.display = 'block';
            }, 1000);
        });

        // Handle PIN entry
        numberPad.addEventListener('click', (event) => {
            if (event.target.tagName === 'BUTTON' && event.target.innerText !== 'C' && event.target.innerText !== '✔') {
                if (pin.length < 4) {
                    pin += event.target.innerText;
                    pinDisplay.innerText = pin.padEnd(4, '_');
                }
            }
        });

        // Clear PIN
        clearBtn.addEventListener('click', () => {
            pin = '';
            pinDisplay.innerText = '____';
        });

        // Submit PIN and send transaction request to the backend
        submitPinBtn.addEventListener('click', () => {
            if (pin.length === 4) {
                fetch('ui.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ cardNumber, pin }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'approved') {
                        alert('Transaction Approved! Balance: ' + data.balance);
                    } else {
                        alert('Invalid Card or PIN');
                    }
                })
                .catch(error => console.log('Error:', error));
            } else {
                alert('Please enter a 4-digit PIN');
            }
        });
    </script>
</body>
</html>
