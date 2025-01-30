<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Database/db_connection.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    // Debug: Print received transaction_id
    error_log("Received transaction_id: " . $transaction_id);

    $stmt = $conn->prepare("SELECT status FROM Transactions WHERE transaction_id = ?");
    
    if (!$stmt) {
        echo json_encode(['status' => 'Error', 'message' => 'Database query error']);
        error_log("SQL Error: " . $conn->error);
        exit;
    }

    $stmt->bind_param("s", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $transaction = $result->fetch_assoc();
        echo json_encode($transaction);
    } else {
        echo json_encode(['status' => 'Error', 'message' => 'Transaction not found']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'Error', 'message' => 'No transaction_id provided']);
}
?>
