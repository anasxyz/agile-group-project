<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_data = $_POST;

    $response = [
        'transaction_id' => $transaction_data['transaction_id'],
        'status' => 'Approved',
        'transaction_type' => $transaction_data['transaction_type']
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}
