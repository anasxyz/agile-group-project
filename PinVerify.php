<?php
SESSION_START();  // Start the session to access session variables
// Array of test PINs that will be successful

$dbhost     = "databaseatm.cv6mcgeknvyl.us-east-1.rds.amazonaws.com";
$dbport     = "3306";
$dbname     = "Bank";
$dbuser     = "admin";
$dbpass     = "databaseatm12";

try {
    // database connection
    $conn = new PDO("mysql:host=$dbhost;port=$dbport;dbname=$dbname", $dbuser, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


if (isset($_POST['PIN'])) {
    $enteredPin = $_POST['PIN'];
    $cardNumber = $_SESSION['CardNumber'];
    $query = "SELECT PIN FROM Accounts WHERE PIN = :pin AND CardNumber = :cardNumber";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':pin', $enteredPin);
    $stmt->bindParam(':cardNumber', $cardNumber);
    $stmt->execute();

    // Check if any rows are returned
    if ($stmt->rowCount() > 0) {
        header("Location: CardEntry.php");
        exit();
    } else {
        $_SESSION['error_message'] = 'Invalid PIN';
        header("Location: PinEntry.php");
        exit();
    }
}



?>