<?php
$response_message = ''; // Placeholder for the response message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Define transaction data for testing
    $transaction_data = [
        'card_number' => '1234567890123456',
        'expiry_date' => '12/25',
        'atm_id' => 'ATM001',
        'transaction_id' => uniqid('txn_'),
        'pin' => '1234',
        'withdrawal_amount' => 100 // Only for cash withdrawal
    ];

    // Send the transaction data to the Transaction Switch via POST request
    $url = 'http://localhost/transaction_switch.php'; // The endpoint of the transaction switch
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($transaction_data));
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the response from the Transaction Switch
    $response_data = json_decode($response, true);
    if ($response_data && isset($response_data['status'])) {
        $response_message = "Transaction Status: " . $response_data['status'] . " (Transaction ID: " . $response_data['transaction_id'] . ")";
    } else {
        $response_message = "Error: Unable to process the transaction.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATM UI</title>
</head>
<body>
    <h1>ATM Transaction</h1>
    <p>Click the button below to simulate a transaction:</p>
    <form method="POST">
        <button type="submit">Send Transaction</button>
    </form>
    <?php if ($response_message): ?>
        <p><?php echo htmlspecialchars($response_message); ?></p>
    <?php endif; ?>
</body>
</html>
