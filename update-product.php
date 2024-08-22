<?php
header('Access-Control-Allow-Origin: *'); // Allows all origins; adjust as needed for security
header('Access-Control-Allow-Methods: POST, OPTIONS'); // Allows POST requests and preflight OPTIONS requests
header('Access-Control-Allow-Headers: Content-Type'); // Allows Content-Type header
header('Content-Type: application/json'); // Specifies that the content is JSON


// Include the database connection script
include_once 'dbconnection.php';


$data = json_decode(file_get_contents('php://input'), true);
$category = $data['category'];
$itemname = $data['itemname'];
$priceunit = $data['priceunit'];
$quantityinstock = $data['quantityinstock'];
$description = $data['description'];
$addeddate = $data['addeddate'];
$id = $data['id']; 

$stmt = $conn->prepare("UPDATE inventory SET category = ?, itemname = ?, priceunit = ?, quantityinstock = ?, description = ?,addeddate= ? WHERE id = ?");
$stmt->bind_param('ssiisss', $category, $itemname, $priceunit, $quantityinstock, $description, $addeddate,$id);

$stmt->execute();
echo json_encode(['success' => true]);
?>
