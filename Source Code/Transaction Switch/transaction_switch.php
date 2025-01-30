<?php
include '../Database/db_connection.php'; 

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_data = $_POST;

    $firstDigit = $transaction_data['card_number'][0];

    if ($firstDigit >= 1 && $firstDigit <= 3) {
        $url = 'http://localhost/../Network%20Simulator/network_simulator_1.php';
    } elseif ($firstDigit >= 4 && $firstDigit <= 6) {
        $url = 'http://localhost/../Network%20Simulator/network_simulator_2.php';
    } elseif ($firstDigit >= 7 && $firstDigit <= 9) {
        $url = 'http://localhost/../Network%20Simulator/network_simulator_3.php';
    }
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($transaction_data));
    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
}
