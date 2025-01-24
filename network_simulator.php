<?php
// Allow cross-origin requests from your front-end domain
header('Access-Control-Allow-Origin: *');  // * allows all origins, replace * with your domain to restrict

// Allow specific HTTP methods (GET, POST, etc.)
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Allow specific headers (e.g., Content-Type, Authorization)
header('Access-Control-Allow-Headers: Content-Type, Authorization');

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
