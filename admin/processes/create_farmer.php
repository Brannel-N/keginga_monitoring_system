<?php
// Include the database connection
require_once '../../config/db.php';
require_once '../../models/Farmer.php';

session_start();

global $conn;

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $fullName = isset($_POST['fullName']) ? htmlspecialchars(trim($_POST['fullName'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $phoneNumber = isset($_POST['phoneNumber']) ? htmlspecialchars(trim($_POST['phoneNumber'])) : '';
    $location = isset($_POST['location']) ? htmlspecialchars(trim($_POST['location'])) : '';

    // Validate required fields
    if (empty($fullName) || empty($email) || empty($phoneNumber) || empty($location)) {
        $_SESSION['error'] = 'All fields are required.';
        exit;
    }

    echo "Attempting to create farmer with the following details:<br>";

    // Create a new Farmer instance
    $farmer = new Farmer($conn);
    
    // check if farmer instance is created
    if (!$farmer) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create farmer instance.']);
        exit;
    }
    
    // Save the farmer to the database
    if ($farmer->createFarmer($fullName, $email, $phoneNumber, "12345678", $location)) {
        echo json_encode(['status' => 'success', 'message' => 'Farmer created successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create farmer.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
