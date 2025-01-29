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
                'message' => 'Approved by network simulator 1'
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
        $Cardno = $transaction_data['card_number'];
        $query = "SELECT AccountId, card_number, Balance FROM Accounts WHERE AccountId = :cardno";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':cardno', $Cardno);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $storedBalance = (double)$row['Balance'];

            if ($storedBalance >= $withdrawalAmount) {

            $newBalance = $storedBalance - $withdrawalAmount;


            // Update the balance in the database
            $withdrawQuery = "UPDATE Accounts SET Balance = :newBalance WHERE card_number = :cardno";
            $withdrawStmt = $conn->prepare($withdrawQuery);
            $withdrawStmt->bindParam(':newBalance', $newBalance);
            $withdrawStmt->bindParam(':cardno', $Cardno);
            $withdrawStmt->execute();
            
            $response = [
                'transaction_id' => $transaction_data['transaction_id'],
                'status' => 'Approved',
                'transaction_type' => $transaction_data['transaction_type'],
                'message' => 'Withdrawal has been made successfully'
            ];
            } 
            
            else {
            //Insufficient funds
            $response = [
                'transaction_id' => $transaction_data['transaction_id'],
                'status' => 'Declined',
                'transaction_type' => $transaction_data['transaction_type'],
                'message' => 'Insufficient funds'
            ];
        }
        } else {
            //Card number not found
            $response = [
                'transaction_id' => $transaction_data['transaction_id'],
                'status' => 'Declined',
                'transaction_type' => $transaction_data['transaction_type'],
                'message' => 'Card number not found'
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
