<?php
// Require the database connection
require_once '../../config/db.php';

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validate input
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required.";
        header("Location: ../login.php");
        exit();
    }

    // Query to check if the user exists and is an admin
    $query = "SELECT * FROM users WHERE email = ? AND isAdmin = 1 AND isActive = 1 LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['userId'] = $user['userId'];
            $_SESSION['fullName'] = $user['fullName'];
            $_SESSION['isAdmin'] = $user['isAdmin'];

            // Redirect to admin dashboard
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password.";
        }
    } else {
        $_SESSION['error'] = "Invalid email or password.";
    }

    // Redirect back to login page with error
    header("Location: ../login.php");
    exit();
} else {
    // Redirect if accessed directly
    header("Location: ../login.php");
    exit();
}
