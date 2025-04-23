<?php
// Include the database connection
require_once '../../config/db.php';

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
        exit();
    }

    $isActive = 1; // Assuming the user is active by default
    $isAdmin = 0; // Assuming the user is not an admin by default
    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Email already exists.';
        header("Location: ../create_farmer.php");
        exit();
    }
    // Check if the phone number already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE phoneNumber = ?");
    $stmt->bind_param("s", $phoneNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Phone number already exists.';
        header("Location: ../create_farmer.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users (fullName, email, phoneNumber, isActive, isAdmin) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $fullName, $email, $phoneNumber, $isActive, $isAdmin);

    if ($stmt->execute()) {
        $userId = $stmt->insert_id; // Get the last inserted ID

        if ($userId) {
            $stmt = $conn->prepare("INSERT INTO farmers (userId, location) VALUES (?, ?)");
            $stmt->bind_param("is", $userId, $location);

            if ($stmt->execute()) {
                header("Location: ../farmers.php");
                $stmt->close();
                exit();
            } else {
                $error = $stmt->error;
                $_SESSION['error'] = "Error creating farmer record";
                header("Location: ../create_farmer.php");
                $stmt->close();
                exit();
            }
        } else {
            $error = $stmt->error;
            $_SESSION['error'] = "Error creating user record";
            echo "Error creating user: " . $error;
            header("Location: ../create_farmer.php");
            $stmt->close();
            exit();
        }
    } else {
        $error = $stmt->error;
        echo "Error creating user: " . $error;
        $_SESSION['error'] = "Error creating farmer record";
        header("Location: ../create_farmer.php");
        $stmt->close();
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
