<?php
// Store transaction data in session for later use (we'll set this up after PIN approval)
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get card number and PIN from form submission
    $card_number = $_POST['card_number'];
    $pin = $_POST['pin'];

    // For simplicity, we'll approve any PIN automatically (no validation for now)
    if ($card_number && $pin) {
        // Store card number in session and redirect to the transaction choice page
        $_SESSION['card_number'] = $card_number;
        $_SESSION['pin'] = $pin;
        header("Location: transaction_choice.php");
        exit;
    } else {
        $error_message = 'Please enter both card number and PIN.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATM - Card Insert</title>
</head>
<body>
    <h1>ATM - Card Insert</h1>
    
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="card_number">Card Number:</label>
        <input type="text" name="card_number" id="card_number" required><br><br>

        <label for="pin">PIN:</label>
        <input type="password" name="pin" id="pin" required><br><br>

        <button type="submit">Insert</button>
    </form>
</body>
</html>
