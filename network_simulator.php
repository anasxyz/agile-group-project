<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the transaction data from the Transaction Switch
    $transaction_data = $_POST;

    // Prepare the response
    $response = [
        'transaction_id' => $transaction_data['transaction_id'], // Return the same transaction ID
        'status' => 'Approved' // Simulate approval
    ];

    // Return the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}
