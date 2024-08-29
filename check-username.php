<?php
// Include the database connection file
require 'dbconnection.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'];

$sql = "SELECT COUNT(*) as count FROM account WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result['count'] > 0) {
    echo json_encode(true);  // Username exists
} else {
    echo json_encode(false); // Username does not exist
}

$stmt->close();
$conn->close();
?>