<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "password";
$db = "hm";
$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);
?>