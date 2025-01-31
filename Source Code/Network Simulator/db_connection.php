<?php
$servername = "databaseatm.cv6mcgeknvyl.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "databaseatm12";
$dbname = "Bank";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo 'CONNECTION ERROR';
    die("Connection failed: " . $conn->connect_error);
}
?>