<?php
// Database connection
require 'dbconnection.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents("php://input"), true);
    $itemName = $inputData['itemName'] ?? '';

    if (!empty($itemName)) {
        $query = "SELECT COUNT(*) as count FROM inventory WHERE itemname = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $itemName);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            echo json_encode(['exists' => true]);
        } else {
            echo json_encode(['exists' => false]);
        }
    } else {
        echo json_encode(['error' => 'Invalid item name']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

$conn->close();
?>

