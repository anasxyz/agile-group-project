<?php
$filePath = 'switch.txt'; 

if (file_exists($filePath)) {
    file_put_contents($filePath, "");
} 
?>