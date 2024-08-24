<?php

header('Access-Control-Allow-Origin: *'); // Allow access from any origin
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

require 'dbconnection.php';

// Retrieve itemname using GET method
$itemname = isset($_GET['itemname']) ? $_GET['itemname'] : '';

// Validate input
if (empty($itemname)) {
    echo json_encode(['error' => 'itemname is required']);
    exit;
}

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("SELECT quantityinstock FROM inventory WHERE itemname = ?");
$stmt->bind_param("s", $itemname);

// Execute the query
if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the result as an associative array
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'No item found']);
    }
} else {
    echo json_encode(['error' => 'Query failed']);
}

// Close the connection
$stmt->close();
$conn->close();

?>
