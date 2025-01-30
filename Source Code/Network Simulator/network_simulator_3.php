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
                    'message' => 'Approved by network simulator 3'
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
        $conn->close();
    }
    elseif ($transaction_data['transaction_type'] === 'balance inquiry') {
        $stmt = $conn->prepare("SELECT balance FROM Accounts WHERE cardNumber = ? AND pin = ?");
        $stmt->bind_param("ss", $card_number, $pin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $account = $result->fetch_assoc();
            $balance = $account['balance']; 

            $response = [
                'transaction_id' => $transaction_data['transaction_id'],
                'status' => 'Approved',
                'transaction_type' => $transaction_data['transaction_type'],
                'balance' => $balance,
                'message' => 'Approved by network simulator 3'
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
        $conn->close();
    } 
    elseif ($transaction_data['transaction_type'] === 'withdrawal') {
        // Assuming 'amount' is passed in the transaction data for withdrawal
        $withdrawal_amount = $transaction_data['withdrawal_amount'];

        // Check if the transaction data includes a valid amount
        if ($withdrawal_amount <= 0) {
            $response = [
                'transaction_id' => $transaction_data['transaction_id'],
                'status' => 'Declined',
                'transaction_type' => $transaction_data['transaction_type'],
                'message' => 'Transaction declined due to invalid withdrawal amount'
            ];
        } else {
            $stmt = $conn->prepare("SELECT balance, status FROM Accounts WHERE cardNumber = ? AND pin = ?");
            $stmt->bind_param("ss", $card_number, $pin);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $account = $result->fetch_assoc();
                $current_balance = $account['balance'];
                $account_status = $account['status'];

                if ($account_status === 'blocked') {
                    $response = [
                        'transaction_id' => $transaction_data['transaction_id'],
                        'status' => 'Declined',
                        'transaction_type' => $transaction_data['transaction_type'],
                        'message' => 'Transaction declined because your account is blocked'
                    ];
                } elseif ($current_balance < $withdrawal_amount) {
                    $response = [
                        'transaction_id' => $transaction_data['transaction_id'],
                        'status' => 'Declined',
                        'transaction_type' => $transaction_data['transaction_type'],
                        'message' => 'Transaction declined due to insufficient funds'
                    ];
                } else {
                    // Proceed with withdrawal
                    $new_balance = $current_balance - $withdrawal_amount;

                    // Update the account balance
                    $update_stmt = $conn->prepare("UPDATE Accounts SET balance = ? WHERE cardNumber = ?");
                    $update_stmt->bind_param("ds", $new_balance, $card_number);
                    if ($update_stmt->execute()) {
                        $response = [
                            'transaction_id' => $transaction_data['transaction_id'],
                            'status' => 'Approved',
                            'transaction_type' => $transaction_data['transaction_type'],
                            'balance' => $new_balance,
                            'message' => 'Withdrawal successful'
                        ];
                    } else {
                        $response = [
                            'transaction_id' => $transaction_data['transaction_id'],
                            'status' => 'Declined',
                            'transaction_type' => $transaction_data['transaction_type'],
                            'message' => 'Error processing withdrawal'
                        ];
                    }
                    $update_stmt->close();
                }
            } else {
                $response = [
                    'transaction_id' => $transaction_data['transaction_id'],
                    'status' => 'Declined',
                    'transaction_type' => $transaction_data['transaction_type'],
                    'message' => 'Transaction declined due to incorrect PIN or account not found'
                ];
            }

            $stmt->close();
        }

        $conn->close();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
