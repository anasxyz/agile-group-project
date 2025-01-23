<?php
SESSION_START();  // Start the session to access session variables
// Array of test PINs that will be successful
$testPins = [
    ['12','1234'],
    ['58','5678'],
    ['91','9011'],
    ['23','1213'],
    ['55','1415']
];

// Function to verify if the entered PIN is in the list of test PINs
function verifyPin($enteredPin) {
    global $testPins;
    foreach ($testPins as $pin) {
        if ($enteredPin === $pin[1] && $_SESSION['CardNumber'] === $pin[0]) {
            return true;
        }
    }
    return false;
}

if (isset($_POST['PIN'])) {
    $enteredPin = $_POST['PIN'];
    if (verifyPin($enteredPin)) {
        echo '<script>alert(PIN verified);</script>';
        
    } else {
        $_SESSION['error_message'] = 'Invalid PIN';
        header ("Location: PinEntry.php");
    }
}



?>