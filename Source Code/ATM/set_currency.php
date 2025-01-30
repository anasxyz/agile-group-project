<?php
session_start(); 

if (isset($_GET['currency'])) {
    $_SESSION['currencyType'] = $_GET['currency'];
}
?>
