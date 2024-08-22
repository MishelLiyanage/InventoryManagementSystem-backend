<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

require 'dbconnection.php';

$query = "SELECT COUNT(*) as customerCount FROM account WHERE role = 'user'";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row);
} else {
    echo json_encode(["customerCount" => 0]);
}
?>