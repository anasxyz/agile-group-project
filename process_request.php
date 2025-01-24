<?php
header('Content-Type: application/json');

// Get the JSON data from the request
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Check if data is received
if ($data) {
    // Determine the redirect URL based on the action
    $redirectUrl = '';
    if ($data['Action'] === 'view_balance') {
        $redirectUrl = '/view_balance'; // Destination for VIEW BALANCE
    } elseif ($data['Action'] === 'print_balance') {
        $redirectUrl = '/print_balance'; // Destination for PRINT BALANCE
    }

    // Prepare the response
    $response = [
        'status' => 'success',
        'receivedData' => $data,
        'redirect' => $redirectUrl
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'No data received'
    ];
}

// Send the response back as JSON
echo json_encode($response);
?> 