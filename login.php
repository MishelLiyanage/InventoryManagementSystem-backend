<?php
// Include the database connection file
require 'dbconnection.php';

header('Access-Control-Allow-Origin: *'); // Allow access from any origin
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Get the POST data
$postData = json_decode(file_get_contents("php://input"), true);
$username = $postData['username'];
$password = $postData['password'];

// SQL query to check for the matching username
$sql = "SELECT id, firstname, role, password FROM account WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if a matching user was found
if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $userData['password'])) {
        // Password is correct
        unset($userData['password']); // Remove the password from the response
        echo json_encode(['success' => true, 'user' => $userData]);
    } else {
        // Password is incorrect
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
}

$stmt->close();
$conn->close();
?>
