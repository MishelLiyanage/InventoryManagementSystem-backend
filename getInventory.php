<?php
require 'dbconnection.php';

header('Access-Control-Allow-Origin: *'); 
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

try {
  
    $stmt = $conn->prepare('SELECT * FROM inventory');
    $stmt->execute();


    $result = $stmt->get_result();

   
    $items = $result->fetch_all(MYSQLI_ASSOC);

  
    echo json_encode($items);

} catch (Exception $e) { 
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close(); 