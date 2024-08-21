<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include the database connection script
include_once 'dbconnection.php';



// Query the database for all records in the products table
$result = $conn->query('SELECT * FROM invetory');
$products = [];

// Fetch each row as an associative array and add it to the $products array
while($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Output the array of products as a JSON string
echo json_encode($products);
?>
