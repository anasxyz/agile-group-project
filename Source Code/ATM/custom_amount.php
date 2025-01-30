<?php
session_start();

$language = $_SESSION['language'] ?? 'en';
$lang = include "../languages/{$language}.php";

// Retrieve the account type from the URL
$accountType = isset($_GET['account_type']) ? htmlspecialchars($_GET['account_type']) : 'Unknown';
?>

<!DOCTYPE html>
<html lang="<?= $language ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['custom_amount_page'] ?></title>
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
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h2 {
            font-size: 26px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        input {
            padding: 12px;
            font-size: 16px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            box-sizing: border-box;
            text-align: center;
        }

        .button {
            padding: 12px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .button.submit {
            background-color: #007bff;
            color: white;
        }

        .button.submit:hover {
            background-color: #0056b3;
        }

        .exit-btn {
            padding: 10px 20px;
            font-size: 1.2em;
            background-color: rgb(243, 187, 183);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            width: 100%;
            margin-top: 15px;
        }

        .exit-btn:hover {
            background-color: rgb(250, 157, 157);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-content h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .modal-content p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .modal-footer {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .modal-button {
            padding: 10px 20px;
            border: none;
            border-radius: 30px;
            font-size: 14px;
            cursor: pointer;
            width: 120px;
        }

        .modal-button.confirm {
            background-color: #28a745;
            color: white;
        }

        .modal-button.confirm:hover {
            background-color: #218838;
        }

        .modal-button.cancel {
            background-color: #dc3545;
            color: white;
        }

        .modal-button.cancel:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2><?= $lang['enter_custom_amount'] ?></h2>
        <input type="number" id="amount" placeholder="<?= $lang['enter_amount_placeholder'] ?>" min="0" step="0.01">
        <button class="button submit" onclick="showModal()"><?= $lang['submit'] ?></button>
        <button class="exit-btn" onclick="cancelTransaction()"><?= $lang['exit'] ?></button>
    </div>

    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <h3><?= $lang['confirm_withdrawal'] ?></h3>
            <p id="modalMessage"></p>
            <div class="modal-footer">
                <button class="modal-button confirm" onclick="confirmWithdrawal()"><?= $lang['confirm'] ?></button>
                <button class="modal-button cancel" onclick="closeModal()"><?= $lang['cancel'] ?></button>
            </div>
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
                modalMessage.textContent = `<?= $lang['confirm_message'] ?> $${amount}.`;
                document.getElementById('confirmationModal').style.display = 'flex';
            } else {
                alert('<?= $lang['error_invalid_amount'] ?>');
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

        function cancelTransaction() {
            window.location.href = "index.php";
        }
    </script>
</body>

</html>