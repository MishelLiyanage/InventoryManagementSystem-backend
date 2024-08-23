<?php
// db_connection.php

$servername = "localhost:3306";
$username = "hackxjr1_ims";
$password = "hackxjr1_ims";
$dbname = "hackxjr1_ims";

//$host = 'localhost:3306';
//$dbname = 'hackxjr1_ims';
//$user = 'hackxjr1_ims';
//$pass = 'hackxjr1_ims';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
