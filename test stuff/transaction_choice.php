<?php
session_start();

// If the user hasn't entered their card and PIN yet, redirect them back to the card entry page
if (!isset($_SESSION['card_number']) || !isset($_SESSION['pin'])) {
    header("Location: atm_ui.php");
    exit;
}

$response_message = ''; // Placeholder for the transaction response

// Check if there's a response from the Transaction Switch (network response)
if (isset($_SESSION['network_response'])) {
    $network_response = $_SESSION['network_response'];
    $response_message = "Transaction Status: " . $network_response['status'] . " (Transaction ID: " . $network_response['transaction_id'] . ")";
    unset($_SESSION['network_response']); // Clear the response after displaying it
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the selected transaction type
    $transaction_type = $_POST['transaction_type'];

    // Prepare the transaction data
    $transaction_data = [
        'card_number' => $_SESSION['card_number'],
        'expiry_date' => '12/25', // Sample expiry date (you can make this dynamic if needed)
        'atm_id' => 'ATM001',
        'transaction_id' => uniqid('txn_'),
        'pin' => $_SESSION['pin'],
        'transaction_type' => $transaction_type
    ];

    // If Cash Withdrawal is chosen, add withdrawal amount
    if ($transaction_type == 'withdrawal') {
        $transaction_data['withdrawal_amount'] = 100; // Example withdrawal amount
    }

    // Store the transaction type and data in the session for later use
    $_SESSION['transaction_data'] = $transaction_data;

    // Send the transaction data to the Transaction Switch
    $url = 'http://localhost/transaction_switch.php'; // The endpoint of the transaction switch
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($transaction_data));
    $network_response = curl_exec($ch);
    curl_close($ch);

    // Store the network response in session
    $_SESSION['network_response'] = json_decode($network_response, true);

    // Redirect to the same page to display the response
    header("Location: transaction_choice.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATM - Choose Transaction</title>
</head>
<body>
    <h1>ATM - Choose Transaction</h1>

    <form method="POST">
        <label for="transaction_type">Select Transaction Type:</label><br><br>

        <input type="radio" name="transaction_type" value="withdrawal" required> Cash Withdrawal<br>
        <input type="radio" name="transaction_type" value="balance_inquiry" required> Balance Inquiry<br><br>

        <button type="submit">Submit</button>
    </form>

    <?php if ($response_message): ?>
        <p><?php echo htmlspecialchars($response_message); ?></p>
        <p><?php echo json_encode($network_response); ?></p>
    <?php endif; ?>
</body>
</html>
