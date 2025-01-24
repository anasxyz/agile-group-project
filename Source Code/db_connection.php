<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Bank";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo 'CONNECTION ERROR';
    die("Connection failed: " . $conn->connect_error);
}
?>