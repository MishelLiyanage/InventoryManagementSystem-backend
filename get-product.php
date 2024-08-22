<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// Include the database connection script
include_once 'dbconnection.php';


$id = $_GET['id'];
$result = $conn->query("SELECT * FROM inventory WHERE id = $id");

echo json_encode($result->fetch_assoc());
?>
