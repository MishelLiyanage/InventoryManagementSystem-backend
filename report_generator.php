<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Database connection
$host = 'localhost';
$dbname = 'root';
$user = '';
$pass = 'ims';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Function to fetch data and return in JSON or CSV format
function generateReport($query, $filename, $format = 'json') {
    global $pdo;
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($format === 'csv') {
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=$filename.csv");
        $output = fopen('php://output', 'w');
        fputcsv($output, array_keys($result[0]));
        foreach ($result as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
    } else {
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}

// Order Amount/Profit by Month
if (isset($_GET['report']) && $_GET['report'] === 'order_profit') {
    $query = "SELECT 
                DATE_FORMAT(date, '%Y-%m') AS month, 
                SUM(amount) AS total_amount, 
                SUM(profit) AS total_profit 
              FROM orders 
              GROUP BY month";
    generateReport($query, 'order_profit_report', $_GET['format'] ?? 'json');
}

// Inventory Stock Report
if (isset($_GET['report']) && $_GET['report'] === 'inventory_stock') {
    $query = "SELECT 
                itemname, 
                quantityinstock 
              FROM inventory 
              GROUP BY itemname";
    generateReport($query, 'inventory_stock_report', $_GET['format'] ?? 'json');
}

// Weekly Sales Summary
if (isset($_GET['report']) && $_GET['report'] === 'weekly_sales') {
    $query = "SELECT 
                DATE_FORMAT(date, '%Y-%U') AS week, 
                SUM(amount) AS total_sales 
              FROM orders 
              GROUP BY week";
    generateReport($query, 'weekly_sales_report', $_GET['format'] ?? 'json');
}



// Daily Orders Report
if (isset($_GET['report']) && $_GET['report'] === 'daily_orders') {
    $query = "SELECT 
                date, 
                COUNT(id) AS total_orders, 
                SUM(amount) AS total_amount 
              FROM orders 
              GROUP BY date";
    generateReport($query, 'daily_orders_report', $_GET['format'] ?? 'json');
}

//Order Amount/Profit by Month (JSON): http://site.com/report_generator.php?report=order_profit
//Inventory Stock Report (CSV): http://site.com/report_generator.php?report=inventory_stock&format=csv
//Weekly Sales Summary (JSON): http://site.com/report_generator.php?report=weekly_sales
//Daily Orders Report (CSV): http://site.com/report_generator.php?report=daily_orders&format=csv

?>

