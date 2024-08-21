<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'dbconnection.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$stmt = $conn->prepare("DELETE FROM invetory WHERE id = ?");
$stmt->bind_param('i', $id);

$stmt->execute();
echo json_encode(['success' => true]);
?>
