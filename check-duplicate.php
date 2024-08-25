<?php
header('Access-Control-Allow-Origin: *'); // Allow access from any origin
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

require 'dbconnection.php';

// Get the item name and category from the query parameters
$itemName = $_GET['itemName'];
$category = $_GET['category'];

// Prepare the SQL query
$sql = "SELECT * FROM inventory WHERE itemname = ? AND category = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $itemName, $category);
$stmt->execute();
$result = $stmt->get_result();

// Check if the item exists in the given category
if ($result->num_rows > 0) {
    echo json_encode(["exists" => true]);
} else {
    echo json_encode(["exists" => false]);
}

$stmt->close();
$conn->close();
?>
