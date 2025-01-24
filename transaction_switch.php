<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_data = $_POST;

    // Forward the transaction data to the Network Simulator
    $url = 'http://localhost/network_simulator.php';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($transaction_data));
    $response = curl_exec($ch);
    curl_close($ch);

    // Return the response from the Network Simulator to the ATM
    echo $response;
}
