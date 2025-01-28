<?php
include '../Database/db_connection.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_data = $_POST;

    $response = [
        'transaction_id' => $transaction_data['transaction_id'],
        'status' => 'Approved',
        'transaction_type' => $transaction_data['transaction_type'],
        'message' => 'Approved by network simulator 1'
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}
