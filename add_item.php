<?php
header('Access-Control-Allow-Origin: *'); // Allow access from any origin
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

require 'dbconnection.php';

// Get data from the JSON request body
$data = json_decode(file_get_contents('php://input'), true);

// Validate if all required fields are present
if (!isset($data['Category'], $data['ItemName'], $data['unitprice'], $data['Quantity'], $data['Description'], $data['adminid'])) {
    echo json_encode(["error" => "All fields are required."]);
    exit();
}

$category = $data['Category'];
$itemName = $data['ItemName'];
$unitPrice = $data['unitprice'];
$quantityInStock = $data['Quantity'];
$description = $data['Description'];
$adminId = $data['adminid'];

// Prepare and execute the SQL statement
$sql = "INSERT INTO invetory (category, itemname, priceunit, quantityinstock, description, addeddate, adminid)
        VALUES (?, ?, ?, ?, ?, CURDATE(), ?)";
$stmt = $conn->prepare($sql);

// Check if the preparation was successful
if ($stmt === false) {
    echo json_encode(['error' => 'Error preparing SQL: ' . $conn->error]);
    exit();
}

$stmt->bind_param("ssdisi", $category, $itemName, $unitPrice, $quantityInStock, $description, $adminId);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Inventory added successfully"]);
} else {
    echo json_encode(['error' => 'Error adding item: ' . $stmt->error]);
}


$stmt->close(); // Always close the statement
$conn->close();
?>
