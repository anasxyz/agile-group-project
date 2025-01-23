<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATM Card Entry</title>
    <!--<link rel="stylesheet" href="styles.css"> -->
    <style>
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        #cardButtonsContainer {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        #cardButtonsContainer button {
            width: 60px;
            height: 60px;
            font-size: 20px;
            border-radius: 10px;
            border: 2px solid #333;
            background-color: #f0f0f0;
            cursor: pointer;
        }

        #cardButtonsContainer button:hover {
            background-color: #ddd;
        }

        #cardButtonsContainer button:active {
            background-color: #ccc;
        }

        #card {
            margin-top: 20px;
            font-size: 20px;
            padding: 10px;
            width: 180px;
            text-align: center;
        }

        
    </style>  
</head>

<body>
    <div class="container">
        <h1>ATM System</h1>
        <div id="cardEntry" style="display: block;">
            <?php
                session_start();  // Start the session to access session variables

                // Check if there's an error message in the session and display it
                if (isset($_SESSION['error_message'])) {
                    echo "<p style='color:red;'>" . $_SESSION['error_message'] . "</p>";
                    unset($_SESSION['error_message']);  // Clear the error message after showing it
                }
            ?>
            <form method="post" action="CardVerify.php">
                <label for="card">Enter Card:</label>
                <input type="password" id="card" name="Card" maxlength="16" readonly>
            </form>
        </div>
    </div>

    <script>
        const cardInput = document.getElementById('card');
        const cardButtonsContainer = document.createElement('div');
        cardButtonsContainer.id = 'cardButtonsContainer';

        for (let i = 0; i <= 11; i++) {
            const button = document.createElement('button');
            if (i === 9) {
                button.textContent = 'Clear';
                button.addEventListener('click', function() {
                cardInput.value = '';
                });
                cardButtonsContainer.appendChild(button);
                continue;
            } else
            if (i === 10) {
                button.textContent = 0;
                button.addEventListener('click', function() {
                if (cardInput.value.length < 16) {
                    cardInput.value += 0;
                }
                });
                cardButtonsContainer.appendChild(button);
                continue;
            } else if (i === 11) {
                button.textContent = 'Enter';
                
                button.addEventListener('click', function() {
                const card = cardInput.value;
                document.querySelector('form').submit();
                alert('Card entered: ' + card);
                });
                cardButtonsContainer.appendChild(button);
                continue; }
                else {
                button.textContent = i+1; }
            button.addEventListener('click', function() {
            if (cardInput.value.length < 16) {
                cardInput.value += i+1;
            }
            });
            cardButtonsContainer.appendChild(button);
        }

        document.getElementById('cardEntry').appendChild(cardButtonsContainer);
        

        
    </script>
</body></div></head>