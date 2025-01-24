<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_data = $_POST;

    $response = [
        'transaction_id' => $transaction_data['transaction_id'],
        'status' => 'Approved',
        'transaction_type' => $transaction_data['transaction_type']
    ];

    // if ($transaction_data['transaction_type'] == 'balance inquiry') {
    //     $response['status'] = 'Rejected';
    //     $response['message'] = 'Not enough funds';
    // } else {
    //     $response['status'] = 'Approved';
    // }

    header('Content-Type: application/json');
    echo json_encode($response);
}
