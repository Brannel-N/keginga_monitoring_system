<?php
// filepath: c:\xampp\htdocs\JEAN\admin\download_farmers.php

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
header('Content-Disposition: attachment; filename=farmers_data.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Write the column headers to the CSV file
fputcsv($output, ['Farmer ID', 'Full Name', 'Email', 'Phone Number', 'Location', 'Total Qty Sold', 'Outstanding Balance', 'Total Amount Earned', 'Total Amount Paid']);

// Fetch farmers' data from the database
$query = "SELECT f.farmerId, u.fullName, u.email, u.phoneNumber, f.location, f.totalQtySold, f.totalOutstandingBalance, f.totalAmountEarned, f.totalAmountPaid 
          FROM farmers f 
          INNER JOIN users u ON f.userId = u.userId";
$result = $conn->query($query);

if ($result) {
    // Write each row of farmers' data to the CSV file
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['farmerId'],
            $row['fullName'],
            $row['email'],
            $row['phoneNumber'],
            $row['location'],
            $row['totalQtySold'],
            $row['totalOutstandingBalance'],
            $row['totalAmountEarned'],
            $row['totalAmountPaid']
        ]);
    }
} else {
    // Handle query error
    die("Error fetching farmers' data: " . $conn->error);
}

// Close the output stream
fclose($output);
exit();
?>