<?php
//To place an Order
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'dbconnection.php';
$data = json_decode(file_get_contents('php://input'), true);

$userid = $data['userid'];
$totalAmount = $data['totalAmount'];
$items = $data['items'];

// Insert order into the `order` table
$orderInsertQuery = "INSERT INTO `orders` (customerId, amount, date) VALUES (?, ?, CURDATE())";
$stmt = $conn->prepare($orderInsertQuery);
$stmt->bind_param("id", $userid, $totalAmount);

if ($stmt->execute()) {
    $orderId = $conn->insert_id;

    // Insert items into `orderinventory` table and update `inventory` table
    foreach ($items as $item) {
        $itemName = $item['name'];
        $quantity = $item['quantity'];

        // Get the item ID from the inventory table
        $itemQuery = "SELECT id, quantityinstock FROM inventory WHERE itemname = ?";
        $itemStmt = $conn->prepare($itemQuery);
        $itemStmt->bind_param("s", $itemName);
        $itemStmt->execute();
        $itemResult = $itemStmt->get_result();
        $inventoryItem = $itemResult->fetch_assoc();
        $itemId = $inventoryItem['id'];
        $currentStock = $inventoryItem['quantityinstock'];

        if ($inventoryItem) {
            // Update inventory quantity
            $newStock = $currentStock - $quantity;
            
            $updateInventoryQuery = "UPDATE inventory SET quantityinstock = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateInventoryQuery);
            $updateStmt->bind_param("ii", $newStock, $itemId);
            $updateStmt->execute();

            // Insert into `orderinventory`
            $orderInventoryQuery = "INSERT INTO orderinventory (orderId, itemId, quantity) VALUES (?, ?, ?)";
            $orderInventoryStmt = $conn->prepare($orderInventoryQuery);
            $orderInventoryStmt->bind_param("iii", $orderId, $itemId, $quantity);
            $orderInventoryStmt->execute();
        }
    }

    echo json_encode(["success" => true, "message" => "Order placed successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to place order"]);
}

$conn->close();
?>
