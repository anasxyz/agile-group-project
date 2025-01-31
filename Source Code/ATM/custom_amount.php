<?php
session_start(); // Start session to get card details

// Retrieve actual card details from session
$cardNumber = isset($_SESSION['card_number']) ? $_SESSION['card_number'] : null;
$pin = isset($_SESSION['pin']) ? $_SESSION['pin'] : null;
$expiryDate = isset($_SESSION['expiry_date']) ? $_SESSION['expiry_date'] : null;
$atmId = isset($_SESSION['atm_id']) ? $_SESSION['atm_id'] : 'ATM001'; // Default ATM ID
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

        /* Loading Modal */
        .spinner {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #333;
            border-radius: 50%;
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
    <div class="amount-form">
        <h2>Enter Custom Amount</h2>
        <input type="number" id="amount" placeholder="Enter amount" min="0" step="0.01">
        <button onclick="showModal()">Submit</button>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <h3>Confirm Withdrawal</h3>
            <p id="modalMessage"></p>
            <button class="confirm" onclick="confirmWithdrawal()">Confirm</button>
            <button class="cancel" onclick="closeModal()">Cancel</button>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="modal">
        <div class="modal-content">
            <div class="spinner"></div>
            <p>Processing your request...</p>
        </div>
    </div>

    <!-- Receipt Confirmation Modal -->
    <div id="receiptModal" class="modal">
        <div class="modal-content">
            <h2>Do you want a receipt?</h2>
            <div class="modal-footer">
                <button class="confirm" onclick="redirectToReceiptPage(true)">Yes</button>
                <button class="cancel" onclick="redirectToReceiptPage(false)">No</button>
            </div>
        </div>
    </div>

    <script>
        const cardNumber = "<?php echo $cardNumber; ?>";
        const pin = "<?php echo $pin; ?>";
        const expiryDate = "<?php echo $expiryDate; ?>";
        const atmId = "<?php echo $atmId; ?>";

        function sendWithdrawalData(withdrawal_amount) {
            document.getElementById('loadingModal').style.display = 'flex';

            const transactionData = {
                'card_number': cardNumber,
                'pin': pin,
                'expiry_date': expiryDate,
                'atm_id': atmId,
                'transaction_id': `txn_${Date.now()}`, // Unique transaction ID
                'transaction_type': 'withdrawal',
                'withdrawal_amount': withdrawal_amount
            };

            fetch('http://localhost/../Transaction%20Switch/transaction_switch.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(transactionData).toString()
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.status === 'Pending') {
                    setTimeout(() => checkTransactionStatus(transactionData.transaction_id), 3000);
                } else if (data.status === 'Approved') {
                    showReceiptModal();
                } else {
                    alert(`Transaction Failed: ${data.message}`);
                    document.getElementById('loadingModal').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error processing your request.');
                document.getElementById('loadingModal').style.display = 'none';
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

        function checkTransactionStatus(transactionId) {
            fetch(`http://localhost/../Transaction%20Switch/check_transaction_status.php?transaction_id=${transactionId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Checking status:', data);
                if (data.status === 'Approved') {
                    showReceiptModal();
                } else {
                    alert('Transaction Declined: ' + data.message);
                    document.getElementById('loadingModal').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error checking transaction status:', error);
            });
        }

        function showReceiptModal() {
            document.getElementById('loadingModal').style.display = 'none';
            document.getElementById('receiptModal').style.display = 'flex';
        }

        function redirectToReceiptPage(wantsReceipt) {
            document.getElementById('receiptModal').style.display = 'none';
            document.getElementById('loadingModal').style.display = 'flex';

            setTimeout(() => {
                document.getElementById('loadingModal').style.display = 'none';
                if (wantsReceipt) {
                    window.location.href = 'collect_cash_receipt.php';
                } else {
                    window.location.href = 'collect_cash.php';
                }
            }, 2000);
        }
    </script>
</body>
</html>
