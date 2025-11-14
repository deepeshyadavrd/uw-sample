<?php
$host = "smtp.gmail.com"; // replace with your SMTP server
$port = 465;                   // or 465 for SSL, or 25
$timeout = 10;                 // seconds

echo "Trying to connect to $host on port $port...\n";

$fp = fsockopen($host, $port, $errno, $errstr, $timeout);

if (!$fp) {
    echo "Connection failed: ($errno) $errstr\n";
} else {
    echo "Connection successful!\n";
    fclose($fp);
}
?>