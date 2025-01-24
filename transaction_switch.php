<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the transaction data from the ATM
    $transaction_data = $_POST;

    // Send the transaction data to the Network Simulator
    $url = 'http://localhost/network_simulator.php'; // The endpoint of the network simulator
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($transaction_data));
    $network_response = curl_exec($ch);
    curl_close($ch);

    // Forward the response back to the ATM
    echo $network_response;
}
