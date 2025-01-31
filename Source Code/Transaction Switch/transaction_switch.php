<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

function logTransaction($transaction_data, $simulatorName, $action = 'Forwarded', $response = null) {
    $logFile = 'switch.txt';
    
    $decResponse = json_decode($response, true);

    $logMessage = str_repeat("-", 50) . "\n";
    
    if ($action == 'Forwarded') {
        $logMessage .= date('[Y-m-d H:i:s]') . " - Incoming Transaction: " . $transaction_data['transaction_id'] . " from " . $transaction_data['atm_id'] . "\n";
        $logMessage .= date('[Y-m-d H:i:s]') . " - Forwarded to: " . $simulatorName . "\n";
    } else {
        $logMessage .= date('[Y-m-d H:i:s]') . " - Response Received from: " . $simulatorName . "\n";
        $logMessage .= date('[Y-m-d H:i:s]') . " - Response: " . $decResponse['status'] . "\n";
        $logMessage .= date('[Y-m-d H:i:s]') . " - Forwarded to: " . $decResponse['atm_id'] . "\n";
    }

    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_data = $_POST;
    $firstDigit = $transaction_data['card_number'][0];

    if ($firstDigit >= 1 && $firstDigit <= 3) {
        $url = 'http://networksim.us-east-1.elasticbeanstalk.com/network_simulator_1.php';
        $simulatorName = "Network Simulator 1";
    } elseif ($firstDigit >= 4 && $firstDigit <= 6) {
        $url = 'http://networksim.us-east-1.elasticbeanstalk.com/network_simulator_2.php';
        $simulatorName = "Network Simulator 2";
    } elseif ($firstDigit >= 7 && $firstDigit <= 9) {
        $url = 'http://networksim.us-east-1.elasticbeanstalk.com/network_simulator_3.php';
        $simulatorName = "Network Simulator 3";
    } else {
        echo "Invalid card number.";
        exit;
    }

    logTransaction($transaction_data, $simulatorName, 'Forwarded');

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($transaction_data));
    $response = curl_exec($ch);
    curl_close($ch);

    logTransaction($transaction_data, $simulatorName, 'Received', $response);

    echo $response;
}
?>
