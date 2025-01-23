<?php
session_start();  // Start the session to access session variables



// Array of test cards that will be successful
$testCards = [
    '12',
    '58',
    '91',
    '23',
    '55'
];

// Function to verify if the entered card is in the list of test cards
function verifyCard($enteredCard) {
    global $testCards;
    foreach ($testCards as $card) {
        if ($enteredCard === $card) {
            $_SESSION['CardNumber'] = $enteredCard;
            return true;
        }
    }
    return false;
}

if (isset($_POST['Card'])) {
    $enteredCard = $_POST['Card'];
    if (verifyCard($enteredCard)) {
        echo '<script>alert(card verified);</script>';
        header ("Location: PinEntry.php");
        
    } else {
        $_SESSION['error_message'] = 'Invalid card';
        header ("Location: CardEntry.php");
    }
}



?>