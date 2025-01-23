<?php
session_start();  // Start the session to access session variables

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

if (isset($_POST['Card'])) {
    $enteredCard = $_POST['Card'];
    // Query to check card number
    $query = "SELECT CardNumber FROM Accounts WHERE CardNumber = :cardNumber";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':cardNumber', $enteredCard);
    $stmt->execute();

    // Check if any rows are returned
    if ($stmt->rowCount() > 0) {
        $_SESSION['CardNumber'] = $enteredCard;
        header("Location: PinEntry.php");
        exit();
    } else {
        $_SESSION['error_message'] = 'Card not found';
        header("Location: CardEntry.php");
        exit();
    }
}
?>
