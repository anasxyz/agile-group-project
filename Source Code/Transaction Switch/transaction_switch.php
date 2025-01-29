<?php
include '../Database/db_connection.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_data = $_POST;

    $incomeStmt = $conn->prepare("INSERT INTO TransactionSwitchLogs (CardNumber, TransactionId, Date, Balance, Direction) 
    VALUES (:cardNumber, :transactionId, CURDATE(), :balance, :direction)");
    $incomeStmt->bindParam(':cardNumber', $transaction_data['card_number']);
    $incomeStmt->bindParam(':transactionId', $transaction_data['transaction_id']);
    $balance = NULL; // No balance yet before sending the request
    $incomeStmt->bindParam(':balance', $balance);
    $direction = "NetworkSim"; // Request being sent
    $incomeStmt->bindParam(':direction', $direction);
    $incomeStmt->execute();

    $url = 'http://localhost/../Network%20Simulator/network_simulator_1.php';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($transaction_data));
    $response = curl_exec($ch);
    curl_close($ch);


    $response_data = json_decode($response, true);
    $balance = isset($response_data['balance']) ? $response_data['balance'] : NULL;

    // Step 2: Log transaction in TransactionSwitchLogs
    $logStmt = $conn->prepare("INSERT INTO TransactionSwitchLogs (CardNumber, TransactionId, Date, Balance, Direction) 
        VALUES (:cardNumber, :transactionId, CURDATE(), :balance, :direction)");
    $logStmt->bindParam(':cardNumber', $transaction_data['card_number']);
    $logStmt->bindParam(':transactionId', $transaction_data['transaction_id']);
    $logStmt->bindParam(':balance', $balance);
    $direction = "ATM"; //Need logic to tell if json is coming from simulator or ATM
    $logStmt->bindParam(':direction', $direction);
    $logStmt->execute();

    echo $response;
}
