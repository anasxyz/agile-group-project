<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    
    $simulator = "simulator.txt";

    if ($file == 'simulator' && file_exists($simulator)) {
        $filePath = $simulator;
        $fileName = 'simulator.txt';
    } else {
        die('File not found!');
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
    header('Content-Length: ' . filesize($filePath));
    header('Pragma: no-cache');
    header('Expires: 0');

    if (readfile($filePath) === false) {
        die('Error reading the file.');
    }

    exit;
} else {
    die('No file specified.');
}
?>
