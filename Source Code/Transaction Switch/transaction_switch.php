<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_data = $_POST;

    if (isset($transaction_data['status']) && isset($transaction_data['transaction_id'])) {
        // This means it's an update from manual approval/rejection
        echo json_encode([
            'transaction_id' => $transaction_data['transaction_id'],
            'status' => $transaction_data['status'],
            'message' => $transaction_data['message'],
            'source' => 'Manual approval from monitor.html'
        ]);
    } else {
        // Process a new transaction request normally
        $url = 'http://localhost/../Network%20Simulator/network_simulator_1.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($transaction_data));
        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }
}
?>
