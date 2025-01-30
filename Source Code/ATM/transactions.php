<?php
include '../Database/db_connection.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$sql = "SELECT * FROM Transactions WHERE status = 'Pending' ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

echo json_encode($transactions);

$stmt->close();
$conn->close();
?>
