<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Include the database connection file
require 'dbconnection.php';

// Prepare SQL query to join account and customer tables
$query = "
    SELECT a.id, a.firstname, a.lastname, a.email, a.username, c.city, c.telno
    FROM account a
    JOIN customer c ON a.id = c.id
    WHERE a.role = 'user'";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $customers = [];

    // Fetch all rows
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }

    // Return the data as JSON
    echo json_encode($customers);
} else {
    echo json_encode([]); // Return an empty array if no results found
}

$conn->close();
?>
