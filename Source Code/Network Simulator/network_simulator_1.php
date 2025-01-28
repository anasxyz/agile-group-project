<?php
include '../Database/db_connection.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_data = $_POST;

    $card_number = $transaction_data['card_number'];
    $pin = $transaction_data['pin'];

    if ($transaction_data['transaction_type'] === 'authorisation') {
        $stmt = $conn->prepare("SELECT * FROM Accounts WHERE cardNumber = ? AND pin = ?");
        $stmt->bind_param("ss", $card_number, $pin);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $response = [
                'transaction_id' => $transaction_data['transaction_id'],
                'status' => 'Approved',
                'transaction_type' => $transaction_data['transaction_type'],
                'message' => 'Approved by network simulator 1'
            ];
        } else {
            $response = [
                'transaction_id' => $transaction_data['transaction_id'],
                'status' => 'Declined',
                'transaction_type' => $transaction_data['transaction_type'],
                'message' => 'Transaction declined due to incorrect PIN'
            ];
        }
    
        $stmt->close();
        $conn->close();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
