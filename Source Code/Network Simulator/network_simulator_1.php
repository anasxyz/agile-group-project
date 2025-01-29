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
        $card_number = $transaction_data['card_number'];
        $withdrawalAmount = (double) $transaction_data['withdrawal_amount']; 

        $stmt = $conn->prepare("SELECT CardNumber, Balance FROM Accounts WHERE CardNumber = :cardno");
        $stmt->bindParam(':cardno', $card_number);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($results) {
            $storedBalance = (double)$results['Balance'];

            if ($storedBalance >= $withdrawalAmount) {

            //Get new balance
            $balance = $storedBalance - $withdrawalAmount;


            //Update the balance in the database
            $withdrawStmt = $conn->prepare("UPDATE Accounts SET Balance = :balance WHERE CardNumber = :cardno");
            $withdrawStmt->bindParam(':balance', $balance);
            $withdrawStmt->bindParam(':cardno', $card_number);
            $withdrawStmt->execute();
            

            //Log transaction to database
                $transactionStmt = $conn->prepare( "INSERT INTO Transactions (TransactionId, CardNumber, Date, PreBalance, NewBalance) 
                             VALUES (: transId, cardNumber, CURDATE(), :preBalance, :newBalance)");
                $transactionStmt->bindParam(':cardNumber', $card_number);
                $transactionStmt->bindParam(':preBalance', $storedBalance);
                $transactionStmt->bindParam(':newBalance', $balance);
                $transactionStmt->bindParam('transId', $transaction_data['transaction_id']);
                $transactionStmt->execute();
            

            
                
            //Successful response
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
    
        } 
        
        else {
            //Card number not found
            $response = [
                'transaction_id' => $transaction_data['transaction_id'],
                'status' => 'Declined',
                'transaction_type' => $transaction_data['transaction_type'],
                'message' => 'Card number not found'
            ];
        }
    }
    
    //Insert a record into the NetworkSimulatorLogs table
    $logStmt = $conn->prepare("INSERT INTO NetworkSimulatorLogs (CardNumber, TransactionId, Date, Balance) 
    VALUES (:cardNumber, :transactionId, CURDATE(), :balance)");
    $logStmt->bindParam(':cardNumber', $card_number);
    $logStmt->bindParam(':transactionId', $transaction_data['transaction_id']); // Ensure this is correctly assigned
    $logStmt->bindParam(':transactionType', $transaction_data['transaction_type']);
    $logStmt->bindParam(':status', $response['status']);
    $logStmt->bindParam(':balance', $balance);
    $logStmt->execute();

    header('Content-Type: application/json');
    echo json_encode($response);
}
