<?php
session_start(); // Start the session

$card_number = isset($_GET['card_number']) ? htmlspecialchars($_GET['card_number']) : 'Unknown';
$expiry = isset($_GET['expiry']) ? htmlspecialchars($_GET['expiry']) : 'Unknown';
$pin = isset($_GET['pin']) ? htmlspecialchars($_GET['pin']) : 'Unknown';

// Set the session variable
$_SESSION['card_number'] = $card_number;
$_SESSION['expiry'] = $expiry;
$_SESSION['pin'] = $pin;

header("Location: txn_types.php");
?>
