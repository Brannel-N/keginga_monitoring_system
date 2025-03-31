<?php
// Include the database connection
require_once '../config/db.php';
require_once '../models/Farmer.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $fullName = isset($_POST['fullName']) ? htmlspecialchars(trim($_POST['fulllName'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $phoneNumber = isset($_POST['phoneNumber']) ? htmlspecialchars(trim($_POST['phoneNumber'])) : '';
    $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
    $location = isset($_POST['location']) ? htmlspecialchars(trim($_POST['location'])) : '';

    // Validate required fields
    if (empty($fullName) || empty($email) || empty($phoneNumber) || empty($password) || empty($location)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Create a new Farmer instance
    $farmer = new Farmer($conn);

    // Save the farmer to the database
    if ($farmer->createFarmer($fullName, $email, $phone, $password, $location)) {
        echo json_encode(['status' => 'success', 'message' => 'Farmer created successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create farmer.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
