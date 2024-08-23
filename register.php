<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Include the database connection file
require 'dbconnection.php';

// Get the data from POST request
$rawData = file_get_contents("php://input");
$data = json_decode($rawData);

if (isset($data->firstname, $data->lastname, $data->email, $data->username, $data->password, $data->city, $data->telno)) {
    $firstname = $data->firstname;
    $lastname = $data->lastname;
    $email = $data->email;
    $username = $data->username;
    $password = password_hash($data->password, PASSWORD_DEFAULT); // Hash the password
    $city = $data->city;
    $telno = $data->telno;
    $role = "user";

    // Prepare and bind for the account table
    $stmt = $conn->prepare("INSERT INTO account (firstname, lastname, email, username, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstname, $lastname, $email, $username, $password, $role);

    if ($stmt->execute() === TRUE) {
        $account_id = $conn->insert_id; // Get the ID of the inserted row

        // Prepare and bind for the user table
        $stmt_user = $conn->prepare("INSERT INTO customer (id, city, telno) VALUES (?, ?, ?)");
        $stmt_user->bind_param("iss", $account_id, $city, $telno);

        if ($stmt_user->execute() === TRUE) {
            echo json_encode(["message" => "Registration successful!"]);
        } else {
            echo json_encode(["message" => "Error: " . $stmt_user->error]);
        }

        $stmt_user->close();
    } else {
        echo json_encode(["message" => "Error: " . $stmt->error]);
    }
} else {
    echo json_encode(["message" => "Missing required data."]);
    exit();
}

$stmt->close();
$conn->close();
?>
