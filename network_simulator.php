<?php
require 'db_connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $card_number = $_POST['card_number'];
    $pin = $_POST['pin'];
    $withdrawal_amount = $_POST['withdrawal_amount'];
    $transaction_id = $_POST['transaction_id'];
    $expiry_date = $_POST['expiry_date'];

    // Validate the card details and PIN
    $stmt = $conn->prepare("SELECT Balance FROM Accounts WHERE CardNumber = ? AND PIN = ? AND ExpiryDate >= CURDATE()");
    $stmt->bind_param("ss", $card_number, $pin);
    $stmt->execute();
    $stmt->bind_result($balance);
    $stmt->fetch();
    $stmt->close();

    if (!isset($balance)) {
        // Invalid card or PIN
        echo json_encode(['transaction_id' => $transaction_id, 'status' => 'Declined', 'message' => 'Invalid card or PIN']);
        exit;
    }

    // Check if the balance is sufficient
    if ($withdrawal_amount > $balance) {
        echo json_encode(['transaction_id' => $transaction_id, 'status' => 'Declined', 'message' => 'Insufficient funds']);
        exit;
    }

    // Simulate approval
    $new_balance = $balance - $withdrawal_amount;

    // Update the balance in the database
    $conn->begin_transaction();
    try {
        // Update account balance
        $stmt = $conn->prepare("UPDATE Accounts SET Balance = ? WHERE CardNumber = ?");
        $stmt->bind_param("ds", $new_balance, $card_number);
        $stmt->execute();
        $stmt->close();

        // Log the transaction
        $stmt = $conn->prepare("INSERT INTO Transactions (CardNumber, Date, PreBalance, NewBalance) VALUES (?, CURDATE(), ?, ?)");
        $stmt->bind_param("sdd", $card_number, $balance, $new_balance);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['transaction_id' => $transaction_id, 'status' => 'Declined', 'message' => 'Transaction error']);
        exit;
    }

    // Return approval response
    echo json_encode(['transaction_id' => $transaction_id, 'status' => 'Approved']);
}
