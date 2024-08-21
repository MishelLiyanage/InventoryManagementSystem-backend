<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'dbconnection.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';

// Prevent SQL Injection by escaping the input
$category = $conn->real_escape_string($category);

$sql = "SELECT itemname, priceunit FROM invetory WHERE category = '$category'";
$result = $conn->query($sql);

$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = [
            'itemname' => $row['itemname'],
            'priceunit' => $row['priceunit']
        ];
    }
}

echo json_encode($items);

$conn->close();
?>
