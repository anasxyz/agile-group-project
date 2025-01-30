<?php
include '../Database/db_connection.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_data = $_POST;

    // Handle transaction status update (from update_transactions.php)
    if (isset($transaction_data['transaction_id']) && isset($transaction_data['status'])) {
        $transaction_id = $transaction_data['transaction_id'];
        $status = $transaction_data['status'];
        $message = $transaction_data['message'];
        $balance = isset($transaction_data['balance']) ? $transaction_data['balance'] : null;

        // Update transaction status in DB
        $stmt = $conn->prepare("UPDATE Transactions SET status = ?, message = ? WHERE transaction_id = ?");
        $stmt->bind_param("sss", $status, $message, $transaction_id);
        $stmt->execute();
        $stmt->close();

        // Forward updated transaction to the Transaction Switch
        $transaction_forward = [
            'transaction_id' => $transaction_id,
            'status' => $status,
            'message' => $message,
            'balance' => $balance
        ];

        $url = 'http://localhost/../Network%20Simulator/transaction_switch.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($transaction_forward));
        $switch_response = curl_exec($ch);
        curl_close($ch);

        echo json_encode(['success' => true, 'switch_response' => $switch_response, 'balance' => $balance]);
        exit;
    }

    // Handle regular transactions (balance inquiry, authorisation, etc.)
    $card_number = $transaction_data['card_number'];
    $pin = $transaction_data['pin'];
    $response = [];

    if ($transaction_data['transaction_type'] === 'balance inquiry' || $transaction_data['transaction_type'] === 'print balance') {
        $stmt = $conn->prepare("SELECT balance FROM Accounts WHERE cardNumber = ? AND pin = ?");
        $stmt->bind_param("ss", $card_number, $pin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $account = $result->fetch_assoc();
            $balance = $account['balance'];

            $response = [
                'transaction_id' => $transaction_data['transaction_id'],
                'status' => 'Pending',
                'transaction_type' => $transaction_data['transaction_type'],
                'balance' => $balance,
                'message' => 'Pending by network simulator 1'
            ];
        } else {
            $response = [
                'transaction_id' => $transaction_data['transaction_id'],
                'status' => 'Declined',
                'transaction_type' => $transaction_data['transaction_type'],
                'message' => 'Transaction declined due to incorrect PIN or account not found'
            ];
        }

        $stmt->close();
    } elseif ($transaction_data['transaction_type'] === 'authorisation') {
        $stmt = $conn->prepare("SELECT * FROM Accounts WHERE cardNumber = ? AND pin = ?");
        $stmt->bind_param("ss", $card_number, $pin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $account = $result->fetch_assoc();
            if ($account['status'] === 'blocked') {
                $response = [
                    'transaction_id' => $transaction_data['transaction_id'],
                    'status' => 'Declined',
                    'transaction_type' => $transaction_data['transaction_type'],
                    'message' => 'Transaction declined because your account is blocked'
                ];
            } else {
                $response = [
                    'transaction_id' => $transaction_data['transaction_id'],
                    'status' => 'Approved',
                    'transaction_type' => $transaction_data['transaction_type'],
                    'message' => 'Approved by network simulator 1'
                ];
            }
        } else {
            $response = [
                'transaction_id' => $transaction_data['transaction_id'],
                'status' => 'Declined',
                'transaction_type' => $transaction_data['transaction_type'],
                'message' => 'Transaction declined due to incorrect PIN'
            ];
        }
    
        $stmt->close();
    }

    // Insert transaction record
    $stmt = $conn->prepare("INSERT INTO Transactions (transaction_id, card_number, transaction_type, status, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $transaction_data['transaction_id'], $card_number, $transaction_data['transaction_type'], $response['status'], $response['message']);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
