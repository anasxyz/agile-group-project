<?php
$filePath = 'simulator.txt'; 

if (file_exists($filePath)) {
    file_put_contents($filePath, "");
}
?>