<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_data = $_POST;

    $response = [
        'transaction_id' => $transaction_data['transaction_id'],
        'status' => 'Approved'
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}
