<?php

// Include the database connection file
require 'dbconnection.php';

header('Access-Control-Allow-Origin: *'); // Allow access from any origin
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Get the POST data
$postData = json_decode(file_get_contents("php://input"), true);
$user = $postData['username'];
$pass = $postData['password'];

// SQL query to check for the matching username and password
$sql = "SELECT id, firstname, role FROM account WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $user, $pass);
$stmt->execute();
$result = $stmt->get_result();

// Check if a matching user was found
if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $userData['id'],
            'firstname' => $userData['firstname'],
            'role' => $userData['role']
        ]
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid username or password'
    ]);
}

$stmt->close();
$conn->close();
?>
