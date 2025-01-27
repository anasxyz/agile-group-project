<?php
// Allow cross-origin requests from your front-end domain
header('Access-Control-Allow-Origin: *');  // * allows all origins, replace * with your domain to restrict

// Allow specific HTTP methods (GET, POST, etc.)
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Allow specific headers (e.g., Content-Type, Authorization)
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_data = $_POST;

    $url = 'networksim.us-east-1.elasticbeanstalk.com';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($transaction_data));
    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
}
