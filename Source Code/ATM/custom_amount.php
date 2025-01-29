<?php
session_start(); // If session data is needed


$_SESSION['language'] = 'es';
$language = $_SESSION['language'] ?? 'en';

$lang = include "../languages/{$language}.php";

// Retrieve the account type from the URL
$accountType = isset($_GET['account_type']) ? htmlspecialchars($_GET['account_type']) : 'Unknown';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Amount Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
            margin: 0;
        }

        .amount-form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .amount-form input {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .amount-form button {
            padding: 10px;
            width: 100%;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .amount-form button:hover {
            background-color: #0056b3;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .modal-content button {
            padding: 10px;
            margin: 10px 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .confirm {
            background-color: #28a745;
            color: #fff;
        }

        .confirm:hover {
            background-color: #218838;
        }

        .cancel {
            background-color: #dc3545;
            color: #fff;
        }

        .cancel:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="amount-form">
        <h2><?= $lang['enter_custom_amount'] ?></h2>
        <input type="number" id="amount" placeholder="Enter amount" min="0" step="0.01">
        <button onclick="showModal()"><?= $lang['submit'] ?></button>
    </div>

    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <h3><?= $lang['confirm_withdrawal'] ?></h3>
            <p id="modalMessage"></p>
            <button class="confirm" onclick="confirmWithdrawal()"><?= $lang['confirm'] ?></button>
            <button class="cancel" onclick="closeModal()"><?= $lang['cancel'] ?></button>
        </div>
    </div>

    <script>
        const accountType = "<?php echo $accountType; ?>";

        function sendWithdrawalData(withdrawal_amount) {
            const transaction_data = {
                'card_number': '1234123412341234',
                'expiry_date': '12/25',
                'atm_id': 'ATM001',
                'transaction_id': `txn_${Date.now()}`, // Unique transaction ID
                'pin': '1234',
                'transaction_type': 'withdrawal',
                'withdrawal_amount': withdrawal_amount
            };

            fetch('http://localhost/../Transaction Switch/transaction_switch.php', {
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
                        const url = `take_card_out.php`;
                        window.location.href = url;
                    } else {
                        alert(`Transaction Failed: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was an error processing your request.');
                });
        }

        function showModal() {
            const amount = document.getElementById('amount').value;
            if (amount && parseFloat(amount) > 0) {
                const modalMessage = document.getElementById('modalMessage');
                modalMessage.textContent = `You are about to withdraw $${amount}. Do you want to proceed?`;
                document.getElementById('confirmationModal').style.display = 'flex';
            } else {
                alert('Please enter a valid amount greater than 0.');
            }
        }

        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        function confirmWithdrawal() {
            const amount = document.getElementById('amount').value;
            closeModal();
            sendWithdrawalData(amount);
        }
    </script>
</body>

</html>