<?php
//TO serch and filter

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require 'dbconnection.php';

$searchQuery = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT * FROM inventory WHERE 1=1";

if ($searchQuery) {
    $sql .= " AND itemname LIKE '%$searchQuery%'";
}

if ($category) {
    $sql .= " AND category = '$category'";
}

$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

$conn->close();
?>
