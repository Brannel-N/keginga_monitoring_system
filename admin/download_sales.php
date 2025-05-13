<?php
// filepath: c:\xampp\htdocs\JEAN\admin\download_sales.php

// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
    exit();
}

// Include database connection
require_once "../config/db.php";

// Set headers to force download of the CSV file
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=sales_data.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Write the column headers to the CSV file
fputcsv($output, ['ID', 'Date', 'Farmer ID', 'Farmer Name', 'Quantity']);

// Fetch sales data from the database
$query = "SELECT 
            sale_records.recordId AS id, 
            sale_records.date, 
            sale_records.farmerId, 
            sale_records.quantity, 
            users.fullName AS farmer_name
          FROM sale_records 
          JOIN farmers ON sale_records.farmerId = farmers.farmerId 
          JOIN users ON farmers.userId = users.userId";
$result = $conn->query($query);

if ($result) {
    // Write each row of sales data to the CSV file
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['date'],
            $row['farmerId'],
            $row['farmer_name'],
            $row['quantity']
        ]);
    }
} else {
    // Handle query error
    die("Error fetching sales data: " . $conn->error);
}

// Close the output stream
fclose($output);
exit();
?>