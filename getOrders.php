<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require 'dbconnection.php';

// Fetch orders from the database
$sql = "SELECT id, customerId, amount, date FROM orders";
$result = $conn->query($sql);

$orders = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

echo json_encode($orders);

$conn->close();
?>
