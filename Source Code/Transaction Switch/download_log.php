<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    
    $switch = "switch.txt";

    if ($file == 'switch' && file_exists($switch)) {
        $filePath = $switch;
        $fileName = 'switch.txt';
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
