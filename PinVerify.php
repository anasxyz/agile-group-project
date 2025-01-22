<?php
// Array of test PINs that will be successful
$testPins = [
    '1234',
    '5678',
    '9011',
    '1213',
    '1415'
];

// Function to verify if the entered PIN is in the list of test PINs
function verifyPin($enteredPin) {
    global $testPins;
    foreach ($testPins as $pin) {
        if ($enteredPin === $pin) {
            return true;
        }
    }
    return false;
}

if (issqet($_POST['PIN'])) {
    $enteredPin = $_POST['PIN'];
    if (verifyPin($enteredPin)) {
        echo 'PIN verified';
        header ("Location: CardEntry.html");
    } else {
        echo 'Invalid PIN';
        header ("Location: CardEntry.html");
    }
}



?>