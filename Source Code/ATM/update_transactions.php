<?php
include '../Database/db_connection.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_id = $_POST['transaction_id'];
    $status = $_POST['status'];

    // Set appropriate message
    $message = ($status === 'Approved') ? 'Transaction approved manually' : 'Transaction declined manually';

    // Fetch transaction details
    $stmt = $conn->prepare("SELECT transaction_type, card_number FROM Transactions WHERE transaction_id = ?");
    $stmt->bind_param("s", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaction = $result->fetch_assoc();
    $stmt->close();

    $balance = null;

    if ($transaction) {
        if ($transaction['transaction_type'] === 'balance inquiry' || $transaction['transaction_type'] === 'print balance' && $status === 'Approved') {
            // Fetch account balance
            $stmt = $conn->prepare("SELECT balance FROM Accounts WHERE cardNumber = ?");
            $stmt->bind_param("s", $transaction['card_number']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $account = $result->fetch_assoc();
                $balance = $account['balance'];
            }
            $stmt->close();
        }

        // Update transaction status
        $stmt = $conn->prepare("UPDATE Transactions SET status = ?, message = ? WHERE transaction_id = ?");
        $stmt->bind_param("sss", $status, $message, $transaction_id);
        $stmt->execute();
        $stmt->close();

        // Forward updated transaction to Network Simulator
        $transaction_data = [
            'transaction_id' => $transaction_id,
            'status' => $status,
            'message' => $message,
            'balance' => $balance // Include balance if applicable
        ];

        $url = 'http://localhost/../Network%20Simulator/network_simulator_1.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($transaction_data));
        $response = curl_exec($ch);
        curl_close($ch);

        echo json_encode(['success' => true, 'simulator_response' => $response, 'balance' => $balance]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Transaction not found']);
    }

    $conn->close();
}
?>
